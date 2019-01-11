<?php
    include_once 'login_limit.php';
    include_once 'global_api/_search_limit.php';
    create_uuid();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1.0, minimum-scale=1.0, maximum-scale=2.0, user-scalable=no, width=device-width" >
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>简单视频 - 在线视频资源搜索</title>
    <meta name="keywords" content="视频资源搜索,视频资源,在线观看,VIP视频,小视频,会员视频,电视剧,视频搜索,最新电视剧,最新电影,OnTV,2naive,简单视频" />
    <meta name="description" content="简单视频 - 全球最大的在线电影资源搜索观看网站,提供海量、优质、高清的在线视频搜索服务,给您更方便的高清无广告电影电视剧在线观看体验" />
    <link rel="stylesheet" type="text/css" href="/static/css/index.css">
    <script type="text/javascript" src="/static/js/jquery-1.11.3.min.js"></script>
    <script src="static/js/jquery.bigautocomplete.js"></script>
    <script type="text/javascript" src="/static/js/index.js?v=1.0.5"></script>
</head>
<body>
    <div class="main_logo"><img src="/static/imgs/logo.png" alt="简单视频"></div>
    <form class="search_form" action="/search.html">
        <div class="input_div">
                <input class="search_txt" name="wd" type="text" placeholder="搜视频，从此开始..." required="required" autocomplete="off">
        </div>     
        <input class="search_btn" type="submit" value="搜 索">
    </form>
    <div id="navdiv">
        <ul>
        <?php
            include_once 'global_api/global.php';
            $nav_list = get_douban_api_nav();
            foreach ($nav_list as $item){
                echo '<li><a href=" movie-'.base64_encode($item[0]).'.html">'.$item[1].'</a></li>';
            }
        ?>
        </ul>
    </div>
    <p class="content"></p>
    <div id="tagscloud"></div>
    <p class="footer">
        <a href="about.html" target="_blank">关于本站</a> - <a href="disclaimer.html" target="_blank">免责声明</a> - <a target="_blank" href="http://mail.qq.com/cgi-bin/qm_share?t=qm_mailme&email=DWBNZmk-ZSNuYmA" style="text-decoration:none;">意见反馈</a> - <a href="http://tb.2naive.cn/?fr=2naive.cn" target="_blank"><?php echo get_loc_city();?>秒杀</a>
    </p>
    <script type="text/javascript" src="/static/js/bd_link.js"></script>
</body>
</html>
<?php
    flush();
    include_once 'global_api/_update_actions.php';
    update_user_actions('访问首页', 1, 'index.html');
?>