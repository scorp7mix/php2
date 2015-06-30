<?php

namespace blog\controllers;

class C_Application extends C_Controller
{
    protected $content;
    protected $layout;

    protected function before()
    {
        $this->content = '';
        $this->layout = './views/layout.php';

        $users = new C_User();
        $this->params['users'] = $users;
        $this->params['user'] = $users->getUser();
    }

    protected function render()
    {
        $page = $this->template(
            $this->layout,
            [
                'user' => $this->params['user']['login'],
                'content' => $this->content
            ]
        );

        header('Content-type: text/html; charset=utf-8');

        echo $page;
    }

    public function execute()
    {
        $c = isset($this->params[0]) ? $this->params[0] : '';

        switch ($c) {
            case 'article':
                $controller = new C_Article();
                break;
            case 'comment':
                $controller = new C_Comment(null);
                break;
            case 'user':
                $controller = new C_User();
                break;
            default:
                $controller = new C_Article();
        }

        $action = isset($this->params[1]) ? $this->params[1] : 'index';

        $this->content = $controller->request($action, $this->params);
    }
}
