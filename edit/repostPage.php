<?php

require_once(".//utils.php");

date_default_timezone_set('Asia/Tokyo');

function fix_comment()
{

    # 保存時のhtml文字エスケープ処理
    $_POST["book"] = htmlspecialchars($_POST["book"]);
    $_POST["tag"] = htmlspecialchars($_POST["tag"]);
    $_POST["tagFixed"] = htmlspecialchars($_POST["tagFixed"]);

    // 保存時のカンマのエスケープ処理
    $_POST["book"] = str_replace(",", "?cma?", $_POST["book"]);
    $comment = str_replace(",", "?cma?", $_POST["comment"]);
    $comment = str_replace("\r\n", "?newl?", $comment);
    $comment = htmlspecialchars($comment);
    $comment = str_replace("?newl?", "<br>", $comment);

    $folderIND = explode(":", explode(",", $_POST["ID"])[0])[0];
    $pathToFolder = __DIR__."\\..\\data\\posted\\".$folderIND;
    
    #　公開状態の設定
    $status = file_get_contents("./../data/initStatus.txt");
    $status = explode(",",$status)[1];
    file_put_contents($pathToFolder."\\status.txt", $status);
    
    // タグの修正。
    $tag_filePath = $pathToFolder."\\search_kwd.txt";
    $tag_content = $_POST["tag"];
    file_put_contents($tag_filePath, $tag_content);
    // 固定タグの修正。
    $tag_filePath = $pathToFolder."\\search_kwd_fixed.txt";
    $tag_content = str_replace(":", ",", $_POST["tagFixed"]);
    file_put_contents($tag_filePath, $tag_content);
    
    // コメントの表示に使われるファイル
    $view_filePath = $pathToFolder."\\view.txt";
    $dateOfMake = explode(",", file_get_contents($view_filePath))[1];
    #$comment = str_replace("?newl?", "<br>", $comment);
    $isPublic = "name_".$_POST["nameStatus"];
    $view_content = $_POST["book"].",".$dateOfMake.",".$comment.",".$isPublic;
    file_put_contents($view_filePath, $view_content);
    
    return true;
}

function main_postPage()
{
    
    $success = fix_comment($_POST);
    if($success){
        echo '
        <br>
        <table width="100%"><tr><td align="center">
        <a style="text-decoration: none;" href="./../view?index='.explode(":", $_POST["ID"])[0].'">
        <font size="+2" color="#696969">編集した投稿を見る</font></a></td></tr></table>
        <br><br>
        <table border="1" width="100%">
        <tr><td align="center">
        この記事の編集は完了しました。<br>
        <a href="./../">トップページへ戻る</a>
        </td></tr>
        </table>
        ';
    }else{
        echo "書き込みに失敗しました。";
    }
}

?>