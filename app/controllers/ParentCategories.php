<?php

namespace app\controllers;

use app\models\ParentCategory;
use core\Controller;
use core\View;

class ParentCategories extends Controller {
    private $alerts = array();

    public function indexAction()
    {
        // get parent categories information
        $parent_categories = ParentCategory::get_all();

        $data = [
            'errors' => $this->alerts,
            'parent_categories'   => $parent_categories,
        ];

        View::render('user/userInfo.php', $data);
    }

    public function create()
    {
        // check if its a post request
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
            $this->alerts = array();

            // create placeholder for title slug
            // all spaces will be replaced with '-' after the sanitization of the title
            $slug_title = '';

            // convert special characters to html entities and strip tags from $_POST values
            // and check if inputs are empty
            // validate $_POST inputs
            // remove white spaces from input values
            if (!empty($_POST['title'])) {
                $title = htmlspecialchars(strip_tags($_POST['title']));

                // replace all spaces with '-'
                $slug_title = str_replace(' ', '-',  $title);

                // check title length
                if (strlen($title) > 100 || strlen($title) < 2) {
                    // add error to alerts array
                    array_push($this->alerts, 'Your title must be between 2 and 100 characters long.');
                }

                // // check title for special characters
                // if (!preg_match('/^[a-zA-Z0-9]+$/', $title)) {
                //     // add error to alerts array
                //     array_push($this->alerts, 'Title can contain only letters (a-z) and numbers (0-9).');
                // }
            } else {
                array_push($this->alerts, 'Title field cannot be empty.');
            }

            if (!empty($_POST['description'])) {
                $description = htmlspecialchars(strip_tags($_POST['description']));
            } else {
                array_push($this->alerts, 'Description field cannot be empty.');
            }

            // check if alerts array is empty
            if (empty($this->alerts)) {
                // check count of records retrieved if its less than 1
                // then add data to DB
                $parent_category = new ParentCategory();
                
                $parent_category->set_title($title);

                // lower case slug
                $parent_category->set_slug(strtolower($slug_title));

                $parent_category->set_description($description);
                
                $parent_category->save();

                header('Location: ' . ROOT_PATH . '/home/index');
            }
        }

        $data = [
            'errors' => $this->alerts,
        ];

        View::render('category/parentCategory.php', $data);
    }

    public function updateAction()
    {

    }

    public function deleteAction()
    {

    }
}