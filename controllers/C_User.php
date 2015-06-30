<?php

namespace blog\controllers;

use blog\models\M_User;

class C_User extends C_Base
{
    protected $model;
    //
    // Конструктор
    //
    public function __construct()
    {
        parent::__construct();

        $this->model = M_User::GetInstance();
    }

    //
    // Предварительная обработка перед обработчиком действия
    //
    public function Before()
    {
        $this->title = 'User';
    }

    //
    // Компоновка страницы и ее вывод
    //
    public function Render()
    {
        $page = $this->Template('./views/user/layout.php',
            ['title' => $this->title,
             'view' => $this->view]);

        return $page;
    }

    //
    // Регистрация
    //
    public function Register()
    {
        $this->title .= '::Register';

        if($this->IsPost())
        {
            $reg_result = $this->model->Register($_POST['login'], $_POST['password'], $_POST['password2']);

            if(true === $reg_result)
            {
                header('Location: /');
            }

            $user = ['login' => $_POST['login'],
                'password' => $_POST['password'],
                'password2' => $_POST['password2']];
            $error = $reg_result;
        }

        $this->view = $this->Template('./views/user/form.php',
            ['form_title' => 'Регистрация',
                'error' => isset($error) ? $error : [],
                'user' => isset($user) ? $user : [],
                'button_value' => 'Зарегистрировать']);
    }

    //
    // Авторизация
    //
    public function Login()
    {
        $this->title .= '::Login';

        if($this->IsPost())
        {
            $log_result = $this->model->Login($_POST['login'], $_POST['password'], $_POST['remember']);

            if(true === $log_result)
            {
                header('Location: /');
            }

            $user = ['login' => $_POST['login'],
                'password' => $_POST['password']];
            $error = $log_result;
        }

        $this->view = $this->Template('./views/user/form.php',
            ['form_title' => 'Авторизация',
                'error' => isset($error) ? $error : [],
                'user' => isset($user) ? $user : [],
                'button_value' => 'Войти']);
    }

    //
    // Получение пользователя
    //
    public function GetUser($id_user = null)
    {
        return $this->model->Get($id_user);
    }

    //
    // Выход
    //
    public function Logout()
    {
        $this->model->Logout();
        header('Location: /');
    }

    //
    // Получение пользователя
    //
    public function CheckPriv($priv, $id_user)
    {
        return $this->model->Can($priv, $id_user);
    }
}