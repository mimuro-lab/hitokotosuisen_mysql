<?php
require_once(__DIR__."\\utils.php");
require_once(__DIR__."\\countViewPage.php");

function getDefailtViewContents(string $recentDate, int $maxComments)
{
  $pathToCommentPosted = __DIR__."/./../data/posted/";
  $listOfFolder = scandir($pathToCommentPosted);
  $max = 0;
  foreach($listOfFolder as $path){
    if((int)$path > $max){
      $max = (int)$path;
    }
  }
  $listTmp = array_fill(0, $max, "none");
  foreach($listOfFolder as $path){
    if($path != "." && $path != ".."){
      $listTmp[(int)$path] = $path;
    } 
  }
  $listOfFolder = $listTmp;
  $listOfFolder = array_reverse($listOfFolder);

  $viewContentOfList = array();

  //　必要な個数分取る
  foreach($listOfFolder as $path){
    if($path == "none"){
      continue;
    }
    
    if(count($viewContentOfList) >= $maxComments){
      break;
    }
    
    $pathToFolder = $pathToCommentPosted."/".$path;

    $viewContentOfList[] = getContentsFromFolder($pathToFolder);
    
  }

  // 日程が古い物は捨てる
  $tmp = array();
  
  foreach($viewContentOfList as $content){

    if($content === false){
      continue;
    }
    $contentYear = explode("/", $content["date"])[0];
    $contentMonth = explode("/", $content["date"])[1];
    $contentDay = preg_replace('/[^0-9]/', '', explode("/", $content["date"])[2]);
    $contentDate = $contentYear."/".$contentMonth."/".$contentDay;
      
    if(strtotime($recentDate) < strtotime($contentDate)){
      $tmp[] = $content;
    }
      
  }
    
  $viewContentOfList = $tmp;

  return $viewContentOfList;
}

function getDefailtViewContentsAscend(int $maxComments)
{
  $viewContentOfList = getAscendContents();
  $viewContentOfList = array_reverse($viewContentOfList);
  $viewContentOfList = array_slice($viewContentOfList, 0, $maxComments);
  return $viewContentOfList;
}

function viewDefaultComment(string $recentDate, int $maxComments, bool $isTopPage = false)
{
  // 閲覧数の多い順で、$maxComments分表示する。
  $viewContentAscend = getDefailtViewContentsAscend($maxComments);
  echo '<a href="'.file_get_contents(__DIR__."/../data/servername.txt").'/view?viewCount=descend" style="text-decoration: none;"><font size="+2" color="#696969">閲覧数の多い投稿</font></a>';
  printHTMLOfComment($viewContentAscend, !$isTopPage);
  echo '<br><br><br><br>';

  // 2週間分、上限10コメント読み込む。
  $viewContents = getDefailtViewContents($recentDate, $maxComments);
  echo '<a href="'.file_get_contents(__DIR__."/../data/servername.txt").'/view?tag=___time_" style="text-decoration: none;"><font size="+2" color="#696969">最新の投稿</font></a>';
  printHTMLOfComment($viewContents, !$isTopPage);
}

?>