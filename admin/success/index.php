<?php

require_once(".//..//utils.php");

if(!isset($_COOKIE["username"]) || !isset($_COOKIE["password"]) || !isset($_COOKIE["token_admin"])){
    echo "正しい認証手順を踏んでください。";
    exit();
}

// ここでは、cookieに保存された token_admin, username, password がすべて一致しない限り、exit()を実行する。
if(!isOkUserInfo($_COOKIE["username"], $_COOKIE["password"]) || !isOkToken($_COOKIE["token_admin"])){
    exit();
}

require_once(".//utils.php");
require_once(".//allView.php");
require_once(".//view.php");
require_once(".//leftPage.php");
require_once(".//initStatus.php");
require_once(".//siteStatus.php");
#print_r($_GET); echo "<br>";
#print_r($_POST); echo "<br>";
#print_r($_COOKIE); echo "<br>";



$scene = "default";
if(isset($_GET["scene"])){
    $scene = $_GET["scene"];
}

function printDefault()
{
    echo '
    <table width="100%">
    <tr><td align="center">
    <a href="./?scene=allView">投稿内容の管理<br><br></a>
    </td></tr>
    <tr><td align="center">
    <a href="./?scene=initStatus">投稿初期状態の管理<br><br></a>
    </td></tr>
    <td align="center">
    <a href="./?scene=siteStatus">サイトの状態管理<br><br></a>
    </td></tr>
    </table>
    ';
}

function main(string $_scene)
{
    echo '
    <table width="100%">
    <tr>
    <td width="5%"></td><td width="20%" valign="top">';
    printLeftPage();
    echo '</td>
    <td width="50%" valign="top">
    ';
    switch($_scene){
        case "default":
            printDefault();
        break;
        case "allView":
            main_allView();
        break;
        case "view":
            main_view();
        break;
        case "initStatus":
            main_initStatus();
        break;
        case "siteStatus":
            main_siteStatus();
        break;
    }
    echo '
    </td>
    <td witdth="5%"></td><td width="20%" valign="top">';
    
    if($_scene === "allView"){
        printInputIndex();
    }

    echo '</td>
    </tr>
    </table>
    ';
}

?>

<!DOCTYPE html>
<html>
  <head>
    <title>管理者用画面</title>
    <meta charset="utf-8">
  </head>
  <body> 
  <table width="100%" border="0">
	<tr><td><br><br></td></tr>
  <tr>
      <td align="center" colspan="2">管理者用画面(認証成功)</td>
	</tr>
	<tr><td><br><br></td></tr>
	
	<table width="100%">

	<?php main($scene)?>
	
	</table> 
  </body>
</html>