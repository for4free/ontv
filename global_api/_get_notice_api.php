<?php
/**
 * Created by PhpStorm.
 * User: M&F
 * Date: 2018-07-11
 * Time: 15:12
 */

if(!strstr($_SERVER["HTTP_REFERER"],'2naive.cn')){
    header("HTTP/1.0 404 Not Found");
    header("Location: /404.html");
    echo 'error!';
    return;
}
include_once 'global.php';
if(get_permission()[0]==1){
    echo json_encode([true,get_notice()]);
}else{
    echo json_encode([false]);
}