<?php

session_start();

if(!isset($_SESSION['login_user'])) {
    header('Location: index.php');
    exit();
}

require_once("./db/ManagementLogic.php");

$level = $_GET['level'];

if(isset($_POST['grades'])) {
    $grades = $_POST['grades'];
} else {
    $grades = [3,4,5,6];
}

//問題を作る
$quiz = createQuiz($grades);
//迷路を作る

//値をセッションに入れて渡す
$_SESSION['quiz'] = $quiz;
$_SESSION['level'] = $level;

header('Location: game.php');
exit();

function createQuiz($grades) {
    $quizLength = 50;
    $quiz = [];

    for($i = 0; $i < $quizLength; $i ++) {
        shuffle($grades);
        $quizArray = ManagementLogic::getQuiz($grades[0]);
        shuffle($quizArray);
        array_push($quiz,$quizArray[0]);
    }

    return $quiz;
}

?>