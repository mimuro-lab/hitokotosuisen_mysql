<?php 


function printRightPage(){
	return '
	<table width="100%"><tr><td>
	<form action="'.file_get_contents(".//..//data//servername.txt").'/view/" method="get">
		<p>
			キーワードで検索する<br>
			<input type="text" name="tag" size="20">
			<input type="hidden" name="page" value="1">
			<input type="submit" value="検索">
		</p>
	</form>

	<form action="'.file_get_contents(".//..//data//servername.txt").'/view/" method="get">
		<p>
			INDEXから探す<br>
			<input type="number" min="0" name="index" size="20">
			<input type="submit" value="検索">
		</p>
	</form>

	<br><br>	
	<a href="'.file_get_contents(".//..//data//servername.txt").'/view/?tag=___time_">最新の投稿順</a><br><br>
	<a href="'.file_get_contents(".//..//data//servername.txt").'/view?viewCount=descend">閲覧数が多い順</a><br><br>
	<a href="'.file_get_contents(".//..//data//servername.txt").'/view/?viewCount=ascend">閲覧数が少ない順</a><br><br>
	</td></tr></table>
	';
}
?>