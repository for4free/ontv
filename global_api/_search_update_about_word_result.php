<?php
/**
 * Created by PhpStorm.
 * User: M&F
 * Date: 2018-07-09
 * Time: 18:13
 */
    if(!strstr($_SERVER["HTTP_REFERER"],'2naive.cn')){
        header("HTTP/1.0 404 Not Found");
        header("Location: /404.html");
        echo 'error!';
        return;
    }

    $keywords = @$_POST['keyword']?$_POST['keyword']:'';
    if(isset($keywords)){
        include_once 'global.php';
        $ak = '';
        $ip = get_client_ip();
        $url_ip = 'http://api.map.baidu.com/location/ip?ak='.$ak.'&coor=bd09ll&ip='.$ip;
        $address = json_decode(@file_get_contents($url_ip), true);
        $addr_city = $address['content']['address_detail']['city'];
        $addr_pri = $address['content']['address_detail']['province'];
        $addr = $address['address'];
        $database_config = get_database_config();
        $db = new mysqli($database_config[0], $database_config[1],$database_config[2], $database_config[3], $database_config[4]);
        $db->set_charset('utf8');
        $time = time();
        $sql = 'INSERT INTO search (content,ip,ip_ad,ip_ad_pri,ip_ad_city,date,date_time) VALUES ("'.$keywords.'","'.$ip.'","'.$addr.'","'.$addr_pri.'","'.$addr_city.'","'.date('Y-m-d H:i:s',$time).'","'.date('H',$time).'")';
        $result = $db->query($sql);
        mysqli_free_result($result);
        mysqli_close($db);
    }



