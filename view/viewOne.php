<?php

function main_viewOne(int $index)
{
    $pathToCommentPosted = __DIR__."/./../data/posted/";
		$listOfFolder = scandir($pathToCommentPosted);
		$isFind = false;
    
    foreach($listOfFolder as $path){
			if($path == "." || $path == ".."){
				continue;
			}
			$path = preg_replace('/[^0-9]/', '', $path);
      if($index == intval($path)){
				$isFind = true;
      }
		}
		if(!$isFind){
			echo '<br>INDEX:<i>'.$index.'</i>に一致する投稿は見つけられませんでした。';
			return ;
		}	
    
    $pathToFolder = $pathToCommentPosted."/".$index;

    #$isPublic = explode(",", file_get_contents($pathToFolder."/info.txt"))[6];
    $isPublic = file_get_contents($pathToFolder."/status.txt");
    if($isPublic === "private"){
			echo '<br>INDEX:<i>'.$index.'</i>は現在非公開です。';
      return;
    }
    if($isPublic === "wait"){
			echo '<br>INDEX:<i>'.$index.'</i>は審査待ちです。';
      return;
    }
    // 閲覧数を記録する
    $pathToCount = $pathToFolder."/count.txt";
    if(!isset($_COOKIE["visit"]) || intval($_COOKIE["visit"]) != $index){  
      $counter = intval(file_get_contents($pathToCount));
      file_put_contents($pathToCount, $counter+1);
    }
    $counter = file_get_contents($pathToCount);

    setcookie("visit", $index, time() + 60 * 10);
    
    // 内容を表示する
    $content = getContentsFromFolder($pathToFolder);
    $content["comment"] = str_replace("?cma?",",",$content["comment"]);
    $content["book"] = str_replace("?cma?",",",$content["book"]);
		echo '
    <table border="0" width="100%"  bgcolor="#fafafa">
    <tr>
      <td colspan="2"><hr style="height:3px;"></td>
    </tr>
    <tr>
      <td style="word-break: break-all;">
      <font size="+2" face="arial black">'.$content["book"].'</font>
      </td>
      <td style="word-break: break-all;"  align="right">'.$content["date"].'
      INDEX:'.$content["index"].'
      </td>
    </tr>
    <tr>
      <td colspan="2" align="right">
        <font style="opacity:0.7" face="arial unicode ms">'.$content["counter"].'&nbsp;回閲覧<br></font>
        <font>'.$content["name"].'：投稿者<br></font>
      </td>
    </tr>
    <tr><td colspan="2" align="center"><font style="opacity:0.5" size="-1" face="arial unicode ms">推薦内容</font></td></tr>
    <tr>
      <td style="word-break: break-all;"  colspan="2">'.$content["comment"].'</td>
    </tr>
    <tr><td><br></td></tr>
    <tr><td colspan="2" align="right"><font style="opacity:0.5" size="-1" face="arial unicode ms">以上</font></td></tr>
    <tr>
    <td colspan="2"><hr style="height:3px;"></td>
    </tr>
    </table>
    <table width="100%">
    <tr><td colspan="2" align="right">タグ<br>
    ';
    foreach($content["tag"] as $tag){
      if($tag != ""){
        $tag_ = str_replace("&amp;", "%26", $tag);
        echo '&nbsp;&nbsp;<a href="./?tag='.$tag_.'">'.$tag.'</a>';
      }
    }

    echo '<br>';
    foreach($content["tagFixed"] as $tag){
      if($tag != ""){
        $tag_ = str_replace("&amp;", "%26", $tag);
        echo '&nbsp;&nbsp;<a href="./?tag='.$tag_.'">'.$tag.'</a>';
      }
    }
    echo '
  </td></tr>
  <tr><td align="center" colspan="2">
    <br><br><a href="javascript:history.back()">[戻る]</a><br><br>
  </td></tr>
  </table> ';

}

?>