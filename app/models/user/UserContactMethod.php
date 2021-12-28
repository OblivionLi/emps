<?php

namespace app\models\user;

use core\Model;
use PDO;
use PDOException;

class UserContactMethod extends Model
{
    private $id;
    private $user_id;
    private $steam = null;
    private $discord = null;
    private $email = null;
    private $twitch = null;
    private $facebook = null;
    private $twitter = null;
    private $instagram = null;

    public function save() {
        try {
            $db = static::getDb();

            $query = "INSERT INTO
                        user_contacts
                            (
                                user_id, 
                                steam,
                                discord, 
                                email, 
                                twitch, 
                                facebook,
                                twitter, 
                                instagram,
                                created_at, 
                                updated_at
                            )

                    VALUES (
                        :user_id, 
                        :steam,
                        :discord, 
                        :email, 
                        :twitch, 
                        :facebook,
                        :twitter, 
                        :instagram,
                        NOW(),
                        NOW()
                    )
                ";


            $stmt = $db->prepare($query);

            $stmt->bindParam(':user_id', $this->user_id);
            $stmt->bindParam(':steam', $this->steam);
            $stmt->bindParam(':discord', $discord);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':twitch', $twitch);
            $stmt->bindParam(':facebook', $facebook);
            $stmt->bindParam(':twitter', $twitter);
            $stmt->bindParam(':instagram', $instagram);

            $stmt->execute();
        } catch (PDOException $e) {
            throw new \Exception($e->getMessage(), 500);
        }
    }

    public static function find_by_user_id($user_id) {
        try {
            $db = static::getDb();

            $query = 'SELECT 
                            * 
                        FROM 
                            user_contacts
                        WHERE 
                            user_id = :user_id
                        LIMIT 1;
                ';

            $stmt = $db->prepare($query);
            
            $stmt->bindParam(':user_id', $user_id);

            $stmt->execute();

            return $stmt->fetch();

        } catch (PDOException $e) {
            throw new \Exception($e->getMessage(), 500);
        }
    }

    public static function get_all_by_user_id($user_id)
    {
        try {
            $db = static::getDb();

            $query = 'SELECT
                        *
                        FROM
                            user_contacts

                        WHERE
                            user_id = :user_id
                    ';

            $stmt = $db->prepare($query);

            $stmt->bindParam(':user_id', $user_id);

            $stmt->execute();

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $results;
        } catch (PDOException $e) {
            throw new \Exception($e->getMessage(), 500);
        }
    }

    public function update() {
        try {
            $db = static::getDb();

            $query = "UPDATE user_contacts
                        SET 
                            steam = :steam, 
                            discord = :discord, 
                            email = :email, 
                            twitch = :twitch,
                            facebook = :facebook,
                            twitter = :twitter,
                            instagram = :instagram,
                            updated_at = NOW()
                        WHERE
                            user_id = :user_id
                ";
            
            $stmt = $db->prepare($query);

            $stmt->bindParam(':user_id', $this->user_id);
            $stmt->bindParam(':steam', $this->steam);
            $stmt->bindParam(':discord', $this->discord);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':twitch', $this->twitch);
            $stmt->bindParam(':facebook', $this->facebook);
            $stmt->bindParam(':twitter', $this->twitter);
            $stmt->bindParam(':instagram', $this->instagram);

            $stmt->execute();

        } catch (PDOException $e) {
            throw new \Exception($e->getMessage(), 500);
        }
    }

    public function get_user_id()
    {
        return $this->user_id;
    }
    public function set_user_id($user_id)
    {
        $this->user_id = $user_id;
    }

    public function get_steam()
    {
        return $this->steam;
    }
    public function set_steam($steam)
    {
        $this->steam = $steam;
    }

    public function get_discord()
    {
        return $this->discord;
    }
    public function set_discord($discord)
    {
        $this->discord = $discord;
    }

    public function get_facebook()
    {
        return $this->facebook;
    }
    public function set_facebook($facebook)
    {
        $this->facebook = $facebook;
    }

    public function get_twitter()
    {
        return $this->twitter;
    }
    public function set_twitter($twitter)
    {
        $this->twitter = $twitter;
    }

    public function get_email()
    {
        return $this->email;
    }
    public function set_email($email)
    {
        $this->email = $email;
    }

    public function get_twitch()
    {
        return $this->twitch;
    }
    public function set_twitch($twitch)
    {
        $this->twitch = $twitch;
    }

    public function get_instagram()
    {
        return $this->instagram;
    }
    public function set_instagram($instagram)
    {
        $this->instagram = $instagram;
    }
}
