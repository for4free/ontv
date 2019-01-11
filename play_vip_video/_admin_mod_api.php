<?php
//if (!strstr($_SERVER["HTTP_REFERER"], '2naive.cn')) {
//    header("HTTP/1.0 404 Not Found");
//    header("Location: http://player.2naive.cn/404.html");
//    return;
//}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="initial-scale=1.0, minimum-scale=1.0, maximum-scale=2.0, user-scalable=no, width=device-width">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>vip接口修改 - 综合管理后台</title>
    <link rel="stylesheet" type="text/css" href="/static/css/admin.css?v=1.2.1">
</head>
<body>
<div class="config_div" style="display: block;">
    <?php
    /**
     * Created by PhpStorm.
     * User: M&F
     * Date: 2018-08-22
     * Time: 19:06
     */
    $mod_data = @$_POST['mod_data'] ? $_POST['mod_data'] : 1;

    $phpfile = fopen(dirname(__FILE__) . "/global.php", "r") or die("Unable to open file!");
    $phpdata = fread($phpfile, filesize(dirname(__FILE__) . "/global.php"));
    $regx = "/vip_api=\[([\S\s]*?)\];/";
    preg_match($regx, $phpdata, $php_echo_data);
    fclose($phpfile);
    echo '<div class="config_div" style="display: block">
        <p>&nbsp;&nbsp;&nbsp;&nbsp;辅站VIP视频解析接口</p>
        </div>';

    echo '<form method="post">
            <textarea name="mod_data" id="index_content" class="area_txt" style="width: 95%;margin: 10px 10px">' . $php_echo_data[1] . '</textarea>
            <input type="submit" class="search_btn" value="提交" style="margin-right:5%">
            </form>';
    //TODO:写数据 解析地址
    function mod_api($phpdata, $mod_data)
    {
        $phpfile = fopen(dirname(__FILE__) . "/global.php", "w") or die("Unable to open file in 2!");
        $regx = "/vip_api=\[[\S\s]*?\];/";
        $new_api = "vip_api=[".$mod_data."];";
        $phpdata = preg_replace($regx, $new_api, $phpdata);;
        fwrite($phpfile, $phpdata);
        fclose($phpfile);
        header("Location: http://player.2naive.cn/_admin_mod_api.php");
    }

    if ($mod_data != 1) {
       mod_api($phpdata, $mod_data);
    }
    ?>
</div>
</body>
</html>


