<?php// Языковая настройка.setlocale(LC_ALL, 'ru_RU.UTF-8');mb_internal_encoding('UTF-8');//// Автозагрузка классов//function __autoload ($name){    include_once('./include/' . $name . '.php');}//// Установка контроллера//$controller = '';if(isset($_GET['c']))    $controller = $_GET['c'];switch ($controller){    case 'Article':        $c = new C_Article();        break;    default:        $c = new C_Article();}//// Установка обработчика действия//$action = 'Action_';$action .= (isset($_GET['a'])) ? $_GET['a'] : 'Index';//// Запрос контроллеру//$c->Request($action);