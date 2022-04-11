<?php

session_start();
require_once("./db/UserLogic.php");

if(!isset($_SESSION['login_user'])) {
    header('Location: index.php');
    exit();
}

unset($_SESSION['login_user']);

header('Location: index.php');
exit();

?>

