<?php  
#################################################  
# Log analyzer arena TrinityCore / Mangos #
# It is intended to identify "arena leavers"   #  
#################################################  

###################################################################################################
# Contents
#
# AUTHOR CONTACT
# Description of the script
# INSTRUCTIONS FOR USE
# database connection
# Customize Script
#
###################################################################################################

###################################################################################################
# AUTHOR CONTACT
#
# Author: BeLove
# ICQ: 3320443
# Email: sergeybelove@gmail.com
#
###################################################################################################

##################################################################################################
# Description of the script
#
#
##################################################################################################

###################################################################################################  
# INSTRUCTIONS FOR USE  - SQL FILE
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
# database connection
#
# Specify the options below to connect: host, user name, password, database name 

$mysql_host = "localhost";  
$mysql_user = "root";  
$mysql_pass = "";  
$mysql_db = "characters";  

#  
###################################################################################################  

###################################################################################################  
# Customize Script
#
# Arena Log File Name

$arena_log_file = "arena.log";  

#    Minimal Game Duration Difference  

$max_delta = 40;  

# Parse the extended log (enter 0 if the worldconfig shows ArenaLog.ExtendedInfo = 0, and vice versa)

$extended_logs = 0;  

# Do not modify anything below unless ArenaLog.ExtendedInfo = 0
# Minimum value of healing / damage to both teams for one game (total damage and heal game)

$min_heal = 1;  
$min_dmg = 1;  

# Minimum value for the killing blow of the teams for one game
# (depending on the type of game (2x2, 3x3, 5x5)
# specify "0 "if you do not want to consider this option  

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
# First Step 
# Includes transfer of partially processed entries about games in MySQL
#  
###################################################################################################  

if (!isset($_GET[step]))  
{  

// this is the case for earlier zero 
$team1_damage = 0; $team2_damage = 0;  
$team1_heal = 0; $team2_heal = 0;  
$team1_kb = 0; $team2_kb = 0;  

echo "<h3> first stage. </ h3> Transferring the necessary information from the logs to MySQL DB<br />
     <i> processsing....</ i> <br/> <br/> ";
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
        // check whether there is a record of the beginning for this game. game_time_end.
        $query = mysql_query ("SELECT game_time_start FROM `arena_leavers_temp` where team1_id = {$arena[8]} and team2_id = {$arena[11]}  
                                and game_time_end = ''") or die(mysql_error());  
        $sql = mysql_fetch_array($query);  
        if ($sql['game_time_start'] > 0)  
        {  
            // record datetime of the game
            $ymd = explode ("-", $arena[0]);  
            $hms = explode (":", $arena[1]);  
            $time = mktime($hms[0], $hms[1], $hms[2], $ymd[1], $ymd[2], $ymd[0]);  
            // record extended statistics, if necessary
            if ($extended_logs)  
            {  
                // Reset summary data (damage, healing, the number of murders)
                $team1_damage = 0; $team2_damage = 0;  
                $team1_heal = 0; $team2_heal = 0;  
                $team1_kb = 0; $team2_kb = 0;  
                $extended_info = explode(" ", fgets($fp, 999));  
                while ($extended_info[2] == "Statistics")  
                {  
                    // A comma at the end - not to cut her out of the first variable, faster and easier:) -- NOT TRANSLATED PROPERLY
                    // for team A -- NOT TRANSLATED PROPERLY
                    if ($extended_info[8] == $arena[8].",")  
                        {  
                              
                            $team1_damage = $team1_damage + $extended_info[11];  
                            $team1_heal = $team1_heal + $extended_info[13];  
                            $team1_kb = $team1_kb + $extended_info[15];  
                        }  
                    // for team B
                    else if ($extended_info[8] == $arena[11].",")  
                        {  
                            $team2_damage = $team2_damage + $extended_info[11];  
                            $team2_heal = $team2_heal + $extended_info[13];  
                            $team2_kb = $team2_kb + $extended_info[15];  
                        }  
                    $extended_info = explode(" ", fgets($fp, 999));  
                }  
            }  
            // record data to DB
            mysql_query ("update `arena_leavers_temp` set   
                        game_time_end = '{$time}',  
                        team1_damage = '{$team1_damage}', team1_heal = {$team1_heal}, team1_kb = {$team1_kb},   
                        team2_damage = {$team2_damage}, team2_heal = {$team2_heal}, team2_kb = {$team2_kb}  
                        where  
                        game_time_start = '{$sql['game_time_start']}' and  
                        team1_id = {$arena[8]} and  
                        team2_id =  {$arena[11]};") or die (mysql_error());  
        } else echo "<B>[notice]</b> Was found by recording the end of the game, because there's no earlier information!  
                Game {$arena[8]} vs {$arena[11]}. Game Type - {$arena[5]} x {$arena[5]}<br />";  
    }  
}  
} else echo "Error opening file $arena_log_file. File maybe not exist.";  
fclose($fp);  

echo "<br />Data Transfer completed successfully!<br />  
    <a href = ?step=2>Go to the second step...</a>";  
}  

###################################################################################################  
#  
# second step
# Games Analyser
#  
###################################################################################################  

if (isset($_GET[step]) && $_GET[step] == 2)  
{  
    echo "<h3> second step. </ h3> Analysing Data. ";  
    $sql = mysql_query("SELECT count(1) as count FROM `arena_leavers_temp`;") or die (mysql_error());  
    $result = mysql_fetch_array($sql);  
    echo "<br /><br /><li>Total Games: <b>$result[count]</b></li>";  
    $sql = mysql_query("SELECT 1 FROM `arena_leavers_temp` where game_time_end = '';") or die (mysql_error());  
    $result = mysql_num_rows($sql);  
    if ($result) echo "<li>Number of games that we couldn't find the final data: <b>$result</b></li>  
    <small>* Possible causes - incomplete log / Server Crash during games</small><br />";  
    $sql = mysql_query("SELECT team1_id FROM `arena_leavers_temp` where game_time_end <> ''  
                        UNION SELECT `team2_id` FROM `arena_leavers_temp` where game_time_end <> '';") or die (mysql_error());  
    $result = mysql_num_rows($sql);  
    echo "<li>Number of teams to handle: <b>$result</b><br />";  
    //fill in the "zero" statistics for each team
    mysql_query ("TRUNCATE `arena_leavers_stat`") or die(mysql_error());   
    while ($row=mysql_fetch_assoc($sql))  
    {  
        mysql_query ("INSERT INTO `arena_leavers_stat` VALUES ({$row[team1_id]}, '0')") or die (mysql_error());  
    }  
    //Start analysing. For extended logs:
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
    //conventional logs, non-extended logs
    else  
    {  
        $sql = mysql_query("SELECT * FROM `arena_leavers_temp` where  
                            game_time_end <> '' and  
                            game_time_end - game_time_start < {$max_delta}") or die (mysql_error());  
        while ($row=mysql_fetch_array($sql))  
            mysql_query ("UPDATE arena_leavers_stat   
                        set count = count + 1 where teamid in ({$row[team1_id]}, {$row[team2_id]})");  
    }  
    // everything!
    $sql = mysql_query("SELECT SUM(COUNT) as sum FROM `arena_leavers_stat`") or die (mysql_error());  
    $total = mysql_fetch_array($sql);  
    echo "<br />Working script zavershena. <br /> 
        Arena Leavers identified: {$total[sum]}<br /> 
        You can use the data for each team in the table `arena_leavers_stat` for any purpose";  
}  
?>