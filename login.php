<?php

session_start();
require_once("./db/UserLogic.php");

if(isset($_SESSION['login_user'])) {
    header('Location: main.php');
    exit();
}

$err = "";
$userName = $_POST['userName'];

if (empty($userName)) {
    $err = 'お名前を入力してください';
}

if ($err != "") {
    $_SESSION['err'] = $err;
    header( "location: /login_form.php");
    exit;
}

$result = UserLogic::login($userName);

if($result) {
    $_SESSION['login_user'] = $result;
    header('Location: main.php');
    exit();
} else {
    $err = "ログインに失敗しました";
    $_SESSION['err'] = $err;
    header('Location: login_form.php');
    exit();
}

?>