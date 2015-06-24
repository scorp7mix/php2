<?php
include_once('model/startup.php');
include_once('model/M_Users.php');

// Установка параметров, подключение к БД, запуск сессии.
startup();

// Менеджеры.
$mUsers = M_Users::GetInstance();

// Очистка старых сессий.
$mUsers->ClearSessions();

// Обработка отправки формы.
if (!empty($_POST))
{
    $reg_result = $mUsers->Register($_POST['login'], $_POST['password']);
    if (true === $reg_result)
    {
        header('Location: index.php');
        die();
    }
    $login = $_POST['login'];
    $password = $_POST['password'];
}

// Кодировка.
header('Content-type: text/html; charset=utf-8');
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <title>Веб-Гуру</title>
    <meta content="text/html; charset=Windows-1251" http-equiv="content-type">
    <link rel="stylesheet" type="text/css" media="screen" href="theme/style.css" />
</head>
<body>
<h1>Регистрация</h1>
<a href="index.php">Главная</a>
<form method="post">
    E-mail: <input type="text" name="login" value="<?= isset($login) ? $login : '' ?>"/><br/>
    Пароль: <input type="password" name="password"  value="<?= isset($password) ? $password : '' ?>"/><br/>
    <input type="submit" />
</form>
<?= isset($reg_result) ? $reg_result : '' ?>
</body>
</html>
