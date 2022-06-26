<?php

function isSetAll()
{
    if(isset($_POST["book"]) &&  $_POST["book"] !== "" &&  
       isset($_POST["comment"]) && $_POST["comment"] !== "" ){
           return true;
    }
    return false;
}

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

function printButton_EditedPreview($next)
{
    //入力画面へ戻るボタンと、確定ボタン
    $fixedTag = "";
    foreach(getFixedTags() as $f){
        $fixedTag .= ":".$f;
    }
    echo '
    <table width="100%">
    <tr>
    <td align="left">
    <form action="" method="post">
        <input type="hidden" name="scene" value="edit_comment">
        <input type="hidden" name="back" value="backed">
        <input type="hidden" name="ID" value="'.$_POST["ID"].'">
        <input type="hidden" name="book" value="'.$_POST["book"].'">
        <input type="hidden" name="tag" value="'.htmlspecialchars($_POST["tag"]).'">
        <input type="hidden" name="tagFixed" value="'.$fixedTag.'">
        <input type="hidden" name="comment" value="'.$_POST["comment"].'">
        <input type="hidden" name="nameStatus" value="'.$_POST["nameStatus"].'">
        <button type="submit">　戻る　</button>
    </form>
    </td>

    <td align="right">
    ';
    if($next){
    echo '
    <form action="" method="post">
        <input type="hidden" name="scene" value="post_comment">　
        <input type="hidden" name="ID" value="'.$_POST["ID"].'">
        <input type="hidden" name="book" value="'.$_POST["book"].'">
        <input type="hidden" name="tag" value="'.htmlspecialchars($_POST["tag"]).'">
        <input type="hidden" name="tagFixed" value="'.$fixedTag.'">
        <input type="hidden" name="comment" value="'.$_POST["comment"].'">
        <input type="hidden" name="nameStatus" value="'.$_POST["nameStatus"].'">
        <button type="submit">確定する</button>
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
    /*
    $_POST["comment"] = str_replace("\r\n", "?newl?", $_POST["comment"]);
    $_POST["comment"] = htmlspecialchars($_POST["comment"]);
    $_POST["comment"] = str_replace("?newl?", "<br>", $_POST["comment"]);
    */
    $_POST["book"] = htmlspecialchars($_POST["book"]);

    $book = false;
    $comment = false;
    $tag = "";
    $nameStatus = "";
    
    if(isset($_POST["book"]) && $_POST["book"] !==""){
        $book = $_POST["book"];
    }    
    if(isset($_POST["comment"]) && $_POST["comment"] !==""){
        $comment = str_replace("\r\n", "?newl?", $_POST["comment"]);
        $comment = htmlspecialchars($comment);
        $comment = str_replace("?newl?", "<br>", $comment);
    }
    if(isset($_POST["tag"]) && $_POST["tag"] !==""){
        $tag = explode(",",$_POST["tag"]);
    }
    if(isset($_POST["nameStatus"])){
        if($_POST["nameStatus"] === "public"){
            $nameStatus = "公開";
        }else if($_POST["nameStatus"] === "private"){
            $nameStatus = "非公開";
        }
    }
    
    echo '
    <br><br>
    <table width="100%" bgcolor="#fafafa">
    <td align="center" width="50%">〇名前の公開状態</td><td align="center" width="50%">'.$nameStatus.'</td>
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
    <td width="50%" align="center">〇自由タグ</td><td width="50%" align="center">';
    if($tag !== ""){
        foreach($tag as $t){
            echo htmlspecialchars($t);
            if($t !== ""){
                echo "<br>";
            }
        }
    }
    echo '
    </td>
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
    if($book !== false && $comment !== false){
        return true;
    }else{
        return false;
    }

}

function main_previewPage(){

    echo '<table width="100%"><tr><td align="center">';
    if(!isSetAll()){
        echo '<font size="+2" color="#696969">以下の<font color="red">未入力</font>を入力してください。<br>戻るボタンから入力しなおせます。</font>';
    }else{
        echo '<font size="+2" color="#696969">
        入力した内容が正しければ、確定ボタンを押してください。<br>
        再度入力したければ、戻るボタンを押してください。<br></font>
        ';
    }
    echo '</td></tr></table>';

    $next = printPreview();
    printButton_EditedPreview($next);

}

?>