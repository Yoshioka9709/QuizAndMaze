<?php

session_start();

if(isset($_SESSION['login_user'])) {
    header('Location: main.php');
    exit();
}

?>

<?php include("./_header.php");?>

<div id="index">
<div class="title-image-wrapper">
    <img src="./img/logo.png" alt="">
</div>
    <div class="btn">
        <a href="./register_form.php">はじめから</a>
    </div>
    <div class="btn">
        <a href="./login_form.php">つづきから</a>
    </div>
    <div class="btn">
        <a href="./howtouse.php">遊び方をみる</a>
    </div>
</div>

<?php include("./_footer.php");?>