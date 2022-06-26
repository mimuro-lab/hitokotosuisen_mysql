<?php

// ここでは、cookieに保存された token_admin, username, password がすべて一致しない限り、exit()を実行する。
if(!isOkUserInfo($_COOKIE["username"], $_COOKIE["password"]) || !isOkToken($_COOKIE["token_admin"])){
    exit();
}

function printLeftPage()
{
    echo '
    <table width="100%" border="0">
    <tr><td align="center" valign="top"><a href="./">管理者用トップページへ</a><br></td></tr>
    </table>
    ';
}

?>