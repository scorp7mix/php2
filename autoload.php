<?php

function __autoload($namespace)
{
    $path = __DIR__ . '\\' . trim($namespace, '\\') . '.php';

    if (file_exists($path)) {
        require_once($path);
    } else {
        die("Call to unresolved instance");
    }
}
