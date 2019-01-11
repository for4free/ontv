<?php
$type = @$_GET['type'] ? $_GET['type'] : header("Location: /");
$type = base64_decode($type);
include_once 'global_api/global.php';
$name = get_douban_api_name($type);
$item = get_douban_json2item_data($type);
$subjects = $item[1];
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
        <title><?php echo $name ?> - 最新电影榜单</title>
        <meta name="keywords" content="视频资源搜索,视频资源,在线观看,VIP视频,小视频,会员视频,电视剧,视频搜索,最新电视剧,最新电影,OnTV,2naive,简单视频"/>
        <meta name="description" content="简单视频 - 全球最大的在线电影资源搜索观看网站,提供海量、优质、高清的在线视频搜索服务,给您更方便的高清无广告电影电视剧在线观看体验"/>
        <link rel="stylesheet" type="text/css" href="/static/css/index.css">
        <link rel="stylesheet" type="text/css" href="/static/css/main.css">
        <link rel="stylesheet" type="text/css" href="/static/css/search.css">
        <script type="text/javascript" src="/static/js/jquery-1.11.3.min.js"></script>
        <script src="static/js/jquery.bigautocomplete.js"></script>
        <?php
        if (strstr($type, 'top250')) {
            echo '<script type="text/javascript" src="static/js/movie.js?v=1.3.2"></script>';
        }
        ?>

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
        $res_num = $item[0];
        echo '<p class="result"><span>' . $name . '</span> 榜单前 ' . $res_num . ' 条 </p>';
        if ($res_num != 0) {
            echo '<ul>';
            foreach ($subjects as $items) {
                echo '<a href="search.html?wd=' . $items["title"] . '"><li>';
                if ($items["rating"]["average"] != 0) {
                    echo number_format($items["rating"]["average"], 1) . '分 ';
                }
                echo $items["title"] . '</li></a>';
                flush();
            }
            echo '</ul>';
            if (strstr($type, 'top250')) {
                echo '<div class="footer"></div><br><br>';
            }
        } else {
            echo '<p class="comefrom">您可能需要：(1)刷新重试；(2)服务器出错。</p>';
        }
        ?>
    </div>
    <script type="text/javascript" src="/static/js/bd_link.js"></script>
    </body>
    </html>
<?php
flush();
include_once 'global_api/_update_actions.php';
update_user_actions($name, 3, $res_num . ' 条榜单数据');
?>
