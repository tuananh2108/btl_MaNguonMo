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

    public static function getById($id)
    {
        try {
            $db = static::getDB();
            $stmt = $db->query('SELECT posts.id, posts.title, posts.content, posts.category_id, posts.user_id, users.name as users_name, category.name as category_name FROM posts INNER JOIN users ON posts.user_id = users.id INNER JOIN category ON posts.category_id = category.id WHERE posts.id = '.$id);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $results;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public static function getAll($page)
    {
        $qtyOnPage = 5;
        if ($page == '') {
            $p = 1;
        }
        else {
            $p = $page;
        }
        $onPage = ($p - 1) * $qtyOnPage;
        try {
            $db = static::getDB();
            $stmt = $db->query('SELECT posts.*, users.name as users_name FROM posts INNER JOIN users ON posts.user_id = users.id ORDER BY posts.created_at DESC LIMIT '.$onPage.', '.$qtyOnPage);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $results;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public static function getQtyField()
    {
        try {
            $db = static::getDB();
            $stmt = $db->query('SELECT COUNT(id) as qty FROM posts');
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $results;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public static function getAllByUserId($id, $page)
    {
        $qtyOnPage = 3;
        if ($page == '') {
            $p = 1;
        }
        else {
            $p = $page;
        }
        $onPage = ($p - 1) * $qtyOnPage;
        try {
            $db = static::getDB();

            $stmt = $db->query('SELECT * FROM posts WHERE user_id = '.$id.' ORDER BY created_at LIMIT '.$onPage.', '.$qtyOnPage);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $results;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public static function getQtyFieldWithUserId($id)
    {
        try {
            $db = static::getDB();
            $stmt = $db->query('SELECT COUNT(id) as qty FROM posts WHERE user_id = '.$id);
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

            $stmt = $db->query('SELECT * FROM posts ORDER BY views DESC LIMIT 3');
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

            $stmt = $db->query('SELECT posts.*, users.name as users_name, category.name as category_name FROM posts INNER JOIN users ON posts.user_id = users.id INNER JOIN category ON posts.category_id = category.id WHERE posts.id = '.$id.' LIMIT 1');
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

            $sql = 'INSERT INTO posts (title, img_title, content, category_id, user_id, created_at)
                    VALUES (:title, :img_title, :content, :category_id, :user_id, :created_at)';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':title', $this->title, PDO::PARAM_STR);
            $stmt->bindValue(':img_title', $this->img_title, PDO::PARAM_STR);
            $stmt->bindValue(':content', $this->content, PDO::PARAM_STR);
            $stmt->bindValue(':category_id', $this->category_id, PDO::PARAM_INT);
            $stmt->bindValue(':user_id', $this->user_id, PDO::PARAM_INT);
            $stmt->bindValue(':created_at', $created_at, PDO::PARAM_STR);

            return $stmt->execute();
        }

        return false;
    }

    public function update($data)
    {
        $this->id = $data['id'];
        $this->title = $data['title'];
        if ($data['img_title'] != '') {
            $this->img_title = $data['img_title'];
        }
        $this->content = $data['content'];
        $this->category_id = $data['category_id'];
        $this->user_id = $data['user_id'];

        if (empty($this->errors)) {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $updated_at = date('Y-m-d H:m:s');

            $sql = 'UPDATE posts
                    SET title = :title,
                        content = :content,
                        category_id = :category_id,';

            if(isset($this->img_title)) {
                $sql .= 'img_title = :img_title,';
            }

            $sql .= 'updated_at = :updated_at WHERE id = :id AND user_id = :user_id';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':title', $this->title, PDO::PARAM_STR);
            $stmt->bindValue(':content', $this->content, PDO::PARAM_STR);
            if(isset($this->img_title)) {
                $stmt->bindValue(':img_title', $this->img_title, PDO::PARAM_STR);
            }
            $stmt->bindValue(':category_id', $this->category_id, PDO::PARAM_INT);
            $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
            $stmt->bindValue(':user_id', $this->user_id, PDO::PARAM_INT);
            $stmt->bindValue(':updated_at', $updated_at, PDO::PARAM_STR);

            return $stmt->execute();
        }

        return false;
    }

    public function delete($data)
    {
        $this->id = $data['id'];

        if (empty($this->errors)) {
            $sql = 'DELETE FROM posts WHERE id = :id';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

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

    public static function getPostsBySearch($keyword)
    {
        try {
            $db = static::getDB();

            $stmt = $db->query('SELECT posts.*, users.name as users_name, category.name as category_name FROM posts INNER JOIN users ON posts.user_id = users.id INNER JOIN category ON posts.category_id = category.id WHERE posts.title LIKE "%'.$keyword.'%" OR posts.content LIKE "%'.$keyword.'%" OR category.name LIKE "%'.$keyword.'%"');
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $results;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}