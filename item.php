<?php
include_once 'login_limit.php';
$name = @$_GET['name'] ? $_GET['name'] : '2naive.cn';
$name = urldecode($name);
$type = @$_GET['type'] ? $_GET['type'] : 0;
$id = @$_GET['id'] ? $_GET['id'] : header("Location: /");
$json_data = shell_exec('python3 pyshell/item.py ' . $type . ' ' . $id);
include_once 'global_api/global.php';
include_once 'global_api/_search_limit.php';
create_uuid();
?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="initial-scale=1.0, minimum-scale=1.0, maximum-scale=2.0, user-scalable=no, width=device-width">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title><?php echo $name; ?> - 在线视频播放资源</title>
        <meta name="keywords" content="视频资源搜索,视频资源,在线观看,VIP视频,小视频,会员视频,电视剧,视频搜索,最新电视剧,最新电影,OnTV,2naive,简单视频"/>
        <meta name="description" content="简单视频 - 全球最大的在线电影资源搜索观看网站,提供海量、优质、高清的在线视频搜索服务,给您更方便的高清无广告电影电视剧在线观看体验"/>
        <link rel="stylesheet" type="text/css" href="/static/css/index.css">
        <link rel="stylesheet" type="text/css" href="/static/css/main.css">
        <link rel="stylesheet" type="text/css" href="/static/css/item.css">
        <script type="text/javascript" src="/static/js/jquery-1.11.3.min.js"></script>
        <script src="static/js/jquery.bigautocomplete.js"></script>
    </head>
    <body>
    <form class="search_form" action="/search.html">
        <div class="input_div">
            <input class="search_txt" name="wd" type="text" placeholder="搜视频，从此开始..." required="required"
                   autocomplete="off">
        </div>
        <input class="search_btn" type="submit" value="搜 索">
    </form>
    <div class="main_box">
        <?php
        $arr_data = json_decode($json_data, true);
        $info_count = 0;
        foreach ($arr_data as $item) {//播放源个数
            $info_count += count($item["play_info"]);
        }
        echo '<p class="result"><span>' . $name . '</span>共 ' . $info_count . ' 个源</p>';
        if ($info_count == 0) {
            echo '<p class="comefrom">您可能需要：(1)刷新重试;(2)更换播放源</p>';
            flush();
        } else {
            foreach ($arr_data as $item) {
                echo '<p class="comefrom">' . $item["type"] . '</p>';
                echo '<ul>';
                foreach ($item["play_info"] as $play_info) {
                    echo '<a href="play-' . base64_encode($play_info["link"]) . '-' . $type . '-n-' . '' . urlencode($name) . '-v-' . urlencode($play_info["name"]) . '.html" target="_blank"><li>' . $play_info["name"] . '</li></a>';
                    flush();
                }
                echo '</ul>';
            }
        }
        ?>
    </div>
    <script type="text/javascript" src="/static/js/bd_link.js"></script>
    </body>
    </html>
<?php
include_once 'global_api/_update_actions.php';
update_user_actions($name, 0, $info_count.' 个播放源');
?>