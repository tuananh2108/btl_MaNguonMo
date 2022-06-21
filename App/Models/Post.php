<?php

namespace App\Models;

use PDO;

class Post extends \Core\Model
{
    public $errors = [];

    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };
    }

    public static function getAll()
    {
        try {
            $db = static::getDB();
            $stmt = $db->query('SELECT posts.id, posts.title, posts.img_title, posts.content, posts.user_id, users.name, posts.created_at FROM posts INNER JOIN users ON posts.user_id = users.id ORDER BY posts.created_at');
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $results;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public static function getAllByUserId($id)
    {
        try {
            $db = static::getDB();

            $stmt = $db->query('SELECT id, title, content FROM posts WHERE user_id = '.$id.' ORDER BY created_at');
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $results;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public static function getPopularPosts()
    {
        try {
            $db = static::getDB();

            $stmt = $db->query('SELECT id, title, img_title, content FROM posts ORDER BY views DESC LIMIT 3');
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $results;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public static function getContentPosts($id)
    {
        try {
            $db = static::getDB();

            $stmt = $db->query('SELECT posts.title, posts.content, posts.views, users.name, posts.created_at FROM posts INNER JOIN users ON posts.user_id = users.id WHERE posts.id = '.$id.' LIMIT 1');
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $results;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function save()
    {
        if (empty($this->errors)) {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $created_at = date('Y-m-d H:m:s');

            $sql = 'INSERT INTO posts (title, img_title, content, user_id, created_at)
                    VALUES (:title, :img_title, :content, :user_id, :created_at)';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':title', $this->title, PDO::PARAM_STR);
            $stmt->bindValue(':img_title', $this->img_title, PDO::PARAM_STR);
            $stmt->bindValue(':content', $this->content, PDO::PARAM_STR);
            $stmt->bindValue(':user_id', $this->user_id, PDO::PARAM_INT);
            $stmt->bindValue(':created_at', $created_at, PDO::PARAM_STR);

            return $stmt->execute();
        }

        return false;
    }

    public static function countViews($post_id, $views) {
        $sql = 'UPDATE posts SET views = :views WHERE id = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':views', $views + 1, PDO::PARAM_INT);
        $stmt->bindValue(':id', $post_id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}