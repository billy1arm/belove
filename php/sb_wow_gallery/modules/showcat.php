<style type="text/css">
td {background-repeat:no-repeat}
</style>
<td width="240" height="177" background="images/w01.jpg">
<?php
if (!defined('GALLERY')) die ("Hack attempt!");
$start = 0;
if (is_numeric($_GET[page]) && $_GET[page]>0)
	{
		$start += ($_GET[page]-1)*12;
		$page = $_GET[page];
	}
switch ($_GET[cat])
{
  case "girls":
		echo "<a href = index.php?action=show>Все пользователи</a> >>> Девушки нашего сервера";
	   $where = "gender = 1 and";
   break;
   case "guys": 
	   echo "<a href = index.php?action=show>Все пользователи</a> >>> Парни нашего сервера";	
	   $where = "gender = 0 and";
   break;
   case "horde": 
	   echo "<a href = index.php?action=show>Все пользователи</a> >>> Игроки орды";
	   $where = "char_race IN (2, 5, 6, 8, 10) and";
   break;	 
   case "alliance": 
	   echo "<a href = index.php?action=show>Все пользователи</a> >>> Игроки альянса";
	   $where = "char_race IN (1, 3, 4, 7, 11) and";
   break;   
   default:		 
   	   echo "<a href = index.php?action=show>Все пользователи</a>";
	  $where = "";
}
$query = "select * from users where {$where} photo > 0 limit $start, 12  ";
$sql = mysql_query($query) or die(mysql_error());
$i = 0;
echo '<ul><table width = "440"><tr>';
while ($row = mysql_fetch_assoc($sql)) 
{
	if ($i == 3) {
	echo "</tr><tr>";
	$i = 0;	
	}
	print '<td>
	<a rel="lightbox" href= "photos/'.$row[id].'/'.$row[photo].'.jpg" title = "'.$row[char_name].'.Уровень : '.$row[char_level].
	', '.showClass($row[char_class]).', '.showRace($row[char_race]).'">
	<img src="photos/'.$row[id].'/thumbs/'.$row[photo].'.jpg" /></a><br>
	<a href = index.php?action=showuser&id='.$row[id].'><b>'.$row[char_name].'</b></a><br>
	Уровень : '.$row[char_level].'<br>
	Раса: '.showRace($row[char_race]).'<br>
	Класс: '.showClass($row[char_class]).'<br>
	</td>';
	$i++;
}
echo '</tr></table></ul><center>';
$query = "select * from users where {$where} photo > 0";
$sql = mysql_query($query) or die(mysql_error());
	for ($i = 0; $i < ceil(mysql_num_rows($sql)/12); $i++)
		if ($i+1 == $page) echo " ",$i+1, " ";
			else echo "<a href = index.php?action=show&cat=$_GET[cat]&page=",$i+1,">",$i+1,"</a> ";	
	echo "</center>";
?>
</td>