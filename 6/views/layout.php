<!DOCTYPE html>
<html>
<head>
    <title>Блог</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" media="screen">
    <link rel="stylesheet" href="css/style.css" media="screen">
</head>
<body>
    <div class="container">
        <header class="page-header">
            <h1><?= $title ?></h1>
        </header>

        <menu class="nav nav-pills">
            <?= $menu ?>
        </menu>

        <hr>

        <main class="container-fluid" role="main">
            <?= $view ?>
        </main>

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