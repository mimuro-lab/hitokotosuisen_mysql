<?php

require_once(".//quitPage.php");

// emailを取得する。tokenにマッチするemailを返す関数。
// 失敗したら必ずfalseを返す。
function get_email(String $token){
    $pathToToken = __DIR__."/../data/token.csv";

    // token.csvがなかったらリターンする。
    if(!file_exists($pathToToken)){
        //echo "token.csvが見つかりません。";
        return false;
    }
    // ファイルを開けなかったらリターンする。
    if(!fopen($pathToToken, "a")){
        //echo "token.csvを開けませんでした。";
        return false;
    }
    $fp = fopen($pathToToken, "r");
    
    // 一行ずつ読み込む
    $tokenLine = "";
    while(!feof($fp)){

        // fgetにより一行読み込み
        $tokenLine = fgets($fp);
        #if(strpos($tokenLine, $token) !== false){
        // 最後の行になったらbreak
        if($tokenLine == ""){
            break;
        }
        if(str_getcsv($tokenLine)[1] == $token){
            return str_getcsv($tokenLine)[0];
        }
    }

    //echo "tokenに対するメールアカウントを見つけられませんでした。";
    return false;
}

function showForm(string $token, string $email){

    // cookieに値が保存されている場合は、その値を使う。
    $pre_name = "";
    $pre_number = "";
    $pre_level = "";
    $pre_book = "";
    $pre_tag = "";
    $pre_comment = "";
    $preFixed = array();
    if(isset($_COOKIE["name"])){
        $pre_name = $_COOKIE["name"];
    }
    if(isset($_COOKIE["number"])){
        $pre_number = $_COOKIE["number"];
    }
    if(isset($_COOKIE["level"])){
        $pre_level = $_COOKIE["level"];
    }
    if(isset($_COOKIE["book"])){
        $pre_book = $_COOKIE["book"];
    }
    if(isset($_COOKIE["tag"])){
        $pre_tag = $_COOKIE["tag"];
    }
    if(isset($_COOKIE["comment"])){
        $pre_comment = $_COOKIE["comment"];
        $pre_comment = str_replace("<br>", "\r\n",$pre_comment);
        #echo $pre_comment;
    }
    if(isset($_COOKIE["fixed_tag"])){
        $preFixed = explode(",", $_COOKIE["fixed_tag"]);
        $tmp = array();
        foreach($preFixed as $f){
            if($f !== "date" && $f !== "book" && $f !== ""){
                array_push($tmp, (int)$f);
            }
        }
        $preFixed = $tmp;
    }
    #print_r($preFixed);
    echo '
    <form action="." method="post">
    <table border="0" width="100%" bgcolor="#fafafa">

    <tr>
    <td width="50%" align="center">〇学籍番号</td><td width="50%" align="center"><input type="text" id="number" name="number" value="'.$pre_number.'"></td>
    </tr>
    <tr><td><br></td></tr>
    <tr>
    <td width="50%" align="center">〇学　年　</td><td width="50%" align="center"><input type="number" min="1" max="5" id="name" name="level" value="'.$pre_level.'"></td>
    </tr>
    <tr><td><br></td></tr>
    <tr>
    <td width="50%" align="center">〇名　前　</td><td width="50%" align="center"><input type="text" id="name" name="name" value="'.$pre_name.'"></td>
    </tr>
    <tr><td><br></td></tr>
    <tr>
    <td width="50%" align="center">〇名前の公開</td><td width="50%" align="center">
    <select name="nameStatus">
    <option value="private">非公開</option>
    <option value="public">公開</option>
    </select>
    </td>
    </tr>
    <tr><td><br></td></tr>
    <tr>
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
        foreach($preFixed as $pre){
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
    <td width="50%" align="center">〇自由タグ</td><td width="50%" align="center"><input type="text" name="tag" value="'.$pre_tag.'"></input>
    </td>
    </tr>
    <tr><td><br></td></tr>
    <tr>
    <td width="50%" align="center">〇推薦する本の名前</td>
    <td width="50%" align="center"><input type="text" name="book" value="'.$pre_book.'"></input></td>
    </td>
    <tr><td><br></td></tr>
    <tr><td><br></td></tr>
    </tr>
    <tr><td colspan="2" align="center">〇推薦内容</td></tr>
    <tr><td colspan="2" align="center">
    <textarea name="comment" rows="20" cols="80">'.$pre_comment.'</textarea>
    </td></tr>
        <tr>
            <td colspan="2" align="center">
                <br>
                <input type="hidden" name="email" value="'.$email.'">
                <input type="hidden" name="token" value="'.$token.'">
                <input type="hidden" name="scene" value="preview_comment">
                <input type="submit" value="プレビュー画面へ行く">
            </td>
        </tr>
    </table>
    </form>
    ';

}

function main_inputPage($token){
    setcookie("token", $token);
    if(get_email($token) != false){
        echo '<table width="100%"><tr><td align="center"><font size="+2" color="#000000">
        ようこそ、'.get_email($token).'さん。<br>以下の項目を全て入力してください。<br><br></font>
        </tr></td></table>';
        showForm($token, get_email($token));
    }else{
        echo '<table width="100%"><tr><td align="center"><font size="+2" color="#000000">
        無効なURLを受け取りました。<br>
        もう一度最初からやり直してください。<br>
        </font></tr></td></table>
        ';
        delete_token_re($token);
    }
}

?>