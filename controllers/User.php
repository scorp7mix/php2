<?php

namespace controllers;

use models\Users;

class User extends Base
{
    protected $model;

    public function __construct()
    {
        $this->model = Users::getInstance();
    }

    public function before()
    {
        $this->title = 'User';
    }

    public function render()
    {
        $page = $this->template(
            './views/user/layout.php',
            ['title' => $this->title,
             'view' => $this->view]
        );

        return $page;
    }

    public function register()
    {
        $this->title .= '::Register';

        if ($this->isPost()) {
            $reg_result = $this->model->register($_POST['login'], $_POST['password'], $_POST['password2']);

            if(true === $reg_result)
                header('Location: /');

            $user = [
                'login'     => $_POST['login'],
                'password'  => $_POST['password'],
                'password2' => $_POST['password2']
            ];

            $error = $reg_result;
        }

        $this->view = $this->template(
            './views/user/form.php',
            [
                'form_title'    => 'Регистрация',
                'error'         => isset($error) ? $error : [],
                'user'          => isset($user) ? $user : [],
                'button_value'  => 'Зарегистрировать'
            ]
        );
    }

    public function login()
    {
        $this->title .= '::Login';

        if ($this->isPost()) {
            $log_result = $this->model->login($_POST['login'], $_POST['password'], $_POST['remember']);

            if(true === $log_result)
                header('Location: /');

            $user = [
                'login' => $_POST['login'],
                'password' => $_POST['password']
            ];

            $error = $log_result;
        }

        $this->view = $this->template(
            './views/user/form.php',
            [
                'form_title'    => 'Авторизация',
                'error'         => isset($error) ? $error : [],
                'user'          => isset($user) ? $user : [],
                'button_value'  => 'Войти'
            ]
        );
    }

    public function getUser($id_user = null)
    {
        return $this->model->get($id_user);
    }

    public function logout()
    {
        $this->model->logout();
        header('Location: /');
    }

    public function checkPriv($priv, $id_user)
    {
        return $this->model->can($priv, $id_user);
    }
}
