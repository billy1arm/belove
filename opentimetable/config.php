<?php
	$dbhost = "localhost";
	$dbname = "timetable";
	$dbuser = "timetable";
	$dbpasswd = "";
	$link = mysql_connect($dbhost,$dbuser,$dbpasswd) or die(mysql_error()); 
	mysql_select_db($dbname,$link)  or die(mysql_error());
?>