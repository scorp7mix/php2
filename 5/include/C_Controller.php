<?php

abstract class C_Controller
{
    protected abstract function Render ();
    protected abstract function Before ();

    public function Request ($action)
    {
        $this->Before();
        $this->$action();
        $this->Render();
    }

    protected function IsGet()
    {
        return $_SERVER['REQUEST_METHOD'] == "GET";
    }

    protected function IsPost()
    {
        return $_SERVER['REQUEST_METHOD'] == "POST";
    }
/*
    protected function check_params($func, $array, $params = [])
    {
        $check_result = false;

        if(self::$func())
        {
            $check_result = true;
            foreach($params as $p)
            {
                $check_result = $check_result && !empty($array[$p]);
            }
        }
        return $check_result;
    }
*/
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

    public function __call($name, $params)
    {
        die("Error");
    }
}