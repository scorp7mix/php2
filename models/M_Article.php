<?php

namespace blog\models;

class M_Article
{
    use M_Singleton;

    private static $db;

    private function __construct()
    {
        static::$db = M_PDO::getInstance();
    }

    public static function index()
    {
        $articles = static::$db->select('articles', null, 'id_article');

        foreach ($articles as $key => $value) {
            $content_intro = mb_substr($value['content'], 0, 50, 'UTF-8') . '...';
            $content_length = strlen($value['content']);
            $articles[$key]['intro'] = ($content_length > 50) ? $content_intro : $value['content'];
        }

        return $articles;
    }

    public static function show($id)
    {
        return static::$db->select('articles', 'id_article=' . $id)[0];
    }

    public static function create($object)
    {
        return static::$db->insert('articles', $object);
    }

    public static function edit($object, $id_article)
    {
        $conditions = 'id_article=' . $id_article;

        return static::$db->update('articles', $object, $conditions);
    }

    public static function delete($id_article)
    {
        $conditions = 'id_article' . $id_article;

        return static::$db->delete('articles', $conditions);
    }

    public static function get_menu($for_action)
    {
        $menu_items = [
            'index' => 'Главная',
            'editor' => 'Консоль редактора'
        ];

        $menu_for_action = [];

        foreach ($menu_items as $key => $value) {
            $menu_for_action[] = [
                'title' => $value,
                'action' => $key,
                'class' => ($key == $for_action) ? 'active' : ''
            ];
        }

        return $menu_for_action;
    }
}
