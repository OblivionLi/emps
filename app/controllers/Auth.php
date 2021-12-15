<?php

namespace app\controllers;

use app\models\PasswordReset;
use app\models\SendEmail;
use app\models\user\User;
use core\Controller;
use core\traits\LibraryTrait;
use core\View;

class Auth extends Controller
{
    use LibraryTrait;

    private $alerts = array();

    public function registerAction()
    {
        // check if its a post request
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
            $this->alerts = array();

            // convert special characters to html entities and strip tags from $_POST values
            // and check if inputs are empty
            // validate $_POST inputs
            // remove white spaces from input values
            if (!empty($_POST['username'])) {
                $username = htmlspecialchars(strip_tags($_POST['username']));
                $username = str_replace(' ', '', $username);

                // check username length
                if (strlen($username) > 10 || strlen($username) < 2) {
                    // add error to alerts array
                    array_push($this->alerts, 'Your username must be between 10 and 2 characters long.');
                }

                // check username for special characters
                if (!preg_match('/^[a-zA-Z0-9]+$/', $username)) {
                    // add error to alerts array
                    array_push($this->alerts, 'Username can contain only letters (a-z) and numbers (0-9).');
                }
            } else {
                array_push($this->alerts, 'Username field cannot be empty.');
            }

            if (!empty($_POST['password'])) {
                $password = htmlspecialchars(strip_tags($_POST['password']));
                $password = str_replace(' ', '', $password);

                if (!empty($_POST['confirm_password'])) {
                    $confirm_password = htmlspecialchars(strip_tags($_POST['confirm_password']));
                    $confirm_password = str_replace(' ', '', $confirm_password);

                    // check if password match
                    if ($password === $confirm_password) {
                        if (!preg_match('/^[a-zA-Z0-9]+$/', $password)) {
                            // add error to alerts array
                            array_push($this->alerts, 'Your password can contain only letters(a-z) and numbers (0-9).');
                        }
                    } else {
                        // add error to alerts array if password and confirm_password does not match
                        array_push($this->alerts, 'Password and Confirm Password do not match.');
                    }
                } else {
                    array_push($this->alerts, 'Confirm Password field cannot be empty.');
                }
            } else {
                array_push($this->alerts, 'Password field cannot be empty.');
            }

            if (!empty($_POST['email'])) {
                $email = htmlspecialchars(strip_tags($_POST['email']));
                $email = str_replace(' ', '', $email);

                // check if email is valid
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    // assign validated email
                    $email = filter_var($email, FILTER_VALIDATE_EMAIL);

                    // check if email is unique
                    $check_email_unique = User::check_if_email_unique($email);

                    // check number of rows is bigger than 0 (email already exist)
                    if ($check_email_unique > 0) {
                        // add error to errrs array
                        array_push($this->alerts, 'Email already in use.');
                    }
                } else {
                    // add error to alerts array
                    array_push($this->alerts, 'Email has invalid format.');
                }
            } else {
                array_push($this->alerts, 'Email field cannot be empty.');
            }

            // check if alerts array is empty
            if (empty($this->alerts)) {
                // create new user
                $user = new User($username, $password, $email);
                $user->save();

                header('Location: ' . ROOT_PATH . '/auth/login');
            }
        }

        $data = [
            'errors' => $this->alerts,
        ];

        View::render('auth/register.php', $data);
    }

    public function loginAction()
    {
        // check if its a post request
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
            $this->alerts = array();

            // convert special characters to html entities and strip tags from $_POST values
            // and check if inputs are empty
            // validate $_POST inputs
            // remove white spaces from input values
            if (!empty($_POST['username'])) {
                $username = htmlspecialchars(strip_tags($_POST['username']));
                $username = str_replace(' ', '', $username);
            } else {
                array_push($this->alerts, 'Username field cannot be empty.');
            }

            if (!empty($_POST['password'])) {
                $password = htmlspecialchars(strip_tags($_POST['password']));
                $password = str_replace(' ', '', $password);
            } else {
                array_push($this->alerts, 'Password field cannot be empty.');
            }

            // check if alerts array is empty
            if (empty($this->alerts)) {

                // check if user with this username and password exist in DB
                if (User::can_user_login($username, $password)) {
                    header('Location: ' . ROOT_PATH . '/');
                } else {
                    array_push($this->alerts, 'User does not exist.');
                }
            }
        }

        $data = [
            'errors' => $this->alerts,
        ];

        View::render('auth/login.php', $data);
    }

    public function logoutAction()
    {
        // unset sessions
        unset($_SESSION['username']);

        // destroy all data registered to session
        session_destroy();

        // redirect to root page
        header('Location: ' . ROOT_PATH . '/');
    }

    public function forgotPasswordAction()
    {
        // check if its a post request
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
            // create a place to store alerts
            $this->alerts = array();

            // convert special characters to html entities and strip tags from $_POST values
            // and check if inputs are empty
            // validate $_POST inputs
            // remove white spaces from input values
            if (!empty($_POST['email'])) {
                $email = htmlspecialchars(strip_tags($_POST['email']));
                $email = str_replace(' ', '', $email);
            } else {
                // add error to alerts array
                array_push($this->alerts, 'Email field cannot be empty.');
            }

            // check if alerts array is empty
            if (empty($this->alerts)) {

                // find user by email
                $user_email = User::find_by_email($email);

                // check if user found/exist
                if ($user_email) {
                    // generate random token for reset password
                    $token = $this->generateBigNumber(50);

                    // create a new object for password reset with the found email
                    // and the randomly generated token
                    $password_reset = new PasswordReset($email, $token);

                    // save email and token inside password_resets table in DB
                    $password_reset->save();

                    // send email
                    try {
                        SendEmail::send($user_email, $token);
                        // redirect to reset password page
                        header('Location: ' . ROOT_PATH . '/auth/login');
                    } catch (\Exception$e) {
                        throw new \Exception($e->getMessage(), 500);
                    }
                }
            }
        }

        $data = [
            'errors' => $this->alerts,
        ];

        View::render('auth/forgotPassword.php', $data);
    }

    public function resetPasswordAction()
    {
        // get route param reset password token
        $token = $this->route_params['id'];

        // find password reset by token
        $password_reset = PasswordReset::find_by_token($token);
        
        // check if route param and if token exist
        if ($password_reset) {
            // check if form is a post request
            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['cancel'])) {
                // delete token from password_resets table
                PasswordReset::delete($password_reset['email']);

                // redirect to login page
                header('Location: ' . ROOT_PATH . '/auth/login');
            }

            // check if form is a post request
            if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
                $this->alerts = array();

                // convert special characters to html entities and strip tags from $_POST values
                // and check if inputs are empty
                // validate $_POST inputs
                // remove white spaces from input values
                if (!empty($_POST['password'])) {
                    $password = htmlspecialchars(strip_tags($_POST['password']));
                    $password = str_replace(' ', '', $password);

                    if (!empty($_POST['confirm_password'])) {
                        $confirm_password = htmlspecialchars(strip_tags($_POST['confirm_password']));
                        $confirm_password = str_replace(' ', '', $confirm_password);

                        // check if password match
                        if ($password === $confirm_password) {
                            if (!preg_match('/^[a-zA-Z0-9]+$/', $password)) {
                                // add error to alerts array
                                array_push($this->alerts, 'Your password can contain only letters(a-z) and numbers (0-9).');
                            }
                        } else {
                            // add error to alerts array if password and confirm_password does not match
                            array_push($this->alerts, 'Password and Confirm Password do not match.');
                        }
                    } else {
                        array_push($this->alerts, 'Confirm Password field cannot be empty.');
                    }
                } else {
                    array_push($this->alerts, 'Password field cannot be empty.');
                }

                // check if alerts array is empty
                if (empty($this->alerts)) {
                    // update password
                    User::update_password($password_reset['email'], $password);

                    // delete token from password_resets table
                    PasswordReset::delete($password_reset['email']);

                    // redirect to login page
                    header('Location: ' . ROOT_PATH . '/auth/login');
                }
            }
        } else {
            // redirect to login page
            header('Location: ' . ROOT_PATH . '/auth/login');
        }

        $data = [
            'errors' => $this->alerts,
            'token' => $token
        ];

        View::render('auth/resetPassword.php', $data);
    }
}
