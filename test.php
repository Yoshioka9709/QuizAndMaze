<?php

session_start();

require_once("./db/ManagementLogic.php");

$count = $_GET['count'];

$quizData = ManagementLogic::getQuizByCount($count);
$quiz = json_encode($quizData);

?>

<?php include("./_header.php");?>

<div id="game">
    <div class="quiz-wrapper">
        <div class="quiz-content">
            <div class="bar">
                <p class="quiz-count">Q<span class="quiz-count-label"></span>.<?php echo $count + 1?></p>
                <p class="quiz-category">Category</p>
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
                </div>
                <div class="incorrect_btn btn">
                    <img class="quiz-img" src="" alt="">
                </div>
                <div class="incorrect_btn btn">
                    <img class="quiz-img" src="" alt="">
                </div>
                <div class="incorrect_btn btn">
                    <img class="quiz-img" src="" alt="">
                </div>
            </div>
        </div>
        <div class="result-content">
            <div id="next-quiz-btn" class="btn next-btn">
                <a href="#">つぎへ</a>
            </div>
        </div>
        <a id="download" href="#"></a>
    </div>
</div>

<script src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>
<script>

let quiz = <?php echo $quiz?>;

const nextResultButton = document.querySelector("#next-result-btn");
const Q_quizContent = document.querySelector(".quiz-content");
const Q_incorrect_btn = document.querySelectorAll(".incorrect_btn");
const quizImage = document.querySelector('.quiz-image');

//ゲームループ関数
setQuizView();
html2image();

function setQuizView() {
    Q_quizContent.style.display = "flex";
    setQuiz();
}

//クイズ・デザインをセットする関数
function setQuiz() {
    const quizCategory = document.querySelector(".quiz-category");
    const quizText = document.querySelector(".quiz-text");

    quizCategory.innerText = quiz['unit'];
    quizText.innerText = quiz['text'];

    let list = document.querySelector(".answer-button-list");

    for(let count = list.children.length ; count >= 0; count--){
        list.appendChild(list.children[Math.random()*count|0]);
    }

    nextResultButton.style.height = "40px";

    let i = 1
    if(quiz['quizType'] == "画像選択") {
        nextResultButton.style.height = "200px";
        nextResultButton.querySelector(".quiz-img").setAttribute('src', "./img/quizImg/" + quiz['id'] + "-A.jpg");
        Q_incorrect_btn.forEach(function (button) {
            button.style.height = "200px";
            switch(i) {
                case 1:
                    button.querySelector(".quiz-img").setAttribute('src', "./img/quizImg/" + quiz['id'] + "-B.jpg");
                    break;
                case 2:
                    button.querySelector(".quiz-img").setAttribute('src', "./img/quizImg/" + quiz['id'] + "-C.jpg");
                    break;
                case 3:
                    button.querySelector(".quiz-img").setAttribute('src', "../img/quizImg/" + quiz['id'] + "-D.jpg");
                    break;
            }
            i ++;
        });
    } else {
        if(quiz['quizType'] == "画像問題"){
            quizImage.src = "../img/quizImg/" + quiz['id'] + ".jpg";
        } else {
            quizImage.src = "";
        }

        nextResultButton.innerText = quiz['answer'];
        Q_incorrect_btn.forEach(function (button) {
            button.style.height = "40px";

            if(quiz['option' + i] != null) {
                button.innerText = quiz['option' + i];
            } else {
                button.style.display = "none";
            }
            i ++;
        });
    }
}

function html2image() {
    var capture = document.querySelector("html");
    html2canvas(capture, {useCORS: true}).then(canvas => {
        var base64 = canvas.toDataURL('image/png');
        var button = document.createElement("a");
        button.setAttribute('href', base64);
        button.setAttribute('download', "quiz-" + quiz['id']);
        button.click();
    });
}

</script>

<?php include("./_footer.php");?>