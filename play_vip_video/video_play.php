<?php
$name = @$_GET['name'] ? $_GET['name'] : '2naive.cn';
$name = urldecode($name);
$version = @$_GET['version'] ? $_GET['version'] : '简单视频';
$version = urldecode($version);
$play_url = @$_GET['id'] ? $_GET['id'] : header("Location: https://www.k2tv.cn");
$type = @$_GET['type'] ? $_GET['type'] : 0;
$play_url = base64_decode(str_replace(" ", "+", $play_url));
include_once 'global.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="initial-scale=1.0, minimum-scale=1.0, maximum-scale=2.0, user-scalable=no, width=device-width">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo $name . '-' . $version ?> - 在线视频播放</title>
    <meta name="keywords" content="视频资源搜索,视频资源,在线观看,VIP视频,小视频,会员视频,电视剧,视频搜索,最新电视剧,最新电影,OnTV,2naive,简单视频"/>
    <meta name="description" content="简单视频 - 全球最大的在线电影资源搜索观看网站,提供海量、优质、高清的在线视频搜索服务,给您更方便的高清无广告电影电视剧在线观看体验"/>
    <link rel="stylesheet" type="text/css" href="/static/css/index.css">
    <link rel="stylesheet" type="text/css" href="/static/css/main.css?v=1.1">
    <script type="text/javascript" src="/static/js/jquery-1.11.3.min.js"></script>
</head>
<body>
<form class="search_form" action="https://www.k2tv.cn/search.html" target="_blank">
    <div class="input_div">
        <input class="search_txt" name="wd" type="text" placeholder="搜视频，从此开始..." required="required"
               autocomplete="off">
    </div>
    <input class="search_btn" type="submit" value="搜 索">
</form>
<div class="main_box">
    <p class="result"><span><?php echo $version . ' - ' . $name ?></span>在线播放</p>
        <?php
        flush();//刷新缓存
        $is_vip_url = judge_url($play_url);
        if (isMobile() and $is_vip_url) {
            echo '<div class="playframe" style="height: 100vh;">';
            echo '<iframe src="/url-is-' . $play_url . '" frameborder="0"  width="100%" height="100%" scrolling="no"></iframe>';
        } elseif ($is_vip_url) {
            echo '<div class="playframe">';
            echo '<iframe src="/url-is-' . $play_url . '" frameborder="0"  width="80vw" height="45vw" scrolling="no"></iframe>';
        } else {
            echo '<div class="playframe">';
            echo '<iframe src="' . $play_url . '" frameborder="0"  width="100%" height="100%" scrolling="no"></iframe>';
        }
        ?>
    </div>
</div>
<script type="text/javascript" src="/static/js/bd_link.js"></script>
</body>
</html>