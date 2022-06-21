<?php

namespace App\Controllers;

use Core\View;

class Users extends \Core\Controller
{
    public function showAction()
    {
        View::renderTemplate('Users/show.html');
    }
}