<?php

require_once(".//utils.php");

function printEditFormBack()
{
    # html文字のエスケープ処理
    $_POST["book"] = htmlspecialchars($_POST["book"]);
    $_POST["tag"] = htmlspecialchars($_POST["tag"]);
    $_POST["tagFixed"] = htmlspecialchars($_POST["tagFixed"]);
    #$comment = str_replace("<br>", "\r\n", $_POST["comment"]);
    $comment = $_POST["comment"];
    echo '
    <table width="100%">
    <tr><td align="center"><font size="+2" color="#696969">編集内容を入力してください</font><br>
    ※名前と学籍番号は変更できません。</td></tr></table>
    <form action="." method="post">
    <table width="100%" bgcolor="#fafafa">
    <tr>
    <td width="50%" align="center">〇名前の公開</td><td width="50%" align="center">
    <select name="nameStatus">
    <option value="private">非公開</option>
    <option value="public">公開</option>
    </select>
    </td>
    </tr>
    <tr><td><br></td></tr>
    <td width="50%" align="center">〇固定タグ</td>
    <td width="50%" align="center">
    ';
    echo '
    <input type="checkbox" name="fix_date" value="checked_fix_date" checked>投稿日時</input> &nbsp;<input type="checkbox" name="fix_book" value="checked_fix_book" checked>本の名前</input><br>';
    //固定タグを表示する
    $tagFixed = explode(":", $_POST["tagFixed"]);
    $tagFixedInd = getFixedInd($tagFixed);
    $fixedTags = explode(",", file_get_contents(__DIR__."\\..\\data\\tagTable.txt"));
    for($i = 0; $i < count($fixedTags); $i++){
        if($fixedTags[$i] == "?newl?"){
            echo '<br>';
            continue;
        }
        $checked = "";
        foreach($tagFixedInd as $pre){
            if($pre == $i){
                $checked = "checked";
            }
        }
        echo '<input type="checkbox" name="fix_'.$i.'" value="checked_fix_'.$i.'" '.$checked.'>'.$fixedTags[$i]."&nbsp;";
    }
    
    echo '
    </td>
    </tr>

    <tr><td><br></td></tr>
    <tr>
    <td width="50%" align="center">〇自由fタグ</td><td width="50%" align="center"><input type="text" size="45" name="tag" value="'.$_POST["tag"].'"></input>
    </td>
    <tr><td><br></td></tr>
    </tr>
    <tr>
    <td width="50%" align="center">〇推薦する本の名前</td>
    <td  width="50%" align="center"><input type="text" name="book" value="'.$_POST["book"].'"></input></td>
    </tr>
    <tr><td><br></td></tr>
    <tr><td><br></td></tr>
    <tr><td colspan="2" align="center">〇推薦内容</td></tr>
    <tr><td colspan="2" align="center">
    <textarea name="comment"  rows="20" cols="80">'.$comment.'</textarea>
    </td></tr>
    <tr><td colspan="2" align="center"><br><input type="submit" value="プレビュー画面へ行く"></td></tr>
    </table>
    <input type="hidden" name="ID" value="'.$_POST["ID"].'">
    <input type="hidden" name="scene" value="preview_comment">
    </form>
    ';
}

function printEditForm()
{
    $tag = array();
    $tagFixed = explode(":", $_POST["tagFixed"]);
    $tagFixedInd = getFixedInd($tagFixed);

    # htmlのエスケープ処理
    $_POST["book"] = htmlspecialchars($_POST["book"]);
    
    for($i = 0; $i < count(explode(":",$_POST["tag"]));$i++){
        array_push($tag, explode(":",$_POST["tag"])[$i]);
    }
    
    echo '
    <form action="." method="post">
    <table width="100%" bgcolor="#fafafa">
    <tr>
    <td width="50%" align="center">〇名前の公開</td><td width="50%" align="center">
    <select name="nameStatus">
    <option value="private">非公開</option>
    <option value="public">公開</option>
    </select>
    </td>
    </tr>
    <tr><td><br></td></tr>
    <td width="50%" align="center">〇固定タグ</td>
    <td width="50%" align="center">
    ';
    echo '
    <input type="checkbox" name="fix_date" value="checked_fix_date" checked>投稿日時</input> &nbsp;<input type="checkbox" name="fix_book" value="checked_fix_book" checked>本の名前</input><br>';
    //固定タグを表示する
    $fixedTags = explode(",", file_get_contents(__DIR__."\\..\\data\\tagTable.txt"));
    for($i = 0; $i < count($fixedTags); $i++){
        if($fixedTags[$i] == "?newl?"){
            echo '<br>';
            continue;
        }
        $checked = "";
        foreach($tagFixedInd as $pre){
            if($pre == $i){
                $checked = "checked";
            }
        }
        echo '<input type="checkbox" name="fix_'.$i.'" value="checked_fix_'.$i.'" '.$checked.'>'.htmlspecialchars($fixedTags[$i])."&nbsp;";
    }
    
    echo '
    </td>
    </tr>
    <tr><td><br></td></tr>
    <tr>
    <td width="50%" align="center">〇自由タグ</td><td width="50%" align="center"><input type="text" size="45" name="tag" value="';
    foreach($tag as $t){
        echo htmlspecialchars($t);
        if($t !== ""){
            echo ",";
        }
    }
    echo '"></input>
    </td>
    </tr>
    <tr><td><br></td></tr>
    <tr>
    <td width="50%" align="center">〇推薦する本の名前</td>
    <td width="50%" align="center"><input type="text" name="book" value="'.$_POST["book"].'"></input></td>
    </td>
    <tr><td><br></td></tr>
    <tr><td><br></td></tr>
    </tr>
    
    <tr><td colspan="2" align="center">〇推薦内容</td></tr>
    <tr><td colspan="2" align="center">
    <textarea name="comment" rows="20" cols="80">'.$_POST["comment"].'</textarea>
    </td></tr>
    <tr><td colspan="2" align="center"><br><input type="submit" value="プレビュー画面へ行く"></td></tr>
    </table>
    <input type="hidden" name="ID" value="'.$_POST["ID"].'">
    <input type="hidden" name="scene" value="preview_comment">
    </form>
    ';
}

function main_editPage()
{
    //savePostToCookie($_POST);

    if(!isset($_POST["back"])){
        printEditForm();
    }else{
        printEditFormBack();
    }

}

?>