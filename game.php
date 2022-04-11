<?php

session_start();

if(!isset($_SESSION['login_user'])) {
    header('Location: index.php');
    exit();
}

$quiz = json_encode($_SESSION['quiz']);
$level = $_SESSION['level'];

?>

<?php include("./_header.php");?>

<div id="game">
    <div class="quiz-wrapper">
        <div class="title-content">
            <h1 class="title">Quiz</h1>
            <p class="subTitle">クイズに正解してポイントをためよう</p>
        </div>
        <div class="quiz-content">
            <div class="bar">
                <p class="quiz-count">Q<span class="quiz-count-label"></span>.</p>
                <p class="quiz-category">Category</p>
                <p class="total-point">Pt:<span id="total-point">0</span></p>
            </div>
            <div class="quiz-text">
                <p></p>
            </div>
            <div class="quiz-image-wrapper">
                <img class="quiz-image" src="" alt="">
            </div>
            <div class="answer-button-list">
                <div id="next-result-btn" class="answer_btn btn">
                    <img class="quiz-img" src="" alt="">
                    <p class="text-label"></p>
                </div>
                <div class="incorrect_btn btn">
                    <img class="quiz-img" src="" alt="">
                    <p class="text-label"></p>
                </div>
                <div class="incorrect_btn btn">
                    <img class="quiz-img" src="" alt="">
                    <p class="text-label"></p>
                </div>
                <div class="incorrect_btn btn">
                    <img class="quiz-img" src="" alt="">
                    <p class="text-label"></p>
                </div>
            </div>
            <div class="point-wrapper">
                <div class="point-bar"></div>
                <div class="point-count"></div>
            </div>
        </div>
        <div class="result-content">
            <div id="next-quiz-btn" class="btn next-btn">
                <a href="#">つぎへ</a>
            </div>
        </div>
    </div>
    <div class="maze-wrapper">
        <div class="title-content">
            <h1 class="title">Maze</h1>
            <p class="subTitle">電気を通すものにふれずにゴールへむかおう</p>
        </div>
        <div class="maze-content">
            <canvas id="maze-can"></canvas>
            <div class="circuit-image-wrapper">
                <img id="circuit-image" src="./img/maze/turn-off.jpg" alt="">
            </div>
            <div class="point-wrapper">
                <div class="point-bar"></div>
                <div class="point-count">
                    <span></span>
                </div>
            </div>
        </div>
        <div class="result-content">
            <h1 class="title">Stage.<span class="stage-count-label">1</span></h1>
            <table>
                <tr>
                    <td class="">Quiz</td>
                    <td class="quiz-point"></td>
                </tr>
                <tr>
                    <td class="">Maze</td>
                    <td class="maze-point"></td>
                </tr>
                <tr>
                    <td class="">Total</td>
                    <td class="total-point"></td>
                </tr>
            </table>
            <div id="next-stage-btn" class="btn">
                <a href="#">次のステージへ</a>
            </div>
            <div id="suspension-btn" class="btn">
                <a href="./main.php">はじめからやりなおす</a>
            </div>
            <div id="score-save-btn" class="btn">
                <a href="#">記録する</a>
            </div>
            <form id="form" action="./saveScore.php" method="post">
                <input type="hidden" name="level" value="<?php echo $_SESSION['level']?>">
                <input type="hidden" name="userName" value="<?php echo $_SESSION['login_user']['userName']?>">
                <input type="hidden" name="point" id="input-point">
            </form>
        </div>
    </div>
</div>

<script>
    let level = <?php echo $level?>;
    let quiz = <?php echo $quiz?>;
</script>
<script src="./js/game.js?ver1.9"></script>

<?php include("./_footer.php");?>