<?php
/*
SB WoW Gallery - Serg BeLove World Of WarCraft Gallery
script for mangos/trinity or look like db struct WoW emu
ICQ: 3320443
*/
session_start();
error_reporting(0);
define ("GALLERY", true);
include ("mysql.php");
include ("functions.php");
?>
<html>
<head>
	<title>GameWorld.kz. Фотогалерея игроков World Of WarCraft.</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<style>
td{font-family:Verdana;font-size:10px;color:#000000}
.small{font-size:9px;padding-top:5px;padding-right:40px;}
.cap{font-family:HeliosExt;font-weight:bold;font-size:8px;color:#287729;padding-top:10px;}
.search{font-family:HeliosExt;font-weight:bold;font-size:8px;color:#003F67}
.best{font-family:HeliosExt;font-weight:bold;font-size:10px;color:#505050;padding-top:60px;padding-right:40px;}
	/* jQuery lightBox plugin - Gallery style */
	#gallery {
		width: 460px;
	}
	#gallery ul { list-style: none; }
	#gallery ul li { display: inline; }
	#gallery ul img {

		border-width: 5px 5px 20px;		
		padding: 2px;
	}
</style>
    <!-- Arquivos utilizados pelo jQuery lightBox plugin -->
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/jquery.lightbox-0.5.js"></script>
    <link rel="stylesheet" type="text/css" href="css/jquery.lightbox-0.5.css" media="screen" />
    <!-- / fim dos arquivos utilizados pelo jQuery lightBox plugin -->
    
    <!-- Ativando o jQuery lightBox plugin -->
<script type="text/javascript">
$(function() {
	$('a[@rel*=lightbox]').lightBox(); // Select all links that contains lightbox in the attribute rel

});
</script>
</head>

<body topmargin="0" leftmargin="0" bottommargin="0" rightmargin="0">
<table cellpadding="0" cellspacing="0" border="0" height="100%"	>
<tr>
	<td rowspan="10" width="50%" height="100%" background="images/bg1222.jpg" style="background-position:right top; background-repeat:repeat-y"></td>
	<td rowspan="10" width="1" bgcolor="#000000"></td>
	<td colspan="2">
	<OBJECT classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0" WIDTH=780 HEIGHT=189>		 
		 <PARAM NAME=movie VALUE="head.swf">
		 <PARAM NAME=quality VALUE=high>
		 <PARAM NAME=bgcolor VALUE=#ffffff>
		 <EMBED src="head.swf" quality=high bgcolor=#ffffff  WIDTH=780 HEIGHT=189 TYPE="application/x-shockwave-flash" PLUGINSPAGE="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash">
		 </EMBED>
	</OBJECT>	
	</td>
	<td rowspan="10" width="1" bgcolor="#000000"></td>
	<td rowspan="10" width="50%" height="100%" background="images/bg1223.jpg" style="background-position:left top; background-repeat:repeat-y"></td>	
</tr>
<tr>
	<td valign="top" height="100%" background="images/bg011.gif">
	<table cellpadding="0" cellspacing="0" border="0" height="100%">
		<tr>
			<td><img src="images/cap01.jpg"></td>
		</tr>
		<tr>
			<td width="284" height="217" background="images/bg01.jpg" valign="top" style="padding-top:25px;padding-left:55px;padding-right:40px;">
			<table cellpadding="0" cellspacing="0" border="0">
				<?php
				if (isset($_SESSION['user_id'])) {
					print '<tr>
					<td colspan="2"><div class="search">Мы рады вас видеть, <b>'.$_SESSION['user_name'].'</b></div><br></td>
				</tr>';
				$user_id = mysql_real_escape_string($_SESSION['user_id']);
				$query = "SELECT photo FROM `users` WHERE `id`='{$user_id}'";
				$sql = mysql_query($query) or die(mysql_error());
				$rows = mysql_fetch_assoc($sql);
				//echo 'photos/'.$user_id.'/'.$rows[photo].'.jpg';
				print '
				<tr>
					<td><a href = index.php?action=account><img src = ';
					//avatar check
					
					if ($rows[photo] <> 0)
						print '"photos/'.$user_id.'/thumbs/'.$rows[photo].'.jpg">';
					else print '"images/noavatar.jpeg">';
					//end ava check
					print '</a></td>
				</tr>
				<tr>
					<td><a href = index.php?action=account>Личный кабинет</a></td>
				</tr>
				<tr>
					<td><a href = index.php?action=account&logout><b>Выход</b></a></td>
				</tr>				
			</table>
			</td>	

		</tr>';
					}
				else print '<form action="index.php?action=auth" method="post"><tr>
					<td colspan="2"><div class="search">Введите свой логин и пароль от аккаунта в WoW для авторизации</div></td>
				</tr>
				<tr><td height="5"></td></tr>
				<tr>
					<td>Логин:</td>
					<td><input type="text" name = "login" style="width:122;height:20"></td>
				</tr>
				<tr>
					<td>Пароль:</td>
					<td><input type="password" name="password" style="width:122;height:20"></td>
				</tr>
					<td><input type="image" src="images/g0.gif" border="0"></td>
			</table>
			</td>	
			</form>
		</tr>';
		?>
		<tr>
			<td><img src="images/cap02.jpg"></td>
		</tr>
		<tr>
			<td width="284" height="261" background="images/bg02.jpg" valign="top" style="padding-top:10px;padding-right:30px;padding-left:40px;">
			<h2>Статистика:</h2>
			<?php
			$query = "SELECT sum(photos_count) as photo_count, count(id) as users_count FROM `users`";
			$sql = mysql_query($query) or die(mysql_error());
			$rows = mysql_fetch_assoc($sql);
			echo "Всего фотографий : ",$rows[photo_count],"<br>";
			echo "Пользователей : ",$rows[users_count],"<br>";
			?>
			</td>	
		</tr>
		<tr>
			<td height="100%"></td>
		</tr>
		<tr>
			<td>&nbsp; Powered by SB WoW Gallery</td>
		</tr>
		<tr>
			<td height="55"></td>
		</tr>
	</table>
	</td>
	<td valign="top" height="100%" background="images/bg012.gif">
	<table cellpadding="0" cellspacing="0" border="0" height="100%">
		<tr>
			<td colspan="2"><img src="images/subheader.jpg"></td>
		</tr>

		<tr>

<?php
	include(module_switch($_GET[action]));
?>
		</tr>
		<tr>
			<td colspan="2" height="100%" valign="bottom" align="center" style="padding-bottom:15px;"><div class="best" style="font-size:9px;">Server name</div></td>
		</tr>
	</table>
	</td>
</tr>
<tr>
	<td colspan="2"><a href=""><img src="images/f01.jpg" border="0"></a><a href=""><img src="images/f02.jpg" border="0"></a><a href=""><img src="images/f03.jpg" border="0"></a><a href=""><img src="images/f04.jpg" border="0"></a><a href=""><img src="images/f05.jpg" border="0"></a><a href=""><img src="images/f06.jpg" border="0"></a></td>
</tr>
</table>
</body>
</html>
