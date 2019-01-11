<?php
/**
 * Created by PhpStorm.
 * User: M&F
 * Date: 2018-07-09
 * Time: 18:13
 */
# header("Content-type:text/html;charset=utf-8");
if(!strstr($_SERVER["HTTP_REFERER"],'2naive.cn')){
    header("HTTP/1.0 404 Not Found");
    header("Location: /404.html");
    echo 'error!';
    return;
}
include_once 'global.php';


if(get_permission()[2]==1){
    echo json_encode(get_tags_cloud_list());
}else{
    $user_IP = get_client_ip();
    $uuid = $_COOKIE['uuid']?$_COOKIE['uuid']: 0;
    $output = shell_exec('python3 ../pyshell/tagContent.py '.$user_IP.' '.$uuid);
    $str = urldecode($output);
    $new_arr = [];
    eval("\$arr = ".$str.'; '); //数组转换
    foreach ($arr as $item){
        if(!get_word_filter_result($item)){
            array_push($new_arr,$item);
        }
    }
    echo json_encode($new_arr);
}




