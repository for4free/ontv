<?php
include_once 'global.php';
$play_url = @$_GET['id'] ? $_GET['id'] : header("Location: https://www.k2tv.cn");
?>
<html>
<head>
    <title>简单视频(www.k2tv.cn) - VIP视频播放器网页版</title>
    <style>
        body{
            margin: 0;
        }
        #opt{
            margin-left: 79vw;
            margin-top: 20px;
            z-index: 1000;
            position: fixed;
            float: right;
        }
        select{
            width: 20vw;
            min-width: 100px;
            height: 30px;
            font-size: 15px;
            float: right;
            padding: 0;
            border: 1px solid #00a2e0;
            text-indent: 10px;
            appearance:none;
            -moz-appearance:none;
            -webkit-appearance: none;
            background-color: #00a2e0;
        }
        select::-ms-expand { display: none; }
    </style>
    <SCRIPT LANGUAGE="JavaScript">
        function onPlay() {
            var oJK = document.getElementById("jk");
            var sJK = oJK.options[oJK.selectedIndex].value;
            var sVipUrl = <?php echo '"' . $play_url . '"'?>;
            var oWin = document.getElementById("play");
            if (sVipUrl.length > 0) {
                oWin.src = sJK + sVipUrl;
            }
        }
    </SCRIPT>
</head>
<body bgcolor=#000>
<div id="opt">
    <select title="如发现视频无法正常播放请尝试更换视频线路！" id="jk" onchange="onPlay()">
        <?php
        $api_url = get_vip_api();
        $index = 1;
        foreach ($api_url as $item) {
            if ($index == 1) {
                echo '<option value="' . $item . '" selected>线路 ' . $index . '</option>';
            } else {
                echo '<option value="' . $item . '">线路 ' . $index . '</option>';
            }
            $index++;
        }
        ?>
    </select>
</div>
<iframe src="" name="play" id="play" width="100%" align="center" height="100%" frameborder="0" marginheight="0" marginwidth="0" scrolling="no"></iframe>

<SCRIPT LANGUAGE="JavaScript">
    onPlay();
</SCRIPT>
</body>
</html>