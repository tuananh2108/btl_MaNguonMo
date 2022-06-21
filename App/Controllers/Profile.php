<?php

namespace App\Controllers;

use App\Models\Post;
use App\Models\User;
use \Core\View;
use \App\Auth;
use \App\Flash;

/**
 * Profile controller
 *
 * PHP version 7.0
 */
class Profile extends Authenticated
{

    /**
     * Before filter - called before each action method
     *
     * @return void
     */
    protected function before()
    {
        parent::before();

        $this->user = Auth::getUser();
    }

    /**
     * Show the profile
     *
     * @return void
     */
    public function showAction()
    {
        if(isset($_GET['id'])) {
            $this->user = User::findByID($_GET['id']);
        }
        if (isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
        }

        View::renderTemplate('Profile/show.html', [
            'user' => $this->user,
            'posts' => Post::getAllByUserId($user_id)
        ]);
    }

    /**
     * Show the form for editing the profile
     *
     * @return void
     */
    public function editAction()
    {
        View::renderTemplate('Profile/edit.html', [
            'user' => $this->user
        ]);
    }

    /**
     * Update the profile
     *
     * @return void
     */
    public function updateAction()
    {
        Auth::uploadImage('avatar');
        if ($this->user->updateProfile($_POST)) {

            Flash::addMessage('Cập nhật thành công');

            $this->redirect('/profile/show');

        } else {

            View::renderTemplate('Profile/edit.html', [
                'user' => $this->user
            ]);

        }
    }
}
