<?php

session_start();

if(isset($_SESSION['login_user'])) {
    header('Location: main.php');
    exit();
}

if(!empty($_SESSION['err'])) {
    $err = $_SESSION['err'];
}

unset($_SESSION['err']);

?>
<?php include("./_header.php");?>

<div id="name_form">
    <h1 class="title">はじめから</h1>
    <form action="./register.php" method="post">
        <input id="name" name="userName" type="text" placeholder="Name">
        <?php if(isset($err)) { ?>
            <span class="err"><?php echo $err ?></span>
        <?php } ?>
        <div class="btn">
            <input type="submit" value="はじめる">
        </div>
    </form>
</div>

<?php include("./_footer.php");?>