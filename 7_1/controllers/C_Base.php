<?php

abstract class C_Base extends C_Controller
{
    protected $title;
    protected $action;
    protected $model;
    protected $view;

    //
    // Конструктор
    //
    public function __construct() {}

    //
    // Предварительная обработка перед обработчиком действия
    //
    protected function Before() {}

    //
    // Компоновка страницы и ее вывод
    //
    protected function Render() {}

    //
    // Получает конкретный элемент из модели по заданным параметрам
    //
    protected function getElement ($params, $headerOnError)
    {
        $id = isset($params['id']) ? intval($params['id']) : false;

        if (!$id)
            header($headerOnError);

        $element = $this->model->Show($id);

        if (!$element)
            header($headerOnError);

        return $element;
    }
}