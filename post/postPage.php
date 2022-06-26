<?php

date_default_timezone_set('Asia/Tokyo');

function exitIndex(int $index)
{
    $posted = scandir(__DIR__.'/../data/posted/');
    foreach($posted as $exitI){
        if(intval($exitI) == $index){
            return true;
        }
    }
    return false;
}

// tokenを削除する。与えられたtokenの行を削除する。
function delete_token(String $token){
    $pathToToken = __DIR__."/../data/token.csv";

    // token.csvがなかったらリターンする。
    if(!file_exists($pathToToken)){
        //echo "token.csvが見つかりません。";
        return false;
    }
    // ファイルを開けなかったらリターンする。
    if(!fopen($pathToToken, "r")){
        //echo "token.csvを開けませんでした。";
        return false;
    }
    $fp = fopen($pathToToken, "r");
    $pathToTmp = __DIR__."/../data/token_tmp.csv";
    $fp_tmp = fopen($pathToTmp, "w");
    
    // 一行ずつ読み込み、tmpファイルに書き込む
    $tokenLine = "";
    while(!feof($fp)){

        // fgetにより一行読み込み
        $tokenLine = fgets($fp);
        // 最後の行になったらbreak
        if($tokenLine == ""){
            break;
        }   
        if(str_getcsv($tokenLine)[1] != $token){
            fwrite($fp_tmp, $tokenLine);
        }
    }

    // tmpファイルの内容をtoken.csvに上書きする。
    if(copy($pathToTmp, $pathToToken)){
    }

}

function delete_cookie()
{
    // クッキーを削除
    if (isset($_SERVER['HTTP_COOKIE'])) {
        $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
        foreach($cookies as $cookie) {
            $parts = explode('=', $cookie);
            $name = trim($parts[0]);
            setcookie($name, '', time()-1000);
            setcookie($name, '', time()-1000, '/');
        }   
    }
}

function sendToken($token, $email)
{
    
    $subject = '小山高専図書情報　ひとことすいせん係より';
    
    // ヘッダー情報
    $headers = "From: ". $email . "\r\n";
    // htmlメールに対応させる
    $headers .= "Content-type: text/html;charset=UTF-8";
    
    // メッセージ部分
    $message = '
    コメントIDをお送りします。

    <h3>'.$token.'</h3>';

    if(mail($email, $subject, $message, $headers)){
        return true;
    }
}

// 次のコメントのフォルダ番号を取得する
function getNextFolder()
{
    $rootDirOfPosted = __DIR__."\\..\\data\\posted";
    $max = 0;
    foreach(scandir($rootDirOfPosted) as $file){
        if(!is_dir($rootDirOfPosted."\\".$file)){
            continue;
        }
        $num = preg_replace("/[^0-9]/", "", $file);
        if($num == ""){
            continue;
        }
        $num = (int)$num;
        if($max < $num){
            $max = $num;
        }
    }
    $nextPath = $rootDirOfPosted."\\".(string)($max + 1);
    return $nextPath;
}

function make_info($pathToFolder)
{
    // 保存時のhtmlエスケープ処理
    $_POST["number"] = htmlspecialchars($_POST["number"]);
    $_POST["name"] = htmlspecialchars($_POST["name"]);
    $_POST["book"] = htmlspecialchars($_POST["book"]);
    $_POST["tag"] = htmlspecialchars($_POST["tag"]);
    $_POST["fixedTag"] = htmlspecialchars($_POST["fixedTag"]);

    // 保存時のカンマのエスケープ処理
    $_POST["number"] = str_replace(",", "?cma?", $_POST["number"]);
    $_POST["name"] = str_replace(",", "?cma?", $_POST["name"]);
    $_POST["book"] = str_replace(",", "?cma?", $_POST["book"]);
    $_POST["comment"] = str_replace(",", "?cma?", $_POST["comment"]);

    $w = date("w");
    $week_name = array("日", "月", "火", "水", "木", "金", "土");
    $dateOfMake = date("Y/m/d") . "($week_name[$w]) ".date("H:i");
    $dateOfTag =  date("Y/m/d");
    $book = $_POST["book"];

    // 検索に使われるファイル（タグと、ほんのタイトル）
    $tag = $_POST["tag"];
    $tag_filePath = $pathToFolder."\\search_kwd.txt";
    file_put_contents($tag_filePath, $tag);

    // 固定タグを保存するファイル
    $fixed_tag = $_POST["fixedTag"];
    $fixed_filePath = $pathToFolder."\\search_kwd_fixed.txt";
    file_put_contents($fixed_filePath, $fixed_tag);

    // コメントの表示に使われるファイル
    $comment = $_POST["comment"];
    $nameStatus = "name_".$_POST["nameStatus"];
    $view_content = $book.",".$dateOfMake.",".$comment.",".$nameStatus;
    $view_filePath = $pathToFolder."\\view.txt";
    file_put_contents($view_filePath, $view_content);

    // 公開状態を設定するファイル
    $initStatus = explode(",", file_get_contents("./../data/initStatus.txt"))[1];
    $status_filePath = $pathToFolder."\\status.txt";
    file_put_contents($status_filePath, $initStatus);

    // 投稿主の情報を格納するファイル
    $length = 10;
    $token_comment = base_convert(mt_rand(pow(36, $length - 1), pow(36, $length) - 1), 10, 36);
    $name = $_POST["name"];
    $number = $_POST["number"];
    $level = $_POST["level"];
    $email = $_POST["email"];
    $info_content = $token_comment.','.$name.','.$number.','.$level.','.$email.','.$dateOfMake;
    $info_filePath = $pathToFolder."\\info.txt";
    file_put_contents($info_filePath, $info_content);

    // カウントのファイル
    file_put_contents($pathToFolder."\\count.txt", 0);

    // インデックスのファイル
    file_put_contents($pathToFolder."\\index.txt", basename($pathToFolder));

    return $token_comment;
}

function main_postPage()
{
    
    $success = false;
    $token_comment = "";

    if(!isset($_SESSION["savedIndex"])){
        if(isset($_POST["number"]) && isset($_POST["name"]) && isset($_POST["email"]) && 
        isset($_POST["book"]) && isset($_POST["tag"]) && isset($_POST["comment"])){
            $success = true;
            $pathToFolder = getNextFolder();
            mkdir($pathToFolder);
            $token_comment = basename($pathToFolder).":".make_info($pathToFolder);
            $_SESSION["savedIndex"] = basename($pathToFolder);
        }

    }
    if($success){
        delete_token($_POST["token"]);
        echo '<table width="100%"><tr><td align="center"><font size="+1" color="#000000">
        投稿が終了しました。INDEXは'.explode(":",$token_comment)[0].'です。<br><br>
        <a style="text-decoration: none;" href="./../view?index='.explode(":",$token_comment)[0].'">
        <font size="+2" color="#696969">投稿した内容を見る</font></a><br><br>
        また、コメントIDを発行しました。<br><br>
        <font color="red">'.$token_comment.'<br><br><br>
        ※コメントを編集するのに必要なIDです。メモしておいてください。<br><br>

        '.$_POST["email"].' 宛てにこのコメントIDを送信しますか？</font><br><br>        
        </font></td></tr>
        <tr><td align="center">
        <form action="" method="post">
        <input type="hidden" name="scene" value="post_comment">
        <input type="hidden" name="sendToken" value="true">
        <input type="hidden" name="token_comment" value="'.$token_comment.'">
        <input type="hidden" name="email" value="'.$_POST["email"].'">
        <input type="submit" value="送信する">
        </form>
        </td></tr></table>

        ';
        // 「トップページへ戻る」を表示する。
        echo '
        <table border="1" width="100%">
        <tr><td align="center">
        投稿は完了しました。<br>
        <a href="./../">トップページへ戻る</a>
        </td></tr>
        </table>
        ';
        delete_cookie();
        return;
    }else if(isset($_POST["sendToken"]) && $_POST["sendToken"] == "true"){
        sendToken($_POST["token_comment"], $_POST["email"]);
        echo '
        <table width="100%"><tr><td align="center">
        コメントIDを'.$_POST["email"].'宛てに送信しました。<br><br>
        送信したコメントIDは以下の通りです。<br><br>
        <h3>'.$_POST["token_comment"].'</h3><br><br>
        <form action="" method="post">
        <input type="hidden" name="scene" value="post_comment">
        <input type="hidden" name="sendToken" value="true">
        <input type="hidden" name="token_comment" value='.$_POST["token_comment"].'>
        <input type="hidden" name="email" value="'.$_POST["email"].'">
        <input type="submit" value="再送信する">
        </form>
        </td></tr></table>
        <table border="1" width="100%">
        <tr><td align="center">
        投稿は完了しました。<br>
        <a href="./..">トップページへ戻る</a>
        </td></tr>
        </table>
        ';
        return ;
    }

    echo '
    <table border="1" width="100%">
    <tr><td align="center">
    投稿できませんでした。最初からやり直してください。<br>
    <a href="./..">トップページへ戻る</a>
    </td></tr>
    </table>
    ';
    delete_token_re($_POST["token"]);
    return;
}

?>