<?php

namespace app\models;

use core\Model;
use core\traits\LibraryTrait;
use PDO;
use PDOException;

class User extends Model {
    use LibraryTrait;

    private $id;
    private $username;
    private $password;
    private $email;
    private $last_time_visit;
    
    private $created_at;
    private $updated_at;

    public function __construct($username, $password, $email) {
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;

        $this->created_at = date_create()->format('Y-m-d H:i:s');
        $this->updated_at = date_create()->format('Y-m-d H:i:s');
    }

    // save user
    public function save() {
        try {
            $db = static::getDb();

            $query = "INSERT INTO
                        users
                            (
                                username, 
                                email,
                                password, 
                                created_at, 
                                updated_at
                            )

                    VALUES (
                        :username,
                        :email,
                        :password,
                        :created_at,
                        :updated_at
                    )
                ";

            
            $stmt = $db->prepare($query);
            
            $stmt->bindParam(':username', $this->username);
            $stmt->bindParam(':email', $this->email);

            // hash password before binding
            $hashed_password = password_hash($this->password, PASSWORD_DEFAULT);

            $stmt->bindParam(':password', $hashed_password);
            $stmt->bindParam(':created_at', $this->created_at);
            $stmt->bindParam(':updated_at', $this->updated_at);

            $stmt->execute();

        } catch (PDOException $e) {
            throw new \Exception($e->getMessage(), 500);
        }
    }

    public static function update_password($email, $password) {
        try {
            $db = static::getDb();

            $query = "UPDATE users
                        SET 
                            password = :password, 
                            updated_at = NOW()
                        WHERE
                            email = :email
                ";
            
            $stmt = $db->prepare($query);

            // hash password before binding
            $stmt->bindParam(':email', $email);
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt->bindParam(':password', $hashed_password);

            $stmt->execute();

        } catch (PDOException $e) {
            throw new \Exception($e->getMessage(), 500);
        }
    }

    public static function check_if_email_unique($email) {
        try {
            $db = static::getDb();

            $query = 'SELECT 
                            email 
                        FROM 
                            users
                        WHERE 
                            email = :email
                        LIMIT 1;
                ';

            $stmt = $db->prepare($query);
            
            $stmt->bindParam(':email', $email);

            $stmt->execute();

            return $stmt->fetchColumn();

        } catch (PDOException $e) {
            throw new \Exception($e->getMessage(), 500);
        }
    }

    public static function find_by_username($username) {
        try {
            $db = static::getDb();

            $query = 'SELECT 
                            * 
                        FROM 
                            users
                        WHERE 
                            username = :username
                        LIMIT 1;
                ';

            $stmt = $db->prepare($query);
            
            $stmt->bindParam(':username', $username);

            $stmt->execute();

            return $stmt->fetch();

        } catch (PDOException $e) {
            throw new \Exception($e->getMessage(), 500);
        }
    }

    public static function update_last_time_visit($username) {
        try {
            $db = static::getDb();

            $query = "UPDATE users
                        SET 
                            last_time_visit = NOW()
                        WHERE
                            username = :username
                ";
            
            $stmt = $db->prepare($query);

            // hash password before binding
            $stmt->bindParam(':username', $username);

            $stmt->execute();

        } catch (PDOException $e) {
            throw new \Exception($e->getMessage(), 500);
        }
    }

    public static function can_user_login($username, $password) {
        $user = self::find_by_username($username);

        // check if user query returned anything
        if ($user) {
            // check passwords
            if (password_verify($password, $user['password'])) {
                // create session 
                $_SESSION['username'] = $username;

                // update last time visit
                self::update_last_time_visit($username);

                // return true if passwords match
                return true;
            }
        }

        // return false if user doesn't exist || if passwords do not match
        return false;
    }   

    public static function find_by_email($email) {
        try {
            $db = static::getDb();

            $query = 'SELECT 
                            * 
                        FROM 
                            users
                        WHERE 
                            email = :email
                        LIMIT 1;
                ';

            $stmt = $db->prepare($query);
            
            $stmt->bindParam(':email', $email);

            $stmt->execute();

            return $stmt->fetch();

        } catch (PDOException $e) {
            throw new \Exception($e->getMessage(), 500);
        }
    }

    // get all users
    public static function get_all_users() {
        try {
            $db = static::getDb();

            $stmt = $db->query('SELECT 
                                    username, 
                                    password, 
                                    email 
                                FROM 
                                    users 
                                ORDER BY 
                                    id 
                                ASC');

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $results;
        } catch (PDOException $e) {
            throw new \Exception($e->getMessage(), 500);
        }
    }
}