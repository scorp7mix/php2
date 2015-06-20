<?php

class M_Article
{
    private static $instance;
    private static $db;

    //
    // Конструктор
    //
    private function __construct()
    {
        // Подключаем базу через MySQLi
        self::$db = M_MySQLi::GetInstance();
    }

    //
    // для Singleton
    //
    public static function GetInstance()
    {
        if(null === self::$instance)
            self::$instance = new M_Article();

        return self::$instance;
    }

    //
    // Список всех статей
    //
    public static function All()
    {
        $query = "SELECT * FROM articles ORDER BY id_article DESC";

        $response = self::$db->Select($query);

        foreach($response as $key => $value)
        {
            $response[$key]['intro'] =
                (strlen($value['content']) > 50) ?
                    mb_substr($value['content'], 0, 50, 'UTF-8') . '...' :
                    $value['content'];
        }

        return $response;
    }

    //
    // Конкретная статья
    //
    public static function Show($id)
    {
        $query = sprintf("SELECT * FROM articles WHERE id_article = %d", $id);

        return self::$db->Select($query)[0];
    }

    //
    // Создать статью
    //
    public static function Create($title, $content)
    {
        $object = ['title' => $title, 'content' => $content];

        return self::$db->Insert('articles', $object);
    }

    //
    // Изменить статью
    //
    public static function Edit($id_article, $title, $content)
    {
        $object = ['title' => $title, 'content' => $content];
        $conditions = 'id_article=' . $id_article;

        return self::$db->Update('articles', $object, $conditions);
    }

    //
    // Удалить статью
    //
    public static function Delete($id_article)
    {
        $conditions = 'id_article' . $id_article;

        return self::$db->Delete('articles', $conditions);
    }

    //
    // Формирование меню
    //
    public static function get_menu($for_action)
    {
        $menu_items = [
            'Index' => 'Главная',
            'Editor' => 'Консоль редактора'
        ];

        $menu_for_action = [];

        foreach($menu_items as $key => $item)
        {
            $menu_for_action[] =
                ['title' => $item,
                'action' => $key,
                'class' => ($key == $for_action) ? 'active' : ''];
        }

        return $menu_for_action;
    }
}