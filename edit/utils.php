<?php

function get_email(String $token){
    $pathToToken = __DIR__."//../data/token.csv";

    // token.csvがなかったらリターンする。
    if(!file_exists($pathToToken)){
        echo "token.csvが見つかりません。(get_email)";
        return false;
    }
    // ファイルを開けなかったらリターンする。
    if(!fopen($pathToToken, "a")){
        echo "token.csvを開けませんでした。";
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

function getFixedInd(array $fixeTags)
{
    $fixed_table = explode(",", file_get_contents(__DIR__."/../data/tagTable.txt"));
    $indexArray = array();
    for($i = 0; $i < count($fixed_table); $i++){
        foreach($fixeTags as $pre){
            if($pre === $fixed_table[$i]){
                array_push($indexArray, $i);
            }
        }
    }
    return $indexArray;
}

?>