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
    header( "location: /register_form.php");
    exit;
}

$result = UserLogic::createUser($userName);

if($result) {
    $_SESSION['login_user'] = $result;
    header('Location: main.php');
    exit();
} else {
    $err = "その名前はすでに使われています";
    $_SESSION['err'] = $err;
    header('Location: register_form.php');
    exit();
}

?>