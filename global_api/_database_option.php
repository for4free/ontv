<meta charset="utf-8">
<?php
/**
 * Created by PhpStorm.
 * User: M&F
 * Date: 2018-07-06
 * Time: 20:39
 */
$start = @$_GET['start']?$_GET['start']:0;

//$db = new mysqli('localhost', 'itoffice', 'itoffice', 'video', 3306);
//$db->set_charset('utf8');

$pri_list = ['北京','天津','上海','重庆','河北','河南','云南','辽宁','黑龙江','湖南','安徽','山东','新疆','江苏','浙江','江西',
'湖北','广西','甘肃','山西','内蒙','陕西','吉林','福建','贵州','广东','青海','西藏','四川','宁夏','海南','台湾','香港','澳门'];

echo '正在更新,'.($start).' - '.($end).'<br>';
//flush();

// 最近50位访问
$sql = 'SELECT id,ip_ad_pri,ip_ad_city FROM search WHERE 1 ORDER BY id LIMIT '.$start.',1000';

$result = $db->query($sql);
//echo substr('山佛那个' , 0 , 9);

while($row = $result->fetch_assoc()){
    $str = $row['ip_ad_pri'];
    if(!empty($str)){
        foreach ($pri_list as $item){
            $pri_name = substr($str , 0 , 9);
            $city_name = substr($row['ip_ad_city'] , 0 , 9);
            $sql_update = 'UPDATE search SET ip_ad_pri="'.$pri_name.'",ip_ad_city="'.$city_name.'" WHERE id='.$row['id'];

            $db->query($sql_update);
            $js = 1;
            break;

        }
        $id = $row['id'];
    }
}
echo $id;
$result->free();
$db->close();

sleep(1);
$url = "/_databse_option.php?start=".($start+1000);
//echo $url;
//header("Location:".$url);
echo '<script type="text/javascript">
  window.location.href="'.$url.'"
</script>';
