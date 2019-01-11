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
include_once 'global.php';
$database_config = get_database_config();
$db = new mysqli($database_config[0], $database_config[1], $database_config[2], $database_config[3], $database_config[4]);
$db->set_charset('utf8');

$sql = 'SELECT ip_ad_city, count( 1 ) AS counts FROM search GROUP BY ip_ad_city ORDER BY counts DESC LIMIT 0 , 300';

$result = $db->query($sql);
$result_json = [];
while ($row = $result->fetch_row()) {
    $sql_city = 'SELECT Longitude,Latitude FROM Area WHERE RegionName="' . $row[0] . '"';
    $result_city = $db->query($sql_city);
    $row_city = mysqli_fetch_row($result_city);
    $json_data = null;
    if (!empty($row_city[0]) and !empty($row_city[1])) {
        $json_data->lng = floatval($row_city[0]);
        $json_data->lat = floatval($row_city[1]);
//        $json_data->name = $row[0];
        $json_data->count = floatval($row[1]);
        array_push($result_json, $json_data);
    }
}
//echo json_encode($result_json);

$result_city->free();
$result->free();
$db->close();
?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no"/>
    <script type="text/javascript" src="https://api.map.baidu.com/api?v=2.0&ak=<?php echo get_bdmap_ak() ?>"></script>
    <script type="text/javascript" src="https://api.map.baidu.com/library/Heatmap/2.0/src/Heatmap_min.js"></script>
    <title>用户分布热力图 - 限内部使用</title>
    <style type="text/css">
        ul, li {
            list-style: none;
            margin: 0;
            padding: 0;
            float: left;
        }

        html {
            height: 100%
        }

        body {
            height: 100%;
            margin: 0px;
            padding: 0px;
            font-family: "微软雅黑";
        }

        #container {
            height: 100%;
            width: 100%;
        }

        #mapTitle {
            z-index: 1000;
            font-size: 25px;
            text-align: center;
            line-height: 40px;
            color: #00a2e0;
            position: fixed;
            width: 100%;
        }

        /*去除百度地图版权*/
        .anchorBL {
            display: none;
        }
    </style>
</head>
<body>
<div id="mapTitle">用户分布热力图</div>
<div id="container"></div>
<script type="text/javascript">
    var map = new BMap.Map("container");          // 创建地图实例

    var point = new BMap.Point(109.418261, 36.921984);
    map.centerAndZoom(point, 5);             // 初始化地图，设置中心点坐标和地图级别
    map.enableScrollWheelZoom(); // 允许滚轮缩放
    map.setMapStyle({style: 'hardedge'});
    var cr = new BMap.CopyrightControl({anchor: BMAP_ANCHOR_TOP_LEFT});   //设置版权控件位置
    map.addControl(cr); //添加版权控件
    var bs = map.getBounds();   //返回地图可视区域
    cr.addCopyright({
        id: 1,
        content: "<a href='/' style='color: #000;text-decoration: none' >简单视频 - 2naive.cn</a>",
        bounds: bs
    });

    //    var points =[
    //        {"lng":116.418261,"lat":39.921984,"count":50},
    //        {"lng":116.423332,"lat":39.916532,"count":51},
    //        {"lng":116.419787,"lat":39.930658,"count":15},
    //        {"lng":116.418455,"lat":39.920921,"count":40},
    //        {"lng":116.418843,"lat":39.915516,"count":100},
    //        {"lng":116.42546,"lat":39.918503,"count":6},]
    var points = <?php echo json_encode($result_json);?>;

    if (!isSupportCanvas()) {
        alert('热力图目前只支持有canvas支持的浏览器,您所使用的浏览器不能使用热力图功能~')
    }
    //详细的参数,可以查看heatmap.js的文档 https://github.com/pa7/heatmap.js/blob/master/README.md
    //参数说明如下:
    /* visible 热力图是否显示,默认为true
     * opacity 热力的透明度,1-100
     * radius 势力图的每个点的半径大小
     * gradient  {JSON} 热力图的渐变区间 . gradient如下所示
     *	{
     .2:'rgb(0, 255, 255)',
     .5:'rgb(0, 110, 255)',
     .8:'rgb(100, 0, 255)'
     }
     其中 key 表示插值的位置, 0~1.
     value 为颜色值.
     */
    heatmapOverlay = new BMapLib.HeatmapOverlay({"radius": 15});
    map.addOverlay(heatmapOverlay);
    heatmapOverlay.setDataSet({data: points, max: 100});
    //是否显示热力图
    heatmapOverlay.show();
    function setGradient() {
        /*格式如下所示:
         {
         0:'rgb(102, 255, 0)',
         .5:'rgb(255, 170, 0)',
         1:'rgb(255, 0, 0)'
         }*/
        var gradient = {};
        var colors = document.querySelectorAll("input[type='color']");
        colors = [].slice.call(colors, 0);
        colors.forEach(function (ele) {
            gradient[ele.getAttribute("data-key")] = ele.value;
        });
        heatmapOverlay.setOptions({"gradient": gradient});
    }
    //判断浏览区是否支持canvas
    function isSupportCanvas() {
        var elem = document.createElement('canvas');
        return !!(elem.getContext && elem.getContext('2d'));
    }
</script>
</body>
</html>