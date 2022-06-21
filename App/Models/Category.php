<?php

namespace App\Models;

use PDO;

class Category extends \Core\Model
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
            $stmt = $db->query('SELECT * FROM category ORDER BY created_at DESC');
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $results;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function save()
    {
        $this->validate();

        if (empty($this->errors)) {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $created_at = date('Y-m-d H:m:s');

            $sql = 'INSERT INTO category (name, created_at)
                    VALUES (:name, :created_at)';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
            $stmt->bindValue(':created_at', $created_at, PDO::PARAM_STR);

            return $stmt->execute();
        }

        return false;
    }

    public function update($data)
    {
        $this->id = $data['id'];
        $this->name = $data['name'];

        $this->validate();

        if (empty($this->errors)) {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $updated_at = date('Y-m-d H:m:s');

            $sql = 'UPDATE category
                    SET name = :name,
                        updated_at = :updated_at
                    WHERE id = :id';


            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
            $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
            $stmt->bindValue(':updated_at', $updated_at, PDO::PARAM_STR);

            return $stmt->execute();
        }

        return false;
    }

    public function delete($data)
    {
        $this->id = $data['id'];

        if (empty($this->errors)) {
            $sql = 'DELETE FROM category WHERE id = :id';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

            return $stmt->execute();
        }

        return false;
    }

    public function validate()
    {
        if ($this->name == '') {
            $this->errors[] = 'Name is required';
        }
        else {
            if (static::nameExists($this->name, $this->id ?? null)) {
                $this->errors[] = 'Name exists';
            }
        }
    }

    public static function nameExists($name, $ignore_id = null)
    {
        $category = static::findByName($name);

        if ($category) {
            if ($category->id != $ignore_id) {
                return true;
            }
        }

        return false;
    }

    public static function findByName($name)
    {
        $sql = 'SELECT * FROM category WHERE name = :name';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetch();
    }

    public static function findById($id)
    {
        $sql = 'SELECT * FROM category WHERE id = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetch();
    }
}