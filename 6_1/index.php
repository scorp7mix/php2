<?php// Языковая настройка.setlocale(LC_ALL, 'ru_RU.UTF-8');mb_internal_encoding('UTF-8');//// Автозагрузка классов//function __autoload ($name){    $type = substr($name, 0, 2);    switch($type)    {        case 'C_':            $dir = '/controllers/';            break;        case 'M_':            $dir = '/models/';            break;    }    if(isset($dir))    {        $include_path = __DIR__ . $dir . $name . '.php';        if(file_exists($include_path))        {            include_once($dir . $name . '.php');        }        else        {            die("There's no such class as " . $name);        }    }    else    {        die("There's no such class type as " . $type);    }}$get = $_SERVER['QUERY_STRING'];$get_elements = explode('&', $get);$params = [];foreach($get_elements as $param){    $param_parts = explode('=', $param);    $params[$param_parts[0]] =  $param_parts[1];}$app = new C_Application();$app->Request('Execute', $params);