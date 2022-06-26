<?php

// ここでは、cookieに保存された token_admin, username, password がすべて一致しない限り、exit()を実行する。
if(!isOkUserInfo($_COOKIE["username"], $_COOKIE["password"]) || !isOkToken($_COOKIE["token_admin"])){
    exit();
}

function printNowStatus()
{
    echo '現在の初期状態';
    $nowStatus = file_get_contents('./../../data/initStatus.txt');
    $nowStatus = explode(",", $nowStatus);
    $nowStatus[0] = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $nowStatus[0]);
    $nowStatus[0] = preg_replace('/[^a-zA-Z]/', '', $nowStatus[0]);

    $echoStatus = "";
    $color = "";
    if($nowStatus[0] === "wait"){
        $color = "#C0C0C0";
        $echoStatus = "認証待ち";
    }else if($nowStatus[0] === "public"){
        $color = "#78FF94";
        $echoStatus = "公開状態";
    }else if($nowStatus[0] == "private"){
        $color = "#FF367F";
        $echoStatus = "非公開状態";
    }

    $echoStatusEdit = "";
    $colorEdit = "";
    if($nowStatus[1] === "wait"){
        $colorEdit = "#C0C0C0";
        $echoStatusEdit = "認証待ち";
    }else if($nowStatus[1] === "public"){
        $colorEdit = "#78FF94";
        $echoStatusEdit = "公開状態";
    }else if($nowStatus[1] == "private"){
        $colorEdit = "#FF367F";
        $echoStatusEdit = "非公開状態";
    }

    echo '
    <br><br>
    <table border="1" width="100%">
    <tr>
    <td align="center" widht="50%">投稿時</td>
    <td align="center" width="50%"><font color="'.$color.'">'.$echoStatus.'</font></td>
    </tr>
    <tr>
    <td align="center" widht="50%">編集時</td>
    <td align="center" width="50%"><font color="'.$colorEdit.'">'.$echoStatusEdit.'</font></td>
    </tr>
    </table>
    ';
}

function printNextStatus()
{
    $nowStatus = file_get_contents('./../../data/initStatus.txt');
    $nowStatus = explode(",", $nowStatus);
    $preStatus = $nowStatus[0];
    $preStatusEdit = $nowStatus[1];
    echo '
    <br>
    以下のように変更しますか？
    <br><br>
    <form action=".?scene=initStatus" method="post">
    投稿時：
    <select name="status">
    <option value="wait" ';
    if($preStatus === "wait"){
        echo 'selected';
    }
    echo '>承認待ち</option>
    <option value="public" ';
    if($preStatus === "public"){
        echo 'selected';
    }
    echo'>公開状態</option>
    <option value="private" ';
    if($preStatus === "private"){
        echo 'selected';
    }
    echo '>非公開状態</option>
    </select><br><br>
    編集時：
    <select name="statusEdit">
    <option value="wait" ';
    if($preStatusEdit === "wait"){
        echo 'selected';
    }
    echo '>承認待ち</option>
    <option value="public" ';
    if($preStatusEdit === "public"){
        echo 'selected';
    }
    echo '>公開状態</option>
    <option value="private" ';
    if($preStatusEdit === "private"){
        echo 'selected';
    }
    echo '>非公開状態</option>
    </select><br><br>
    <button type="submit">更新する</button>
    </form>
    ';
}

function saveNextStatus()
{
    $next = $_POST["status"].",".$_POST["statusEdit"];
    file_put_contents("./../../data/initStatus.txt", $next);
}

function main_initStatus()
{
    if(isset($_POST["status"])&&isset($_POST["statusEdit"])){
        saveNextStatus();
    }
    echo '
    <table width="100%">
    <tr><td align="center">ここでは、投稿時の最初の状態を管理します。</td></tr>
    ';
    echo '<tr><td align="center">';
    printNowStatus();
    echo '</td></tr>
    <tr><td align="center">';
    printNextStatus();
    echo '</td></tr>';
    echo '</table>';
}

?>