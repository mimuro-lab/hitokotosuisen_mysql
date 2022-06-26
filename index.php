<?php
require_once(".//view//defaultPage.php");
?>

<!DOCTYPE html>
<html>
  <head>
    <title>トップページ</title>
    <meta charset="utf-8">
  </head>
  <body>  
		<table border="0" width="100%">
		<tr>
			<td colspan="5"" align="center">
			<img src="./title_1.gif"><br>
            <font size="+2" color="#000000">トップページ</font>
			</td>
		</tr>
		<tr>
			<td width="5%"></td>
			<td width="20%" valign="top">
				<br><br>
				<a href="./view">閲覧ページへ</a>
				<br><br>
				<a href="./post">投稿ページへ</a>
				<br><br>
				<a href="./edit">編集ページへ</a>
			</td>
			<td align="center" width="50%">
				<table border="0"  bordercolor="#adff2f" width="100%">
				<tr><td>
					<?php 
					$scene = "default";
					if(isset($_GET["scene"])){
						$scene = $_GET["scene"];
					}
					switch($scene){
						case "default":
							echo file_get_contents(__DIR__."\\top.html");
							echo "<hr><br><br>";
							$status = explode(",", file_get_contents("./data/siteStatus.txt"))[0];
							if($status === "public"){
								viewDefaultComment(7, 3, True);
							}
						break;
						case "about":
							echo file_get_contents(__DIR__."\\about.html");
						break;
						case "how":
							echo file_get_contents(__DIR__."\\how.html");
						break;
						case "info":
							echo file_get_contents(__DIR__."\\info.html");
						break;
					}
					?>	
				</td></tr>
				</table>
			</td>
			<td width="5%"></td>
			<td width="20%" valign="top">
				<br><br>
				<a href=".?scene=about">このサイトについて</a>
				<br><br>
				<a href=".?scene=how">使用方法</a>
				<br><br>
				<a href=".?scene=info">お問合せ</a>
			</td>
		</tr>
		</table>
	</body>
</html>