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

$q = "SELECT * FROM " . $table_name;
$result = mysql_query($q);
if(!$result) {
    exitWithErr();
}

$res_array = [];
while($row = mysql_fetch_assoc($result)) {
    $res_array[] = $row;
}

echo "Работа с базой удалась!\nВыборка всех записей:\n";
print_r($res_array);