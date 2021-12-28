<?php

namespace app\controllers;

use app\models\user\User;
use app\models\user\UserContactMethod;
use app\models\user\UserInfo;
use core\Controller;
use core\View;

class UserContacts extends Controller
{

    private $alerts = array();

    public function indexAction()
    {
        // get user contacts information
        $user = User::find_by_username($_SESSION['username']);
        $user_contact = UserContactMethod::find_by_user_id($user['id']);

        $data = [
            'errors' => $this->alerts,
            'user_contact'   => $user_contact,
        ];
        View::render('user/userInfo.php', $data);
    }

    public function create()
    {
        // check if its a post request
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
            $this->alerts = array();

            // get user information
            $user = User::find_by_username($_SESSION['username']);

            // convert special characters to html entities and strip tags from $_POST values
            // and check if inputs are empty
            // validate $_POST inputs
            // remove white spaces from input values
            if (!empty($_POST['steam'])) {
                $steam = htmlspecialchars(strip_tags($_POST['steam']));
            } else {
                $steam = null;
            }

            if (!empty($_POST['discord'])) {
                $discord = htmlspecialchars(strip_tags($_POST['discord']));
            } else {
                $discord = null;
            }

            if (!empty($_POST['twitch'])) {
                $twitch = htmlspecialchars(strip_tags($_POST['twitch']));
            } else {
                $twitch = null;
            }

            if (!empty($_POST['email'])) {
                $email = htmlspecialchars(strip_tags($_POST['email']));
            } else {
                $email = null;
            }

            if (!empty($_POST['facebook'])) {
                $facebook = htmlspecialchars(strip_tags($_POST['facebook']));
            } else {
                $facebook = null;
            }

            if (!empty($_POST['twitter'])) {
                $twitter = htmlspecialchars(strip_tags($_POST['twitter']));
            } else {
                $twitter = null;
            }

            if (!empty($_POST['instagram'])) {
                $instagram = htmlspecialchars(strip_tags($_POST['instagram']));
            } else {
                $instagram = null;
            }

            // check if alerts array is empty
            if (empty($this->alerts)) {

                // get all records from user_contacts table where user_id
                $user_contacts = UserContactMethod::get_all_by_user_id($user[0]); // here $user[0] means the id of the user

                // check count of records retrieved if its less than 1
                // then add data to DB
                $user_contact = new UserContactMethod();
                
                $user_contact->set_user_id($user[0]); // here 0 return user id
                $user_contact->set_steam($steam);
                $user_contact->set_discord($discord);
                $user_contact->set_facebook($facebook);
                $user_contact->set_email($email);
                $user_contact->set_twitter($twitter);
                $user_contact->set_instagram($instagram);
                $user_contact->set_twitch($twitch);
                
                if (count($user_contacts) < 1) {
                    $user_contact->save();
                } else {
                    $user_contact->update();
                }

                header('Location: ' . ROOT_PATH . '/userinfos/index');
            }
        }

        $data = [
            'errors' => $this->alerts,
        ];

        View::render('user/userContactMethod.php', $data);
    }

    public function updateAction()
    {

    }

    public function deleteAction()
    {

    }
}
