<td width="245" height="177" background="images/w01.jpg"  valign="top">
<style type="text/css">
td {background-repeat:no-repeat}
</style>
<?php
if (!defined('GALLERY')) die ("Hack attempt!");
echo "Поиск пользователей<br>";
if (isset($_POST[user]))
{
$username = strip_tags(trim(htmlspecialchars($_POST[user])));
mysql_query("SET NAMES 'utf8'");
$notfound = 0;
if (!preg_match('#^(?:[а-я_]+|[a-z_]+)$#i', $username)) $notfound = 1;
$query="SELECT * FROM users WHERE char_name LIKE '%".mysql_real_escape_string($_POST['user'])."%' and photo > 0";
$sql = mysql_query($query) or die(mysql_error());
$rows_num = mysql_num_rows($sql);
echo "Имя персонажа : $username";
if ($rows_num > 0 && $notfound == 0)
{
	echo '<br><br>
	  <center><table border = "1" width = "450">
	  <tr><td>Фото</td> <td>Имя персонажа</td> <td>Уровень</td> <td>Раса</td> <td>Класс</td>';
	for ($i = 0; $i < $rows_num; $i++)
	{
		$row = mysql_fetch_assoc($sql);
		echo "<tr align = center><td><a href = index.php?action=showuser&id=".$row[id]."><img src = photos/".$row[id]."/thumbs/".$row[photo].".jpg></a></td>
				<td><a href = index.php?action=showuser&id=".$row[id]."><b>".$row[char_name]."</b></a></td>
				<td>$row[char_level]</td>
				<td>".showRace($row[char_race])."</td>
				<td>".showClasS($row[char_class])."</td></tr>";
	}
	echo "</table></center>";
}
	else print '<form action="index.php?action=search'.$_GET[user].'" method="POST">
			<input type="text" name="user" /><br>
			<input type="submit" value="Поиск" /></form>
			<br>С таким именем совпадений не найдено';
}
else print '<form action="index.php?action=search'.$_GET[user].'" method="POST">
			<input type="text" name="user" /><br>
			<input type="submit" value="Поиск" /></form>
			'
?>
</td>