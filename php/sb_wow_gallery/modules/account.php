<td width="245" height="177" background="images/w01.jpg" style="padding-left:30px;padding-top:40px;" valign="top">
<?php
if (!defined('GALLERY')) die ("Hack attempt!");
if (isset($_GET[logout]))
   {
		unset($_SESSION['user_id']);
		echo "Вы успешно вышли!<br>";
   }
if (isset($_SESSION['user_id'])) 
{
   session_start();
   $user_id = mysql_real_escape_string($_SESSION['user_id']);
	if (!check_user($user_id) || isset($_GET[edit_profile]))
	{			
			 user_profile($user_id);
	}
	else
	{
			$errors=0; //0 - нет препятствий для заливки новых фото
			$query = "SELECT * FROM `users` WHERE `id`='{$user_id}'";
			$sql = mysql_query($query) or die(mysql_error());
			$rows = mysql_fetch_assoc($sql);
			if ($rows[photos_count] == 6) { $errors = 1; echo "Уведомление: вы достигли максимума загружаемых фото"; }
			//define a maxim size for the uploaded images in Kb
			 define ("MAX_SIZE","1024"); 
			 if(isset($_POST['Submit']) && $errors == 0) 
			 {
				$image=$_FILES['image']['name'];
				if ($image) 
				{
				//get the original name of the file from the clients machine
					$filename = stripslashes($_FILES['image']['name']);
				//get the extension of the file in a lower case format
					$extension = getExtension($filename);
					$extension = strtolower($extension);
			 if (($extension != "jpg") && ($extension != "jpeg")) 
					{
					//print error message
						echo 'Не верный формат файла! Используйте фотографии с расширением jpeg/jpg<br><br>';
						$errors=1;
					}
					else
					{
			 $size=filesize($_FILES['image']['tmp_name']);
			if ($size > MAX_SIZE*1024)
			{
				echo '<h1>Вы превысили лимит веса фотографии! (1 мб)</h1>';
				$errors=1;
			}
			if ($errors == 0)
			{
				$image_name = time();
				$query = "select photo from users where id = {$user_id}";
				//если у пользователя нет аватары, то ставим загружаемое фото ему аватарой
				//чтобы он автоматом попал в список категорий (скорее всего первая загрузка фото)
				$ava_check = mysql_query($query) or die(mysql_error());
				$ava_check = mysql_fetch_assoc ($ava_check);
				if ($ava_check[photo] == "0") $set_ava = "photo = {$image_name},";
				$query = "update `users` set photos_count = photos_count + 1, {$set_ava} photo_last = {$image_name} WHERE `id`='{$user_id}'";
				mysql_query($query) or die(mysql_error());
				$newname="photos/".$user_id."/".$image_name.'.jpg';
				imageJpeg(resizeImage($_FILES['image']['tmp_name'],300,700), $newname);
				$thumbname="photos/".$user_id."/thumbs/".$image_name.'.jpg';
				imageJpeg(resizeImage($_FILES['image']['tmp_name'],100,100), $thumbname);
				echo "Фотография успешно загружена!";
			}
			}}}
			 		print '
					<h3>Личный кабинет</h3>
					<br><a href = index.php?action=account&edit_profile>Изменить личные данные</a><br><br>
					Загрузить новое фото (при загрузке посторонних фото и любых "левых" изображений - получаете бан здесь и бан в игре):
					 <form name="newad" method="post" enctype="multipart/form-data"  action="">
					 <table>
						<tr><td><input type="file" name="image"></td></tr>
						<tr><td><input name="Submit" type="submit" value="Добавить"></td></tr>
					 </table>	
					 </form>
					 <b>Ваши фотографии:</b>
					<form method="post" action="index.php?action=account"><ul>';
					if (isset($_POST[delete]) )
					{
						$file = explode(".", $_POST[foto]);
						// гипер мудрая проверка входящих данных, дабы нам левого не снесли
						//is_file для того, чтобы проверить, есть ли вообще файл
						if ((is_numeric($file[0])) && ($file[1] == "jpg") &&
						is_file('photos/'.$user_id.'/'.$_POST[foto]))
						{
							$query = "select photo, photo_last from users WHERE `id`='{$user_id}'";
							$sql = mysql_query($query) or die(mysql_error());	
							$rows = mysql_fetch_assoc ($sql);
							if ($rows[photo] == "$file[0]") $empty_photo = ", photo = 0";
							if ($rows[photo_last] == "$file[0]") $empty_photo_last = ", photo_last = 0";
							$query = "update `users` set photos_count = photos_count -1
							{$empty_photo} {$empty_photo_last} WHERE `id`='{$user_id}'";
							$sql = mysql_query($query) or die(mysql_error());
														unlink ('photos/'.$user_id.'/'.$_POST[foto]);
							unlink ('photos/'.$user_id.'/thumbs/'.$_POST[foto]);
							
						}
					}
					if (isset($_POST[ava]) )
					{
						$file = explode(".", $_POST[foto]);
						if ((is_numeric($file[0])) && ($file[1] == "jpg" || $file[1] == "jpeg") &&
						is_file('photos/'.$user_id.'/'.$_POST[foto]))
						{
							$query = "update `users` set photo = '{$_POST[foto]}' WHERE `id`='{$user_id}'";
							$sql = mysql_query($query) or die(mysql_error());	
							echo "<script>alert('Аватара успешно выставлена!');</script>";
						}
					}
					$dir = "photos/".$user_id;
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
										<img src="'.$dir.'/thumbs/'.$file.'" /></a><input name="foto" type="radio" value="'.$file.'">';
								$i++;
								}
						   }
						   closedir($dh);
					   }
					echo '</ul></div><br>
					<input name="delete" type=submit value="Удалить выбранное фото"> 
					<input name="ava" type=submit value="Назначить аватарой">
					</form>
					<br>
					*Максимум 6 фотографий';

	}
}
else {
    print 'Доступ к личному кабинету закрыт, пожалуйста, авторизуйтесь!';
}
?>
</td>