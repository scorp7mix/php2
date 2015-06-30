<?php

namespace blog\models;

class M_MySQLi
{
    protected static $instance;
    protected static $link;

    //
    // Конструктор
    //
    private function __construct()
    {
        // Настройки подключения к БД.
        include_once("./models/mysqli_config.php");

        // Подключение к БД.
        self::$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME)
            or die('Error ' . mysqli_error(self::$link));
    }

    //
    // для Singleton
    //
    public static function GetInstance()
    {
        if (null === self::$instance)
            self::$instance = new self();

        return self::$instance;
    }

    private function __clone()
    {
    }

    //
    // SELECT
    //
    public function Select($sql)
    {
        $result = mysqli_query(self::$link, $sql);

        if (!$result)
            die(mysqli_error(self::$link));

        $n = mysqli_num_rows($result);

        $rows = [];

        for ($i = 0; $i < $n; $i++)
        {
            $rows[] = mysqli_fetch_assoc($result);
        }

        return $rows;
    }

    //
    // INSERT
    //
    public function Insert($table, $object)
    {
        $table = mysqli_real_escape_string(self::$link, $table);

        $fields = [];
        $values = [];

        foreach ($object as $key => $value)
        {
            $key = mysqli_real_escape_string(self::$link, $key);
            $fields[] = $key;

            if ($value == NULL)
            {
                $values[] = 'NULL';
            }
            else
            {
                $value = mysqli_real_escape_string(self::$link, $value);
                $values[] = "'" . $value . "'";
            }
        }

        $fields_string = implode(',', $fields);
        $values_string = implode(',', $values);

        $query = sprintf("INSERT INTO %s (%s) VALUES(%s)", $table, $fields_string, $values_string);

        $result = mysqli_query(self::$link, $query);

        if (!$result)
            die(mysqli_error(self::$link));

        return mysqli_insert_id(self::$link);
    }

    //
    // UPDATE
    //
    public function Update($table, $object, $conditions)
    {
        $settings = [];

        foreach ($object as $key => $value)
        {
            $key = mysqli_real_escape_string(self::$link, $key);

            if ($value == NULL)
            {
                $settings[] = $key . "=NULL";
            }
            else
            {
                $value = mysqli_real_escape_string(self::$link, $value);
                $settings[] = $key . "='" . $value . "'";
            }
        }

        $settings_string = implode(',', $settings);

        $query = sprintf("UPDATE %s SET %s WHERE %s", $table, $settings_string, $conditions);
        $result = mysqli_query(self::$link, $query);

        if (!$result)
            die(mysqli_error(self::$link));

        return mysqli_affected_rows(self::$link);
    }

    //
    // DELETE
    //
    public function Delete($table, $conditions)
    {
        $query = sprintf("DELETE FROM %s WHERE %s", $table, $conditions);

        $result = mysqli_query(self::$link, $query);

        if (!$result)
            die(mysqli_error(self::$link));

        return mysqli_affected_rows(self::$link);
    }
}