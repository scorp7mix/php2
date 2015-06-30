<?php

use controllers\Application;

session_start();

require_once 'autoload.php';

set_exception_handler(
    function ($e) {
        echo 'exception: ';
        echo $e->getMessage();
    }
);

$get = explode('/', $_GET['q']);
$params = [];

foreach ($get as $v) {
    if ($v != '') {
        $params[] = $v;
    }
}

$app = new Application();
$app->request('execute', $params);
