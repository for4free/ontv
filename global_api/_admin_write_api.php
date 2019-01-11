<?php
/**
 * Created by PhpStorm.
 * User: M&F
 * Date: 2018-07-07
 * Time: 20:23
 */

if(!isset($_COOKIE[''])){
    return; 
}

$web_api = @$_GET['api']?$_GET['api']:0;
$web_url = @$_GET['url']?@$_GET['url']:0;
$get_switch = @$_GET['switch']?$_GET['switch']:0;
$get_content = @$_GET['content']?@$_GET['content']:0;
$get_word = @$_GET['word']?@$_GET['word']:0;
$get_tags = @$_GET['tags_cloud']?@$_GET['tags_cloud']:0;

if(!empty($web_api) || !empty($web_url)|| !empty($get_content)|| !empty($get_switch)){
    //TODO:写数据 解析地址
    $phpfile = fopen(dirname(__FILE__) ."/global.php", "r") or die("Unable to open file in 1!");
    $phpdata =  fread($phpfile,filesize(dirname(__FILE__) ."/global.php"));
    fclose($phpfile);
    $phpfile = fopen(dirname(__FILE__) ."/global.php", "w") or die("Unable to open file in 2!");
    $regx = "/vip_url = '.*?';/";
    $new_api = "vip_url = '".$web_api."';";
    $phpdata = preg_replace($regx,$new_api,$phpdata);;

    //TODO:写数据 开关量
    $regx = "/permission = \[\d,\d,\d\];/";
    $new_api = "permission = [".$get_switch."];";
    $phpdata = preg_replace($regx,$new_api,$phpdata);

    //TODO:写数据 敏感词汇
    $regx = "/word_lsit = \[[\S\s]*?\];/";
    $new_api = "word_lsit = [".$get_word."];";
    $phpdata = preg_replace($regx,$new_api,$phpdata);

    //TODO:写数据 标签云
    $regx = "/tags_cloud_list = \[[\S\s]*?\];/";
    $new_api = "tags_cloud_list = [".$get_tags."];";
    $phpdata = preg_replace($regx,$new_api,$phpdata);

    //TODO:写数据 主页公告
    $regx = "/notice = '.*?';/";
    $new_api = "notice = '".$get_content."';";
    $phpdata = preg_replace($regx,$new_api,$phpdata);
    fwrite($phpfile, $phpdata);
    fclose($phpfile);

    //TODO:写数据 资源站地址
    $pyfile = fopen(dirname(__FILE__) ."/../pyshell/global_data.py", "r") or die("Unable to open file in 3!");
    $pydata =  fread($pyfile,filesize(dirname(__FILE__) ."/../pyshell/global_data.py"));
    fclose($pyfile);
    $pyfile = fopen(dirname(__FILE__) ."/../pyshell/global_data.py", "w") or die("Unable to open file in 4!");
    $regx_ = "/__url = \[[\S\s]*?\]/";
    $new_api_ = "__url = [".$web_url."]";
    $pydata = preg_replace($regx_,$new_api_,$pydata);
    fwrite($pyfile, $pydata);
    fclose($pyfile);

    echo json_encode(get_api_data());
}

function get_api_data(){
    //解析地址
    $phpfile = fopen(dirname(__FILE__) ."/global.php", "r") or die("Unable to open file  in 5!");
    $phpdata =  fread($phpfile,filesize(dirname(__FILE__) ."/global.php"));
    $regx = "/vip_url = '(.*?)';/";
    preg_match($regx,$phpdata,$php_echo_data);
    //开关
    $regx_switch = "/permission = \[(\d),(\d),(\d)\];/";
    preg_match($regx_switch,$phpdata,$php_echo_data_switch);
    //主页公告
    $regx_content = "/notice = '(.*?)';/";
    preg_match($regx_content,$phpdata,$php_echo_data_content);
    //敏感词汇
    $regx_word = "/word_lsit = \[([\S\s]*?)\];/";
    preg_match($regx_word,$phpdata,$php_echo_data_word);
    //标签云
    $regx_word = "/tags_cloud_list = \[([\S\s]*?)\];/";
    preg_match($regx_word,$phpdata,$php_echo_data_tags);

    //资源站地址
    $pyfile = fopen(dirname(__FILE__) ."/../pyshell/global_data.py", "r") or die("Unable to open file  in 6");
    $pydata =  fread($pyfile,filesize(dirname(__FILE__) ."/../pyshell/global_data.py"));
    $regxpy = "/__url = \[([\S\s]*?)\]/";
    preg_match($regxpy,$pydata,$py_echo_data);
    fclose($phpfile);
    fclose($pyfile);

    return [$php_echo_data,$py_echo_data,$php_echo_data_switch,$php_echo_data_content,$php_echo_data_word,$php_echo_data_tags];
}