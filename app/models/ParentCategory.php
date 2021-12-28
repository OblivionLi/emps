<?php

namespace app\models;

use core\Model;
use core\traits\CategoryTrait;
use PDO;
use PDOException;

class ParentCategory extends Model {
    use CategoryTrait;

    // save user
    public function save() {
        try {
            $db = static::getDb();

            $query = "INSERT INTO
                        parent_categories
                            (
                                title, 
                                slug,
                                description, 
                                created_at, 
                                updated_at
                            )

                    VALUES (
                        :title,
                        :slug,
                        :description,
                        NOW(),
                        NOW()
                    )
                ";

            
            $stmt = $db->prepare($query);
            
            $stmt->bindParam(':title', $this->title);
            $stmt->bindParam(':slug', $this->slug);
            $stmt->bindParam(':description', $this->description);

            $stmt->execute();

        } catch (PDOException $e) {
            throw new \Exception($e->getMessage(), 500);
        }
    }

    public static function get_all()
    {
        try {
            $db = static::getDb();

            $query = 'SELECT
                        *
                        FROM
                            user_contacts
                        LIMIT 1
                    ';

            $stmt = $db->prepare($query);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $results;
        } catch (PDOException $e) {
            throw new \Exception($e->getMessage(), 500);
        }
    }
}