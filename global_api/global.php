<?php
/**
 * Created by PhpStorm.
 * User: M&F
 * Date: 2018-07-03
 * Time: 11:22
 */
//数据库用户名 密码
function get_database_config()
{
    return ['localhost', '', '', '', 3306];
}
//redis数据库配置
function get_redis_config(){
    return ['127.0.0.1', 6379, 1, 0];
}
//后台密码
function get_admin_pwd()
{
    return '';
}

//百度地图ak
function get_bdmap_ak()
{
    return '';
}

//获取京东云ip定位ak
function get_jd_ip_loc()
{
    return '';
}

// 视频网站解析接口
function get_vip_url()
{
    $vip_url = 'http://zuida-jiexi.com/zuidazy/index.php?url=';
    return $vip_url;
}

function get_permission()
{
    // 是否显示公告  是否过滤敏感词汇 是否使用自定义标签云
    $permission = [0,0,1];
    return $permission;
}

//公告
function get_notice()
{
    $notice = '公告:今天下午进行网站服务器迁移，可能出现暂时无法使用的现象。';
    return $notice;
}

//过滤词汇
function get_word_filter_list()
{
    $word_lsit = ['波多野结衣', '苍井空', '金瓶梅', 'av', '国产', '妈妈的朋友', '性', '强奸', '乱伦', '丝袜', '91', '偷拍', '巨乳', '人妻'];
    return $word_lsit;
}

//推荐标签
function get_tags_cloud_list()
{
    $tags_cloud_list = [];
    return $tags_cloud_list;
}

//过滤敏感
function get_word_filter_result($str)
{
    if (get_permission()[1] == 1) {
        $key = get_word_filter_list();
        $blacklist = "/" . implode("|", $key) . "/i";
        if (preg_match($blacklist, $str, $matches)) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

//搜索过滤判断 从源头过滤
function get_word_filter_at_search_result($str)
{
    if (get_permission()[1] == 1) {
        if (strstr($str, '伦理')||strstr($str, '福利')) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

/*豆瓣接口 https://developers.douban.com/wiki/?title=movie_v2
URL https://api.douban.com
many_data: 正在上映 in_theaters?city=北京  即将上映 coming_soon  TOP250 top250
us_box: 北美票房 v2/movie/us_box

口碑榜 /v2/movie/weekly
新片榜 /v2/movie/new_movies*/
//通过用户ip获取地点
function get_loc_city()
{
    $user_IP = get_client_ip();
    $city = json_decode(@file_get_contents('http://ip.taobao.com/service/getIpInfo.php?ip=' . $user_IP), true)["data"]["city"];
    if (strcmp('XX', $city) == 0 or strcmp('', $city) == 0) {
        $city = '爆款';
    }
    return $city;
}

//获取导航 //api对应名称
function get_douban_api_nav()
{    //参数说明： [豆瓣api请求字段, 字段队形名称, 用哪个函数获取数据, ]
    return [["in_theaters", "正在上映", 1],
        ["us_box", "北美票房", 0],
        ["coming_soon", "即将上映", 1],
        ["top250?start=0&count=30", "高分电影", 1]];
}

//判断字符串相等
function judge_str($request)
{
    $str = get_douban_api_nav();
    foreach ($str as $item) {
        if (strcmp($item[0], $request) == 0) {
            return $item[2];
        }
    }
}

//分流函数
function get_douban_json2item_data($request)
{

    if (judge_str($request) == 1) {
        return get_many_data($request);
    } else {
        return get_usbox_data($request);
    }
}

//api对应名称
function get_douban_api_name($request)
{
    $name_arry = get_douban_api_nav();
    $name_dict = '';
    foreach ($name_arry as $item) {
        $name_dict[$item[0]] = $item[1];
    }
    return $name_dict[$request];
}

//去豆瓣获取数据
function get_douban_data($request)
{
//    $header = array('CLIENT-IP:58.68.44.61', 'X-FORWARDED-FOR:58.68.44.61',);
    $url = 'https://api.douban.com/v2/movie/' . $request . '';
    $ch = curl_init();
    $timeout = 3;
    curl_setopt($ch, CURLOPT_URL, $url);
//    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $contents = curl_exec($ch);
    curl_close($ch);
    return $contents;
}

//获取 usbox 数据 对应0获取数据
function get_usbox_data($request)
{
    $data = get_douban_data($request);
    $json_data = json_decode($data, true);
    $res_num = count($json_data["subjects"]);
    $item_num = 0;
    foreach ($json_data["subjects"] as $item) {
        $subjects_list[$item_num] = $item["subject"];
        $item_num++;
    }
    return [$res_num, $subjects_list];
}

//获取 其他 数据 对应 1 获取数据
function get_many_data($request)
{
    $data = get_douban_data($request);
    $json_data = json_decode($data, true);
    $res_num = count($json_data["subjects"]);
    $subjects = $json_data["subjects"];
    return [$res_num, $subjects];
}

//判断接口可用
function judge_douban_api_can_use()
{
    if (json_decode(get_douban_data('in_theaters?city=北京'), true)['code']) {
        return true;
    } else {
        return false;
    }
}

//判断视频网站
function judge_url($url)
{
    $vip = ['.iqiyi.com', '.youku.com', '.qq.com', '.sohu.com', '.letv.com', '.mgtv.com', '.le.com', '.tudou.com', '.pptv.com', '.1905.com', '.acfun.cn', '.bilibili.com', '.wasu.cn', '.163.com'];
    foreach ($vip as $item) {
        if (strpos($url, $item)) return true;
    }
    return false;
}

// 获取用户ip
function get_client_ip()
{
    foreach (array(
                 'HTTP_CLIENT_IP',
                 'HTTP_X_FORWARDED_FOR',
                 'HTTP_X_FORWARDED',
                 'HTTP_X_CLUSTER_CLIENT_IP',
                 'HTTP_FORWARDED_FOR',
                 'HTTP_FORWARDED',
                 'REMOTE_ADDR') as $key) {
        if (array_key_exists($key, $_SERVER)) {
            foreach (explode(',', $_SERVER[$key]) as $ip) {
                $ip = trim($ip);
                //会过滤掉保留地址和私有地址段的IP，例如 127.0.0.1会被过滤
                //也可以修改成正则验证IP
                if ((bool)filter_var($ip, FILTER_VALIDATE_IP,
                    FILTER_FLAG_IPV4 |
                    FILTER_FLAG_NO_PRIV_RANGE |
                    FILTER_FLAG_NO_RES_RANGE)
                ) {
                    return $ip;
                }
            }
        }
    }
    return null;
}
