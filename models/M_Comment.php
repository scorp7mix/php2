<?php

namespace blog\models;

class M_Comment
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
            self::$instance = new M_Comment();

        return self::$instance;
    }

    //
    // Список всех комментариев
    //
    public static function All($id_article)
    {
        $query = sprintf("SELECT * FROM comments AS c " .
                         "LEFT JOIN users AS u ON c.id_user = u.id_user " .
                         "WHERE id_article=%d " .
                         "ORDER BY id_comment DESC", $id_article);

        return self::$db->Select($query);
    }

    //
    // Конкретный комментарий
    //
    public static function Show($id_comment)
    {
        $query = sprintf("SELECT * FROM comments WHERE id_comment=%d", $id_comment);

        return self::$db->Select($query)[0];
    }

    //
    // Создать комментарий
    //
    public static function Create($id_article, $object)
    {
        $object['id_article'] = $id_article;

        return self::$db->Insert('comments', $object);
    }

    //
    // Изменить комментарий
    //
    public static function Edit($id_comment, $object)
    {
        $conditions = 'id_comment=' . $id_comment;

        return self::$db->Update('comments', $object, $conditions);
    }

    //
    // Удалить комментарий
    //
    public static function Delete($id_comment)
    {
        $conditions = 'id_comment' . $id_comment;

        return self::$db->Delete('comments', $conditions);
    }
}