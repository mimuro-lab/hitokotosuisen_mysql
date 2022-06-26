<?php

require_once(".//inputIDPage.php");
require_once(".//editPage.php");
require_once(".//previewPage.php");
require_once(".//repostPage.php");
require_once(".//selectPage.php");
// 編集ページが有効かどうか？
$status = explode(",", file_get_contents("./../data/siteStatus.txt"))[2];

if($status !== "public"){
	echo '申し訳ありません。ただいまメンテナンス中です。<br>
	<a href="'.file_get_contents("./../data/servername.txt").'">トップページへ戻る</a>';
	exit();
}

// 変数の取得
if(!isset($_POST["scene"])){
	$_POST["scene"] = "default";
}

#print_r(htmlspecialchars($_GET)); echo "<br>";
#print_r($_POST);echo "<br>";
#print_r($_COOKIE);

?>

<!DOCTYPE html>
<html>
  <head>
    <title>編集ページ</title>
    <meta charset="utf-8">
  </head>
  <body>  
		<table border="0" width="100%">
		<tr>
			<td colspan="4" align="center">
		    <img src="./../title_1.gif"><br>
			<font size="+2" color="#000000">編集ページ</font>
			</td>
		</tr>
		<tr>
			<td width="5%"></td>
			<td width="20%" valign="top">
                <?php if($_POST["scene"] == "default" || $_POST["scene"] == "input_ID"){echo file_get_contents(__DIR__."\\leftPage.php");}?>
			</td>
			<td align="left" width="50%">
				<?php

				switch($_POST["scene"]){
				case "default":
					echo file_get_contents(__DIR__."\\defaultPage.php");
					break;
				case "input_ID":
					main_inputID($_POST["ID"]);
					break;
				case "select":
					main_selectPage();
					break;
				case "edit_comment":
					main_editPage($_POST);
					break;
				case "preview_comment":
					main_previewPage();
					break;
				case "post_comment":
					main_postPage();
					break;
				}

				?>
			</td>
			<td width="25%"></td>
		</tr>
		</table>
	</body>
</html>