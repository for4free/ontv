<?php
if (!strstr($_SERVER["HTTP_REFERER"], '')) {
    header("HTTP/1.0 404 Not Found");
    header("Location: /404.html");
    return;
}
if (!isset($_COOKIE[''])) {
    echo 'ACCESS BARRED 2!';
    return;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="initial-scale=1.0, minimum-scale=1.0, maximum-scale=2.0, user-scalable=no, width=device-width">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>最近行为 - 综合管理后台</title>
    <link rel="stylesheet" type="text/css" href="/static/css/admin.css?v=1.2.1">
</head>
<body>

<div id="table_div">
    <table style="margin: 5px 0px">
        <tr>
            <th>内容</th>
            <th>详情</th>
<!--            <th>标识</th>-->
        </tr>
<?php
    include_once 'global.php';
    $ip = @$_GET['ip']?$_GET['ip']:'1';
    $uuid = @$_GET['uuid']?$_GET['uuid']:'1';
    $type = @$_GET['type']?$_GET['type']:'1';
    $database_config = get_database_config();
    $db = new mysqli($database_config[0], $database_config[1], $database_config[2], $database_config[3], $database_config[4]);
    $db->set_charset('utf8');
    echo '<div class="config_div" style="display: block">';
    if($ip==1 and $uuid==1){
        if($type==1){
            echo '<p>&nbsp;&nbsp;&nbsp;&nbsp;最近访问用户深度行为&nbsp;&nbsp;<a href="?type=2">少量页面</a></p>';
            $sql = 'SELECT * FROM actions ORDER BY id DESC LIMIT 0,250';
        }else{
            echo '<p>&nbsp;&nbsp;&nbsp;&nbsp;最近访问用户深度行为&nbsp;&nbsp;<a href="?ip=1">汇总</a></p>';

            $sql = 'SELECT * FROM actions WHERE version=3 OR version=4 ORDER BY id DESC LIMIT 0,250';
        }

    }else{
        if($uuid==1){
            echo '<p>&nbsp;&nbsp;&nbsp;&nbsp;关于（'.$ip.'）<a href="?ip=1">汇总</a></p>';
            $sql = 'SELECT * FROM actions WHERE ip="'.$ip.'" ORDER BY id DESC LIMIT 0,250';
        }else{
            echo '<p>&nbsp;&nbsp;&nbsp;&nbsp;关于（'.$uuid.'）<a href="?ip=1">汇总</a></p>';
            $sql = 'SELECT * FROM actions WHERE uuid="'.$uuid.'" ORDER BY id DESC LIMIT 0,250';
        }
    }
    echo '</div>';
    $result = $db->query($sql);
    $index = 0;
    $count = 0;
    while($row = $result->fetch_assoc()){
        if($index==0){
            $count = $row['id'];
        }
        $index=1;
        if($ip==1 and $uuid==1){
            $_ip = $row['ip'];
            $tip = '编号：'.$row['id'].'\n时间：'.$row['time'].'\n来源：'.$_ip.'\n标识：'.$row['uuid'].'\n是否查看该IP访问信息?';//<td>'.str_replace(".","",substr($row['ip'],0,5)).'</td>
            $tip_url = '?ip='.$_ip;
        }else{
            if($uuid==1){
                $_uuid = $row['uuid'];
                $tip = '编号：'.$row['id'].'\n时间：'.$row['time'].'\n来源：'.$ip.'\n标识：'.$_uuid.'\n是否查看该UUID访问信息?';//<td>'.str_replace(".","",substr($row['ip'],0,5)).'</td>
                $tip_url = '?uuid='.$_uuid;
            }else{
                $_ip = $row['ip'];
                $tip = '编号：'.$row['id'].'\n时间：'.$row['time'].'\n来源：'.$_ip.'\n标识：'.$row['uuid'].'\n是否查看该IP访问信息?';//<td>'.str_replace(".","",substr($row['ip'],0,5)).'</td>
                $tip_url = '?ip='.$_ip;
            }
        }

        echo '<tr><td onclick="if(confirm(\''.$tip.'\')){window.location.href=\''.$tip_url.'\'}">'.$row['title'].' - '.$row['version'].'</td><td>'.$row['link'].'</td></tr>';
    }
    $result->free();
    $db->close();
?>
    </table>
</div>
</body>
</html>


