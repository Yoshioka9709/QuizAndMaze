<?php

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/destyle.css">
    <link rel="stylesheet" href="./css/style.css?ver2.0">
    <link rel="icon" href="./img/favicon.ico" type="image/x-icon"/>
    <title>Quiz & Maze</title>
</head>
<body>
