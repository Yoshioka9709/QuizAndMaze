<?php

require_once __DIR__ . "/dbconnect.php";
ini_set('display_errors', true);

class ManagementLogic {
    public static function getQuiz($grade) {
        $sql = "SELECT * FROM quiz WHERE grade = :grade";

        try {
            $stmt = connect()->prepare($sql);
            $stmt->bindValue(':grade', $grade, PDO::PARAM_STR);
            $stmt->execute();
            $results = $stmt->fetchAll();
            return $results;
        } catch (\Exception $e) {
            echo $e;
            return false;
        }
    }

    public static function createScore($level, $userName, $point){
        $result = false;

        $sql = 'INSERT INTO score (level, userName, point) VALUES (?,?,?)';

        $arr = array();
        $arr[] = $level;
        $arr[] = $userName;
        $arr[] = $point;

        try{
            $stmt = connect()->prepare($sql);
            $result = $stmt->execute($arr);
            return $result;
        } catch(\Exception $e) {
            echo $e;
            return $result;
        }
    }

    public static function getScores($level) {
        $sql = "SELECT * FROM score WHERE level = :level ORDER BY point DESC LIMIT 10";

        try {
            $stmt = connect()->prepare($sql);
            $stmt->bindValue(':level', $level, PDO::PARAM_INT);
            $stmt->execute();
            $results = $stmt->fetchAll();
            return $results;
        } catch (\Exception $e) {
            echo $e;
            return false;
        }
    }

    public static function getScoresByUserName($userName) {
        $sql = "SELECT * FROM score WHERE userName = :userName ORDER BY date DESC";

        try {
            $stmt = connect()->prepare($sql);
            $stmt->bindValue(':userName', $userName, PDO::PARAM_STR);
            $stmt->execute();
            $results = $stmt->fetchAll();
            return $results;
        } catch (\Exception $e) {
            echo $e;
            return false;
        }
    }


    public static function createQuiz($data) {
        $result = false;

        $sql = 'INSERT INTO quiz (id, grade, unit, quizType, text, answer, option1, option2, option3) VALUES (?,?,?,?,?,?,?,?,?)';

        $arr = array();

        $arr[] = $data[0];
        $arr[] = $data[1];
        $arr[] = $data[2];
        $arr[] = $data[3];
        $arr[] = $data[4];
        $arr[] = $data[5];
        $arr[] = $data[6];
        $arr[] = $data[7];
        $arr[] = $data[8];

        try{
            $stmt = connect()->prepare($sql);
            $result = $stmt->execute($arr);
            return $result;
        } catch(\Exception $e) {
            echo $e;
            return $result;
        }
    }

    public static function deleteQuiz() {
        $result = false;

        $sql = 'TRUNCATE TABLE quiz';

        try{
            $stmt = connect()->prepare($sql);
            $result = $stmt->execute();
            return $result;
        } catch(\Exception $e) {
            echo $e;
            return $result;
        }
    }

    public static function getQuizByCount($count) {
        $sql = "SELECT * FROM quiz WHERE id = (SELECT id FROM quiz GROUP BY id ORDER BY id ASC LIMIT $count ,1)";

        try {
            $stmt = connect()->prepare($sql);
            $stmt->execute();
            $results = $stmt->fetch();
            return $results;
        } catch (\Exception $e) {
            echo $e;
            return false;
        }
    }
}