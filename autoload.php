<?php

//
// Автозагрузка классов
//
function __autoload($name)
{
    switch ($name[0]) {
        case 'C':
            $dir = '/controllers/';
            break;
        case 'M':
            $dir = '/models/';
            break;
    }

    if (isset($dir)) {
        $include_path = __DIR__ . $dir . $name . '.php';
        if (file_exists($include_path)) {
            include_once($include_path);
        } else {
            die("There's no such class as " . $name);
        }
    } else {
        die("There's no such class type as " . $name[0]);
    }
}
