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
        $this->title = 'Article';
        $this->action = '';
        $this->view = '';
    }

    //
    // Компоновка страницы и ее вывод
    //
    public function Render()
    {
        $menu = $this->Template('./views/menu.php',
            ['menu_items' => M_Article::get_menu($this->action)]);

        $page = $this->Template('./views/layout.php',
            ['title' => $this->title,
            'menu' => $menu,
            'view' => $this->view]);

        header('Content-type: text/html; charset=utf-8');

        echo $page;
    }
}