<?php 
if (!defined('GALLERY')) die ("Hack attempt!"); 
echo "6 последних добавленных фото:";
$query = "select * from `users` where photo_last > 0 ORDER BY photo_last DESC limit 6";
$sql = mysql_query($query) or die(mysql_error());
$chars = array (array('','','','','',''),
				array('','','','','',''),
				array('','','','','',''),
				array('','','','','',''),
				array('','','','','',''),
				array('','','','','',''));
$i = 0;
while ($row = mysql_fetch_assoc($sql)) 
{
		$chars[$i][0] = $row['char_name'];
		$chars[$i][1] = $row['photo_last'];
		$chars[$i][2] = $row['char_level'];
		$chars[$i][3] = $row['char_race'];
		$chars[$i][4] = $row['char_class'];
		$chars[$i][5] = $row['id'];
		$i++;
}
?>
<td width="245" height="177"  background="images/w02.jpg" style="padding-left:30px;padding-top:40px;" valign="top">
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td><img src="photos/<?php echo $chars[0][5]."/thumbs/".$chars[0][1].".jpg"; ?>" hspace="10" vspace="5"></td>
					<td width="180" valign="top">
					<div class="cap"><a href = index.php?action=showuser&id=<?php echo $chars[0][5].">".$chars[0][0]."</a>"; ?></div>
					<div class="small"><?php echo $chars[0][2]."lvl<br> ".showRace($chars[0][3]).", ".showClass($chars[0][4]); ?></div>
					</td>
				</tr>
				<tr>

				</tr>
			</table>
			</td>
			<td width="251" height="177" background="images/w03.jpg" valign="top" style="padding-left:11px;padding-top:40px;">
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td><img src="photos/<?php echo $chars[1][5]."/thumbs/".$chars[1][1].".jpg"; ?>" hspace="10" vspace="5"></td>
					<td width="180" valign="top">
					<div class="cap"><a href = index.php?action=showuser&id=<?php echo $chars[1][5].">".$chars[1][0]."</a>"; ?></div>
					<div class="small"><?php echo $chars[1][2]."lvl<br> ".showRace($chars[1][3]).", ".showClass($chars[1][4]); ?></div>
					</td>
				</tr>
				<tr>
				</tr>
			</table>
			</td>
		</tr>
		<tr>
			<td width="245" height="172" background="images/w04.jpg" style="padding-left:30px;padding-top:40px;" valign="top">
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td><img src="photos/<?php echo $chars[2][5]."/thumbs/".$chars[2][1].".jpg"; ?>" hspace="10" vspace="5"></td>
					<td width="180" valign="top">
					<div class="cap"><a href = index.php?action=showuser&id=<?php echo $chars[2][5].">".$chars[2][0]."</a>"; ?></div>
					<div class="small"><?php echo $chars[2][2]."lvl<br> ".showRace($chars[2][3]).", ".showClass($chars[2][4]); ?></div>
					</td>
				</tr>
				<tr>
				</tr>
			</table>
			</td>
			<td width="251" height="172" background="images/w05.jpg" valign="top" style="padding-left:11px;padding-top:40px;">
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td><img src="photos/<?php echo $chars[3][5]."/thumbs/".$chars[3][1].".jpg"; ?>" hspace="10" vspace="5"></td>
					<td width="180" valign="top">
					<div class="cap"><a href = index.php?action=showuser&id=<?php echo $chars[3][5].">".$chars[3][0]."</a>"; ?></div>
					<div class="small"><?php echo $chars[3][2]."lvl<br> ".showRace($chars[3][3]).", ".showClass($chars[3][4]); ?></div>
					</td>
				</tr>
				<tr>
				</tr>
			</table>
			</td>
		</tr>
		<tr>
			<td width="245" height="175" background="images/w06.jpg" style="padding-left:30px;padding-top:40px;" valign="top">
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td><img src="photos/<?php echo $chars[4][5]."/thumbs/".$chars[4][1].".jpg"; ?>" hspace="10" vspace="5"></td>
					<td width="180" valign="top">
					<div class="cap"><a href = index.php?action=showuser&id=<?php echo $chars[4][5].">".$chars[4][0]."</a>"; ?></div>
					<div class="small"><?php echo $chars[4][2]."lvl<br> ".showRace($chars[4][3]).", ".showClass($chars[4][4]); ?></div>
					</td>
				</tr>
				<tr>
				</tr>
			</table>
			</td>
			<td width="251" height="175" background="images/w07.jpg" style="padding-left:11px;padding-top:40px;" valign="top">
			<table cellpadding="0" cellspacing="0" border="0">
				<tr>
					<td><img src="photos/<?php echo $chars[5][5]."/thumbs/".$chars[5][1].".jpg"; ?>" hspace="10" vspace="5"></td>
					<td width="180" valign="top">
					<div class="cap"><a href = index.php?action=showuser&id=<?php echo $chars[5][5].">".$chars[5][0]."</a>"; ?></div>
					<div class="small"><?php echo $chars[5][2]."lvl<br> ".showRace($chars[5][3]).", ".showClass($chars[5][4]); ?></div>
					</td>
				</tr>
				<tr>
				</tr>
			</table>
</td>