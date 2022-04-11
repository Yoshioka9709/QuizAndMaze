<?php

session_start();
require_once("./db/UserLogic.php");
require_once("./db/ManagementLogic.php");

if(!isset($_SESSION['login_user'])) {
    header('Location: index.php');
    exit();
}

$userName = $_POST['userName'];
$point = $_POST['point'];
$level = $_POST['level'];

if(isset($userName) && isset($point) && isset($level)) {
    $result1 = ManagementLogic::createScore($level, $userName, $point);
    $result2 = UserLogic::userLevelUpById($_SESSION['login_user']['id'],$level);
}

if($result1 && $result2) {
    $userName = $_SESSION['login_user']['userName'];
    unset($_SESSION['login_user']);
    $_SESSION['login_user'] = UserLogic::login($userName);
    header('Location: main.php');
    exit();
}

var_dump($result1);
var_dump($result2);

?>