<?php
    header('Content-Type: text/xml');
    echo '<?xml version="1.0" encoding="utf-8"?><urlset><url><loc>https://2naive.cn/</loc></url>';
    include_once 'global_api/global.php';
    $database_config = get_database_config();
    $db = new mysqli($database_config[0], $database_config[1],$database_config[2], $database_config[3], $database_config[4]);
    $db->set_charset('utf8');
    $sql = 'SELECT content,date FROM search ORDER BY id DESC LIMIT 0 , 1000';
    $result = $db->query($sql);
    $list = [];

    function check_url($url){
        if(!preg_match('/http[s]*:\/\/.+/is',$url)){
            return true;
        }
        return false;
    }
    while($row = $result->fetch_row()){
        $title = $row[0];
        if(!in_array($title,$list) and check_url($title)){
            echo '<url><loc>https://2naive.cn/search.html?wd='.$title.'</loc><lastmod>'.$row[1].'</lastmod></url>';
            flush();
            array_push($list,$title);
        }
    }
    echo '</urlset>';
    $result->free();
    $db->close();
