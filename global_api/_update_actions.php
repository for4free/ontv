<?php
/**
 * Created by PhpStorm.
 * User: M&F
 * Date: 2018-08-19
 * Time: 21:01
 *
 * 用户行为 首页:1  搜索2  详情0  播放是具体版本号  榜单3  关于/免责 4
 */
//用户行为
function update_user_actions($title, $version, $link)
{
    try {
        include_once 'global.php';
        $ip = get_client_ip();
        $uuid = $_COOKIE['uuid']?$_COOKIE['uuid']:'error';
        $database_config = get_database_config();
        $db = new mysqli($database_config[0], $database_config[1], $database_config[2], $database_config[3], $database_config[4]);
        $db->set_charset('utf8');
        $sql = "INSERT INTO actions(title, version, link, ip, uuid, time) VALUES ('" . $title . "','" . $version . "','" . $link . "','" . $ip . "','".$uuid."',now())";
        $result = $db->query($sql);
        $result->free();
        $db->close();
    } catch (Exception $e) {
    }
}

//IP精确定位记录
function update_ip_2_loc($ip, $lat, $lng, $radius)
{
    try {
        include_once 'global.php';
        $database_config = get_database_config();
        $db = new mysqli($database_config[0], $database_config[1], $database_config[2], $database_config[3], $database_config[4]);
        $db->set_charset('utf8');
        $sql = "INSERT INTO ip2loc( ip, lat, lng, radius,createtime) VALUES ('" . $ip . "','" . $lat . "','" . $lng . "','" . $radius . "',now())";
        $result = $db->query($sql);
        $result->free();
        $db->close();
    } catch (Exception $e) {
    }
}