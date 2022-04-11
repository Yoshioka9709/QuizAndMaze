<?php

session_start();

if(!empty($_SESSION['err'])) {
    $err = $_SESSION['err'];
}

unset($_SESSION['err']);
?>

<?php include("./_header.php");?>

<div id="setting_form">
    <p>Excelファイルからクイズデータの更新を行います</p>
    <form action="./quizSetting.php" method="post"  enctype="multipart/form-data">
        <input type="file" name="file" id="file">
        <input type="password" name="password" id="password" placeholder="Password">
        <?php if(isset($err)) { ?>
            <span class="err"><?php echo $err ?></span>
        <?php } ?>
        <div class="btn">
            <input type="submit" value="更新する">
        </div>
    </form>
    <div class="btn">
        <a href="./quiz.xlsx" download="quiz.xlsx">クイズデータをダウンロードする</a>
    </div>
</div>

<?php include("./footer.php");?>
