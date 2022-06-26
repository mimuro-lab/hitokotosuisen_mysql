<?php

require_once(__DIR__."\\utils.php");

#print_r($_POST);echo "<br>";
#print_r($_GET);echo "<br>";
#print_r($_COOKIE);echo "<br>";
date_default_timezone_set('Asia/Tokyo');

$scene = "default";
if(isset($_POST["scene"])){
	$scene = $_POST["scene"];
}
if(isset($_GET["token"])){
	if(isOkToken($_GET["token"])){
		setcookie("token_admin", $_GET["token"], time() + 60*60*3);
		$scene = "success";
	}else{
		$scene = "failed";
	}
}

function printDefaultPage(){
	echo'
	<table width="100%">
	<form action="." method="post">
	
	<tr width="100%">
    <td align="right" width="50%">username&nbsp;</td>
    <td align="left" width="50%"><input type="text" name="username"></td>
	</tr>
	<tr width="100%">
    <td align="right" width="50%">password&nbsp;</td>
    <td align="left" width="50%"><input type="text" name="password"></td>
	</tr>

	<tr><td><br></td></tr>
	<tr><td align="center" colspan="2">
	<input type="hidden" name="scene" value="verificate">
	<input type="submit" value="ログイン"></input>
	</td></tr>
	</table>
	';
}

function printReInput(){
	echo'
	<table width="100%">
	<tr><td colspan="2" width="100%" align="center">再度入力してください<br><br></td></tr>
	<form action=".\\success.php" method="post">
	<tr width="100%">
    <td align="right" width="50%">username&nbsp;</td>
    <td align="left" width="50%"><input type="text" name="username"></td>
	</tr>
	<tr width="100%">
    <td align="right" width="50%">password&nbsp;</td>
    <td align="left" width="50%"><input type="text" name="password"></td>
	</tr>

	<tr><td><br></td></tr>
	<tr><td align="center" colspan="2">
	<input type="submit" value="ログイン">
	</td></tr>
	</table>
	';
}


function printAdminedPage(bool $isOk)
{
	if($isOk){

		$length = 20;
		$token = substr(bin2hex(random_bytes($length)), 0, $length);
		# 有効な時刻
		$limitDate = date("Y-m-d H:i:s",strtotime("+3 hour"));
		file_put_contents(__DIR__."\\..\\data\\token_admin.csv", $token.",".$limitDate);

		$to = "hitokotosuisen@gmail.com";
		$subject = "管理画面へのログイン";
		$message = 'ipアドレス　'.$_SERVER["REMOTE_ADDR"].'　から管理画面へのアクセスが求められました。
		<br><a href="'.file_get_contents("./../data/servername.txt").'/admin?token='.$token.'">管理画面へ行く</a>';
		$headers = "From: from@example.com\r\n";
    	$headers .= "Content-type: text/html;charset=UTF-8";
		mail($to, $subject, $message, $headers);
		echo '
		<table width="100%">
		<tr>
			<td align="center">認証に成功しました。<br>
			管理者用メールアドレスに<font color="red">一時的に有効なURL</font>を送信しました。<br>
			有効期限が3時間なので注意してください。</td>
		</tr>
		</table>
		';
	}else{
		echo '
		<table width="100%">
		<tr>
			<td align="center">認証に失敗しました。</td>
		</tr>
		<tr>
			<td align="center">';
		print_r($_SERVER["REMOTE_ADDR"]);
		echo '</td>
		</tr>
		</table>
		';
	}
}

function printFailed(){
	echo '
	<table width="100%><tr><td align="center">
	無効なURLです。IPアドレスを保存します。
	</td></tr></table>
	';
}

function main($_scene){
	switch($_scene){
		case "default":
			printDefaultPage();
		break;
		case "verificate":
			$ok = isOkUserInfo($_POST["username"], $_POST["password"]);
			printAdminedPage($ok);
		break;
		case "success":
			printReInput();
		break;
		case "failed":
			printFailed();
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