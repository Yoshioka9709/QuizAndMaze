<?php

require_once __DIR__ . "/dbconnect.php";
ini_set('display_errors', true);

class UserLogic {
    public static function createUser($userName) {
        $sql = 'INSERT INTO user (userName,level) VALUES (?,?)';

        $users = self::getUsers();

        forEach($users as $user) {
            if($user['userName'] == $userName) {
                return false;
            }
        }

        $arr = array();
        $arr[] = $userName;
        $arr[] = 1;

        try{
            $stmt = connect()->prepare($sql);
            $result = $stmt->execute($arr);
            return $result;
        }
        catch(\Exception $e){
            echo $e;
            return $result;
        }
    }

    public static function getUsers() {
        $sql = "SELECT * FROM user";

        try {
            $stmt = connect()->query($sql);
            $results = $stmt->fetchAll();
            return $results;
        } catch (\Exception $e) {
            echo $e;
            return false;
        }
    }

    public static function getUserById($id) {
        $sql = "SELECT * FROM user Where id = :id";

        try {
            $stmt = connect()->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch();
            return $result;
        } catch (\Exception $e) {
            echo $e;
            return false;
        }
    }

    public static function login($userName) {
        $sql = "SELECT * FROM user WHERE userName = :userName";

        try {
            $stmt = connect()->prepare($sql);
            $stmt->bindValue(':userName', $userName, PDO::PARAM_STR);
            $stmt->execute();
            $result = $stmt->fetch();
            return $result;
        } catch (\Exception $e) {
            echo $e;
            return false;
        }
    }

    public static function userLevelUpById($id,$level) {
        $user = self::getUserById($id);

        if($user['level'] > $level) {
            return true;
        }

        $sql = "UPDATE user SET level = :level WHERE id = :id";

        try {
            $stmt = connect()->prepare($sql);
            $stmt->bindValue(':level', ((int)$level + 1), PDO::PARAM_INT);
            $stmt->bindValue(':id', $user['id'], PDO::PARAM_INT);
            $result = $stmt->execute();
            return $result;
        } catch (\Exception $e) {
            echo $e;
            return false;
        }
    }
}