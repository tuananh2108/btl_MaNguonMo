<?php

namespace App\Models;

use PDO;

class Folow extends \Core\Model
{
    private $cookies_folowing = [];
    public $errors = [];

    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };
    }

    public function save()
    {
        if (empty($this->errors)) {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $created_at = date('Y-m-d H:m:s');

            $sql = 'INSERT INTO folow (folowed_id, folowing_id, created_at)
                    VALUES (:folowed_id, :folowing_id, :created_at)';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':folowed_id', $this->folowed_id, PDO::PARAM_INT);
            $stmt->bindValue(':folowing_id', $this->folowing_id, PDO::PARAM_INT);
            $stmt->bindValue(':created_at', $created_at, PDO::PARAM_STR);

            //set cookies save folowing_id
            $this->cookies_folowing[] = $this->folowing_id;
            setcookie("folowing_id", json_encode($this->cookies_folowing));

            return $stmt->execute();
        }

        return false;
    }

    public function delete($data)
    {
        $this->folowing_id = $data['folowing_id'];
        $this->folowed_id = $data['folowed_id'];

        if (empty($this->errors)) {
            $sql = 'DELETE FROM folow WHERE folowing_id = :folowing_id AND folowed_id = :folowed_id';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':folowing_id', $this->folowing_id, PDO::PARAM_INT);
            $stmt->bindValue(':folowed_id', $this->folowed_id, PDO::PARAM_INT);

            return $stmt->execute();
        }

        return false;
    }

    public static function getQtyFolowing($id)
    {
        try {
            $db = static::getDB();
            $stmt = $db->query('SELECT COUNT(folowing_id) as qty FROM folow WHERE folowed_id = '.$id);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $results;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public static function getQtyFolowed($id)
    {
        try {
            $db = static::getDB();
            $stmt = $db->query('SELECT COUNT(folowed_id) as qty FROM folow WHERE folowing_id = '.$id);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $results;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public static function checkFolow($folowed_id, $folowing_id, $cookies)
    {
        try {
            $db = static::getDB();
            $stmt = $db->query('SELECT COUNT(folowing_id) as qty FROM folow WHERE folowed_id = '.$folowed_id.' AND folowing_id = '.$folowing_id.' AND folowing_id = '.$cookies);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $results;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}