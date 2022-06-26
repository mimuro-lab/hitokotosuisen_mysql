<?php

// ここでは、cookieに保存された token_admin, username, password がすべて一致しない限り、exit()を実行する。
if(!isOkUserInfo($_COOKIE["username"], $_COOKIE["password"]) || !isOkToken($_COOKIE["token_admin"])){
    exit();
}

function update_status($nextStatus)
{
    $pathToInfo = __DIR__."\\..\\..\\data\\posted\\".$_GET["index"]."\\status.txt";
    file_put_contents($pathToInfo, $nextStatus);
}

function main_view()
{
    if(!isset($_GET["index"])){
        echo 'INDEXが選択されていません。';
        return;
    }

    $exitDirs = scandir("./../../data/posted");
    $isExit = false;
    foreach($exitDirs as $d){
        if($d !== "." && $d !== ".."){
            if($d === $_GET["index"]){
                $isExit = true;
            }
        }
    }
    if(!$isExit){
        echo "有効なINDEXが入力されませんでした。";
        return;
    }

    if(isset($_POST["status"])){
        update_status(($_POST["status"]));
    }
    $content = getPostedFromIndex($_GET["index"]);

    $status = "none";
    $color = "#C0C0C0";
    if(isset($content["status"])){
        if($content["status"] === "public"){
            $status = "公開状態";
            $color = "#78FF94";
        }else if($content["status"] === "private"){
            $status = "非公開状態";
            $color = "#FF367F";
        }else if($content["status"] === "wait"){
            $status = "認証待ち状態";
        }
    }
    $preMode = "default";
    if(isset($_GET["mode"])){
        $preMode = $_GET["mode"];
    }
    
    echo '
    <table width="100%">
    <tr><td colspan="2" align="center">
    <form action="./?scene=view&index='.$_GET["index"].'&mode='.$preMode.'" method="post" id="refresh">
    <button type="submit">更新</button>
    </form>
    </td></tr>
    <tr>
    <td colspan="2"><hr style="height:3px;"></td>
    </tr>
    <tr>
    <td colspan="2" width="100%" align="center"><font size="+1" color="'.$color.'">'.$status.'</font></td>
    </tr>
    <tr>
    <td width="50%">INDEX</td><td width="50%">'.$content["index"].'</td>
    </tr>
    <tr>
    <td width="50%">氏名</td><td width="50%">'.$content["info"][1].'</td>
    </tr>
    <tr>
    <td width="50%">氏名の公開状態</td><td width="50%">';
    
    if($content["nameStatus"]){
        echo "公開";
    }else if(!$content["nameStatus"]){
        echo "非公開";
    }
    
    echo '</td>
    </tr>
    <tr>
    <td width="50%">学籍番号</td><td width="50%">'.$content["info"][2].'</td>
    </tr>
    <tr>
    <td width="50%">メールアドレス</td><td width="50%"><a href="mailto:'.$content["info"][4].'">'.$content["info"][4].'</a></td>
    </tr>
    <tr>
    <td width="50%">コメントID</td><td width="50%">'.$content["index"].":".$content["info"][0].'</td>
    </tr>
    <tr>
    <td width="50%">投稿日時</td><td width="50%">'.$content["info"][5].'</td>
    </tr>
    </table>
    <br>
    <table width="100%"><tr><td align="center">閲覧プレビュー</td></tr></table>
    <br>
    <table border="0" width="100%"  bgcolor="#fafafa">
    <tr>
      <td colspan="2"><hr style="height:3px;"></td>
    </tr>
    <tr>
      <td style="word-break: break-all;">
      <font size="+2" face="arial black">'.$content["view"][0].'</font>
      </td>
      <td style="word-break: break-all;"  align="right">'.$content["info"][5].'
      INDEX:'.$content["index"].'
      </td>
    </tr>
    <tr>
      <td colspan="2" align="right"><font style="opacity:0.7" face="arial unicode ms">'.$content["count"][0].'&nbsp;回閲覧</font></td>
    </tr>
    <tr><td colspan="2" align="center"><font style="opacity:0.5" size="-1" face="arial unicode ms">推薦内容</font></td></tr>
    <tr>
      <td style="word-break: break-all;"  colspan="2">'.$content["view"][2].'</td>
    </tr>
    <tr><td><br></td></tr>
    <tr><td colspan="2" align="right"><font style="opacity:0.5" size="-1" face="arial unicode ms">以上</font></td></tr>
    <tr>
    <td colspan="2"><hr style="height:3px;"></td>
    </tr>
    </table>
    <table width="100%">
    <tr><td width="30%">タグ</td><td align="right"> 
    ';
    foreach($content["serch_kwd"] as $tag){
        echo "&nbsp;".$tag;
    }
    echo '<br>';
    foreach($content["serch_kwd_fixed"] as $tag){
        echo "&nbsp;".$tag;
    }
    
    echo '
    </td></tr>
    <tr><td><br></td></tr>
    <tr><td colspan="2" align="center">
    <table width="100%" border="1"><tr><td align="center"s>
    <form action="./?scene=view&index='.$_GET["index"].'&mode='.$preMode.'" method="post" id="refrect">
    状態を変更する<br><br>
    <select name="status" form="refrect">
    <option value="public">公開状態</option>
    <option value="private">非公開状態</option>
    </select></li>
    <button type="submit">反映</button>
    </form>
    </td></tr></table>
    </td></tr>
    <tr><td colspan="2"s align="center"><br><br><a href="./?scene=allView&mode='.$preMode.'">[一覧に戻る]</a></td></tr>
    </table>';

}

?>