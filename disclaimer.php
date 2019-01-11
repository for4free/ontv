<?php
    include_once 'login_limit.php';
    include_once 'global_api/_search_limit.php';
    create_uuid();
?>
<!DOCTYPE html>
<html>
<head>
    <title>免责声明 - 2naive.cn</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/static/css/about.css">
</head>
<body>
<div class="container">
    <img src="/static/imgs/logo.png" width="130px" alt="简单视频">
    <div class="row">
        <div class="col-md-12">
            <h1>免责声明</h1>
            <h3>（1）本站内容来自网友分享，仅供个人学习、开发、测试使用，请勿用于其他用途；</h3>
            <h3>（2）本站是一个免费的视频资源搜索网站，不存在任何盈利模式，视频/网站中出现的广告或属于资源提供方；</h3>
            <h3>（3）本站不提供任何资源存储服务，只提供查询服务，查询数据全部来源于网络，若侵犯了您的权益，请即时发邮件联系处理。</h3>
            </div>
    </div>
</div>
<script src="/static/js/bd_link.js"></script>
</body>
</html>
<?php
    flush();
    include_once 'global_api/_update_actions.php';
    update_user_actions('免责声明', 4, 'disclaimer.html');
?>