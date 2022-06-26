<?php
require_once(__DIR__."\\utils.php");
//print_r($_GET);echo "<br>";
//print_r($_POST);echo "<br>";
//print_r($_COOKIE);echo "<br>";

if(!isOkUserInfo($_POST["username"], $_POST["password"])){
    echo '一致しないユーザー名とパスワードが入力されました。';
    exit();
}else{
    setcookie("username", $_POST["username"], time() + 60 * 60 * 3);
    setcookie("password", $_POST["password"], time() + 60 * 60 * 3);
}

$scene = "default";

function main($_scene){
    switch($_scene){
        case "default":
            echo '<table width="100%"><tr><td align="center">認証に成功しました。<br><a href=".//success/">管理者画面へ行く</a></td></tr></table>';
        break;
    }
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
      <td align="center" colspan="2">管理者用画面</td>
	</tr>
	<tr><td><br><br></td></tr>
	
	<table width="100%">

	<?php main($scene)?>
	
	</table> 
  </body>
</html>