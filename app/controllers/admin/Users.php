<?php

namespace app\controllers\admin;

use core\Controller;

class Users extends Controller {
    protected function before() {

    }

    public function indexAction() {
        echo 'User admin index';
    }
}