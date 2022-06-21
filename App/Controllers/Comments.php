<?php

namespace App\Controllers;

use App\Models\Comment;

class Comments extends \Core\Controller
{
    public function createAction()
    {
        $comment = new Comment($_POST);
        $comment->save();
        $this->redirect('/posts/show?id='.$_POST['post_id']);
    }
}