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

$id = $_GET['id'];
if(!isset($id)) {
    exit("Номер статьи не задан!");
}

$article_title = "Новое название статьи";
$article_author = "новый автор";
$article_text = "Новый текст статьи";

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

$q = "UPDATE " . $table_name .
    " SET title = '" . $article_title . "', " .
         "author = '" . $article_author . "', " .
         "text = '" . $article_text . "' " .
    "WHERE id = " . $id;
$result = mysql_query($q);
if(!$result) {
    exitWithErr();
}

echo "Работа с базой удалась!\n";
if(mysql_affected_rows() == 0) {
    echo "Записи с указанным id не найдено";
} else {
    echo "Запись с id: " . $id . " изменена!";
}