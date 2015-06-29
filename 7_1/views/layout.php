<!DOCTYPE html>
<html>
<head>
    <title>Блог</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" media="screen">
    <link rel="stylesheet" href="css/style.css" media="screen">
</head>
<body>
    <header>
        <div class="container text-right">
            <br>
            <? if(null == $user_login): ?>
                <a href="/User/Login" class="btn btn-info btn-sm">Вход</a>
                <a href="/User/Register" class="btn btn-info btn-sm">Регистрация</a>
            <? else: ?>
                <span>Текущий пользователь: [ <?= $user_login ?> ]</span>
                <a href="/User/Logout" class="btn btn-info btn-sm">Выход</a>
            <? endif ?>
        </div>
    </header>
    <div class="container">
        <?= $content ?>

        <hr>

        <footer class="footer">
            <div class="container text-center">
                <small><a href="mailto:scorp7mix@gmail.com">scorp7mix@gmail.com</a> &copy;</small>
            </div>
        </footer>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</body>
</html>