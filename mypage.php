<?php

session_start();

require_once("./db/ManagementLogic.php");
require_once("./db/UserLogic.php");

if(!isset($_SESSION['login_user'])) {
    header('Location: index.php');
    exit();
}

$scores = ManagementLogic::getScoresByUserName($_SESSION['login_user']['userName']);

?>

<?php include("./_header.php");?>

<div id="mypage">
    <h1 class="title"><?php echo $_SESSION['login_user']['userName']?> さんのマイページ</h1>
    <h2 class="subtitle">プレイデータ</h2>
    <div class="table-wrapper">
        <table>
            <tr>
                <th class="date">日付</th>
                <th class="level">レベル</th>
                <th class="point">合計点</th>
            </tr>
            <?php foreach ($scores as &$score) { ?>
                <tr>
                    <td class="date"><?php echo $score['date']?></td>
                    <td class="level"><?php echo $score['level']?></td>
                    <td class="point"><?php echo $score['point']?></td>
                </tr>
            <?php } ?>
        </table>
    </div>
</div>

<?php include("./_footer.php");?>
