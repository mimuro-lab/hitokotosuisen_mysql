<?php

// ここでは、cookieに保存された token_admin, username, password がすべて一致しない限り、exit()を実行する。
if(!isOkUserInfo($_COOKIE["username"], $_COOKIE["password"]) || !isOkToken($_COOKIE["token_admin"])){
    exit();
}

require_once(".//utils.php");

function printContent_allView($nowPage, $viewsPerPage)
{
    $mode = "default";
    if(isset($_POST["mode"])){
        $mode = $_POST["mode"];
    }
    if(isset($_GET["mode"])){
        $mode = $_GET["mode"];
    }

    switch($mode){
        case "default":
            echo '<table width="100%"><tr><td align="center">デフォルト（認証待ち）</td></tr></table>';
        break;
        case "public":
            echo '<table width="100%"><tr><td align="center">公開</td></tr></table>';
        break;
        case "private":
            echo '<table width="100%"><tr><td align="center">非公開</td></tr></table>';
        break;
        case "wait":
            echo '<table width="100%"><tr><td align="center">認証待ち</td></tr></table>';
        break;        
        case "ascend":
            echo '<table width="100%"><tr><td align="center">INDX（昇順）</td></tr></table>';
        break;
        case "descend":
            echo '<table width="100%"><tr><td align="center">INDX（降順）</td></tr></table>';
        break;
    }

    $contentAll = getPostedAll($mode);
    $allCount = count($contentAll);
    $maxPage = ceil($allCount / $viewsPerPage);
    if($maxPage == 0){
        $maxPage = 1;
    }
    echo '<table width="100%"><tr><td align="center">('.$nowPage.'/'.$maxPage.')<br><br></td></tr></table>';
    printContentPre($contentAll, $viewsPerPage, ($nowPage-1) * $viewsPerPage);
    echo '<table width="100%"><tr><td align="center">';
    $mode = "default";
    if(isset($_GET["mode"])){
        $mode = $_GET["mode"];
    }
    for($i = 1; $i <= $maxPage; $i++){
        echo '<a href="./?scene=allView&page='.$i.'&mode='.$mode.'">'.$i.'</a>&nbsp;';
    }
    echo '</td></tr></table>';
    return $maxPage;
}

function printInputIndex(){
    echo '
        INDEXから探す<br>
    <form action="." method="get" id="byIND">
        <input type="number" min="0" name="index" size="20" form="byIND">
        <input type="submit" value="検索" form="byIND">
    </form>
    <form action="." method="get" id="byIND">
        <input type="hidden" name="scene" value="view" form="byIND">
    </form>
    <br>
    <form action="./?scene=allView&mode=wait" method="post" id="sort3">
        <input type="submit" value="認証待ちのみ" form="sort3">
    </form>
    <br>
    <form action="./?scene=allView&mode=public" method="post" id="sort4">
        <input type="submit" value="公開状態のみ" form="sort4">
    </form>
    <br>
    <form action="./?scene=allView&mode=private" method="post" id="sort5">
        <input type="submit" value="非公開状態のみ" form="sort5">
    </form>
    <br>
    <form action="./?scene=allView&mode=ascend" method="post" id="sort1">
        <input type="submit" value="INDEX（昇順）" form="sort1">
    </form>
    <br>
    <form action="./?scene=allView&mode=descend" method="post" id="sort2">
        <input type="submit" value="INDEX（降順）" form="sort2">
    </form>
    <br>
    ';
}

function main_allView()
{
    $page = 1;
    if(isset($_GET["page"])){
        $page = $_GET["page"];
    }
    $viewsPerPage = 10;
    $maxPage = printContent_allView($page, $viewsPerPage);
    // ボタンを表示する
    $prePage = $page <= 1 ? 1 : $page - 1;
    $nextPage = $page >= $maxPage ? $maxPage : $page + 1;
    $nextMode = "default";
    if(isset($_GET["mode"])){
        $nextMode = $_GET["mode"];
    }
    echo '
    <br>
    <table width="100%"><tr><td align="center">
    <form action="./?scene=allView&mode='.$nextMode.'" method="get" id="movePage">
    <input type="hidden" name="scene" value="allView" form="movePage">
    <input type="hidden" name="mode" value="'.$nextMode.'" form="movePage">
    <button type="submit" name="page" value="'.$prePage.'" form="movePage">前へ</button>
    <button type="submit" name="page" value="'.$nextPage.'" form="movePage">次へ</button>
    </fotm>
    </td></tr></table>';
}

?>