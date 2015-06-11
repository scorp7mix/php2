<?php
include_once('models/startup.php');
include_once('models/model.php');
include_once('models/template.php');

// Установка параметров, подключение к БД, запуск сессии.
startup();

// Извлечение статьи.
if (!isset($_GET['id']))
    header('Location: index.php');

$id = intval($_GET['id']);
$article = $id ? articles_get($id) : false;

if (!$article)
    header('Location: index.php');

$title = $article['title'];
$content = $article['content'];

$menu = template('views/menu.php', ['pageId' => 0]);
$view = template('views/show.php', [
    'title' => $title,
    'content' => $content
]);
$page = template('views/layout.php', [
    'menu' => $menu,
    'view' => $view
]);

// Кодировка.
header('Content-type: text/html; charset=utf-8');

// Вывод в шаблон.
echo $page;
