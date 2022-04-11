<?php
ini_set('display_errors', "On");

session_start();

require('./vendor/autoload.php');
require_once("./db/ManagementLogic.php");

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Worksheet;

$err = "";

$password = $_POST['password'];
$password = $_POST['password'];

$tempfile = $_FILES['file']['tmp_name'];
$filename = './quiz.xlsx';

if ($password != "password" || !isset($password)) {
    $err = '正しいパスワードを入力してください';
}

if ($err != "") {
    $_SESSION['err'] = $err;
    header( "location: /quizSetting_form.php");
    exit;
}

if(is_uploaded_file($tempfile)) {
    if (!move_uploaded_file($tempfile , $filename )) {
        $err = 'ファイルをアップロードできませんでした';
        $_SESSION['err'] = $err;
        header( "location: /quizSetting_form.php");
        exit;
    }
} else {
    $err = 'ファイルが選択されていません';
    $_SESSION['err'] = $err;
    header( "location: /quizSetting_form.php");
    exit;
}

$objSpreadsheet = IOFactory::load('./quiz.xlsx');
$objSheet = $objSpreadsheet->getSheet(0);
$strRange = $objSheet->calculateWorksheetDimension();
$arrData = $objSheet->rangeToArray($strRange);

ManagementLogic::deleteQuiz();
for($i = 1; $i < count($arrData) - 1; $i ++) {
    if(isset($arrData[$i][0])) {
        ManagementLogic::createQuiz($arrData[$i]);
    }
}

print("更新が完了しました");
print("<br>")
?>

<a href="./main.php">【戻る】</a>