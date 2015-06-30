<?php

namespace blog\models;

define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASS", "");
define("DB_NAME", "php2");

class M_MySQLi
{
    use M_Singleton;

    protected static $link;

    private function __construct()
    {
        static::$link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME)
            or die('Error ' . mysqli_error(static::$link));
    }

    public function Select($query)
    {
        $result = mysqli_query(static::$link, $query);

        if (!$result)
            die(mysqli_error(static::$link));

        $n = mysqli_num_rows($result);

        $rows = [];

        for ($i = 0; $i < $n; $i++)
        {
            $rows[] = mysqli_fetch_assoc($result);
        }

        return $rows;
    }

    public function Insert($table, $object)
    {
        $table = mysqli_real_escape_string(static::$link, $table);

        $fields = [];
        $values = [];

        foreach ($object as $key => $value)
        {
            $key = mysqli_real_escape_string(static::$link, $key);
            $fields[] = $key;

            if ($value == NULL)
            {
                $values[] = 'NULL';
            }
            else
            {
                $value = mysqli_real_escape_string(static::$link, $value);
                $values[] = "'" . $value . "'";
            }
        }

        $fields_string = implode(',', $fields);
        $values_string = implode(',', $values);

        $query = sprintf("INSERT INTO %s (%s) VALUES(%s)", $table, $fields_string, $values_string);

        $result = mysqli_query(static::$link, $query);

        if (!$result)
            die(mysqli_error(static::$link));

        return mysqli_insert_id(static::$link);
    }

    public function Update($table, $object, $conditions)
    {
        $settings = [];

        foreach ($object as $key => $value)
        {
            $key = mysqli_real_escape_string(static::$link, $key);

            if ($value == NULL)
            {
                $settings[] = $key . "=NULL";
            }
            else
            {
                $value = mysqli_real_escape_string(static::$link, $value);
                $settings[] = $key . "='" . $value . "'";
            }
        }

        $settings_string = implode(',', $settings);

        $query = sprintf("UPDATE %s SET %s WHERE %s", $table, $settings_string, $conditions);
        $result = mysqli_query(static::$link, $query);

        if (!$result)
            die(mysqli_error(static::$link));

        return mysqli_affected_rows(static::$link);
    }

    public function Delete($table, $conditions)
    {
        $query = sprintf("DELETE FROM %s WHERE %s", $table, $conditions);

        $result = mysqli_query(static::$link, $query);

        if (!$result)
            die(mysqli_error(static::$link));

        return mysqli_affected_rows(static::$link);
    }
}