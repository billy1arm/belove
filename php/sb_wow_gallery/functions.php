<?php
if (!defined('GALLERY')) die ("Hack attempt!");
function module_switch($page)
{
	switch ($page)
	{
	   case "auth":
		   $page = "modules/auth.php"; 
	   break;
	   case "account": 
		  $page = "modules/account.php"; 
	   break;
	   case "show": 
		  $page = "modules/showcat.php"; 
	   break;	
	   case "showuser": 
		  $page = "modules/showuser.php"; 
	   break;		
	   case "search": 
		  $page = "modules/search.php"; 
	   break;		 
	   case "contact": 
		  $page = "modules/contact.php"; 
	   break;		   
	   default:		 
		  $page = "modules/main.php";
	}
	return $page;
}

//This function reads the extension of the file. It is used to determine if the file  is an image by checking the extension.
function getExtension($str) {
	$i = strrpos($str,".");
	if (!$i) { return ""; }
	$l = strlen($str) - $i;
	$ext = substr($str,$i+1,$l);
	return $ext;
	}
function user_profile($user_id)
{
include ("mysql.php");
$query = "SELECT id FROM `users` WHERE `id`='{$user_id}'";
$sql = mysql_query($query) or die(mysql_error());
if (mysql_num_rows($sql) == 0)
{
		echo "<b>Создание профиля</b><br><br>";
		if (isset($_POST[char]) && is_numeric($_POST[gender]))
		{
			//add user profile
			//select char data
			mysql_connect($host, $user, $pass) or die (mysql_error());
			mysql_select_db($char_db) or die (mysql_error());
			$query = "SELECT name,race,class,level
					FROM `characters`
					WHERE `account`='{$user_id}'";
			$sql = mysql_query($query) or die(mysql_error());
			$rows = mysql_fetch_assoc($sql);
			if ($rows <> NULL)
			{
				//add new user
				mysql_connect($gallery_host, $gallery_user, $gallery_pass) or die (mysql_error());
				mysql_select_db($gallery_db) or die (mysql_error());
				$char_name = mysql_real_escape_string($_POST[char]);
				$query = "INSERT into users VALUES ({$user_id},{$_POST[gender]},0,0,0,
					'{$rows['name']}',{$rows['race']},{$rows['class']},${rows['level']})";
				//create user photo dir
				mkdir("photos/".$user_id, 0777);
				mkdir("photos/".$user_id."/thumbs", 0777);
				$sql = mysql_query($query) or die(mysql_error());
				echo "Успешно завершено!";
			} else echo "Странно, но этот персонаж вам не принадлежит...";
		}
		else
		{
			//show reg form
			mysql_connect($host, $user, $pass) or die (mysql_error());
			mysql_select_db($char_db) or die (mysql_error());
			$query = "SELECT name FROM `characters` WHERE `account`='{$user_id}'";
			$sql = mysql_query($query) or die(mysql_error());
			echo 'Пожалуйста, укажите имя основого персонажа
			<FORM ACTION="index.php?action=account" METHOD=POST><select name="char"> ';
			while ($row = mysql_fetch_assoc($sql)) 
			{
				echo '<option value=',$row['name'],'>',$row['name'],'</option>';
			}
			echo '</select><br><br>
			<br>Ваш пол:
			<input name="gender" type="radio" value= 0>Мужской
			<input name="gender" type="radio" value= 1>Женский
			<br><br><input name="Submit" type=submit value="Создать профиль"> </FORM>';
		}
}
else
{
		//edit profile
		echo "<b>Редактирование профиля</b><br><br>";
		if (isset($_POST[char]) && is_numeric($_POST[gender]))
		{
			//add user profile
			//select char data
			mysql_connect($host, $user, $pass) or die (mysql_error());
			mysql_select_db($char_db) or die (mysql_error());
			$charname = mysql_real_escape_string($_POST[char]);
			$query = "SELECT race,class,level
					FROM `characters`
					WHERE `name`='{$charname}'";
			$sql = mysql_query($query) or die(mysql_error());
			$rows = mysql_fetch_assoc($sql);
			if ($rows <> NULL)
			{
				//add new user
				mysql_connect($gallery_host, $gallery_user, $gallery_pass) or die (mysql_error());
				mysql_select_db($gallery_db) or die (mysql_error());
				$char_name = mysql_real_escape_string($_POST[char]);
				$query = "update users set gender = {$_POST[gender]},
										char_name = '{$_POST[char]}',
										char_race = {$rows['race']},
										char_class = {$rows['class']},
										char_level = {$rows['level']}
										where id = {$user_id}";
				$sql = mysql_query($query) or die(mysql_error());
				echo "Успешно завершено!";
			} else echo "Странно, но этот персонаж вам не принадлежит...";
		}
		else
		{
			//show edit form
			mysql_connect($host, $user, $pass) or die (mysql_error());
			mysql_select_db($char_db) or die (mysql_error());
			$query = "SELECT name FROM `characters` WHERE `account`='{$user_id}'";
			$sql = mysql_query($query) or die(mysql_error());
			echo 'Пожалуйста, укажите имя основого персонажа<br>
			<FORM ACTION="index.php?action=account&edit_profile" METHOD=POST><select name="char"> ';
			while ($row = mysql_fetch_assoc($sql)) 
			{
				echo '<option value=',$row['name'],'>',$row['name'],'</option>';
			}
			echo '</select><br><br>
			<br>Ваш пол:
			<input name="gender" type="radio" value= 0>Мужской
			<input name="gender" type="radio" value= 1>Женский
			<br><br><input name="Submit" type=submit value="Обновить данные"> </FORM>';
		}
}
}
function check_user($user_id)
{
	$query = "select id from users where id = $user_id limit 1";
	$sql = mysql_query($query) or die(mysql_error());
	$rows = mysql_num_rows($sql);
	if ($rows == 0)	return false;
		else return true;
}

function resizeImage($filename, $newwidth, $newheight){
    list($width, $height) = getimagesize($filename);
    if($width > $height && $newheight < $height){
        $newheight = $height / ($width / $newwidth);
    } else if ($width < $height && $newwidth < $width) {
        $newwidth = $width / ($height / $newheight);
    } else {
        $newwidth = $width;
        $newheight = $height;
    }
    $thumb = imagecreatetruecolor($newwidth, $newheight);
    $source = imagecreatefromjpeg($filename);
    imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
    return $thumb;
}
function showRace($race)
{
	switch ($race)
	{
	   case "1":
		   return "Человек";
	   break;
	   case "2":
		   return "Орк";
	   break;
	   case "3":
		   return "Дварф";
	   break;
	   case "4":
		   return "Ноч. эльф";
	   break;
	   case "5":
		   return "Нежить";
	   break;
	   case "6":
		   return "Таурен";
	   break;
	   case "7":
		   return "Гном";
	   break;
	   case "8":
		   return "Тролль";
	   break;
	   case "10":
		   return "Кров. эльф";
	   break;
	   case "11":
		   return "Дреней";
	   break;	   
	   default:		 
		  return "?";
	}
}

function showClass($class)
{
	switch ($class)
	{
	   case "1":
		   return "Воин";
	   break;
	   case "2":
		   return "Паладин";
	   break;
	   case "3":
		   return "Охотник";
	   break;
	   case "4":
		   return "Разбойник";
	   break;
	   case "5":
		   return "Прист";
	   break;
	   case "6":
		   return "ДК";
	   break;
	   case "7":
		   return "Шаман";
	   break;
	   case "8":
		   return "Маг";
	   break;
	   case "9":
		   return "Колдун";
	   break;
	   case "11":
		   return "Друид";
	   break;	   
	   default:		 
		  return "?";
	}
}
?>