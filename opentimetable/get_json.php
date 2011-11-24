<?php
	include("config.php");
	if ($_POST["type"]=="country")
	{
		$get_query="SELECT * FROM country";
		$res = mysql_query($get_query) or die(mysql_error());
		$str=array();
		while ($row = mysql_fetch_array($res))
		{
			$str[]=$row;
		}
		
		echo json_encode($str);
	} 
	elseif ($_POST["type"]=="city") 
	{	
		$country_name=trim(mysql_real_escape_string($_POST['country']));		
		$get_query="SELECT * FROM country  JOIN city ON country.id=city.country_id where country.url_name='$country_name'";
		$res = mysql_query($get_query) or die(mysql_error());
		$str=array();
		while ($row = mysql_fetch_array($res))
		{
			$str[]=$row;
		}
		echo json_encode($str);
	} 
	elseif ($_POST["type"]=="org_type")
	{
		$get_query="SELECT * FROM category";
		$res = mysql_query($get_query) or die(mysql_error());
		$str=array();
		while ($row = mysql_fetch_array($res))
		{
			$str[]=$row;
		}
		echo json_encode($str);	 
	}
?>