<td width="245" height="177" background="images/w01.jpg"  valign="top">
<?php
if (!defined('GALLERY')) die ("Hack attempt!");
if (is_numeric($_GET[id]))
{
$query = "select * from users where id = {$_GET[id]}";
$sql = mysql_query($query) or die(mysql_error());
$rows = mysql_fetch_assoc($sql);
print '<h2>Фотографии игрока '.$rows[char_name].'</h2><ul>
		Уровень : '.$rows[char_level].'<br>
		Класс : '.showClass($rows[char_class]).'<br>
		Раса : '.showRace($rows[char_race]).'<br><br>';
$dir = "photos/".$_GET[id];
	$i = 0;
	if ($dh = opendir($dir)) {
		while (($file = readdir($dh)) !== false) {
			if ($file != "." && $file != ".." && $file != "thumbs" ) 
				{
					if ($i == 3)
					{
						echo "<br>";
						$i = 0;
					}
					print '<a rel="lightbox" href="'.$dir.'/'.$file.'" >
					<img src="'.$dir.'/thumbs/'.$file.'" /></a>';
		$i++;
				}
						   }
						   closedir($dh);
					   }
					echo '</ul>';

}
else {
    print 'Не верные входные данные.';
}
?>
</td>