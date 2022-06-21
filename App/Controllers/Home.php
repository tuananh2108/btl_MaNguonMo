<?php

namespace App\Controllers;

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
        $posts = Post::getAll();
        $popular_posts = Post::getPopularPosts();
        View::renderTemplate('Home/index.html', [
            'posts' => $posts,
            'popular_posts' => $popular_posts
        ]);
    }
}
