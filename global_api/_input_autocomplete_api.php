<?php
/**
 * Created by PhpStorm.
 * User: M&F
 * Date: 2018-07-10
 * Time: 19:45
 */
if(!strstr($_SERVER["HTTP_REFERER"],'2naive.cn')){
    header("HTTP/1.0 404 Not Found");
    header("Location: /404.html");
    echo 'error!';
    return;
}

$keywords = $_POST['keyword'];


if(isset($keywords)){
    include_once 'global.php';
    if(!get_word_filter_result($keywords)){
        $database_config = get_database_config();
        $db = new mysqli($database_config[0], $database_config[1],$database_config[2], $database_config[3], $database_config[4]);
        $db->set_charset('utf8');
        $sql = 'SELECT content,count(1) as counts FROM search WHERE content like "%'.$keywords.'%" group by content  order by counts desc limit 0,5';
        $result = $db->query($sql);
        while($row = $result->fetch_assoc()){
            $link = $row['content'];
            if(!strstr($link,'http')){ //  自动补全去掉网址
                $suggestions[]= array('title' => $link);
            }
        }
        mysqli_free_result($result);
        mysqli_close($db);
        echo json_encode(array('data' => $suggestions));
    }
}