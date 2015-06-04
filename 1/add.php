<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 01.06.2015
 * Time: 22:20
 */

$host = 'localhost';
$user = 'root';
$pass = '';
$db_name = 'php2';
$table_name = 'articles';

$article_title = "Новая статья";
$article_author = "автор";
$article_text = "Текст новой статьи";

function exitWithErr() {
    exit(mysql_errno() . ": " . mysql_error());
}

if (!(mysql_connect($host, $user, $pass))) {
    exitWithErr();
}
if (!(mysql_set_charset("utf8"))) {
    exitWithErr();
}
if (!(mysql_select_db($db_name))) {
    exitWithErr();
}

$q = "INSERT INTO " .
     $table_name . " (title, author, text)
     VALUES ('" . $article_title . "','" .
                  $article_author . "','" .
                  $article_text . "')";
$result = mysql_query($q);
if(!$result) {
    exitWithErr();
}

echo "Работа с базой удалась!\n";
echo "Запись добавлена с id: " . mysql_insert_id();