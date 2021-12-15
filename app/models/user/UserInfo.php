<?php

namespace app\models\user;

use core\Model;
use PDO;
use PDOException;

class UserInfo extends Model
{
    private $id;
    private $user_id;
    private $profile_avatar = null;
    private $profile_banner = null;
    private $rank = null;
    private $birthday_date = null;
    private $gender = null;
    private $content_count = 0;
    private $community_reputation = 0;
    private $follower_count = 0;
    private $profile_views = 0;

    public static function get_all()
    {
        try {
            $db = static::getDb();

            $stmt = $db->query('SELECT 
                                    *
                                FROM 
                                    user_infos 
                                ORDER BY 
                                    id 
                                ASC');

            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $results;
        } catch (PDOException $e) {
            throw new \Exception($e->getMessage(), 500);
        }
    }

    public function save()
    {
        try {
            $db = static::getDb();

            $query = "INSERT INTO
                        user_infos
                            (
                                user_id, 
                                profile_avatar,
                                profile_banner, 
                                rank, 
                                birthday_date, 
                                gender,
                                content_count, 
                                community_reputation, 
                                follower_count, 
                                profile_views, 
                                created_at, 
                                updated_at
                            )

                    VALUES (
                        :user_id, 
                        :profile_avatar,
                        :profile_banner, 
                        :rank, 
                        :birthday_date, 
                        :gender,
                        :content_count, 
                        :community_reputation, 
                        :follower_count, 
                        :profile_views,
                        NOW(),
                        NOW()
                    )
                ";


            $stmt = $db->prepare($query);

            $stmt->bindParam(':user_id', $this->user_id);
            $stmt->bindParam(':profile_avatar', $this->profile_avatar);
            $stmt->bindParam(':profile_banner', $this->profile_banner);
            $stmt->bindParam(':rank', $this->rank);
            $stmt->bindParam(':birthday_date', $this->birthday_date);
            $stmt->bindParam(':gender', $this->gender);
            $stmt->bindParam(':content_count', $this->content_count);
            $stmt->bindParam(':community_reputation', $this->community_reputation);
            $stmt->bindParam(':follower_count', $this->follower_count);
            $stmt->bindParam(':profile_views', $this->profile_views);

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

    public function get_profile_avatar()
    {
        return $this->profile_avatar;
    }
    public function set_profile_avatar($profile_avatar)
    {
        $this->profile_avatar = $profile_avatar;
    }

    public function get_profile_banner()
    {
        return $this->profile_banner;
    }
    public function set_profile_banner($profile_banner)
    {
        $this->profile_banner = $profile_banner;
    }

    public function get_rank()
    {
        return $this->rank;
    }
    public function set_rank($rank)
    {
        $this->rank = $rank;
    }

    public function get_birthday_date()
    {
        return $this->birthday_date;
    }
    public function set_birthday_date($birthday_date)
    {
        $this->birthday_date = $birthday_date;
    }

    public function get_gender()
    {
        return $this->gender;
    }
    public function set_gender($gender)
    {
        $this->gender = $gender;
    }

    public function get_content_count()
    {
        return $this->content_count;
    }
    public function set_content_count($content_count)
    {
        $this->content_count = $content_count;
    }

    public function get_community_reputation()
    {
        return $this->community_reputation;
    }
    public function set_community_reputation($community_reputation)
    {
        $this->community_reputation = $community_reputation;
    }

    public function get_follower_count()
    {
        return $this->follower_count;
    }
    public function set_follower_count($follower_count)
    {
        $this->follower_count = $follower_count;
    }

    public function get_profile_views()
    {
        return $this->profile_views;
    }
    public function set_profile_views($profile_views)
    {
        $this->profile_views = $profile_views;
    }
}
