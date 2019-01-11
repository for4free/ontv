<?php
/**
 * Created by PhpStorm.
 * User: M&F
 * Date: 2018-07-06
 * Time: 20:39
 * create view v as select * from table
 * SELECT * FROM 表名 where DATE_SUB(CURDATE(), INTERVAL 30 DAY) <= date(时间字段名)
 * create view v_lastdata as SELECT content FROM search where DATE_SUB(CURDATE(), INTERVAL 30 DAY) <= date
 */

if(!isset($_COOKIE[''])){
    return;
}

$type = @$_GET['type']?@$_GET['type']:0;
include_once 'global.php';
$database_config = get_database_config();
$db = new mysqli($database_config[0], $database_config[1],$database_config[2], $database_config[3], $database_config[4]);
$db->set_charset('utf8');

if($type == 1){
    // 最近50位访问 ok
    $sql = 'SELECT content,ip,ip_ad,date FROM search ORDER BY id DESC LIMIT 0,50';
}elseif($type == 2){
    //热搜
    $sql = 'SELECT content, count( 1 ) AS counts FROM v_lastdata GROUP BY content ORDER BY counts DESC LIMIT 0 , 50';
}elseif($type == 3){
    //关键字排行 ok
    $sql = 'SELECT content, count( 1 ) AS counts FROM search GROUP BY content ORDER BY counts DESC LIMIT 0 , 50';
}elseif($type == 4){
    //时段 ok
    $sql = 'SELECT date_time, count( 1 ) AS counts FROM search GROUP BY date_time ORDER BY date_time LIMIT 0 , 50';
}elseif($type == 5){
    //区域 排行 按省排行 ok
    $sql = 'SELECT ip_ad_pri, count( 1 ) AS counts FROM search GROUP BY ip_ad_pri ORDER BY counts DESC LIMIT 0 , 50';
}elseif($type == 6){
    //关键词搜索 ok
    $key = @$_GET['key']?@$_GET['key']:0;
    $sql = 'SELECT * FROM search WHERE concat(content,ip,ip_ad,date) LIKE "%'.$key.'%"  order by id desc limit 0,50';
}elseif($type == 7){
    //总搜索次数  本年次数 本月次数  本周次数 ok
    $sql = 'SELECT v_total_date.counts as total,v_year_date.counts as year,v_month_date.counts as month,v_week_date.counts as week FROM v_total_date, v_year_date, v_month_date, v_week_date';
}
else{
    echo 'need type';
}
$result = $db->query($sql);
$result_json = [];
while($row = $result->fetch_assoc()){
    array_push($result_json,$row);
}
echo json_encode($result_json);



$result->free();
$db->close();
