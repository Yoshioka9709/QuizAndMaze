<?php

session_start();

require_once("./db/ManagementLogic.php");

if(!isset($_SESSION['login_user'])) {
    header('Location: index.php');
    exit();
}

if(isset($_GET['level'])) {
    $level = $_GET['level'];
} else {
    $level = 0;
}

if(isset($_GET['level'])) {
    $scores = ManagementLogic::getScores($level);
}

?>

<?php include("./_header.php");?>

<div id="main">
    <?php if($level == 1) { ?>
        <h1 class="title">初級</h1>
        <div class="ranking-content">
            <table>
                <tr>
                    <th class="rank">Rank</th>
                    <th class="userName">Name</th>
                    <th class="point">Point</th>
                </tr>
                <?php
                    $i = 0; foreach ($scores as &$score) { $i ++; ?>
                    <tr>
                        <td class="rank"><?php echo $i ?></td>
                        <td class="userName"><?php echo $score['userName'] ?></td>
                        <td class="point"><?php echo $score['point'] ?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>
        <div class="option-content">
            <form action="./setting.php?level=1" method="post">
                <h2 class="subtitle">オプションをえらぶ</h2>
                <div class="option-list">
                    <div class="option-btn">
                        <input type="checkbox" name="grades[]" value="3">３年生
                    </div>
                    <div class="option-btn">
                        <input type="checkbox" name="grades[]" value="4">４年生
                    </div>
                    <div class="option-btn">
                        <input type="checkbox" name="grades[]" value="5">５年生
                    </div>
                    <div class="option-btn">
                        <input type="checkbox" name="grades[]" value="6">６年生
                    </div>
                </div>
        </div>
                <div class="btn">
                    <input type="submit" value="はじめる">
                </div>
            </form>
        <div class="btn">
            <a href="#" onclick="history.back();">もどる</a>
        </div>
    <?php } ?>
    <?php if($level == 2 && $_SESSION['login_user']['level'] >= 2) { ?>
        <h1 class="title">中級</h1>
        <div class="ranking-content">
            <table>
                <tr>
                    <th class="rank">Rank</th>
                    <th class="userName">Name</th>
                    <th class="point">Point</th>
                </tr>
                <?php
                    $i = 0; foreach ($scores as &$score) { $i ++; ?>
                    <tr>
                        <td class="rank"><?php echo $i ?></td>
                        <td class="userName"><?php echo $score['userName'] ?></td>
                        <td class="point"><?php echo $score['point'] ?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>
        <div class="option-content">
            <form action="./setting.php?level=2" method="post">
                <h2 class="subtitle">オプションをえらぶ</h2>
                <div class="option-list">
                    <div class="option-btn">
                        <input type="checkbox" name="grades[]" value="3">３年生
                    </div>
                    <div class="option-btn">
                        <input type="checkbox" name="grades[]" value="4">４年生
                    </div>
                    <div class="option-btn">
                        <input type="checkbox" name="grades[]" value="5">５年生
                    </div>
                    <div class="option-btn">
                        <input type="checkbox" name="grades[]" value="6">６年生
                    </div>
                </div>
        </div>
                <div class="btn">
                    <input type="submit" value="はじめる">
                </div>
            </form>
        <div class="btn">
            <a href="#" onclick="history.back();">もどる</a>
        </div>
    <?php } ?>
    <?php if($level == 3 && $_SESSION['login_user']['level'] >= 3) { ?>
        <h1 class="title">上級</h1>
        <div class="ranking-content">
            <table>
                <tr>
                    <th class="rank">Rank</th>
                    <th class="userName">Name</th>
                    <th class="point">Point</th>
                </tr>
                <?php
                    $i = 0; foreach ($scores as &$score) { $i ++; ?>
                    <tr>
                        <td class="rank"><?php echo $i ?></td>
                        <td class="userName"><?php echo $score['userName'] ?></td>
                        <td class="point"><?php echo $score['point'] ?></td>
                    </tr>
                <?php } ?>
            </table>
        </div>
        <div class="option-content">
            <form action="./setting.php?level=3" method="post">
            <h2 class="subtitle">オプションをえらぶ</h2>
            <div class="option-list">
                <div class="option-btn">
                    <input type="checkbox" name="grades[]" value="3">３年生
                </div>
                <div class="option-btn">
                    <input type="checkbox" name="grades[]" value="4">４年生
                </div>
                <div class="option-btn">
                    <input type="checkbox" name="grades[]" value="5">５年生
                </div>
                <div class="option-btn">
                    <input type="checkbox" name="grades[]" value="6">６年生
                </div>
            </div>
        </div>
                <div class="btn">
                    <input type="submit" value="はじめる">
                </div>
            </form>
        <div class="btn">
            <a href="#" onclick="history.back();">もどる</a>
        </div>
    <?php } ?>
    <?php if($level == 0) { ?>
        <h1 class="title">ステージをえらぶ</h1>
        <div class="btn">
            <a href="./main.php?level=1">初級</a>
        </div>
        <?php if($_SESSION['login_user']['level'] >= 2) { ?>
            <div class="btn">
                <a href="./main.php?level=2">中級</a>
            </div>
        <?php }?>
        <?php if($_SESSION['login_user']['level'] >= 3) { ?>
            <div class="btn">
                <a href="./main.php?level=3">上級</a>
            </div>
        <?php }?>
        <h1 class="title"></h1>
        <div class="btn">
            <a href="./howtouse.php">遊び方をみる</a>
        </div>
        <div class="btn">
            <a href="./mypage.php?id=<?php echo $_SESSION['login_user']['id']?>">マイページ</a>
        </div>
        <div class="btn">
            <a href="./logout.php?id=<?php echo $_SESSION['login_user']['id']?>">ログアウトする</a>
        </div>
    <?php } ?>
</div>

<script>
    let bgm = new Audio();
    bgm.src = "./se/home.mp3";
    bgm.loop = true;
    bgm.play();
</script>

<?php include("./_footer.php");?>