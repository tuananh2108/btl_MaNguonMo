<?php

namespace App\Controllers;

use App\Auth;
use App\Flash;
use App\Models\Category;
use App\Models\Comment;
use \Core\View;
use App\Models\Post;

class Posts extends \Core\Controller
{
    public function showAction() {
        $post_id = $_GET['id'];
        $post = Post::getContentPosts($post_id);
        $popular_posts = Post::getPopularPosts();
        $categories = Category::getAll();
        $comments = Comment::getAll($post_id);
        View::renderTemplate('Posts/show.html', [
            'post' => $post,
            'popular_posts' => $popular_posts,
            'categories' => $categories,
            'comments' => $comments
        ]);
        $qty_views = $post[0]['views'];
        Post::countViews($post_id, $qty_views);
    }

    public function newAction() {
        $categories = Category::getAll();
        View::renderTemplate('Posts/new.html',[
            'categories' => $categories
        ]);
    }

    public function createAction() {
        $_POST['img_title'] = '';
        $post = new Post($_POST);
        if ($post->save()) {
            Auth::uploadImage('img_title');
            Flash::addMessage('Thêm mới thành công!');
        } else {
            Flash::addMessage('Có lỗi! Thêm mới thất bại!');
        }
        $this->redirect('/posts/new');
    }

    public function editAction() {
        $categories = Category::getAll();
        $post = Post::getById($_GET['id']);
        View::renderTemplate('Posts/edit.html',[
            'categories' => $categories,
            'post' => $post
        ]);
    }

    public function updateAction() {
        $_POST['img_title'] = '';
        Auth::setImageName('img_title');
        $post = new Post();
        if ($post->update($_POST)) {
            Auth::uploadImage('img_title');
            Flash::addMessage('Cập nhật thành công!');
        } else {
            Flash::addMessage('Có lỗi! Cập nhật thất bại!');
        }
        $this->redirect('/posts/edit?id='.$_POST['id']);
    }

    public function destroyAction() {
        $post = new Post();
        if ($post->delete($_GET)) {
            Flash::addMessage('Đã xoá thành công!');
        } else {
            Flash::addMessage('Có lỗi! Xoá thất bại!');
        }
        $this->redirect('/profile/show');
    }
}
