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

$q = "SELECT * FROM " . $table_name .
     " WHERE id = " . $id;
$result = mysql_query($q);
if(!$result) {
    exitWithErr();
}

echo "Работа с базой удалась!\n";
if(mysql_num_rows($result) == 0) {
    echo "Записи с указанным id не найдено";
} else {
    $res = mysql_fetch_assoc($result);
    echo "Выборка одной записи:\n";
    print_r($res);
}