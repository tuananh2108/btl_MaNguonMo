<?php

namespace App\Controllers;

use App\Models\Category;
use App\Models\Post;
use \Core\View;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Home extends \Core\Controller
{

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {
        if (!isset($_GET['page'])) {
            $page = '';
        }
        else {
            $page = $_GET['page'];
        }
        $posts = Post::getAll($page);
        $qtyPost = Post::getQtyField();
        $popular_posts = Post::getPopularPosts();
        $categories = Category::getAll();
        View::renderTemplate('Home/index.html', [
            'posts' => $posts,
            'qtyPost' => $qtyPost,
            'popular_posts' => $popular_posts,
            'categories' => $categories
        ]);
    }
}
