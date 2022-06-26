<?php

echo '
    <form action="" method="post">
        <li>
        <input type="hidden" name="scene" value="quit_post">
        <input type="hidden" name="token" value="
        ';
if(isset($_POST["token"])){
    echo $_POST["token"];
}else if(isset($_GET["token"])){
    echo $_GET["token"];
}
echo '
        ">
        <button type="submit">投稿をやめる</button>
        </li>
    </form>
';

?>