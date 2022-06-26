<?php

require_once(__DIR__."\\defaultPage.php");
require_once(__DIR__."\\tagPage.php");
require_once(__DIR__."\\viewOne.php");
require_once(__DIR__."\\countViewPage.php");
require_once(__DIR__."\\rightPage.php");

// 閲覧ページが有効かどうか？
$status = explode(",", file_get_contents("./../data/siteStatus.txt"))[0];

if($status !== "public"){
  echo '申し訳ありません。ただいまメンテナンス中です。<br>
  <a href="'.file_get_contents("./../data/servername.txt").'">トップページへ戻る</a>
  ';
	exit();
}

date_default_timezone_set('Asia/Tokyo');

function printTitleLine(string $inputTag, string $viewCount)
{

  # htmlのエスケープ処理
  $inputTag = htmlspecialchars($inputTag);

  if($viewCount == "descend"){
    return "<h4>閲覧数が多い順</h4>";
  }

  if($viewCount == "ascend"){
    return "<h4>閲覧数が少ない順</h4>";
  }

  if($inputTag == "___time_"){
    return "<h4>投稿時刻が早い順</h4>";
  }
  
  if($inputTag != ""){
    #$inputTag = str_replace("___and___", "&amp;", $inputTag);
    return 'キーワード<b>'.$inputTag."</b>を含む投稿";
  }

}

function printPageButton(string $viewTag, int $nowPage, int $maxPage)
{
  
  # htmlのエスケープ処理
  $viewTag = htmlspecialchars($viewTag);
  
  if($nowPage < 1){
    $nowPage = 1;
  }
  echo "<br><br>".$nowPage."/".$maxPage."<br><br>";
  $nextPage = $nowPage + 1;
  if($maxPage < $nextPage){
    $nextPage = $maxPage;
  }
  $backPage = $nowPage - 1;
  if($backPage <= 0){
    $backPage = 1;
  }
  echo '
  <form action="" method="get">
    <input type="hidden" name="tag" value="'.$viewTag.'">';
    if($nowPage != 1){
      echo '<button type="submit" name="page" value="'.$backPage.'">前へ</button>';
    echo '<button type="submit" name="page" value="1">検索トップ</button>';
    }
    if($nowPage != $maxPage){
      echo '<button type="submit" name="page" value="'.$nextPage.'">次へ</button>';
    }
  echo '
  </form>
  ';
}
function printPageButtonViewCount(string $UpOrDown, int $nowPage, int $maxPage)
{
  
  if($nowPage < 1){
    $nowPage = 1;
  }

  $nextPage = $nowPage + 1;
  if($maxPage < $nextPage){
    $nextPage = $maxPage;
  }
  $backPage = $nowPage - 1;
  if($backPage <= 0){
    $backPage = 1;
  }
  echo '
  <form action="" method="get">
    <input type="hidden" name="viewCount" value="'.$UpOrDown.'">';
    if($nowPage != 1){
      echo '<button type="submit" name="page" value="'.$backPage.'">前へ</button>';
    echo '<button type="submit" name="page" value="1">検索トップ</button>';
    }
    if($nowPage != $maxPage){
      echo '<button type="submit" name="page" value="'.$nextPage.'">次へ</button>';
    }
  echo '
  </form>
  ';
}
?>

<!DOCTYPE html>
<html>
  <head>
    <title>閲覧ページ</title>
    <meta charset="utf-8">
  </head>
  <body>
  <?php
  
  // メイン処理
  // 閲覧ページの状態を表す変数
  $viewTag = "";
  $maxPage = 0;
  $index = 0;
  $viewCount = "";
  $scene = "default";

  if(isset($_GET["tag"]) && $_GET["tag"] != ""){
    $scene = "tag";
    $viewTag = $_GET["tag"];
  }
  
  if(isset($_GET["page"])){
    $nowPage = intval($_GET["page"]);
  }else{
    $nowPage = 0;
  }

  if(isset($_GET["index"]) && $_GET["index"] != ""){
    $scene = "index";
    $index = intval($_GET["index"]);
  }
  
  if(isset($_GET["viewCount"])){
    $scene = "viewCount";
    $viewCount = $_GET["viewCount"];
  }

  echo '
  <table border="0" width="100%">
  <tr>
    <td colspan="5" align="center">
    <img src="./../title_1.gif"><br>
    <a href="." style="text-decoration: none;"><font size="+2" color="#000000">閲覧ページ</font></a>
    </td>
  </tr>
  
  <tr>
    <td width="5%"></td>
    <td align="left" valign="top"  width="20%">'.file_get_contents(__DIR__."\\leftPage.php").'</td>
    <td align="left" valign="top" width="50%">
    '.printTitleLine($viewTag, $viewCount).'
  ';
  
  switch($scene){
    case "default":
      $recent = date('Y/m/d', strtotime('-2 week', time()));
      viewDefaultComment($recent, 5);
      break;
    case "tag":
      $maxPage = viewTagComment($viewTag, $nowPage);
      break;
    case "index":
      main_viewOne($index);
      break;
    case "viewCount":
      $maxPage = main_countView($viewCount, $nowPage);
      break;
  }

  echo '
  </td>
  <td width="5%"></td>
  <td align="center" valign="top" width="20%">'.printRightPage().'</td>
  </tr>
  <tr>
    <td align="center" colspan="5">
  ';
  if($scene != "default" && $scene != "index" && $scene != "viewCount"){ 
    printPageButton($viewTag, $nowPage, $maxPage);
    echo'
    <table width="100%">
    <tr><td align="center" colspan="2">
      <br><br><br><br>
    </td></tr>
    </table>';
  }else if($scene == "viewCount"){
    printPageButtonViewCount($viewCount, $nowPage, $maxPage);
    echo'
    <table width="100%">
    <tr><td align="center" colspan="2">
      <br><br><br><br>
    </td></tr>
    </table>';
  }
  echo '
    </td>
  </tr>
  ';

  ?>
  
  </body>
</html>