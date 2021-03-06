<?php

namespace app\controllers;

use app\models\user\User;
use app\models\user\UserInfo;
use core\Controller;
use core\View;

class UserInfos extends Controller
{

    private $alerts = array();

    public function indexAction()
    {
        // get user information
        $user = User::find_by_username($_SESSION['username']);

        $data = [
            'errors' => $this->alerts,
            'user'   => $user,
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

            // placeholder for avatar/banner files to be uploaded using a for loop
            $avatar_filepath = '';
            $banner_filename = '';
            
            // validate inputs
            if ($_FILES['profile_avatar']['size'] != 0) {
                // file target dir
                $avatar_target_dir = $_SERVER['DOCUMENT_ROOT'] . '/emps';

                // get file name with unique prefix defined by time and a random uniqid
                $avatar_filepath = '/public/images/avatars/' . time() . uniqid(rand()) . '-' . $_FILES['profile_avatar']['name'];

                // get file extension
                $filename_ext = strtolower(pathinfo($avatar_filepath, PATHINFO_EXTENSION));

                // get file from input
                $file = $_FILES['profile_avatar']['tmp_name'];

                if ($file) {
                    // check if is file
                    if (!getimagesize($file)) {
                        array_push($this->alerts, 'File is not an image.');

                        // check for file has extension
                    } else if (!in_array($filename_ext, ['jpg', 'png', 'jpeg'])) {
                        array_push($this->alerts, 'Invalid file format. Only jpg/jpeg/png file format are allowed.');

                        // check for file size
                    } else if ($_FILES['profile_avatar']['size'] > 5000000) {
                        array_push($this->alerts, 'File too large! max: 5mb');
                    }

                    // make full destination path where the file will be uploaded
                    $destination = $avatar_target_dir . $avatar_filepath;

                    // check if avatar file already exists in DB 
                    if (!$user['profile_avatar']) {
                        // check if move_uploaded_file is false, then throw error
                        // else success
                        if (!move_uploaded_file($file, $destination)) {
                            array_push($this->alerts, "Failed to upload file.");
                        }
                    } else {
                        $fpath = $_SERVER['DOCUMENT_ROOT'] . '/emps/' . $user['profile_avatar'];
                        $placeholder_path = '/public/images/avatars/placeholder.png';
                        // check if file exists
                        if (file_exists($fpath)) {
                            if ($user['profile_avatar'] != $placeholder_path) {
                                // delete old file
                                unlink($fpath);
                            }

                            // check if move_uploaded_file is false, then throw error
                            // else success
                            if (!move_uploaded_file($file, $destination)) {
                                array_push($this->alerts, "Failed to upload file.");
                            }
                        }
                    }
                }
            } else if ($user['profile_avatar'] && $_FILES['profile_avatar']['size'] == 0) {
                $avatar_filepath = $user['profile_avatar'];
            } else if (!$user['profile_avatar'] && $_FILES['profile_avatar']['size'] == 0) {
                $avatar_filepath = '/public/images/avatars/placeholder.png';
            } else {
                $avatar_filepath = null;
            }

            if ($_FILES['profile_banner']['size'] != 0) {
                // file directory
                $banner_target_dir = $_SERVER['DOCUMENT_ROOT'] . '/emps';

                // get file name with unique prefix defined by time and a random uniqid
                $banner_filename = '/public/images/banners/' . time() . uniqid(rand()) . '-' . $_FILES['profile_banner']['name'];

                // get file extension
                $filename_ext = strtolower(pathinfo($banner_filename, PATHINFO_EXTENSION));

                // get file from input
                $file = $_FILES['profile_banner']['tmp_name'];

                if ($file) {
                    // check if is file
                    if (!getimagesize($file)) {
                        array_push($this->alerts, 'File is not an image.');
                        
                        // check for file has extension
                    } else if (!in_array($filename_ext, ['jpg', 'png', 'jpeg'])) {
                        array_push($this->alerts, 'Invalid file format. Only jpg/jpeg/png file format are allowed.');
                        
                        // check for file size
                    } else if ($_FILES['profile_banner']['size'] > 5000000) {
                        array_push($this->alerts, 'File too large! max: 5mb');
                    }

                    // make full destination path where the file will be uploaded
                    $destination = $banner_target_dir . $banner_filename;
                    
                    if (!$user['profile_banner']) {
                        // check if move_uploaded_file is false, then throw error
                        // else success
                        if (!move_uploaded_file($file, $destination)) {
                            array_push($this->alerts, "Failed to upload file.");
                        }
                    } else {
                        $fpath = $_SERVER['DOCUMENT_ROOT'] . '/emps/' . $user['profile_banner'];
                        $placeholder_path = '/public/images/banners/placeholder.png';

                        // check if file exists
                        if (file_exists($fpath)) {
                            if ($user['profile_banner'] != $placeholder_path) {
                                // delete old file
                                unlink($fpath);
                            }

                            // check if move_uploaded_file is false, then throw error
                            // else success
                            if (!move_uploaded_file($file, $destination)) {
                                array_push($this->alerts, "Failed to upload file.");
                            }
                        }
                    }
                }
            } else if ($user['profile_banner'] && $_FILES['profile_banner']['size'] == 0) {
                $banner_filename = $user['profile_banner'];
            } else if (!$user['profile_banner'] && $_FILES['profile_banner']['size'] == 0) {
                $banner_filename = '/public/images/banners/placeholder.png';
            } else {
                $banner_filename = null;
            }

            if (!empty($_POST['birthday_date'])) {
                $date = htmlspecialchars(strip_tags($_POST['birthday_date']));
            } else {
                $date = null;
            }

            if (!empty($_POST['gender'])) {
                $gender = htmlspecialchars(strip_tags($_POST['gender']));
                $gender = str_replace(' ', '', $gender);
            } else {
                $gender = null;
            }

            // check if alerts array is empty
            if (empty($this->alerts)) {

                // get all records from user_infos table where user_id
                $user_infos = UserInfo::get_all_by_user_id($user[0]); // here $user[0] means the id of the user

                // check count of records retrieved if its less than 1
                // then add data to DB
                $user_info = new UserInfo();
                
                $user_info->set_user_id($user[0]); // here 0 return user id
                $user_info->set_profile_avatar($avatar_filepath);
                $user_info->set_profile_banner($banner_filename);
                $user_info->set_birthday_date($date);
                $user_info->set_gender($gender);
                
                if (count($user_infos) < 1) {
                    $user_info->save();
                } else {
                    $user_info->update();
                }

                header('Location: ' . ROOT_PATH . '/userinfos/index');
            }
        }

        $data = [
            'errors' => $this->alerts,
        ];

        View::render('user/userInfo.php', $data);
    }

    public function updateAction()
    {

    }

    public function deleteAction()
    {

    }
}
