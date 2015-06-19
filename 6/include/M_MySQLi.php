<?php

class M_MySQli
{
    private static $instance;
    private $link;

    //
    // Конструктор
    //
    private function __construct()
    {
        // Настройки подключения к БД.
        include_once("./mysqli_config.php");

        // Подключение к БД.
        $this->link = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME)
            or die('Error ' . mysqli_error($this->link));
    }

    //
    // для Singleton
    //
    public static function GetInstance()
    {
        if (self::$instance == null)
            self::$instance = new M_MySQli();

        return self::$instance;
    }

    //
    // SELECT
    //
    public function Select($sql)
    {
        $result = mysqli_query($this->link, $sql);

        if (!$result)
            die(mysqli_error($this->link));

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
        $table = mysqli_real_escape_string($this->link, $table);

        $fields = [];
        $values = [];

        foreach ($object as $key => $value)
        {
            $key = mysqli_real_escape_string($this->link, $key);
            $fields[] = $key;

            if ($value == NULL)
            {
                $values[] = 'NULL';
            }
            else
            {
                $value = mysqli_real_escape_string($this->link, $value);
                $values = "'" . $value . "'";
            }
        }

        $fields_string = implode(',', $fields);
        $values_string = implode(',', $values);

        $query = sprintf("INSERT INTO %s (%s) VALUES(%s)", $table, $fields_string, $values_string);

        $result = mysqli_query($this->link, $query);

        if (!$result)
            die(mysqli_error($this->link));

        return mysqli_insert_id($this->link);
    }

    //
    // UPDATE
    //
    public function Update($table, $object, $conditions)
    {
        $settings = [];

        foreach ($object as $key => $value)
        {
            $key = mysqli_real_escape_string($this->link, $key);

            if ($value == NULL)
            {
                $settings[] = $key . "=NULL";
            }
            else
            {
                $value = mysqli_real_escape_string($this->link, $value);
                $settings[] = $key . "='" . $value . "'";
            }
        }

        $settings_string = implode(',', $settings);

        $query = sprintf("UPDATE %s SET %s WHERE %s", $table, $settings_string, $conditions);
        $result = mysqli_query($this->link, $query);

        if (!$result)
            die(mysqli_error($this->link));

        return mysqli_affected_rows($this->link);
    }

    //
    // DELETE
    //
    public function Delete($table, $conditions)
    {
        $query = sprintf("DELETE FROM %s WHERE %s", $table, $conditions);

        $result = mysqli_query($this->link, $query);

        if (!$result)
            die(mysqli_error($this->link));

        return mysqli_affected_rows($this->link);
    }
}