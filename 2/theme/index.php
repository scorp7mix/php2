<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <title>PHP. Уровень 2</title>
    <meta content="text/html; charset=utf-8" http-equiv="content-type">
    <link rel="stylesheet" type="text/css" media="screen" href="theme/style.css"/>
</head>
<body>
<h1>PHP. Уровень 2</h1>
<br/>
<b>Главная</b> |
<a href="editor.php">Консоль редактора</a>
<hr/>
<h3>Перечень статей:</h3>
<ul>
    <? if (empty($articles)): ?>
        <p>Статей в базе не найдено..</p>
    <? else: ?>
        <? foreach ($articles as $article): ?>
            <li>
                <p>
                    <a href="article.php?id=<?= $article['id_article'] ?>">
                        <?= $article['title'] ?>
                    </a>
                </p>

                <p><?= $article['content'] ?></p>
            </li>
        <? endforeach; ?>
    <? endif; ?>
</ul>
<hr/>
<small><a href="http://prog-school.ru">Школа Программирования</a> &copy;</small>
</body>
</html>