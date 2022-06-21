<?php

namespace App\Controllers;

use App\Models\Category;
use App\Models\Post;
use Core\View;

class Search extends \Core\Controller
{
    public function searchAction()
    {
        $posts = Post::getPostsBySearch($_GET['keyword']);
        $popular_posts = Post::getPopularPosts();
        $categories = Category::getAll();
        View::renderTemplate('Home/index.html', [
            'posts' => $posts,
            'popular_posts' => $popular_posts,
            'categories' => $categories
        ]);
    }
}