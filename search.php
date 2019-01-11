<?php
include_once 'login_limit.php';
$wd = @$_GET['wd'] ? $_GET['wd'] : header("Location: /");
include_once 'global_api/global.php';
include_once 'global_api/_search_limit.php';
$user_IP = get_client_ip();
create_uuid();
$uuid = $_COOKIE['uuid'] ? $_COOKIE['uuid'] : 0;
?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="initial-scale=1.0, minimum-scale=1.0, maximum-scale=2.0, user-scalable=no, width=device-width">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title><?php echo $wd ?> - 在线视频搜索结果</title>
        <meta name="keywords" content="视频资源搜索,视频资源,在线观看,VIP视频,小视频,会员视频,电视剧,视频搜索,最新电视剧,最新电影,OnTV,2naive,简单视频"/>
        <meta name="description" content="简单视频 - 全球最大的在线电影资源搜索观看网站服务,提供海量、优质、高清的在线视频搜索,给您更方便的高清无广告电影电视剧在线观看体验"/>
        <link rel="stylesheet" type="text/css" href="/static/css/index.css">
        <link rel="stylesheet" type="text/css" href="/static/css/main.css">
        <link rel="stylesheet" type="text/css" href="/static/css/search.css">
        <script type="text/javascript" src="/static/js/jquery-1.11.3.min.js"></script>
        <script src="static/js/jquery.bigautocomplete.js"></script>
    </head>
    <body>
    <form class="search_form" action="/search.html">
        <div class="input_div">
            <input class="search_txt" name="wd" type="text" placeholder="搜视频，从此开始..." required="required"
                   value="<?php echo $wd ?>" autocomplete="off">
        </div>
        <input class="search_btn" type="submit" value="搜 索">
    </form>
    <div class="main_box">
        <?php
        function my_flush()
        {
            ob_flush(); //将数据从php的buffer中释放出来
            flush(); //将释放出来的数据发送给浏览器
        }

        //echo '<input type="hidden" name="'.$wd.'" value="2naive.cn">';
        my_flush();
        function display_no_result($wd, $search_limit)
        {
            echo '<p class="comefrom">';
            if (judge_url($wd)) {//play-(.*?)-(.*?)-n-(.*)-v-(.*)\.html
                echo '您可能需要：<a href="/play-' . base64_encode($wd) . '-0-n-网络视频-v-2naive.cn.html" target="_blank">点击此处直接播放</a>';
            } else {
                if ($search_limit) {
                    echo '注意：您的搜索过于频繁';
                } else {
                    echo '您可能需要：(1)刷新重试;(2)修改关键字';
                }
            }
            echo '</p>';
            my_flush();
        }

        //搜索限制
        $search_limit = limit_ip($user_IP);
        //敏感词过滤
        if (get_word_filter_result($wd) or $search_limit) {
            $json_data = '';
        } else {
            $json_data = shell_exec('python3 pyshell/search.py ' . urlencode(preg_replace("/(\s|\&nbsp\;|　|\xc2\xa0)/", "", $wd)) . ' ' . urlencode($user_IP) . ' ' . $uuid);
        }
        $arr_data = json_decode($json_data, true);
        $total_num = count($arr_data["result"]);
        echo '<p class="result"><span>' . $wd . '</span>共 ' . $total_num . ' 条结果 </p>';
        if ($arr_data["ok"] == true) {
            $display_total = 0;
            echo '<ul>';
            foreach ($arr_data["result"] as $item) {
                if (!get_word_filter_at_search_result($item["version"])) {
                    $name = preg_replace("/<span>|<\/span>|\//i", "", $item["name"]);
                    echo '<a href="item-' . $item["id"] . '-' . $item["come_from"] . '-' . urlencode($name) . '.html"><li>' . $item["name"] . '</li></a>';
                    my_flush();
                    $display_total = 1;
                }
            }
            echo '</ul>';
            if ($display_total == 0) {
                display_no_result($wd, $search_limit);
            }
        } else {
            display_no_result($wd, $search_limit);
        }
        ?>
    </div>
    <script type="text/javascript" src="/static/js/bd_link.js"></script>
    </body>
    </html>
<?php
flush();
include_once 'global_api/_update_actions.php';
update_user_actions($wd, 2, $total_num . ' 条搜索结果');
?>