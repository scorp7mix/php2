<?php

namespace blog\models;

class M_Comment
{
    use M_Singleton;

    private static $db;

    private function __construct()
    {
        static::$db = M_PDO::getInstance();
    }

    public static function index($id_article)
    {
        $query = sprintf("SELECT * FROM comments AS c " .
                         "LEFT JOIN users AS u ON c.id_user = u.id_user " .
                         "WHERE id_article=%d " .
                         "ORDER BY id_comment DESC", $id_article);

        return static::$db->customSelect($query);
    }

    public static function show($id_comment)
    {
        return static::$db->select('comments', 'id_comment=' . $id_comment)[0];
    }

    public static function create($id_article, $object)
    {
        $object['id_article'] = $id_article;

        return static::$db->insert('comments', $object);
    }

    public static function edit($id_comment, $object)
    {
        $conditions = 'id_comment=' . $id_comment;

        return static::$db->update('comments', $object, $conditions);
    }

    public static function delete($id_comment)
    {
        $conditions = 'id_comment' . $id_comment;

        return static::$db->delete('comments', $conditions);
    }
}
