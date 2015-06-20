<?php

abstract class C_Base extends C_Controller
{
    protected $title;
    protected $action;
    protected $view;

    //
    // Конструктор
    //
    public function __construct()
    {

    }

    //
    // Предварительная обработка перед обработчиком действия
    //
    public function Before()
    {
        $this->action = '';
        $this->view = '';
    }

    //
    // Компоновка страницы и ее вывод
    //
    public function Render()
    {
        header('Content-type: text/html; charset=utf-8');
    }
}