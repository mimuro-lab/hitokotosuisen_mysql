<?php

function rewrite_status(string $status)
{
    $folderIND = explode(":", explode(",", $_POST["ID"])[0])[0];
    $pathToFolder = __DIR__."\\..\\data\\posted\\".$folderIND;
    
    #　公開状態の設定
    file_put_contents($pathToFolder."\\status.txt", $status);
}

function get_status()
{
    $folderIND = explode(":", explode(",", $_POST["ID"])[0])[0];
    $pathToFolder = __DIR__."\\..\\data\\posted\\".$folderIND;
    
    #　公開状態の設定
    return file_get_contents($pathToFolder."\\status.txt");
}

function printButton_select_toEdit()
{
    echo'
    <table width="100%" border="0">
    <tr>
        <td align="center">
        <form action="" method="post">
        <input type="hidden" name="scene" value="edit_comment">
        <input type="hidden" name="ID" value="'.$_POST["ID"].'">
        <input type="hidden" name="tag" value="'.htmlspecialchars($_POST["tag"]).'">
        <input type="hidden" name="tagFixed" value="'.htmlspecialchars($_POST["tagFixed"]).'">
        <input type="hidden" name="comment" value="'.htmlspecialchars($_POST["comment"]).'">
        <input type="hidden" name="book" value="'.htmlspecialchars($_POST["book"]).'">
        <button type="submit">内容を編集する</button>
        </form>     
    </td>
    </tr>    
    </table>
    ';
}

function printButton_select_status()
{
    echo'
    <table width="100%" border="1">
    <tr>
        <td align="center">
        <form action="" method="post">
        <input type="hidden" name="scene" value="select">
        <input type="hidden" name="ID" value="'.$_POST["ID"].'">
        <input type="hidden" name="tag" value="'.htmlspecialchars($_POST["tag"]).'">
        <input type="hidden" name="tagFixed" value="'.htmlspecialchars($_POST["tagFixed"]).'">
        <input type="hidden" name="comment" value="'.htmlspecialchars($_POST["comment"]).'">
        <input type="hidden" name="book" value="'.htmlspecialchars($_POST["book"]).'">
        <select name="status">
        <option value="private">非公開</option>
        <option value="wait">承認待ち</option>
        </select>
        <button type="submit">更新</button>
        </form>     
    </td>
    </tr>
    </table>
    ';
}

function main_selectPage()
{

    if(isset($_POST["status"])){
        rewrite_status($_POST["status"]);
    }

    echo '
    ここでは、公開状態の編集と、内容の編集が行えます。<br>
    <br>
    現在の公開状態は、
    ';

    if(get_status() === "wait"){
        echo "承認待ち";
    }else if(get_status() === "private"){
        echo "非公開";
    }else if(get_status() === "public"){
        echo "公開";
    }
    echo '
    です。<br>
    変更したい場合は、以下の選択を選んで、更新ボタンを押してください。<br><br>
    ';
    printButton_select_status();
    
    echo '<br>
    内容を編集したい場合は、「内容を編集する」ボタンを押してください。<br>
    <br>
    
    ';

    printButton_select_toEdit();

}

?>