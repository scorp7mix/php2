<?php

//
// Генерация шаблона
//
function template($fileName, $vars = [])
{
    // Установка переменных для шаблона
    foreach ($vars as $key => $value) {
        $$key = $value;
    }

    // Загрузка HTML
    ob_start();
    include $fileName;
    return ob_get_clean();
}