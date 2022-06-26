<?php
require_once(".//sendedEmailPage.php");
require_once(".//inputPage.php");
require_once(".//previewPage.php");
require_once(".//postPage.php");
require_once(".//quitPage.php");

// 投稿ページが有効かどうか？
$status = explode(",", file_get_contents("./../data/siteStatus.txt"))[1];

if($status !== "public"){
	echo '申し訳ありません。ただいまメンテナンス中です。<br>
	<a href="'.file_get_contents("./../data/servername.txt").'">トップページへ戻る</a>';
	exit();
}

// 変数の取得
//echo "get:";print_r($_GET); echo "<br>";
#echo "post:";print_r($_POST); echo "<br>";
//echo "cookie:";print_r($_COOKIE); echo "<br>";
//echo "session:";print_r($_SESSION); echo "<br>";
$scene = "default";
$token = "";
$userMail = "";

if(isset($_GET["token"])){
	$token = $_GET["token"];
	$scene = "input_comment";
}else if(isset($_POST["token"])){
	$token = $_POST["token"];
}
# setcookieはここに書く必要がある。
if($scene === "input_comment"){
	setcookie("token", $token, time() + 60 * 15);
}
// postにscineがセットされていたら、postを優先する。
if(isset($_POST["scene"])){
	$scene = $_POST["scene"];
}

?>

<!DOCTYPE html>
<html>
  <head>
    <title>投稿ページ</title>
    <meta charset="utf-8">
  </head>
  <body>  
		<table border="0" width="100%">
		<tr>
			<td colspan="5" align="center">
		    <img src="./../title_1.gif"><br>
			<font size="+2" color="#000000">投稿ページ</font>
			</td>
		</tr>
		<tr>
			<td width="5%"></td>
			<td width="20%" valign="top">
				<?php
				if($scene == "default"){
					echo file_get_contents(__DIR__."\\leftPage.php");
				}?>
			</td>
			<td align="left" width="50%">
				<?php

				switch($scene){
				case "default":
					echo file_get_contents(__DIR__."\\defaultPage.php");
					break;
				case "sended_email":
					session_unset();
					// クッキーを削除
					if (isset($_SERVER['HTTP_COOKIE'])) {
						$cookies = explode(';', $_SERVER['HTTP_COOKIE']);
						foreach($cookies as $cookie) {
							$parts = explode('=', $cookie);
							$name = trim($parts[0]);
							setcookie($name, '', time()-1000);
							setcookie($name, '', time()-1000, '/');
						}   
					}
					main_sendMail($_POST);
					break;
				case "input_comment":
					main_inputPage($token);
					//setcookieはHTMLより前に記載する必要がある。よって、上のほうに記載。
					//setcookie("token", $token, time() + 60 * 15);
					break;
				case "preview_comment":
					main_previewPage();
					break;
				case "post_comment":
					session_start();
					main_postPage();
					break;
				case "quit_post":
					session_unset();
					main_quitPage();
					break;
				}

				?>
			</td>
			<td width="5%"></td>
			<td width="20%" valign="top">
			<br>
			<?php
			if($scene == "input_comment" || $scene == "preview_comment"){
				require_once(".//rightPage.php");
			}?>
			</td>
		</tr>
		</table>
	</body>
</html>