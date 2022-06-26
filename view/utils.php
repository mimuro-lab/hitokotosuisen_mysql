<?php

require_once(__DIR__."\\..\\data\\getName.php");

function getPreviewComment(string $oriComment, int $restLines)
{

  $listOfComment = explode("<br>", $oriComment);
  $trimedComment = array();
  if(count($listOfComment) < $restLines){
    $restLines = count($listOfComment);
  }
  $i = 0;
  foreach($listOfComment as $line){
    if($i >= $restLines){
      break;
    }
    array_push($trimedComment, $line);
    $i ++;
  }
  
  // コメントをstrinに戻す
  $retComment = "";

  for($i = 0; $i < $restLines; $i++){
    if($i == $restLines - 1){ 
      $retComment .= $trimedComment[$i];
    }else{
      $retComment .= $trimedComment[$i]."<br>";
    }
  }
  
  return $retComment;
}

function getEndComment(string $oriComment, int $restLines)
{
  $listOfComment = explode("<br>", $oriComment);
  if(count($listOfComment) < $restLines){
    return "";
  }else{
    return "以下省略";
  }
}

// date, book, index, tag, commentが必要
function printHTMLOfComment($listOfContents, bool $validURL = True)
{
  foreach($listOfContents as $comment){
    $comment["comment"] = str_replace("?cma?",",",$comment["comment"]);
    $comment["book"] = str_replace("?cma?",",",$comment["book"]);
    
    $rinkDate = substr($comment["date"], 0, 10);
    $date = str_replace($rinkDate, "", $comment["date"]);
    echo '
    <table border="0" width="100%" bgcolor="#fafafa">
    <tr>
      <td colspan="2"><hr></td>
    </tr>
    <tr>
      <td style="word-break: break-all;"><font size="+1" face="arial black">'.$comment["book"].'</font></td>
      <td style="word-break: break-all;"  align="right">
      <font size="-1">'.$comment["date"].'&nbsp;INDEX:'.$comment["index"].'<br></font>
      <font size="-1" style="opacity:0.7" face="arial unicode ms">'.$comment["counter"].'&nbsp;回閲覧<br></font>
      <font size="-1">'.$comment["name"].'：投稿者<br></font>
      </td>
    </tr>

    <tr>
      <td style="word-break: break-all;"  colspan="2"><font size="-1" face="arial unicode ms">'.getPreviewComment($comment["comment"], 3).'</font></td>
    </tr>
    <tr>
      <td style="opacity:0.5" colspan="2" style="word-break: break-all;"  colspan="2"><font size="-1" face="arial unicode ms">'.getEndComment($comment["comment"], 3).'</font></td>
    </tr>

    ';
    
    if($validURL){
      echo'
      <tr>
        <td style="opacity:0.8" colspan="2" align="center"><a href="./?index='.$comment["index"].'"><font color="#696969">この投稿を全部見る</td>
      </tr>
      ';
    }
    echo '<tr><td colspan="2" align="right">';
    if($validURL){
      echo "タグ<br>";
    }

    foreach($comment["tag"] as $tag){
      if($validURL){
        $tag_ = str_replace("&amp;", "%26", $tag);
        echo '<a href="./?tag='.$tag_.'"><font size="-1" color="#6495ed">'.$tag.'</a>&nbsp;';
      }
    }
    echo '<br>';

    foreach($comment["tagFixed"] as $tag){
      $tag_ = str_replace("&amp;", "%26", $tag);
      if($validURL){
        echo '<a href="./?tag='.$tag_.'"><font size="-1" color="#6495ed">'.$tag.'</a>&nbsp;';
      }
    }

    echo '
    </td></tr>
    <tr>
      <td align="right" colspan="2"></td>
    </tr>
    <tr>
      <td colspan="2"><hr></td>
    </tr>
    
    </table> ';
  }  

}

function getContentsFromFolder($pathToFolder)
{
  //公開状態でないのなら取得しない。
#  $isPublic = explode(",", file_get_contents($pathToFolder."/info.txt"))[6];
  $isPublic = file_get_contents($pathToFolder."/status.txt");
  if($isPublic != "public"){
    return false;
  }
  
  if(!file_exists($pathToFolder."/index.txt")){
    return false;
  }
  $contentOfIndex = file_get_contents($pathToFolder."/index.txt");

  if(!file_exists($pathToFolder."/view.txt")){
    return false;
  }
  $contentOfTxt = file_get_contents($pathToFolder."/view.txt");
  $contentOfTxt = explode(",", $contentOfTxt);

  if(!file_exists($pathToFolder."/search_kwd_fixed.txt")){
    return false;
  }
  $contentOfTagFix = file_get_contents($pathToFolder."/search_kwd_fixed.txt");
  $contentOfTagFix = explode(",", $contentOfTagFix);

  if(!file_exists($pathToFolder."/search_kwd.txt")){
    return false;
  }
  $contentOfTag = file_get_contents($pathToFolder."/search_kwd.txt");
  $contentOfTag = explode(",", $contentOfTag);

  if(!file_exists($pathToFolder."/count.txt")){
    return false;
  }
  $contentOfCounter = intVal(file_get_contents($pathToFolder."/count.txt"));

  $OneViewContents = array();
  $OneViewContents["book"] = $contentOfTxt[0];
  $OneViewContents["date"] = $contentOfTxt[1];
  $OneViewContents["comment"] = $contentOfTxt[2];
  $OneViewContents["index"] = $contentOfIndex;
  $OneViewContents["tag"] = $contentOfTag;
  $OneViewContents["tagFixed"] = $contentOfTagFix;
  $OneViewContents["counter"] = $contentOfCounter;
  $OneViewContents["name"] = getName(False, $pathToFolder);

  return $OneViewContents;
}

?>