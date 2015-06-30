<?php

namespace blog\models;

trait M_Singleton
{
    static private $instance;

    private function __construct() {}
    private function __clone() {}
    private function __wakeup() {}

    static public function getInstance()
    {
        if (empty(static::$instance)) {
            static::$instance = new static();
        }

        return static::$instance;
    }
}