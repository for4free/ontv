<?php
    include_once 'global_api/_search_limit.php';
    create_uuid();
?>
<!DOCTYPE html>
<html>
<head>
    <title>关于我们 - 2naive.cn</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="/static/css/about.css">
  </head>
<body>
<div class="container">
    <img src="/static/imgs/logo.png" width="130px" alt="简单视频">
    <div class="row">
        <div class="col-md-12">
            <h1>关 于 本 站</h1>
            <p class="lead" style="text-align: left;">导航：<a href="/">首页</a> <a href="http://tb.2naive.cn/?fr=2naive.cn">优惠券</a>
                <a href="https://blog.2naive.cn/?fr=2naive.cn">博客</a> <a href="http://www.diidaa.cn/?fr=2naive.cn">嘀嗒资讯</a> <a href="http://trip.2naive.cn/?fr=2naive.cn">足迹</a></p>

            <h3>本为追剧写的网站，2017年05月上线，经过一年的更新迭代，功能的上线、下线，目前功能仅保留在线观看视频资源、淘宝优惠券、解析各网站会员视频（输入原视频网址搜索即可）等功能</h3>
            <div id="h5_free">
                <b>沿革（2017 - ）：</b><br>
                2017年05月 视频/电视台搜索功能<br>
                2017年06月 输入网址看各网站VIP视频功能<br>
                2017年06月 关闭电视台搜索功能<br>
                2017年07月 开放电视台(全球1600+)搜索<br>
                2017年07月 开放电视台选台列表<br>
                2017年07月 启用kd2h.com,保留2naive.cn<br>
                2017年07月 开放百度网盘视频链接播放<br>
                2017年08月 重新使用腾讯云服务器<br>
                2017年08月 关闭百度网盘视频链接播放<br>
                2017年08月 优化m3u8格式视频在线播放<br>
                2017年09月 关闭电视台功能<br>
                2017年11月 修复信息采集BUG<br>
                2017年11月 开放优惠券网站<br>
                2017年11月 开放子站电视台(部分)功能<br>
                2017年11月 增加全网音乐功能<br>
                2017年12月 优化主站界面<br>
                2017年12月 解决部分视频不能播放的BUG<br>
                2017年12月 累计提供超过 1W 次搜索服务<br>
                2018年01月 音乐更名为"米听(MeTing)"<br>
                2018年01月 修复"米听(MeTing)"部分BUG<br>
                2018年01月 增加传统视频网站模式<br>
                2018年01月 修复网站中部分已知BUG<br>
                2018年03月 优化部分页面<br>
                2018年03月 暂停电视台维护<br>
                2018年03月 修复网站中部分已知BUG<br>
                2018年04月 增加ss免费账号分享<br>
                2018年04月 修复网站中部分已知BUG<br>
                2018年04月 主站加入畅言评论系统<br>
                2018年05月 整站暂停维护,恢复时间待定<br>
                2018年07月 网站重构完成<br>
                2018年07月 优化界面并解决已知bug<br>
                2018年07月 网站服务器迁移<br>
                2018年07月 部署https服务<br>
                2018年07月 旧版停用<br>
                2018年07月 暂停子站的维护<br>
                2018年08月 终止ss账号分享服务<br>
                2018年08月 修复已知BUG<br>
                2018年09月 修复搜索相关BUG<br>
                2018年09月 优化部分功能<br>
                2018年10月 优化播放器并增加速度调整功能<br>
                2018年10月 开始新一次的重构（内核）<br>
                2018年11月 累计提供超过 10W 次搜索服务<br>
            </div><br>
            <p class="lead" id ="lead_free"><a target="_blank" href="http://mail.qq.com/cgi-bin/qm_share?t=qm_mailme&email=DWBNZmk-ZSNuYmA" style="text-decoration:none;">意见建议/问题反馈</a></p>
            <h5 id="h5_free">免责声明：（1）本站内容来自网友分享，仅供个人学习、开发、测试使用，请勿用于其他用途。<a href="./disclaimer.html" style="color: #000">查看更多内容</a> </h5>
            <h5>©2018 2naive.cn 京ICP备15007046号-3</h5>
        </div>
    </div>
</div>

<script src="/static/js/bd_link.js"></script>

</body>
</html>
<?php
    flush();
    include_once 'global_api/_update_actions.php';
    update_user_actions('关于我们', 4, 'about.html');
?>