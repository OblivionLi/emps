<?php

namespace app\models;

use core\Model;
use PDO;
use PDOException;

class Post extends Model {
    public static function getAll() {
        try {
            $db = static::getDb();

            $stmt = $db->query('SELECT id, name FROM posts ORDER BY id');

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $results;
            
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}