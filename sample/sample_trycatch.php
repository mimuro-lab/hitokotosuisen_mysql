<?php
# try-catch文のサンプルコード
class TestTryCatch
{
    function main(){
        try {
            //正常な処理
             throw new Exception();
            echo("正常な処理");
           }catch (Exception $e) {
            //例外が投げられた時の処理
            echo("例外の処理");
           }
    }
} 

$test = new TestTryCatch();
$test->main();

?>