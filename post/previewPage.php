<?php
date_default_timezone_set('Asia/Tokyo');

function getFixedTagFromTable($key)
{
    if($key == "date"){
        $w = date("w");
        $week_name = array("日", "月", "火", "水", "木", "金", "土");
        $dateOfMake = date("Y/m/d") . "($week_name[$w]) ".date("H:i");
        $dateOfTag =  date("Y/m/d");
        return $dateOfTag;
    }
    if($key == "book"){
        return $_POST["book"];
    }
    $fixedTagsFromTable = explode(",", file_get_contents(__DIR__."\\..\\data\\tagTable.txt"));
    return $fixedTagsFromTable[(int)$key];
}

function getFixedTags()
{
    // 固定タグのプレビュー
    $fixed_tags = array();
    foreach($_POST as $p){
        if(substr($p, 0, 11) == "checked_fix"){
            $tagKey = str_replace("checked_fix_", "", $p);
            $fixedTag = getFixedTagFromTable($tagKey);
            array_push($fixed_tags, $fixedTag);
        }
    }
    return $fixed_tags;
}

function getFixedTagsIndex()
{
    // 固定タグのプレビュー
    $fixed_tags = array();
    foreach($_POST as $p){
        if(substr($p, 0, 11) == "checked_fix"){
            $tagKey = str_replace("checked_fix_", "", $p);
            array_push($fixed_tags, $tagKey);
        }
    }
    return $fixed_tags;
}

function isSetAll()
{
    if(isset($_POST["number"]) && $_POST["number"] !== "" &&  
        isset($_POST["level"]) && $_POST["level"] !== "" &&  
       isset($_POST["name"]) && $_POST["name"] !== "" &&  
       isset($_POST["book"]) &&  $_POST["book"] !== "" &&  
       isset($_POST["comment"]) && $_POST["comment"] !== "" ){
           return true;
    }
    return false;
}

function printButton($next)
{
    //入力画面へ戻るボタンと、確定ボタン
    echo '
    <head>
        <title>トップページ</title>
        <meta charset="utf-8">
    </head>
    <table width="100%">
    <tr>
    
    <td align="left">
    <form action="" method="POST">
        <input type="hidden" name="scene" value="input_comment">
        <input type="hidden" name="token" value="'.$_POST["token"].'">
        <button type="submit">　戻る　</button>
    </form>
    </td>

    <td align="right">
    ';
    if($next){
        $send_comment = htmlspecialchars($_POST["comment"]);
        echo '
        <form action="" method="POST">
            <input type="hidden" name="scene" value="post_comment">
            <input type="hidden" name="email" value="'.$_POST["email"].'">
            <input type="hidden" name="number" value="'.$_POST["number"].'">
            <input type="hidden" name="name" value="'.$_POST["name"].'">
            <input type="hidden" name="book" value="'.$_POST["book"].'">
            <input type="hidden" name="tag" value="'.$_POST["tag"].'">
            <input type="hidden" name="level" value="'.$_POST["level"].'">
            <input type="hidden" name="comment" value="'.$send_comment.'">
            <input type="hidden" name="token" value="'.$_POST["token"].'">
            <input type="hidden" name="nameStatus" value="'.$_POST["nameStatus"].'">
        ';
        // 固定タグ
        $fixed = "";
        foreach(getFixedTags() as $f){
            $fixed .=$f.",";
        }
        echo '
            <input type="hidden" name="fixedTag" value="'.$fixed.'">
            <button type="submit">投稿する</button>
        </form>
        ';
    }

    echo '
    </td>

    </tr>
    </table>
    
    ';

}

function printPreview()
{

    $number = false;
    $level = false;
    $name = false;
    $book = false;
    $tag = false;
    $comment = false;
    $nameStatus = false;
    if(isset($_POST["number"]) && $_POST["number"] !==""){
        $number = $_POST["number"];
    }
    if(isset($_POST["level"]) && $_POST["level"] !== ""){
        $level = $_POST["level"];
    }
    if(isset($_POST["name"]) && $_POST["name"] !==""){
        $name = $_POST["name"];
    }    
    if(isset($_POST["book"]) && $_POST["book"] !==""){
        $book = $_POST["book"];
    }    
    if(isset($_POST["tag"]) && $_POST["tag"] !==""){
        $tag = explode(",", $_POST["tag"]);
    }    
    if(isset($_POST["comment"]) && $_POST["comment"] !==""){
        $comment = $_POST["comment"];
    }
    if(isset($_POST["nameStatus"]) && $_POST["nameStatus"] != ""){
        $nameStatus = $_POST["nameStatus"];
    }

    echo '
    <table border="0" width="100%" bgcolor="#fafafa">
    <tr>
        <td colspan="2"><hr></td>
    </tr>
    <tr>
        <td align="center" width="50%">〇学籍番号</td><td align="center" width="50%">';
    
    if($number !== false){
        echo $number;
    }else{
        echo '<font color="red">未入力</font>';
    }
    
    echo '</td>
    </tr>
    <tr><td><br></td></tr>
    <tr><td align="center" width="50%">〇学　年　</td><td align="center" width="50%">';
    if($level !== false){
        echo $level."学年";
    }else{
        echo '<font color="red">未入力</font>';
    }
    echo '</td></tr>
    
    <tr><td><br></td></tr>
    <tr>
        <td align="center" width="50%">〇名　前　</td><td align="center" width="50%">';

    if($name !== false){
        echo $name;
    }else{
        echo '<font color="red">未入力</font>';
    }

    echo '</td>
    </tr>
    <tr><td><br></td></tr>
    <td width="50%" align="center">〇名前の公開</td><td width="50%" align="center">
    ';
    if($nameStatus !== false){
        if($nameStatus === "public"){
            echo "公開";
        }else if($nameStatus === "private"){
            echo "非公開";
        }
    }
    echo '
    </td>
    </tr>
    <tr><td><br></td></tr>
    <tr>
        <td align="center" width="50%">〇固定タグ</td><td align="center" width="50%">';
    $fixed_tags = getFixedTags();
    foreach($fixed_tags as $f){
        echo $f."<br>";
    }
    echo '
    </td></tr>
    <tr><td><br></td></tr>
    <tr>
    <td align="center" width="50%">〇自由タグ</td><td align="center" width="50%">';

    if($tag !== false){
        foreach($tag as $t){
            echo $t;
            if($t !== ""){
                echo "<br>";
            }
        }
    }else{
        echo 'なし';
    }

    echo '</td>
    </tr>
    <tr><td><br></td></tr>
    <tr>
        <td align="center" width="50%">〇推薦する本の名前</td><td align="center" width="50%">';

    if($book !== false){
        echo $book;
    }else{
        echo '<font color="red">未入力</font>';
    }

    echo '</td>
    </tr>
    <tr><td><br></td></tr>

    <tr>
        <td colspan="2"><hr></td>
    </tr>
    <tr>
        <td align="center" colspan="2">〇推薦内容</td>
    </tr>
    <tr>
        <td colspan="2">';
    
    if($comment !== false){
        echo $comment;
    }else{
        echo '<font color="red">未入力</font>';
    }
    
    echo '</td>
    </tr>
    <tr>
        <td align="center" colspan="2"><hr></td>
    </tr>
    </table>

    ';

    // 確定ボタンを押しても良いかどうか？
    if($number !== false && $name !== false && $book !== false && $comment !== false){
        return true;
    }else{
        return false;
    }

}

function main_previewPage(){

    // html文字は変換する
    $_POST["comment"] = str_replace("\r\n", "?newl?", $_POST["comment"]);
    $_POST["comment"] = htmlspecialchars($_POST["comment"]);
    $_POST["comment"] = str_replace("?newl?", "<br>", $_POST["comment"]);
    $_POST["number"] = htmlspecialchars($_POST["number"]);
    $_POST["name"] = htmlspecialchars($_POST["name"]);
    $_POST["book"] = htmlspecialchars($_POST["book"]);
    $_POST["tag"] = htmlspecialchars($_POST["tag"]);
    
    setcookie("email", $_POST["email"], time() + 60 * 15);
    setcookie("number", $_POST["number"], time() + 60 * 15);
    setcookie("level", $_POST["level"], time() + 60 * 15);
    setcookie("name", $_POST["name"], time() + 60 * 15);
    setcookie("book", $_POST["book"], time() + 60 * 15);
    setcookie("tag", $_POST["tag"], time() + 60 * 15);
    setcookie("comment", $_POST["comment"], time() + 60 * 15);
    $fixed = "";
    foreach(getFixedTagsIndex() as $i){
        $fixed .= $i.",";
    }
    setcookie("fixed_tag", $fixed, time() + 60 * 15);

    if(!isSetAll()){
        echo '<table width="100%"><tr><td align="center"><font size="+2" color="#000000">
        以下の<font color="red">未入力</font>を入力してください。<br>戻るボタンから入力しなおせます。
        </font></td></tr></table>';
    }else{
        echo '<table width="100%"><tr><td align="center"><font size="+2" color="#000000">
        入力した内容が正しければ、確定ボタンを押してください。<br>
        再度入力したければ、戻るボタンを押してください。<br>
        </font></td></tr></table>';
    }

    $next = printPreview();
    printButton($next);

}

?>