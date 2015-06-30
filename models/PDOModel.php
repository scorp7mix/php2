<?php

namespace models;

use \PDO;

define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_NAME", "php2");

class PDOModel
{
    use Singleton;

    protected static $link;

    private function __construct()
    {
        setlocale(LC_ALL, 'ru_RU.UTF8');
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME;

        static::$link = new PDO(
            $dsn,
            DB_USER,
            DB_PASS,
            [PDO::ATTR_PERSISTENT => true]
        );
        static::$link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        static::$link->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }

    public function customSelect($query)
    {
        $t = static::$link->prepare($query);
        $t->execute();

        return $t->fetchAll();
    }

    public function select($table, $conditions, $order = null)
    {
        $query = "SELECT * FROM " . $table;

        if ($conditions) {
            $query .= " WHERE " . $conditions;
        }

        if ($order) {
            $query .= " ORDER BY " . $order;
        }

        $t = static::$link->prepare($query);
        $t->execute();

        return $t->fetchAll();
    }

    public function insert($table, $object)
    {
        $fields = [];
        $masks = [];

        foreach ($object as $key => $value) {
            $fields[] = $key;
            $masks[] = ':' . $key;

            if (NULL === $value) {
                $object[$key] = 'NULL';
            }
        }

        $fields_string = implode(',', $fields);
        $masks_string = implode(',', $masks);

        $query = sprintf("INSERT INTO %s (%s) VALUES(%s)", $table, $fields_string, $masks_string);

        $t = static::$link->prepare($query);
        $t->execute($object);

        return static::$link->lastInsertId();
    }

    public function update($table, $object, $conditions)
    {
        $settings = [];

        foreach ($object as $key => $value) {
            $settings[] = $key . '=:' . $key;

            if (NULL === $value) {
                $object[$key] = 'NULL';
            }
        }

        $settings_string = implode(',', $settings);

        $query = sprintf("UPDATE %s SET %s WHERE %s", $table, $settings_string, $conditions);

        $t = static::$link->prepare($query);
        $t->execute($object);

        return $t->rowCount();
    }

    public function delete($table, $conditions)
    {
        $query = sprintf("DELETE FROM %s WHERE %s", $table, $conditions);

        $t = static::$link->prepare($query);
        $t->execute();

        return $t->rowCount();
    }
}