<?php

namespace App\Controllers;

use App\Models\Folow;
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
        //set user_id
        if(isset($_GET['id'])) {
            $this->user = User::findByID($_GET['id']);
            $user_id = $_GET['id'];
            $folowed = Folow::getQtyFolowed($user_id);
            $folowing = Folow::getQtyFolowing($user_id);
        }
        else {
            if (isset($_SESSION['user_id'])) {
                $user_id = $_SESSION['user_id'];
                $folowed = Folow::getQtyFolowed($user_id);
                $folowing = Folow::getQtyFolowing($user_id);
            }
        }

        //check folowed
        if(isset($_COOKIE['folowing_id'])) {
            $check_folowing = json_decode($_COOKIE['folowing_id'], true);
            foreach ($check_folowing as $val) {
                if(isset($_GET['id'])){
                    $qty = Folow::checkFolow($_SESSION['user_id'], $_GET['id'], $val);

                    if($qty[0]['qty'] == 0) {
                        $checked = 'true';
                    }
                    else {
                        $checked = 'false';
                    }
                }
                else $checked = 'default';
            }
        }
        else $checked = 'default';

        //pagination
        if (!isset($_GET['page'])) {
            $page = '';
        }
        else {
            $page = $_GET['page'];
        }
        $posts = Post::getAllByUserId($user_id, $page);
        $qtyPost = Post::getQtyFieldWithUserId($user_id);

        View::renderTemplate('Profile/show.html', [
            'user' => $this->user,
            'posts' => $posts,
            'qtyPost' => $qtyPost,
            'folowed' => $folowed,
            'folowing' => $folowing,
            'checked' => $checked
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
        $_POST['avatar'] = '';
        Auth::setImageName('avatar');
        if ($this->user->updateProfile($_POST)) {
            Auth::uploadImage('avatar');
            Flash::addMessage('Cập nhật thành công!');
            $this->redirect('/profile/show');
        } else {
            View::renderTemplate('Profile/edit.html', [
                'user' => $this->user
            ]);

        }
    }
}
