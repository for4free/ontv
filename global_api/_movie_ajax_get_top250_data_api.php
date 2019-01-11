<?php
/**
 * Created by PhpStorm.
 * User: M&F
 * Date: 2018-07-08
 * Time: 10:14
 */

if(!strstr($_SERVER["HTTP_REFERER"],'2naive.cn')){
    header("HTTP/1.0 404 Not Found");
    header("Location: /404.html");
    echo 'error!';
    return;
}

$start = @$_GET['start']?$_GET['start']:0;
//去豆瓣获取数据
function get_douban_data($start)
{
    $url = 'https://api.douban.com/v2/movie/top250?count=30&start=' . $start.'';
    $ch = curl_init();
    $timeout = 3;
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $contents = curl_exec($ch);
    curl_close($ch);
    return $contents;
}

echo get_douban_data($start);