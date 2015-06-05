<?php
include_once('startup.php');
include_once('model.php');

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

// Кодировка.
header('Content-type: text/html; charset=utf-8');

// Вывод в шаблон.
include('theme/article.php');
