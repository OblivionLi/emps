<?php

namespace app\controllers;

use core\Controller;
use core\View;

class Home extends Controller {
    public function indexAction() {

        View::render('Home/index.php', [
            'name' => 'Liviu',
            'colors' => ['red', 'green', 'blue']
        ]);
    }
}