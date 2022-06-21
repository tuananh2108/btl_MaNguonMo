<?php

namespace App\Controllers;

use App\Flash;
use App\Models\Folow;
use App\Models\User;
use Core\View;

class Users extends \Core\Controller
{
    public function showAction()
    {
        $users = User::getAll();
        View::renderTemplate('Users/show.html', [
            'users' => $users
        ]);
    }

    public function destroyAction()
    {
        $user = new User();
        if ($user->delete($_GET)) {
            Flash::addMessage('Đã xoá thành công!');
        } else {
            Flash::addMessage('Có lỗi! Xoá thất bại!');
        }
        $this->redirect('/users/show');
    }

    public function folowAction()
    {
        $folow = new Folow($_POST);
        if ($folow->save()) {
            Flash::addMessage('Theo dõi thành công!');
        } else {
            Flash::addMessage('Có lỗi! Theo dõi thất bại!');
        }
        $this->redirect('/profile/show?id='.$_POST['folowing_id']);
    }

    public function unfolowAction()
    {
        $folow = new Folow();
        if ($folow->delete($_POST)) {
            Flash::addMessage('Bỏ theo dõi thành công!');
        } else {
            Flash::addMessage('Có lỗi! Bỏ theo dõi thất bại!');
        }
        $this->redirect('/profile/show?id='.$_POST['folowing_id']);
    }
}