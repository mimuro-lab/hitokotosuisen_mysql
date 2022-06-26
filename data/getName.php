<?php

function getName(bool $isAdmin, string $pathToFolder){
    if($isAdmin){
        $name = file_get_contents($pathToFolder.'\\view.txt');
        $name = explode(",", $name)[3];
        if(str_replace("name_", "", $name) === "public"){
            return True;
        }else if (str_replace("name_", "", $name) === "private"){
            return False;
        }
        return False;
    }
    
    $name = file_get_contents($pathToFolder.'\\view.txt');
    $name = explode(",", $name)[3];
    $isPublic = False;
    if(str_replace("name_", "", $name) === "public"){
        $isPublic = True;
    }else if (str_replace("name_", "", $name) === "private"){
        return "非公開の名前";
    }
    
    if($isPublic){
        $name = file_get_contents($pathToFolder.'\\info.txt');
        $name = explode(",", $name)[1];
        return $name;
    }
    return "未設定";
}

?>