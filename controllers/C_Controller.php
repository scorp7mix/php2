<?php

namespace blog\controllers;

abstract class C_Controller
{
    protected $params;

    protected abstract function render ();

    protected abstract function before ();

    public function request ($action, $params)
    {
        $this->params = $params;
        $this->before();
        $this->$action();
        return $this->render();
    }

    protected function isGet()
    {
        return $_SERVER['REQUEST_METHOD'] == "GET";
    }

    protected function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] == "POST";
    }

    protected function template($fileName, $params = [])
    {
        foreach ($params as $key => $value) {
            $$key = $value;
        }

        ob_start();
        include $fileName;
        return ob_get_clean();
    }

    public function __call($name, $params)
    {
        die("There's no such method as " . $name);
    }
}
