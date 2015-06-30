<?php

abstract class C_Controller
{
    protected abstract function Render ();
    protected abstract function Before ();

    //
    // Обработка запроса
    //
    public function Request ($action, $params)
    {
        $this->params = $params;
        $this->Before();
        $this->$action($params);
        return $this->Render();
    }

    //
    // Есть ли что-нибудь в _GET
    //
    protected function IsGet()
    {
        return $_SERVER['REQUEST_METHOD'] == "GET";
    }

    //
    // Есть ли что-нибудь в _POST
    //
    protected function IsPost()
    {
        return $_SERVER['REQUEST_METHOD'] == "POST";
    }

    //
    // Шаблонизатор
    //
    protected function Template($fileName, $params = [])
    {
        foreach($params as $key => $value)
        {
            $$key = $value;
        }

        ob_start();
        include $fileName;
        return ob_get_clean();
    }

    //
    // Обработка несуществующего метода
    //
    public function __call($name, $params)
    {
        die("There's no such method as " . $name);
    }
}