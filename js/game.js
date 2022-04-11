//１問あたりの時間(15s)
const QuizTimer = 900;
//誤答した場合の減少時間(2s)
const reduceTime = 60;

let quizLength = 3;
let maxStageLevel = 3;

//レベル別の難易度設定
switch(level) {
    //初級
    case 1:
        maxStageLevel = 3;
        quizLength = 3;
        break;
    //中級
    case 2:
        maxStageLevel = 4;
        quizLength = 4;
        break;
    //上級
    case 3:
        maxStageLevel = 5;
        quizLength = 4;
        break;
}

//プレイヤーデータ
const player_size = 10;
let player_x = 30;
let player_y = 300;
let current_x = player_x;
let current_y = player_y;
let canMove = false;

//変数データ
let Q_timer = QuizTimer;
let Q_resultFlag = false;
let Q_quizFlag = false;
let totalPoint = 0;
let isShowMazeView = true;
let isShowQuizView = true;
let isCreateNextStage = true;
let mazeClearFlag = false;
let isEnergize = false;
let quizCount = 1;
let stageLevel = 1;
let imageData = "";
let calcMazeBar = 0;
let mode = "quiz";

//サウンドオブジェクト
let correctSound = new Audio();
correctSound.src = "./se/correct.mp3";
let incorrectSound = new Audio();
incorrectSound.src = "./se/incorrect.mp3";
let mazeSound = new Audio();
mazeSound.src = "./se/maze.mp3";
let commentarySound = new Audio();
commentarySound.src = "./se/commentary.mp3";
let gameoverSound = new Audio();
gameoverSound.src = "./se/gameover.mp3";

//Quizオブジェクト
const quizWrapper = document.querySelector(".quiz-wrapper");
const Q_titleContent = quizWrapper.querySelector(".title-content");
const Q_quizContent = quizWrapper.querySelector(".quiz-content");
const Q_resultContent = quizWrapper.querySelector(".result-content");

const Q_quizCountLabel =  quizWrapper.querySelectorAll(".quiz-count-label");
const Q_pointBar = quizWrapper.querySelector(".point-bar");
const Q_pointCount = quizWrapper.querySelector(".point-count");
const Q_totalPoint = quizWrapper.querySelector("#total-point");
const Q_incorrect_btn = quizWrapper.querySelectorAll(".incorrect_btn");
const Q_point_wrapper = quizWrapper.querySelector(".point-wrapper");

const nextResultButton = Q_quizContent.querySelector("#next-result-btn");
const quizText = Q_quizContent.querySelector(".quiz-text");
const quizImage = Q_quizContent.querySelector('.quiz-image');

//解答ボタンの設定
nextResultButton.addEventListener("click", () => {
    Q_resultFlag = true;
});
Q_incorrect_btn.forEach(function (button) {
    button.addEventListener("click", () => {
        Q_timer -= reduceTime;
        incorrectSound.play();
        button.style.opacity = "0.3";
    });
});

const resultLabel = quizWrapper.querySelector(".result-label");
const nextQuizButton = Q_resultContent.querySelector("#next-quiz-btn");
nextQuizButton.addEventListener("click", () => {
    Q_quizFlag = true;
});

//Mazeオブジェクト
const mazeWrapper = document.querySelector(".maze-wrapper");
const M_titleContent = mazeWrapper.querySelector(".title-content");
const M_mazeContent = mazeWrapper.querySelector(".maze-content");
const M_resultContent = mazeWrapper.querySelector(".result-content");
const M_pointBar = mazeWrapper.querySelector(".point-bar");
const M_pointCount = mazeWrapper.querySelector(".point-count");
const circuitImage = mazeWrapper.querySelector("#circuit-image");
const nextStageButton = M_resultContent.querySelector("#next-stage-btn");
const scoreSaveButton = M_resultContent.querySelector("#score-save-btn");

//次のステージへ移動する
nextStageButton.addEventListener("click", () => {
    mode = "nextStage";
});

//ランキングに記録する
scoreSaveButton.addEventListener("click", () => {
    let form = document.querySelector("#form");
    let inputPoint = document.querySelector("#input-point");
    inputPoint.value = totalPoint;
    form.submit();
});

//キャンバスの設定
const canvas = mazeWrapper.querySelector("#maze-can");
const context = canvas.getContext("2d");
canvas.width = 900;
canvas.height = 600;

//キャンバス上のマウスの動きを制御
canvas.addEventListener("mouseup", dragEnd);
canvas.addEventListener("mouseout", dragEnd);
canvas.addEventListener("mousemove", (event) => {
    current_x = event.layerX;
    current_y = event.layerY;

    if(!canMove) {
        var hitCheckImage = context.getImageData(current_x, current_y, 1, 1);
        var r = hitCheckImage.data[0];
        var g = hitCheckImage.data[1];
        var b = hitCheckImage.data[2];

        if(r == 255 && g == 99 && b == 71) {
            canMove = true;
        }
    }
});

function dragStart(event) {
    context.beginPath();
    isDrag = true;
}

function dragEnd(event) {
    context.closePath();
    isDrag = false;
}

//ゲームループ関数
window.onload = function() {
    gameLoop();
}

setInterval("gameLoop()",1000/60);

async function gameLoop() {
    if(mode == "quiz") {
        if(isShowQuizView) {
            showFirstView();
            setQuiz();
            createMaze((stageLevel * 2) + 3,(stageLevel * 2) + 1);
        } else {
            if(Q_timer > 0) {
                Q_timer -= 0.5;
            } else {
                quizResult(false);
            }
            Q_totalPoint.innerText = totalPoint;
            Q_pointBar.style.width = Q_timer / QuizTimer * 100 + "%";
            Q_pointCount.innerText = Math.floor(Q_timer);

            if(Q_resultFlag) {
                quizResult(true);
            }
            if(Q_quizFlag) {
                nextQuiz();
            }
        }
    }
    if(mode == "maze") {
        if(isShowMazeView) {
            showMazeView();
            M_pointCount.innerText = totalPoint;
            calcMazeBar = totalPoint
        } else {
            if(mazeClearFlag) {
                showResultView(true);
            } else {
                if(totalPoint > 0) {
                    update();
                    draw();
                } else {
                    showResultView(false);
                }
            }
        }
    }
    if(mode == "nextStage") {
        if(isCreateNextStage) {
            createNextStage();
        }
    }
}

//プレイヤーの位置を更新する関数
function update() {
    let next_x = player_x;
    let next_y = player_y;

    if(current_x > player_x) {
        if(current_x - player_x > 17) {
            next_x += 8;
        } else {
            next_x += 1;
        }
    }
    if(current_x < player_x) {
        if(current_x - player_x < 17) {
            next_x -= 8;
        } else {
            next_x -= 1;
        }
    }
    if(current_y > player_y) {
        if(current_y - player_y > 17) {
            next_y += 8;
        } else {
            next_y += 1;
        }
    }
    if(current_y < player_y) {
        if(current_y - player_y < 17) {
            next_y -= 8;
        } else {
            next_y -= 1;
        }
    }
    context.putImageData(imageData, 0, 0);

    var hitCheckImage = context.getImageData(next_x, next_y, 10, 10);
    var r = hitCheckImage.data[0];
    var g = hitCheckImage.data[1];
    var b = hitCheckImage.data[2];

    if(r == 0 && g == 0 && b == 0) {
        isEnergize = true;
        circuitImage.src = "./img/maze/turn-on.jpg"
    } else {
        isEnergize = false;

        if(r == 62 && g == 179 && b == 112)  {
            mazeClearFlag = true;
        }

        if(canMove) {
            player_x = next_x;
            player_y = next_y;
        }

        circuitImage.src = "./img/maze/turn-off.jpg"
    }

    if(isEnergize) {
        totalPoint -= 10;
    } else {
        totalPoint -= 1;
    }
}

//描画関数
function draw() {
    context.fillStyle = "#000000";
    context.fillRect(player_x - (player_size / 2), player_y - (player_size / 2), player_size, player_size);
    M_pointCount.innerText = totalPoint;
    M_pointBar.style.width = (totalPoint / calcMazeBar) *  100 + "%";
}

//クイズのファーストビューを表示する関数
async function showFirstView() {
    isShowQuizView = false;
    Q_titleContent.style.display = "block";
    await _sleep(1000);
    Q_titleContent.style.display = "none";
    await _sleep(1000);
    Q_quizContent.style.display = "flex";
    Q_point_wrapper.style.display = "flex";
}

//迷路画面を表示する関数
async function showMazeView() {
    isShowMazeView = false;
    Q_quizContent.style.display = "none";
    M_titleContent.style.display = "block";
    await _sleep(1000);
    M_titleContent.style.display = "none";
    await _sleep(1000);
    M_mazeContent.style.display = "flex";
    mazeSound.play();
    mazeSound.loop = true;
}

//リザルトを表示する関数
async function showResultView(result) {
    mazeSound.pause();
    mode = "result";
    M_mazeContent.style.display = "none";
    await _sleep(1000);
    M_resultContent.style.display = "block";
    const quizPointLabel = M_resultContent.querySelector(".quiz-point");
    const mazePointLabel = M_resultContent.querySelector(".maze-point");
    const totalPointLabel = M_resultContent.querySelector(".total-point");
    const stageCountLabel = M_resultContent.querySelector(".stage-count-label");
    stageCountLabel.innerText = stageLevel;

    if(result) {
        quizPointLabel.innerText = calcMazeBar;
        mazePointLabel.innerText = totalPoint - calcMazeBar;
        totalPointLabel.innerText = totalPoint;

        if(stageLevel >= maxStageLevel) {
            const scoreSaveButton = M_resultContent.querySelector("#score-save-btn");
            scoreSaveButton.style.display = "block";
            const suspensionBtn = M_resultContent.querySelector("#suspension-btn");
            suspensionBtn.style.display = "none";
            const nextStageBtn = M_resultContent.querySelector("#next-stage-btn");
            nextStageBtn.style.display = "none";
        }
    } else {
        quizPointLabel.innerText = calcMazeBar;
        mazePointLabel.innerText = calcMazeBar * -1;
        totalPointLabel.innerText = 0;
        const nextStageBtn = M_resultContent.querySelector("#next-stage-btn");
        nextStageBtn.style.display = "none";
        gameoverSound.play();
    }
}

//次のステージを作る関数
async function createNextStage() {
    canMove = false;
    isCreateNextStage = false;
    mazeClearFlag = false;
    calcMazeBar = 0;

    M_resultContent.style.display = "none";
    await _sleep(1000);
    stageLevel += 1;
    quizCount = 1;
    mode = "quiz";
    player_x = 20;
    player_y = 300;
    current_x = player_x;
    current_y = player_y;
    isShowMazeView = true;
    isShowQuizView = true;
    isCreateNextStage = true;
}

//クイズ・デザインをセットする関数
function setQuiz() {
    quiz = arrayShuffle(quiz);
    const quizCategory = quizWrapper.querySelector(".quiz-category");
    const quizText = quizWrapper.querySelector(".quiz-text");

    quizCategory.innerText = quiz[0]['unit'];
    quizText.innerText = quiz[0]['text'];
    console.log(Number(quiz[0]['id']));

    let list = quizWrapper.querySelector(".answer-button-list");

    for(let count = list.children.length ; count >= 0; count--){
        list.appendChild(list.children[Math.random()*count|0]);
    }

    let i = 1
    if(quiz[0]['quizType'] == "画像選択") {
        nextResultButton.style.height = "200px";

        nextResultButton.querySelector(".text-label").style.display = "none";
        nextResultButton.querySelector(".quiz-img").style.display = "block";
        nextResultButton.querySelector(".quiz-img").src = "../img/quizImg/" + Number(quiz[0]['id']) + "-A.jpg";
        quizImage.src = "";

        Q_incorrect_btn.forEach(function (button) {
            button.style.height = "200px";
            button.style.display = "flex";

            button.querySelector(".text-label").style.display = "none";
            button.querySelector(".quiz-img").style.display = "block";

            switch(i) {
                case 1:
                    button.querySelector(".quiz-img").src = "../img/quizImg/" + quiz[0]['id'] + "-B.jpg";
                    break;
                case 2:
                    button.querySelector(".quiz-img").src = "../img/quizImg/" + quiz[0]['id'] + "-C.jpg";
                    break;
                case 3:
                    button.querySelector(".quiz-img").src = "../img/quizImg/" + quiz[0]['id'] + "-D.jpg";
                    break;
            }
            i ++;
        });
    } else {
        if(quiz[0]['quizType'] == "画像問題"){
            quizImage.src = "./img/quizImg/" + quiz[0]['id'] + ".jpg";
        } else {
            quizImage.src = "";
        }

        nextResultButton.style.height = "40px";
        nextResultButton.querySelector(".text-label").style.display = "block";
        nextResultButton.querySelector(".quiz-img").style.display = "none";
        nextResultButton.querySelector(".text-label").innerText = quiz[0]['answer'];

        Q_incorrect_btn.forEach(function (button) {
            button.style.height = "40px";
            button.querySelector(".text-label").style.display = "block";
            button.querySelector(".quiz-img").style.display = "none";

            if(quiz[0]['option' + i] != null) {
                button.querySelector(".text-label").innerText = quiz[0]['option' + i];
            } else {
                button.style.display = "none";
            }
            i ++;
        });
    }
}

//クイズの正誤を表示する関数
function quizResult(result) {
    nextResultButton.style.pointerEvents = "none";
    Q_point_wrapper.style.display = "none";
    Q_resultContent.style.display = "block";
    Q_resultFlag = false;

    Q_incorrect_btn.forEach(function (button) {
        button.style.opacity = "0.3";
    });

    if(result) {
        totalPoint += Math.floor(Q_timer);
        correctSound.play();
    }
}

//次のクイズに進む関数
function nextQuiz() {
    nextResultButton.style.pointerEvents = "auto";

    quizCount ++ ;
    Q_timer = QuizTimer;
    Q_quizFlag = false;
    Q_quizCountLabel.forEach(function (label) {
        label.innerText = quizCount;
    });
    Q_incorrect_btn.forEach(function (button) {
        button.style.opacity = "1";
    });
    Q_resultContent.style.display = "none";

    if(quizCount < quizLength) {
        setQuiz();
        Q_point_wrapper.style.display = "flex";
    } else {
        mode = "maze";
    }
}

//迷路を生成する関数
function createMaze(mazeWidth, mazeHeight) {
    let x = 0;
    let y = 0;
    let mazeData = [];

    createMazeArray(mazeWidth,mazeHeight);
    createMaze();
    //(最大値,最小値)
    drawMaze(mazeWidth,mazeHeight);

    function createMazeArray(width, height) {
        let x = width * 2 + 1;
        let y = height * 2 + 1;
        for(let i = 0; i < x; i ++) {
            let dataArray = [];

            for(let j = 0; j < y; j ++) {
                if(i == 0 || j == 0 || j == y - 1 || i == x - 1) {
                    dataArray.push(1);
                } else {
                    dataArray.push(0);
                }
            }
            mazeData.push(dataArray);
        }
    }

    function createMaze() {
        let created = false;
        let nowUpdateData = new Array();

        while(!created) {
            x = returnRandomNumber(mazeData.length);
            y = returnRandomNumber(mazeData[0].length);

            //A.通路の座標を適当に選び,0か確認。1であればbreak
            if(mazeData[x][y] == 0) {
                nowUpdateData = new Array();

                let finished = false;
                let count = 0;
                let cantMoveCount = 0;

                while(!finished) {
                    //1.選ばれた座標を1にする
                    mazeData[x][y] = 1;
                    //2.選ばれた座標を現在拡張している壁に登録する
                    if(cantMoveCount <= 0) {
                        nowUpdateData.push([x,y]);
                    }

                    //3.ランダム方向に現在拡張していない壁方向に壁を拡張する
                    let moveFlags = [];
                    if(0 < x) {
                        if(checkNowUpdateData(x - 2,y)) {
                            moveFlags.push([x - 2, y]);
                        }
                    }
                    if(x < mazeData.length - 1) {
                        if(checkNowUpdateData(x + 2,y)) {
                            moveFlags.push([x + 2 , y]);
                        }
                    }
                    if(0 < y) {
                        if(checkNowUpdateData(x,y - 2)) {
                            moveFlags.push([x , y - 2]);
                        }
                    }
                    if(y < mazeData.length - 1) {
                        if(checkNowUpdateData(x,y + 2)) {
                            moveFlags.push([x , y + 2]);
                        }
                    }

                    if(0 < moveFlags.length) {
                        cantMoveCount = 0;
                        if(!createMazeBlock(moveFlags)) {
                            finished = true;
                        }
                    } else {
                        mazeData[x][y] = 0;
                        cantMoveCount += 1
                        let num = nowUpdateData.length - cantMoveCount - 1;

                        //5.4方が現在拡張している壁になった場合、その壁を拡張した壁に登録し、前の壁へ移動する
                        managementMazeBetweenBlock("delete",x,y,nowUpdateData[num][0],nowUpdateData[num][1]);

                        x = nowUpdateData[num][0];
                        y = nowUpdateData[num][1];
                    }

                    function checkNowUpdateData(x,y) {
                        let result = true;
                        for(let i = 0; i < nowUpdateData.length ; i ++) {
                            if(nowUpdateData[i][0] == x && nowUpdateData[i][1] == y) {
                                result = false;
                            }
                        }
                        return result;
                    }

                    function createMazeBlock(moveFlags) {
                        let result = true;
                        let num = Math.floor(Math.random() * (moveFlags.length));

                        if(mazeData[moveFlags[num][0]][moveFlags[num][1]] == 1) {
                            managementMazeBetweenBlock("create",x,y,moveFlags[num][0],moveFlags[num][1]);
                            result = false;
                        } else {
                            managementMazeBetweenBlock("create",x,y,moveFlags[num][0],moveFlags[num][1]);
                            x = moveFlags[num][0];
                            y = moveFlags[num][1];
                        }
                        return result;
                    }

                    function managementMazeBetweenBlock(method,x1,y1,x2,y2) {
                        let x = 0;
                        let y = 0;

                        if(x1 < x2) {
                            x = x2 - 1;
                        } else if(x1 > x2) {
                            x = x1 - 1;
                        } else if(x1 == x2) {
                            x = x1;
                        }

                        if(y1 < y2) {
                            y = y2 - 1;
                        } else if(y1 > y2) {
                            y = y1 - 1;
                        } else if(y1 == y2){
                            y = y1
                        }
                        if(method == "create") {
                            mazeData[x][y] = 1;
                        } else if(method == "delete") {
                            mazeData[x][y] = 0;
                        }
                    }
                }

                //B.全ての点に壁があるか確認
                if(checkCreatedMaze()) {
                    //あればcreated = true に変更する
                    created = true;
                    //ゴール地点を2に設定
                    mazeData[(mazeWidth * 2) - 1][mazeHeight] = 2;
                    //スタート地点を3に設定
                    mazeData[1][mazeHeight] = 3;
                }
            }
        }
    }

    function drawMaze(width,height) {
        let x = 0;
        let y = 0;
        let mazeBlockWidth = 0;
        let mazeBlockHeight = 0;

        const minSize = 15;
        let maxWidthSize = Math.floor((canvas.width - ((width + 1) * minSize)) / width);
        let maxHeightSize = Math.floor((canvas.height - ((height + 1) * minSize)) / height);

        for(let i=  0 ; i < mazeData.length ; i ++) {
            for(let j=  0 ; j < mazeData[0].length ; j ++) {
                if(i % 2 == 0) {
                    x = Math.floor(i / 2) * (maxWidthSize + minSize);
                    mazeBlockWidth = minSize;
                } else {
                    x = (Math.floor(i / 2) * (maxWidthSize + minSize)) + minSize;
                    mazeBlockWidth = maxWidthSize;
                }

                if(j % 2 == 0) {
                    y = Math.floor(j / 2) * (maxHeightSize + minSize);
                    mazeBlockHeight = minSize;
                } else {
                    y = (Math.floor(j / 2) * (maxHeightSize + minSize)) + minSize;
                    mazeBlockHeight = maxHeightSize;
                }

                if(mazeData[i][j] == 1) {
                    context.fillStyle = "#000000";
                } else if(mazeData[i][j] == 2) {
                    context.fillStyle = "#3EB370";
                } else if(mazeData[i][j] == 3){
                    context.fillStyle = "#ff6347";
                } else {
                    context.fillStyle = "#FFFFFF";
                }
                context.fillRect(x,y,mazeBlockWidth,mazeBlockHeight);
            }
        }

        //隙間を埋める
        context.fillStyle = "#000000";
        context.fillRect((x + mazeBlockWidth),0,(canvas.width - (x + mazeBlockWidth)),canvas.height);
        context.fillRect(0,(y + mazeBlockHeight),canvas.width,(canvas.height - (y + mazeBlockHeight)));

        imageData = context.getImageData(0,0,900,600);
    }

    function returnRandomNumber(length) { //min and max included
        let num = 1;

        while(num % 2 != 0) {
            num = Math.floor(Math.random() * (length + 1));
        }
        return num;
    }

    function checkCreatedMaze() {
        let result = true;

        for(let i= 0 ; i < mazeData.length; i += 2) {
            for(let j=  0 ; j < mazeData[0].length ; j += 2) {
                if(mazeData[i][j] == 0) {
                    result = false;
                }
            }
        }
        return result;
    }
}

function arrayShuffle(array) {
    for(var i = (array.length - 1); 0 < i; i--){

      // 0〜(i+1)の範囲で値を取得
      var r = Math.floor(Math.random() * (i + 1));

      // 要素の並び替えを実行
      var tmp = array[i];
      array[i] = array[r];
      array[r] = tmp;
    }
    return array;
  }

const _sleep = (ms) => new Promise((resolve) => setTimeout(resolve, ms));