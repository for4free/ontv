<?php
include_once 'login_limit.php';
$name = @$_GET['name'] ? $_GET['name'] : '2naive.cn';
$name = urldecode($name);
$version = @$_GET['version'] ? $_GET['version'] : '简单搜索';
$version = urldecode($version);
$play_url = @$_GET['id'] ? $_GET['id'] : header("Location: /");
$type = @$_GET['type'] ? $_GET['type'] : 0;
include_once 'global_api/global.php';
include_once 'global_api/_search_limit.php';
create_uuid();
$play_url = base64_decode(str_replace(" ", "+", $play_url));
$video_ck_flag = strstr($play_url, ".mp4") || strstr($play_url, ".flv") || strstr($play_url, ".m3u8");
$video_ck_flag_m3u8 = strstr($play_url, ".m3u8");
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
        <link rel="stylesheet" type="text/css" href="/static/css/main.css?v=1.2">
        <script type="text/javascript" src="/static/js/jquery-1.11.3.min.js"></script>
        <script src="static/js/jquery.bigautocomplete.js"></script>
        <?php
        if ($video_ck_flag) {
            if ($video_ck_flag_m3u8) {
                echo '<link rel="stylesheet" href="/Dplayer/css/DPlayer.min.css?v=1.0.3">
                        <script src="/Dplayer/js/hls.min.js?v=1.0.3"></script>
                        <script src="/Dplayer/js/DPlayer.min.js?v=1.0.7"></script>';
            } else {
                echo '<script type="text/javascript" src="/player/ckplayer/ckplayer.js?v=1.1"></script>';
            }
        }
        ?>


    </head>
    <body>
    <form class="search_form" action="/search.html" target="_blank">
        <div class="input_div">
            <input class="search_txt" name="wd" type="text" placeholder="搜视频，从此开始..." required="required"
                   autocomplete="off">
        </div>
        <input class="search_btn" type="submit" value="搜 索">
    </form>
    <div class="main_box">
        <p class="result"><span><?php echo $version . ' - ' . $name ?></span>在线播放</p>
        <?php
        $judge_url_flag = judge_url($play_url);
        if ($judge_url_flag && (!$video_ck_flag)) {
            $url_header = get_vip_url();
            echo '<p class="comefrom"><a href="http://player.2naive.cn/play-' . base64_encode($play_url) . '-' . $type . '-n-' . $name . '-v-' . $version . '.html">如果不能正常播放请点击</a></p>';
        }
        ?>
        <div class="playframe">
            <?php
            if ($video_ck_flag) {
                if ($video_ck_flag_m3u8) {
                    echo '<div id="dplayer" align="center" style="width: 100%;height: 100%"></div>';
                    echo '<script type="text/javascript">
                            const dp = new DPlayer({
                                container: document.getElementById("dplayer"),
                                autoplay: true,
                                preload: "auto",
                                logo: "/static/imgs/logo.png",
                                mutex: true,
                                video: {
                                    url: "' . $play_url . '",
                                    type: "hls",
                                    pic:"/player/material/poster.jpg",
                                },
                                    contextmenu: [
                                    {
                                        text: "简单视频-2naive.cn",
                                        link: "https://2naive.cn"
                                    },
                                ],
                            });
                           </script>';
                } else {
                    echo "<div class=\"video\" align='center' style=\"position:absolute;width:100%;height:100%;top:0;bottom:0;background:#000\"></div>";
                    echo "<script type=\"text/javascript\">
                            var videoObject = {
                                container: '.video',
                                variable: 'player',
                                autoplay: true,
                                poster: '/player/material/poster.jpg',
                                video:'$play_url'
                            };
                            var player=new ckplayer(videoObject);
                            </script>";
                }
            } else if ($judge_url_flag) {
                $url_header = get_vip_url();
                echo '<iframe src="' . $url_header . $play_url . '" frameborder="0"  width="80vw" height="45vw" scrolling="no"></iframe>';
            } else {
                //简单判断是否为https网址
                $is_url = strstr($play_url, 'https');
                if ($is_url) {
                    echo '<iframe src="' . $play_url . '" frameborder="0"  width="80vw" height="45vw" scrolling="no"></iframe>';
                } else {
                    $is_url_is_http = strstr($play_url, 'http');
                    if ($is_url_is_http) {//防止混合载入,如果为http跳转到player.2naive.cn播放
                        header('Location: http://player.2naive.cn/play-' . base64_encode($play_url) . '-' . $type . '-n-' . $name . '-v-' . $version . '.html');
                    } else {
                        echo '<div class="tip">内容缺失,正在处理</div>';
                    }
                }
            }
            ?>
        </div>
    </div>
    <script type="text/javascript" src="/static/js/bd_link.js"></script>
    </body>
    </html>
<?php
include_once 'global_api/_update_actions.php';
update_user_actions($name, $version, $play_url);
?>