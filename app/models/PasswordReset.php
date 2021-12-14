<?php

namespace app\models;

use core\Model;
use PDOException;

class PasswordReset extends Model {

    private $email;
    private $token;

    private $created_at;
    private $updated_at;

    public function __construct($email, $token) {
        $this->email = $email;
        $this->token = $token;

        $this->created_at = date_create()->format('Y-m-d H:i:s');
        $this->updated_at = date_create()->format('Y-m-d H:i:s');
    }

    public function save() {
        try {
            $db = static::getDb();

            $query = "INSERT INTO
                        password_resets
                            (
                                email,
                                token, 
                                created_at, 
                                updated_at
                            )

                    VALUES (
                        :email,
                        :token,
                        :created_at,
                        :updated_at
                    )
                ";

            
            $stmt = $db->prepare($query);
            
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':token', $this->token);

            $stmt->bindParam(':created_at', $this->created_at);
            $stmt->bindParam(':updated_at', $this->updated_at);

            $stmt->execute();

        } catch (PDOException $e) {
            throw new \Exception($e->getMessage(), 500);
        }
    }

    public static function delete($email) {
        try {
            $db = static::getDb();

            $query = "DELETE FROM
                        password_resets
                            
                        WHERE 
                            email = :email
                    ";

            
            $stmt = $db->prepare($query);
            
            $stmt->bindParam(':email', $email);

            $stmt->execute();

        } catch (PDOException $e) {
            throw new \Exception($e->getMessage(), 500);
        }
    }

    public static function find_by_token($token) {
        try {
            $db = static::getDb();

            $query = 'SELECT 
                            * 
                        FROM 
                            password_resets
                        WHERE 
                            token = :token
                        LIMIT 1;
                ';

            $stmt = $db->prepare($query);
            
            $stmt->bindParam(':token', $token);

            $stmt->execute();

            return $stmt->fetch();

        } catch (PDOException $e) {
            throw new \Exception($e->getMessage(), 500);
        }
    }
}