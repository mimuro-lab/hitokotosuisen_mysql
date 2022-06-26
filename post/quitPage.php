<?php


// tokenを削除する。与えられたtokenの行を削除する。
function delete_token_re(String $token){
    $pathToToken = __DIR__."/../data/token.csv";

    // token.csvがなかったらリターンする。
    if(!file_exists($pathToToken)){
        #echo "token.csvが見つかりません。";
        return false;
    }
    // ファイルを開けなかったらリターンする。
    if(!fopen($pathToToken, "r")){
        
        #echo "token.csvを開けませんでした。";
        return false;
    }
    $fp = fopen($pathToToken, "r");
    $pathToTmp = __DIR__."/../data/token_tmp.csv";
    $fp_tmp = fopen($pathToTmp, "w");
    
    // 一行ずつ読み込み、tmpファイルに書き込む
    $tokenLine = "";
    while(!feof($fp)){

        #print_r($tokenLine."<br>");
        // fgetにより一行読み込み
        $tokenLine = fgets($fp);
        // 最後の行になったらbreak
        if($tokenLine == ""){
            break;
        }   
        $saved_token = str_getcsv($tokenLine)[1];
        $saved_token = preg_replace('/[^0-9a-zA-Z]/', '', $saved_token);

        $token = preg_replace('/[^0-9a-zA-Z]/', '', $token);
        if($saved_token != $token){
            fwrite($fp_tmp, $tokenLine);
        }else{
            #print_r($tokenLine);
        }
    }

    // tmpファイルの内容をtoken.csvに上書きする。
    if(copy($pathToTmp, $pathToToken)){
    }

}

function main_quitPage()
{
    $token = $_POST["token"]."\r\n";

    //　なんかできない。2020/10/04
    if(delete_token_re($token)===false){
    }else{
        echo '
        <table border="1" width="100%">
        <tr><td align="center">
        投稿を中断しました。<br>
        <a href="./../">トップページへ戻る</a>
        </td></tr>
        </table>
        ';
    }
}

?>