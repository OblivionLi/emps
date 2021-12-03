<?php

namespace app\controllers;

use app\models\Post;
use core\Controller;
use core\View;

class Posts extends Controller {

    public function indexAction() {
        $posts = Post::getAll();

        View::render('Posts/index.php', ['posts' => $posts]);
    }

    public function addNewAction() {
        echo 'hello from the add new action in the Posts controller';
    }

    public function editAction() {
        echo 'hello from the edit action in the Posts controller';
        echo '<p>Query string parameters: <pre>'. htmlspecialchars(print_r($this->route_params,  true)) .'</pre></p>';
    }
}
