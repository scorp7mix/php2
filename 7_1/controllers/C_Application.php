<?php

class C_Application extends C_Controller
{
    protected $content;
    protected $layout;
    protected $user;

    public function __construct()
    {

    }

    //
    // Предварительная обработка перед обработчиком действия
    //
    protected function Before()
    {
        $this->content = '';
        $this->layout = './views/layout.php';

        $users = new C_User();
        $this->user = $users->GetUser();
    }

    //
    // Компоновка страницы и ее вывод
    //
    protected function Render()
    {
        $page = $this->Template($this->layout, ['user_login' => $this->user['login'], 'content' => $this->content]);

        header('Content-type: text/html; charset=utf-8');

        echo $page;
    }

    public function Execute($params)
    {
        // Установка контроллера
        $c = '';

        if(isset($params['c']))
        {
            $c = $params['c'];
            unset($params['c']);
        }

        switch ($c)
        {
            case 'Article':
                $controller = new C_Article();
                break;
            case 'Comment':
                $controller = new C_Comment(null);
                break;
            case 'User':
                $controller = new C_User();
                break;
            default:
                $controller = new C_Article();
        }

        // Установка обработчика действия
        $action = 'Index';
        if(isset($params['a']))
        {
            $action = $params['a'];
            unset($params['a']);
        }

        $params['user'] = $this->user;

        $this->content = $controller->Request($action, $params);
    }
}