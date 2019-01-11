<?php
/**
 * Created by PhpStorm.
 * User: M&F
 * Date: 2019-01-09
 * Time: 21:10
 */
if(isset($_COOKIE[''])){
    setcookie("", "", time()+60*15);
}else{
    $pwd = @$_POST['pwd']?$_POST['pwd']:header("Location: /2login.php");
    if($pwd == ''){
        setcookie("", "", time()+60*30);
        header("Location: /");
    }else{
        header("Location: /2login.php");
    }
}