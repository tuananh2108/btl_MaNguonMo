<?php

namespace App\Models;

use PDO;

class Comment extends \Core\Model
{
    public $errors = [];

    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };
    }

    public static function getAll($post_id)
    {
        try {
            $db = static::getDB();
            $stmt = $db->query('SELECT *, users.name as users_name, users.avatar as users_avatar FROM comments INNER JOIN users ON comments.user_id = users.id WHERE post_id = '.$post_id.' ORDER BY comments.created_at DESC');
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

            $sql = 'INSERT INTO comments (content, user_id, post_id, created_at)
                    VALUES (:content, :user_id, :post_id, :created_at)';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':content', $this->content, PDO::PARAM_STR);
            $stmt->bindValue(':user_id', $this->user_id, PDO::PARAM_INT);
            $stmt->bindValue(':post_id', $this->post_id, PDO::PARAM_INT);
            $stmt->bindValue(':created_at', $created_at, PDO::PARAM_STR);

            return $stmt->execute();
        }

        return false;
    }
}