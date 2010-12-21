<?php 
################################################# 
# Анализатор логов арены для TrinityCore/Mangos    # 
# Предназначен для выявления "переливов"        # 
################################################# 

################################################################################################### 
# СОДЕРЖАНИЕ 
# 
#    АВТОР, КОНТАКТЫ 
#     ОПИСАНИЕ РАБОТЫ СКРИПТА 
#     ИНСТРУКЦИЯ ПО ИСПОЛЬЗОВАНИЮ 
#    НАСТРОЙКА ПОДКЛЮЧЕНИЯ К БАЗЕ 
#    НАСТРОЙКИ СКРИПТА 
# 
################################################################################################### 

################################################################################################### 
# АВТОР, КОНТАКТЫ 
# 
#         Автор    : BeLove 
#         ICQ    : 3320443 
#         email    : sergeybelove@gmail.com 
# 
################################################################################################### 

################################################################################################### 
# ОПИСАНИЕ РАБОТЫ СКРИПТА 
# 
# 
################################################################################################### 

################################################################################################### 
# ИНСТРУКЦИЯ ПО ИСПОЛЬЗОВАНИЮ 
# 
/* 
DROP TABLE IF EXISTS `arena_leavers_temp`; 
CREATE TABLE `arena_leavers_temp` ( 
  `game_time_start` varchar(10) DEFAULT 'NULL', 
  `game_time_end` varchar(10) DEFAULT 'NULL', 
  `game_type` tinyint(3), 
  `team1_id` int(10), 
  `team1_damage` int(10) DEFAULT '0', 
  `team1_heal` int(10) DEFAULT '0', 
  `team1_kb` tinyint(3) DEFAULT '0', 
  `team2_id` int(10), 
  `team2_damage` int(10) DEFAULT '0', 
  `team2_heal` int(10) DEFAULT '0', 
 `team2_kb` tinyint(3)  DEFAULT '0'); 
  
 DROP TABLE IF EXISTS `arena_leavers_stat`; 
CREATE TABLE `arena_leavers_stat` ( 
  `teamid` int(10), 
  `count` int(10) DEFAULT '0'); 
*/ 
# 
################################################################################################### 

################################################################################################### 
# НАСТРОЙКА ПОДКЛЮЧЕНИЯ К БАЗЕ 
# 
#    Укажите ниже параметры для подключения: хост, имя пользователя, пароль, имя базы данных 

$mysql_host = "localhost"; 
$mysql_user = "root"; 
$mysql_pass = ""; 
$mysql_db = "characters"; 

# 
################################################################################################### 

################################################################################################### 
# НАСТРОЙКИ СКРИПТА 
# 
#    Имя файла, содержащего логи арены 

$arena_log_file = "arena.log"; 

#    Минимальная разница в секундах между началом и концом игры 

$max_delta = 40; 

#    Анализировать расширенные логи (ставьте 0, если в конфиге  
#    выставлено ArenaLog.ExtendedInfo = 0 и наоборот) 

$extended_logs = 0; 

#    Ничего не трогайте ниже, если режим ArenaLog.ExtendedInfo равен нулю 
#    Минимальные значения хила/дамага для обеих команд за одну игру (общий дамаг и хил игры) 

$min_heal = 1; 
$min_dmg = 1; 

#    Минимальные значения убийств для одной из команд за одну игру 
#    (в зависимости от типа игры (2х2, 3х3, 5х5) 
#    укажите "0", если не хотите учитывать этот параметр 

$min_kb_2 = 2; 
$min_kb_3 = 3; 
$min_kb_5 = 5; 

# 
################################################################################################### 

set_time_limit(0); 
error_reporting(0); 
mysql_connect ($mysql_host, $mysql_user, $mysql_pass) or die (mysql_error()); 
mysql_select_db ($mysql_db) or die (mysql_error()); 

################################################################################################### 
# 
#    Начало работы первого этапа. 
#    Включает в себя перенос частично обработанных записей об играх в MySQL 
# 
################################################################################################### 

if (!isset($_GET[step])) 
{ 

//это так, за ранее обнулим 
$team1_damage = 0; $team2_damage = 0; 
$team1_heal = 0; $team2_heal = 0; 
$team1_kb = 0; $team2_kb = 0; 

echo "<h3>Первый этап.</h3> Перенос нужной информации из логов в MySQL<br /> 
    <i>Процесс пошел...</i><br/><br/>"; 
mysql_query ("TRUNCATE `arena_leavers_temp`") or die(mysql_error());  
$fp = fopen($arena_log_file, "r"); 
if ($fp)  
{ 
while (!feof($fp)) 
{ 
    $arena = explode(" ", fgets($fp, 999)); 
    // $arena[12] - "started" or "ended" 
    if (trim($arena[12]) == "started.") 
    { 
        $ymd = explode ("-", $arena[0]); 
        $hms = explode (":", $arena[1]); 
        $time = mktime($hms[0], $hms[1], $hms[2], $ymd[1], $ymd[2], $ymd[0]); 
        mysql_query ("INSERT INTO `arena_leavers_temp` VALUES ('{$time}', '', {$arena[5]}, {$arena[8]}, '','','', {$arena[11]}, '', '', '')")  or die(mysql_error()); 
    } 
     
    if ($arena[12] == "ended.") 
    { 
        // проверка, есть ли запись о начале для данной игры. game_time_end нам это и скажет. 
        $query = mysql_query ("SELECT game_time_start FROM `arena_leavers_temp` where team1_id = {$arena[8]} and team2_id = {$arena[11]} 
                                and game_time_end = ''") or die(mysql_error()); 
        $sql = mysql_fetch_array($query); 
        if ($sql['game_time_start'] > 0) 
        { 
            //забираем время игры 
            $ymd = explode ("-", $arena[0]); 
            $hms = explode (":", $arena[1]); 
            $time = mktime($hms[0], $hms[1], $hms[2], $ymd[1], $ymd[2], $ymd[0]); 
            //забираем расширенную статистику, если надо 
            if ($extended_logs) 
            { 
                //сбрасываем сумарные данные по командам (дамаг, хил, количество убийств) 
                $team1_damage = 0; $team2_damage = 0; 
                $team1_heal = 0; $team2_heal = 0; 
                $team1_kb = 0; $team2_kb = 0; 
                $extended_info = explode(" ", fgets($fp, 999)); 
                while ($extended_info[2] == "Statistics") 
                { 
                    //Запятая в конце - чтобы не вырезать ее из первой переменной, быстрее и проще :) 
                    //для первой команды 
                    if ($extended_info[8] == $arena[8].",") 
                        { 
                             
                            $team1_damage = $team1_damage + $extended_info[11]; 
                            $team1_heal = $team1_heal + $extended_info[13]; 
                            $team1_kb = $team1_kb + $extended_info[15]; 
                        } 
                    //для второй 
                    else if ($extended_info[8] == $arena[11].",") 
                        { 
                            $team2_damage = $team2_damage + $extended_info[11]; 
                            $team2_heal = $team2_heal + $extended_info[13]; 
                            $team2_kb = $team2_kb + $extended_info[15]; 
                        } 
                    $extended_info = explode(" ", fgets($fp, 999)); 
                } 
            } 
            //и записываем все в базу 
            mysql_query ("update `arena_leavers_temp` set  
                        game_time_end = '{$time}', 
                        team1_damage = '{$team1_damage}', team1_heal = {$team1_heal}, team1_kb = {$team1_kb},  
                        team2_damage = {$team2_damage}, team2_heal = {$team2_heal}, team2_kb = {$team2_kb} 
                        where 
                        game_time_start = '{$sql['game_time_start']}' and 
                        team1_id = {$arena[8]} and 
                        team2_id =  {$arena[11]};") or die (mysql_error()); 
        } else echo "<B>[notice]</b> Была найдена запись об окончании игры, для которой нет информации о ее начале! 
             Игра {$arena[8]} vs {$arena[11]}. Тип игры - {$arena[5]} x {$arena[5]}<br />"; 
    } 
} 
} else echo "Ошибка при открытии файла $arena_log_file. Возможно, файл не существует."; 
fclose($fp); 

echo "<br />Перенос данных успешно завершен!<br /> 
    <a href = ?step=2>Перейти ко второму этапу...</a>"; 
} 

################################################################################################### 
# 
#    Второй этап 
#    Анализ игр на переливы 
# 
################################################################################################### 

if (isset($_GET[step]) && $_GET[step] == 2) 
{ 
    echo "<h3>Второй этап.</h3> Анализ полученных данных."; 
    $sql = mysql_query("SELECT count(1) as count FROM `arena_leavers_temp`;") or die (mysql_error()); 
    $result = mysql_fetch_array($sql); 
    echo "<br /><br /><li>Всего игр: <b>$result[count]</b></li>"; 
    $sql = mysql_query("SELECT 1 FROM `arena_leavers_temp` where game_time_end = '';") or die (mysql_error()); 
    $result = mysql_num_rows($sql); 
    if ($result) echo "<li>Количество игр, для которых мы не смогли найти конечные данные: <b>$result</b></li> 
    <small>*Возможные причины -  неполный лог/краши сервера во время игр</small><br />"; 
    $sql = mysql_query("SELECT team1_id FROM `arena_leavers_temp` where game_time_end <> '' 
                        UNION SELECT `team2_id` FROM `arena_leavers_temp` where game_time_end <> '';") or die (mysql_error()); 
    $result = mysql_num_rows($sql); 
    echo "<li>Количество команд для обработки: <b>$result</b><br />"; 
    //заполняем "нулевую" статистику для каждой команды 
    mysql_query ("TRUNCATE `arena_leavers_stat`") or die(mysql_error());  
    while ($row=mysql_fetch_assoc($sql)) 
    { 
        mysql_query ("INSERT INTO `arena_leavers_stat` VALUES ({$row[team1_id]}, '0')") or die (mysql_error()); 
    } 
    //Начинаем анализ. Для расширенных логов: 
    if ($extended_logs) 
    { 
        $sql = mysql_query("SELECT * FROM `arena_leavers_temp` where  
                            game_time_end <> '' and  
                            team1_heal + team2_heal < {$min_heal} and 
                            team1_damage + team2_damage < {$min_dmg} and 
                            game_time_end - game_time_start < {$max_delta};") or die (mysql_error()); 
        while ($row=mysql_fetch_array($sql)) 
        { 
            switch ($row[game_type]) 
            { 
                case "2": 
                if ($row[team1_kb] < $min_kb_2 && $row[team2_kb] < $min_kb_2) 
                    mysql_query ("UPDATE arena_leavers_stat set count = count + 1 where teamid in ({$row[team1_id]}, {$row[team2_id]})"); 
                break; 
                 
                case "3": 
                if ($row[team1_kb] < $min_kb_3 && $row[team2_kb] < $min_kb_3) 
                    mysql_query ("UPDATE arena_leavers_stat set count = count + 1 where teamid in ({$row[team1_id]}, {$row[team2_id]})"); 
                break; 
                 
                case "5": 
                if ($row[team1_kb] < $min_kb_5 && $row[team2_kb] < $min_kb_5) 
                    mysql_query ("UPDATE arena_leavers_stat set count = count + 1 where teamid in ({$row[team1_id]}, {$row[team2_id]})"); 
                break; 
            } 
        } 
    } 
    //для обычных логов, смотрим только время 
    else 
    { 
        $sql = mysql_query("SELECT * FROM `arena_leavers_temp` where  
                            game_time_end - game_time_start < {$max_delta}") or die (mysql_error()); 
        while ($row=mysql_fetch_array($sql)) 
            mysql_query ("UPDATE arena_leavers_stat  
                        set count = count + 1 where teamid in ({$row[team1_id]}, {$row[team2_id]})"); 
    } 
    // всё! 
    $sql = mysql_query("SELECT SUM(COUNT) as sum FROM `arena_leavers_stat`") or die (mysql_error()); 
    $total = mysql_fetch_array($sql); 
    echo "<br />Работа скрипта завершена. <br /> 
        Переливов выявлено: {$total[sum]}<br /> 
        Можете использовать данные по каждой команде в таблице `arena_leavers_stat` в любых целях"; 
} 
?>