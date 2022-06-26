<?php

function printButton($canFind, $comment, $ID)
{
    echo'
    <table width="100%">
    <tr>
        <td align="center">
        <form action="" method="post"><input type="hidden" name="scene" value="default"><button type="submit">戻る</button></form>
        </td>
        <td align="right">
    ';
    if($canFind){
        $comment["comment"] = str_replace("<br>", "\r\n", $comment["comment"]);
        /*
        echo '
            <form action="" method="post">
            <input type="hidden" name="scene" value="edit_comment">
            <input type="hidden" name="ID" value="'.$ID.'">
            <input type="hidden" name="tag" value="';
        */
        echo '
            <form action="" method="post">
            <input type="hidden" name="scene" value="select">
            <input type="hidden" name="ID" value="'.$ID.'">
            <input type="hidden" name="tag" value="';
        foreach($comment["tag"] as $tag){
            echo $tag.":";
        }
        echo '"><input type="hidden" name="tagFixed" value="';
        foreach($comment["tagFixed"] as $tag){
          echo $tag.":";
      }
        echo '">
            <input type="hidden" name="comment" value="'.$comment["comment"].'">
            <input type="hidden" name="book" value="'.$comment["book"].'">
            <button type="submit">このコメントを編集する</button>
            </form>
        ';
    }
    echo '            
    </td>
    </tr>
    </table>
    ';
}

function printMessage($canFind)
{
    if($canFind){
        echo '<br>
        <table width="100%"><tr><td align="center">
        <font size="+2" color="#696969">コメントIDに対する内容が見つかりました。<br></font>
        <font color="#696969"><br>以下内容<br></font>
        </td></tr></table>
        ';
    }else{
        echo '<br>
        <table width="100%"><tr><td align="center">
        <font size="+2" color="#696969">
        無効なコメントIDが入力されました。<br>
        正しいIDを入力して下さい。<br>
        </font><br>
        </td></tr></table>';  
    }
}

function printPreviewFromID($comment)
{
    echo '
    <table width="100%" bgcolor="#fafafa">
    <tr><td colspan="2"><hr></td></tr>
    <tr>
    <td width="50%" align="center">〇INDEX</td><td width="50%" align="center">'.$comment["index"].'</td>
    </tr>
    <tr><td><br></td></tr>
    <tr>
        <td width="50%" align="center">〇名前の公開状態</td><td width="50%" align="center">';
    // タグを全て表示する
    if($comment['nameStatus'] == "name_public"){
        echo "公開";
    }else if($comment['nameStatus'] == "name_private"){
        echo "非公開";
    }
    
    echo '</td>
    </tr>
    <tr><td><br></td></tr>
    <tr>
        <td width="50%" align="center">〇固定タグ</td><td width="50%" align="center">';
    // タグを全て表示する
    foreach($comment["tagFixed"] as $t){
      echo $t."<br>";
    }
    
    echo '</td>
    </tr>
    
    <tr>
      <td width="50%" align="center">〇自由タグ</td><td width="50%" align="center">';
    // タグを全て表示する
    foreach($comment["tag"] as $t){
      echo $t."<br>";
    }
    echo '</td></tr>
    <tr><td><br></td></tr>
    <tr>
        <td width="50%" align="center">〇推薦する本の名前</td><td width="50%" align="center">'.$comment["book"].'</td>
    </tr>
    <tr><td><br></td></tr>
    <tr>
        <td align="center" colspan="2">〇推薦内容</td>
    </tr>
    <tr>
        <td colspan="2">'.$comment["comment"].'</td>
    </tr>
    <tr><td colspan="2"><hr></td></tr>
    </table>
    ';
}

function getContentsFromFolder_inFix($pathToFolder)
{
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
  $OneViewContents["nameStatus"] = $contentOfTxt[3];
  $OneViewContents["index"] = $contentOfIndex;
  $OneViewContents["tag"] = $contentOfTag;
  $OneViewContents["tagFixed"] = $contentOfTagFix;
  $OneViewContents["counter"] = $contentOfCounter;

  return $OneViewContents;
}

// コメント内容に関するidとtokenのチェックを行う。
// idとtokenが入力され、両方マッチするものがある場合は、その内容を返す。（idとtokenがつながったものを入力）
// マッチするものがなかった場合、falseを返す。
function get_comment_matched(String $ID_and_token){
    
    // 3つの要素（index, token）で構成されていなかったらfalseを返す
    if(count(explode(":",$ID_and_token)) != 2){
        return false;
    }

    $folderIND = explode(":",$ID_and_token)[0];
    $token_comment = explode(":",$ID_and_token)[1];

    $pathToInfo = __DIR__."\\..\\data\\posted\\".$folderIND."\\info.txt";
    if(!file_exists($pathToInfo)){
        return false;
    }
    
    $savedToken = file_get_contents($pathToInfo);
    $savedToken = explode(",", $savedToken)[0];

    if($savedToken !== $token_comment){
        return false;
    }
    $pathToFolder = __DIR__."\\..\\data\\posted\\".$folderIND;
    return getContentsFromFolder_inFix($pathToFolder);    
}

function main_inputID($ID)
{
    $comment = get_comment_matched($ID);
    $canFind = false;
    if($comment !== false){
        $canFind = true;
    }

    printMessage($canFind);

    if($canFind){
        printPreviewFromID($comment);
    }

    printButton($canFind, $comment, $ID);

}

?>