<?php
/**
 * Created by PhpStorm.
 * 目前是通过ip判断，可以考虑加入uuid
 * User: M&F
 * Date: 2018-08-21
 * Time: 16:57
 */

// 生成uuid
function create_uuid(){
    if(!isset($_COOKIE['uuid'])){
        $str = md5(uniqid(mt_rand(), true));
        $uuid  = substr($str,0,8) . '-';
        $uuid .= substr($str,8,4) . '-';
        $uuid .= substr($str,12,4) . '-';
        $uuid .= substr($str,16,4) . '-';
        $uuid .= substr($str,20,12);
        setcookie("uuid", $uuid, time()+60*60*24*366*10);// 10年
    }
}


function limit_ip($ip)
{
    /**
     * 同一个IP 1 s
     * 同一个uuid 3 s
     * 没有uuid 创建
     **/
    try {
        include_once 'global.php';
        $redis = new Redis();

        $redis_config = get_redis_config();
        $redis->connect($redis_config[0], $redis_config[1], $redis_config[2]);
        $redis->select($redis_config[3]);
        $uuid = $_COOKIE['uuid']?$_COOKIE['uuid']: $ip; //如果不存在uuid 则ip限制5秒
        if ($redis->get($ip) or $redis->get($uuid)) {
            return true;
        }
        $redis->set($ip, 1, 1);//redis格式 key,val,timeout ip限制1
        $redis->set($uuid, 1, 5);//uuid 限制5秒
        return false;
    } catch (Exception $e) {
        return true;
    }
}

