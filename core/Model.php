<?php

namespace core;

use app\Config;
use PDO;
use PDOException;

abstract class Model {
    protected static function getDB() {
        static $db = null;

        if ($db === null) {
            $dsn = 'mysql:host=' . Config::DB_HOST . ';dbname=' . Config::DB_NAME. ';charset=UTF8';
            try {
                $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
                return new PDO($dsn, Config::DB_USER, Config::DB_PASS, $options);
            } catch (PDOException $e) {
                throw new \Exception($e->getMessage(), 500);
            }
        }
    }
}
