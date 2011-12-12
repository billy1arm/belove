<td width="245" height="177" background="images/w01.jpg" style="padding-left:30px;padding-top:40px;" valign="top">
<?php
if (!defined('GALLERY')) die ("Hack attempt!");
if (isset($_POST['login']) && isset($_POST['password']))
{
	mysql_connect($host, $user, $pass) or die (mysql_error());
    mysql_select_db($auth_db) or die (mysql_error());
    $login = strtoupper($_POST['login']);
    $password = strtoupper($_POST['password']);
	$pass_hash = SHA1($login.':'.$password);
    // делаем запрос к БД
    // и ищем юзера с таким логином и паролем

    $query = "SELECT `id`,`username`
            FROM `account`
            WHERE `username`='{$login}' AND `sha_pass_hash`='{$pass_hash}'
            LIMIT 1";
    $sql = mysql_query($query) or die(mysql_error());

    // если такой пользователь нашелся
    if (mysql_num_rows($sql) == 1) {
        // то мы ставим об этом метку в сессии (ID пользователя)

        $row = mysql_fetch_assoc($sql);
        $_SESSION['user_id'] = $row['id'];
		$_SESSION['user_name'] = $row['username'];
		print "Авторизация успешная! <a href = index.php>На главную</a>";
    }
    else {
        echo ('Такой логин с паролем не найдены в базе данных. Попробуйте еще раз.');
    }
}
?>
</td>