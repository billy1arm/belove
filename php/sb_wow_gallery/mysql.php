<?php
	if (!defined('GALLERY')) die ("Hack attempt!");
	$user = "root"; //auth && characters db's user
	$pass = ""; //auth && characters db's pass
	$host = "localhost";
	$auth_db = "auth"; //auth DB name
	$char_db = "char"; //characters DB name
	
	$gallery_user = "root";
	$gallery_pass = "";
	$gallery_host= "localhost";
	$gallery_db = "gallery";

    mysql_pconnect($gallery_host, $gallery_user, $gallery_pass) or die (mysql_error());
	mysql_select_db($gallery_db) or die (mysql_error());
	
?>