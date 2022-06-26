<?php

//とりあえず、このPHPファイルが呼び出されたら送信する(失敗したらfalseを返す)
// 発行したtokenをURLにくっつける
function sendmailToUser($mail_user){

    if(read_token($mail_user) == false){
        //echo "メールアドレスに対応するtokenを見つけられなかったので、メールを送信できませんでした。<br>";
        return false;
    }

    // メールアドレスにマッチするtokenを取得する。
    $token = str_getcsv(read_token($mail_user))[1];
    //echo "<br>".$token."<br>";

    $mail_from      = 'hitokotosuisen@gmail.com';
    
    $subject = '小山高専図書情報　ひとことすいせん係より';
    
    // ヘッダー情報
    $headers = "From: ". $mail_from . "\r\n";
    // htmlメールに対応させる
    $headers .= "Content-type: text/html;charset=UTF-8";
    
    // メッセージ部分
    $message = '
    ひとことすいせんに参加して頂き、ありがとうございます。<br>
    以下のリンクをクリックして、投稿用画面へおすすみください。<br><br>

    <a href = "'.file_get_contents("./../data/servername.txt").'/post/?token='.$token.'">投稿しに行く</a>
    <br>
    ';

    if(mail($mail_user, $subject, $message, $headers)){
        return true;
    }
    
    return false;
}

// ランダムな英数字を作成する。同じ文字が出現する可能性あり。
// 第一引数には文字列の長さを入力する。
function random($length)
{
    return base_convert(mt_rand(pow(36, $length - 1), pow(36, $length) - 1), 10, 36);
}

// tokenのテーブルを作成する。
// 一つのメールアドレスに対して、一つのtokenしか作らない
function make_token_table(String $email){

    // 以前このメールアドレスに対してのtokenを作成したか？
    if(read_token($email) != false){
        //echo "同じメールアカウントに対して、複数のtokenは作りません。";
        return false;
    }
    $tokenAndEmail = $email.",".random(10)."\n";

    $pathToToken = __DIR__."/../data/token.csv";

    // token.csvがなかったらリターンする。
    if(!file_exists($pathToToken)){
        //echo "token.csvが見つかりません。(make_token_table)";
        return false;
    }
    // ファイルを開けなかったらリターンする。
    if(!fopen($pathToToken, "a")){
        return false;
    }
    $fp = fopen($pathToToken, "a");
    // ファイルに書き込めなかったらリターンする。
    if(!fwrite($fp, $tokenAndEmail)){
        return false;
    }
    return true;
}

// tokenを取得する。emailにマッチするtokenを返す関数。
function read_token(String $email){
    $pathToToken = __DIR__."/../data/token.csv";

    // token.csvがなかったらリターンする。
    if(!file_exists($pathToToken)){
        //echo "token.csvが見つかりません。(read_token)";
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
        if(strpos($tokenLine, $email) !== false){
            return $tokenLine;
        }
    }

    //echo "メールアカウントに対するtokenを見つけられませんでした。";
    return false;
}

// 以下、本文
function sendPostMail($mail_to)
{

    if(!make_token_table($mail_to)){
        return "cannot make token";
    }
    
    if(!sendmailToUser($mail_to)){
        return "cannot send mail";
    }

    return true;
}

function main_sendMail($post)
{
    $userMail = $post["email"];

	if(sendPostMail($userMail)){
        echo '<table width="100%"><tr><td align="center"><font size="+1" color="#696969"><br>'
        .$userMail.'宛てに応募用メールを送信しました。メールの内容をご確認ください。</font>
        </td></tr></table><br>';
	}else{
		echo sendPostMail($userMail);
	}

    // メールを再送信する。
    
	if(isset($post["isResend"]) && $post["isResend"] == "true"){
		sendmailToUser($userMail);
        echo '<table width="100%"><tr><td align="center"><font size="+2" color="#696969">再送信しました。
        </tr></td></table><br>';
    }
    

	echo '
    <br>
    <table width="100%"><tr><td align="center">
	<form action="" method="post">
    <input type="hidden" name="isResend" value="true">
    <input type="hidden" name="scene" value="sended_email">
	<input type="hidden" name="email" value="'.$userMail.'">
	<button type="submit">メールを再送信する</button>
    </form>
    </td></tr></table>
	';
}
?>