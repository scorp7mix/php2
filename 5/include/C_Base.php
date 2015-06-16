<?php

abstract class C_Base extends C_Controller
{
    protected $title;
    protected $action;
    protected $view;

    public function __construct()
    {

    }

    public function Before()
    {
        $this->title = 'Article';
        $this->action = '';
        $this->view = '';
    }

    public function Render()
    {
        $menu = $this->Template('./views/menu.php',
            ['menu_items' => get_menu($this->action)]);

        $page = $this->Template('./views/layout.php',
            ['title' => $this->title,
            'menu' => $menu,
            'view' => $this->view]);

        // Кодировка.
        header('Content-type: text/html; charset=utf-8');

        echo $page;
    }
}