<?phpuse blog\controllers\C_Application;// Языковая настройка.setlocale(LC_ALL, 'ru_RU.UTF-8');mb_internal_encoding('UTF-8');// Работа с сессиямиsession_start();$get = explode('/', $_GET['q']);$params = [];foreach($get as $v){    if($v != '')        $params[] = $v;}$app = new C_Application();$app->Request('Execute', $params);