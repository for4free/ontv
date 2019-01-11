<?php
if(isset($_COOKIE[''])){
    setcookie("", "", time()+60*15);
}else{
    $pwd = @$_POST['pwd']?$_POST['pwd']:header("Location: /mm_login.html");
    include_once 'global_api/global.php';
    if($pwd == get_admin_pwd()){
        setcookie("", "", time()+60*30);
        header("Location: /");
    }else{
        header("Location: /mm_login.html");
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="initial-scale=1.0, minimum-scale=1.0, maximum-scale=2.0, user-scalable=no, width=device-width" >
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta content="always" name="referrer">
    <title>综合管理后台</title>
    <link rel="stylesheet" type="text/css" href="static/css/admin.css?v=1.2.1">
    <script type="text/javascript" src="static/js/jquery-1.11.3.min.js"></script>
    <script type="text/javascript" src="static/js/admin_js.js?v=1.0.5"></script>
</head>
<body>
    <p class="main_title">数据查询与系统配置后台</p>
    <div class="main_box">
        <div class="result" style="text-indent: 1%"><span>搜索次数共:</span><span>次,年:</span><span>次,月:</span><span>次,周:</span><span>次</span></div>
        <div class="result" style="margin-top: 1vh;">
            <span id="total" class="span_nav">统计</span>
            <span id="query" class="span_nav">查询</span>
            <span id="config" class="span_nav">配置</span>
                <!-- 统计 -->
                <span id="total_last" class="span_child">最近</span>
                <span id="total_hot" class="span_child">热搜</span>
                <span id="total_key" class="span_child">总榜</span>
                <span id="total_time" class="span_child">时段</span>
                <span id="total_area" class="span_child">区域</span>
                <!-- 查询 -->
                <form class="search_form">
                    <div class="input_div">
                            <input class="search_txt" name="key" type="text" placeholder="数据关键词..." required="required">
                    </div>     
                    <input class="search_btn" type="submit" value="查 询">
                </form>
        </div>
        <div id ="table_div">
            <table>
                <tr>
                    <th>标题一</th>
                    <th>标题二</th>
                </tr>
                <tr>
                    <td>元素1.1</td>
                    <td>元素1.2</td>
                </tr>
            </table>
        </div>
        <div class="config_div">
            <?php
                include_once 'global_api/_admin_write_api.php';
                $api_data = get_api_data();
            ?>
            <p>&nbsp;
                主页公告:<?php
                if($api_data[2][1]==1){
                    echo '<input id="content_switch" name="switch" type="checkbox" value="1" checked="checked">';
                }else{
                    echo '<input id="content_switch" name="switch" type="checkbox" value="1" >';
                }
                ?>&nbsp;&nbsp;&nbsp;&nbsp;
                词汇过滤:<?php
                if($api_data[2][2]==1){
                    echo '<input id="word_filter" name="switch" type="checkbox" value="2" checked="checked">';
                }else{
                    echo '<input id="word_filter" name="switch" type="checkbox" value="2">';
                }
                ?>&nbsp;&nbsp;&nbsp;&nbsp;
                自定义标签:<?php
                if($api_data[2][3]==1){
                    echo '<input id="tags_cloud" name="switch" type="checkbox" value="2" checked="checked">';
                }else{
                    echo '<input id="tags_cloud" name="switch" type="checkbox" value="2">';
                }
                ?>
            </p>
            <p>&nbsp;&nbsp;<a href="/user.php" target="_blank">用户分布图</a>
                &nbsp;&nbsp;<a href="http://123.206.78.18/MyDataBase/" target="_blank">数据库管理</a>
                &nbsp;&nbsp;<a href="http://player.2naive.cn/m-m" target="_blank">HTTP接口</a>
                &nbsp;&nbsp;<a href="/actions.php" target="_blank">深入行为</a>
            <p>
            <p>解析接口<input id="web_api" type="text" class="search_txt" value="<?php echo $api_data[0][1]?>"><p>
            <p>主页公告<textarea id="index_content" class="area_txt"><?php echo $api_data[3][1]?></textarea><p>
            <p>标签推荐<textarea id="tags_cloud_list" class="area_txt"><?php echo $api_data[5][1]?></textarea><p>
            <p>资源站<textarea id="web_url" class="area_txt"><?php echo $api_data[1][1]?></textarea><p>
            <p>敏感词汇<textarea id="word_list" class="area_txt"><?php echo $api_data[4][1]?></textarea><p>
            <button id="sum_data" class="search_btn" style="margin: 1vh 0">提交</button>
        </div>
    </div>
</body>
</html>