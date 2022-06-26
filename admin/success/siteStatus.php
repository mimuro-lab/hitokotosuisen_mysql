<?php

// ここでは、cookieに保存された token_admin, username, password がすべて一致しない限り、exit()を実行する。
if(!isOkUserInfo($_COOKIE["username"], $_COOKIE["password"]) || !isOkToken($_COOKIE["token_admin"])){
    exit();
}

function getNowSiteStatus()
{
    $content = file_get_contents("./../../data/siteStatus.txt");
    $content = preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $content);
    $viewStatus = explode(",", $content)[0];
    $postStatus = explode(",", $content)[1];
    $editStatus = explode(",", $content)[2];

    return array('view'=>$viewStatus, 'post'=>$postStatus, 'edit'=>$editStatus);
    
}

function printSelect(array $preStatus)
{
    echo '
    <br><br>
    変更する場合は、選択して更新ボタンを押してください。
    <br><br>
    <form action=".?scene=siteStatus" method="post">
    閲覧ページ：
    <select name="viewStatus">
    <option value="public" ';
    if($preStatus["view"]==="public"){
     echo 'selected';   
    }
    echo '>公開状態</option>
    <option value="private" ';
    if($preStatus["view"]==="private"){
        echo 'selected';   
    }
    echo '>非公開状態</option>
    </select><br><br>
    投稿ページ：
    <select name="postStatus">
    <option value="public" ';
    if($preStatus["post"]==="public"){
        echo 'selected';   
    }
    echo '>公開状態</option>
    <option value="private" ';
    if($preStatus["post"]==="private"){
        echo 'selected';   
    }
    echo '>非公開状態</option>
    </select><br><br>
    編集ページ：
    <select name="editStatus">
    <option value="public" ';
    if($preStatus["edit"]==="public"){
        echo 'selected';   
    }
    echo '>公開状態</option>
    <option value="private" ';
    if($preStatus["edit"]==="private"){
        echo 'selected';   
    }
    echo '>非公開状態</option>
    </select><br><br>
    <button type="submit">更新する</button>
    </form>
    ';
}

function saveNextSiteStatus()
{
    $saveContente = $_POST["viewStatus"].",".$_POST["postStatus"].",".$_POST["editStatus"];
    file_put_contents("./../../data/siteStatus.txt", $saveContente);
}

function main_siteStatus()
{
    
    if(isset($_POST["viewStatus"])&&isset($_POST["postStatus"])&&isset($_POST["editStatus"])){
        saveNextSiteStatus();
    }

    echo '<table width="100%">
    <tr><td align="center">ここでは、サイトの状態を変更します。<br>現在の状態は以下です。<br><br></td>
    <tr><td>';
    $viewStatus = getNowSiteStatus()['view'];
    if($viewStatus === "public"){
        $viewStatus = '<font color="#78FF94">公開状態</font>';
    }else if($viewStatus === "private"){
        $viewStatus = '<font color="#FF367F">非公開状態</font>';
    }
    $postStatus = getNowSiteStatus()['post'];
    if($postStatus === "public"){
        $postStatus = '<font color="#78FF94">公開状態</font>';
    }else if($postStatus === "private"){
        $postStatus = '<font color="#FF367F">非公開状態</font>';
    }
    $editStatus = getNowSiteStatus()['edit'];
    if($editStatus === "public"){
        $editStatus = '<font color="#78FF94">公開状態</font>';
    }else if($editStatus === "private"){
        $editStatus = '<font color="#FF367F">非公開状態</font>';
    }
    echo '<table width="100%" border="1">
    <tr><td align="center">閲覧ページ</td><td align="center">'.$viewStatus.'</td></tr>
    <tr><td align="center">投稿ページ</td><td align="center">'.$postStatus.'</td></tr>
    <tr><td align="center">編集ページ</td><td align="center">'.$editStatus.'</td></tr>
    </table>
    ';
    echo '</td></tr><tr><td align="center">';
    printSelect(getNowSiteStatus());
    echo '</td></tr>';
    echo "</table>";
}

?>