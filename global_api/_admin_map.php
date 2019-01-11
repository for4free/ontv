<?php
if (!strstr($_SERVER["HTTP_REFERER"], '2naive.cn')) {
    header("HTTP/1.0 404 Not Found");
    header("Location: /404.html");
    echo 'ACCESS BARRED 1!';
    return;
}
if (!isset($_COOKIE[''])) {
    echo 'ACCESS BARRED 2!';
    return;
}
$ip = @$_GET['ip'] ? @$_GET['ip'] : '';
$ip_flag = 0;
if (empty($ip)) {
    echo 'ACCESS BARRED 3!';
    return;
}
$error_str = '简单视频 - 2naive.cn';
include_once 'global.php';
$ak = get_bdmap_ak();
try {
    require_once("../utils/wx_http_request.php");
    $params = array(
        'ip' => $ip,
        'm' => '0',
        'appkey' => get_jd_ip_loc()
    );
    $url = 'https://way.jd.com/RTBAsia/ip_locationy';
    $json_res = wx_http_request($url, $params);
    $res = json_decode($json_res);
} catch (Exception $e) {
    $res = get_loc($ip, $ak);
    $latitude = $res[0];
    $longitude = $res[1];
    $radius = $res[2];
    $error_str = 'Message: ' . $e->getMessage() . '<br>' . $json_res;
}
if ($res->result->code == '200') {
    $latitude = $res->result->location->latitude;
    $longitude = $res->result->location->longitude;
    $radius = $res->result->location->radius;
    $ip_flag = 1;
} else {
    $res = get_loc($ip, $ak);
    $latitude = $res[0];
    $longitude = $res[1];
    $radius = $res[2];
    $error_str = 'Location api has an error!<br>' . $json_res;
}

// 精准定位失败调用函数
function get_loc($ip, $ak)
{
    $url = 'https://api.map.baidu.com/location/ip?ip=' . $ip . '&ak=' . $ak . '&coor=bd09ll';
    $res = json_decode(file($url)[0], true)['content']['point'];
    $latitude = $res['y'] ? $res['y'] : '';
    $longitude = $res['x'] ? $res['x'] : '';
    $radius = 500;
    return [$latitude, $longitude, $radius];
}

?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no"/>
    <style type="text/css">
        body, html, #allmap {
            width: 100%;
            height: 100%;
            overflow: hidden;
            margin: 0;
            font-family: "微软雅黑";
        }
    </style>
    <script type="text/javascript"
            src="https://api.map.baidu.com/api?v=2.0&ak=<?php echo $ak ?>"></script>
    <title>用户定位 - 限内部使用</title>
    <style type="text/css">
        body, html, #allmap {
            width: 100%;
            height: 100%;
            margin: 0;
        }

        /*去除百度地图版权*/
        .anchorBL {
            display: none;
        }
    </style>
</head>
<body>
<div id="allmap"></div>
<script type="text/javascript">
    // 百度地图API功能
    var map = new BMap.Map("allmap");
    var point = new BMap.Point( <?php echo $longitude . ',' . $latitude;?>);
    map.centerAndZoom(point, 15);

    var circle = new BMap.Circle(point,<?php echo $radius;?>, {
        fillColor: "white",
        strokeWeight: 1,
        fillOpacity: 0.3,
        strokeOpacity: 0.3
    });
    map.addOverlay(circle);

    map.setMapStyle({style: 'grayscale'});
    var marker = new BMap.Marker(point);  // 创建标注
    map.addOverlay(marker);               // 将标注添加到地图中
    marker.setAnimation(BMAP_ANIMATION_BOUNCE); //跳动的动画
    map.enableScrollWheelZoom(true);
    var cr = new BMap.CopyrightControl({anchor: BMAP_ANCHOR_TOP_LEFT});   //设置版权控件位置
    map.addControl(cr); //添加版权控件
    var bs = map.getBounds();   //返回地图可视区域
    cr.addCopyright({id: 1, content: '<?php echo $error_str?>', bounds: bs});
</script>
</body>
</html>
<?php
if ($ip_flag == 1) {
    include_once '_update_actions.php';
    update_ip_2_loc($ip, $latitude, $longitude, $radius);
}

?>
