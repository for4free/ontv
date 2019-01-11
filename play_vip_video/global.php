<?php
/**
 * Created by PhpStorm.
 * User: M&F
 * Date: 2018-08-21
 * Time: 21:51
 */
//vip解析接口字典
function get_vip_api()
{
    // 前端会默认选中第一个
    $vip_api=["https://jx.okokjx.com/okokjiexi/jiexi.php?url=","https://api.52mss.com/?url=","http://www.1717yun.com/jx/ty.php?url=", "http://www.3aym.cn/?url=", "http://jiexi.071811.cc/jx2.php?url=", ];
    return $vip_api;
}

//判断vip解析网址
function judge_url($url)
{
    $vip = ['.iqiyi.com', '.youku.com', '.qq.com', '.sohu.com', '.letv.com', '.mgtv.com', '.le.com', '.tudou.com', '.pptv.com', '.1905.com', '.acfun.cn', '.bilibili.com', '.wasu.cn', '.163.com'];
    foreach ($vip as $item) {
        if (strpos($url, $item)) return true;
    }
    return false;
}

//判断手机
function isMobile()
{
    // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
    if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
        return true;
    }
    // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
    if (isset($_SERVER['HTTP_VIA'])) {
        // 找不到为flase,否则为true
        return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
    }
    // 脑残法，判断手机发送的客户端标志,兼容性有待提高。其中'MicroMessenger'是电脑微信
    if (isset($_SERVER['HTTP_USER_AGENT'])) {
        $clientkeywords = array('nokia', 'sony', 'ericsson', 'mot', 'samsung', 'htc', 'sgh', 'lg', 'sharp', 'sie-', 'philips', 'panasonic', 'alcatel', 'lenovo', 'iphone', 'ipod', 'blackberry', 'meizu', 'android', 'netfront', 'symbian', 'ucweb', 'windowsce', 'palm', 'operamini', 'operamobi', 'openwave', 'nexusone', 'cldc', 'midp', 'wap', 'mobile', 'MicroMessenger');
        // 从HTTP_USER_AGENT中查找手机浏览器的关键字
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
            return true;
        }
    }
    // 协议法，因为有可能不准确，放到最后判断
    if (isset ($_SERVER['HTTP_ACCEPT'])) {
        // 如果只支持wml并且不支持html那一定是移动设备
        // 如果支持wml和html但是wml在html之前则是移动设备
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
            return true;
        }
    }
    return false;
}



