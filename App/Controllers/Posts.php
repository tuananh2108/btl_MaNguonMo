<?php

namespace App\Controllers;

use App\Auth;
use App\Flash;
use \Core\View;
use App\Models\Post;

class Posts extends \Core\Controller
{
    public function showAction() {
        $post_id = $_GET['id'];
        $post = Post::getContentPosts($post_id);
        $popular_posts = Post::getPopularPosts();
        View::renderTemplate('Posts/show.html', [
            'post' => $post,
            'popular_posts' => $popular_posts
        ]);
        $qty_views = $post[0]['views'];
        Post::countViews($post_id, $qty_views);
    }

    public function newAction() {
        View::renderTemplate('Posts/new.html');
    }

    public function createAction() {
        Auth::uploadImage('img_title');
        $post = new Post($_POST);

        if ($post->save()) {
            Flash::addMessage('Thêm mới thành công!');
        } else {
            Flash::addMessage('Có lỗi! Thêm mới thất bại!');
        }
        $this->redirect('/posts/new');
//        View::renderTemplate('Posts/new.html');
    }

    public function editAction() {
        echo 'Hello from the edit action in Posts Controller';
        var_dump($this->route_params);
    }
}
