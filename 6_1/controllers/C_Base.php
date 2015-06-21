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

    //
    // Дополнительный метод, используется при создании/изменении элемента
    //
    protected function editElement($id, $fields, $action, $headerOnSuccess)
    {
        $object = [];
        $error = [];

        $fields_not_empty = true;
        foreach ($fields as $field) {
            if (!empty($_POST[$field])) {
                $object[$field] = $_POST[$field];
                $error[$field] = '';
            } else {
                $fields_not_empty = false;
                $object[$field] = '';
                $error[$field] = 'has-error';
            }
        }

        if ($fields_not_empty
            && $this->model->$action($id, $object)
        ) {
            header($headerOnSuccess);
            die();
        }

        return ['object' => $object, 'error' => $error];
    }
}