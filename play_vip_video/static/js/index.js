/**
 * Created by M&F on 2018-07-10.
 */

var radius = 90;
var d = 200;
var dtr = Math.PI / 180;
var mcList = [];
var lasta = 1;
var lastb = 1;
var distr = true;
var tspeed = 10;
var size = 200;
var mouseX = 0;
var mouseY = 10;
var howElliptical = 1;
var aA = null;
var oDiv = null;

$(function ()
{
    var $inner_html_data = '';
    var $omainDiv = $('#tagscloud');

    $.ajax({
        url:'/global_api/_index_tags_api.php',
        type:'GET',
        dataType:'json'
    })
        .done(function (data) {
            $data_len = data.length;
            var $regx = /^http.*\..*$/i;
            for(var index=0;index<$data_len;index++){
                $tag_name = data[index];
                if($tag_name!=''){
                    if(!$regx.test($tag_name)){
                        $inner_html_data += '<a href="/search.html?wd='+data[index]+'" class="tagc'+Math.ceil(Math.random()*3)+'">'+$tag_name+'</a>';
                    }
                }
            }
            $omainDiv.html($inner_html_data);
            run_tags();
        })
        .fail(function () {
            console.log('load data error in tags_cloud_api')
        });
    console.log('%c\t感谢您对本站感兴趣\r\n\t如果想一起搞点事情\r\n\t联系Q:912692510',"font-size:12px;color:red;");
});
    //get notice
    $.ajax({
        url:'/global_api/_get_notice_api.php',
        type:'GET',
        dataType:'json'
    })
        .done(function (data) {
            var $notice = $('.content');
            if(data[0]){
                $notice.html(data[1]);
                $notice.css('display','block');
            }
        }).fail(function () {
        console.log('load data error in get_notice_api')
    });

function run_tags() {
    var i=0;
    var oTag=null;
    oDiv=document.getElementById('tagscloud');
    aA=oDiv.getElementsByTagName('a');
    for(i=0;i<aA.length;i++)
    {
        oTag={};
        aA[i].onmouseover = (function (obj) {
            return function () {
                obj.on = true;
                this.style.zIndex = 9999;
                this.style.color = '#fff';
                this.style.padding = '5px 5px';
                this.style.filter = "alpha(opacity=100)";
                this.style.opacity = 1;
            }
        })(oTag)
        aA[i].onmouseout = (function (obj) {
            return function () {
                obj.on = false;
                this.style.zIndex = obj.zIndex;
                this.style.color = '#fff';
                this.style.padding = '5px';
                this.style.filter = "alpha(opacity=" + 100 * obj.alpha + ")";
                this.style.opacity = obj.alpha;
                this.style.zIndex = obj.zIndex;
            }
        })(oTag)
        oTag.offsetWidth = aA[i].offsetWidth;
        oTag.offsetHeight = aA[i].offsetHeight;
        mcList.push(oTag);
    }
    sineCosine( 0,0,0 );
    positionAll();
    (function () {
        update();
        setTimeout(arguments.callee, 40);
    })();
}

function update()
{
    var a, b, c = 0;
    a = (Math.min(Math.max(-mouseY, -size), size) / radius) * tspeed;
    b = (-Math.min(Math.max(-mouseX, -size), size) / radius) * tspeed;
    lasta = a;
    lastb = b;
    if (Math.abs(a) <= 0.01 && Math.abs(b) <= 0.01) {
        return;
    }
    sineCosine(a, b, c);
    for (var i = 0; i < mcList.length; i++) {
        if (mcList[i].on) {
            continue;
        }
        var rx1 = mcList[i].cx;
        var ry1 = mcList[i].cy * ca + mcList[i].cz * (-sa);
        var rz1 = mcList[i].cy * sa + mcList[i].cz * ca;

        var rx2 = rx1 * cb + rz1 * sb;
        var ry2 = ry1;
        var rz2 = rx1 * (-sb) + rz1 * cb;

        var rx3 = rx2 * cc + ry2 * (-sc);
        var ry3 = rx2 * sc + ry2 * cc;
        var rz3 = rz2;

        mcList[i].cx = rx3;
        mcList[i].cy = ry3;
        mcList[i].cz = rz3;

        per = d / (d + rz3);

        mcList[i].x = (howElliptical * rx3 * per) - (howElliptical * 2);
        mcList[i].y = ry3 * per;
        mcList[i].scale = per;
        var alpha = per;
        alpha = (alpha - 0.6) * (10 / 6);
        mcList[i].alpha = alpha * alpha * alpha - 0.2;
        mcList[i].zIndex = Math.ceil(100 - Math.floor(mcList[i].cz));
    }
    doPosition();
}
function positionAll()
{
    var phi = 0;
    var theta = 0;
    var max = mcList.length;
    for (var i = 0; i < max; i++) {
        if (distr) {
            phi = Math.acos(-1 + (2 * (i + 1) - 1) / max);
            theta = Math.sqrt(max * Math.PI) * phi;
        } else {
            phi = Math.random() * (Math.PI);
            theta = Math.random() * (2 * Math.PI);
        }
        //坐标变换
        mcList[i].cx = radius * Math.cos(theta) * Math.sin(phi);
        mcList[i].cy = radius * Math.sin(theta) * Math.sin(phi);
        mcList[i].cz = radius * Math.cos(phi);

        aA[i].style.left = mcList[i].cx + oDiv.offsetWidth / 2 - mcList[i].offsetWidth / 2 + 'px';
        aA[i].style.top = mcList[i].cy + oDiv.offsetHeight / 2 - mcList[i].offsetHeight / 2 + 'px';
    }
}
function doPosition()
{
    var l = oDiv.offsetWidth / 2;
    var t = oDiv.offsetHeight / 2;
    for (var i = 0; i < mcList.length; i++) {
        if (mcList[i].on) {
            continue;
        }
        var aAs = aA[i].style;
        if (mcList[i].alpha > 0.1) {
            if (aAs.display != '')
                aAs.display = '';
        } else {
            if (aAs.display != 'none')
                aAs.display = 'none';
            continue;
        }
        aAs.left = mcList[i].cx + l - mcList[i].offsetWidth / 2 + 'px';
        aAs.top = mcList[i].cy + t - mcList[i].offsetHeight / 2 + 'px';
        aAs.fontSize=Math.ceil(12*mcList[i].scale/2)+1+'px';
        // aAs.filter = "progid:DXImageTransform.Microsoft.Alpha(opacity="+100*mcList[i].alpha+")";
        aAs.filter = "alpha(opacity=" + 100 * mcList[i].alpha + ")";
        aAs.zIndex = mcList[i].zIndex;
        aAs.opacity = mcList[i].alpha;
    }
}
function sineCosine( a, b, c)
{
    sa = Math.sin(a * dtr);
    ca = Math.cos(a * dtr);
    sb = Math.sin(b * dtr);
    cb = Math.cos(b * dtr);
    sc = Math.sin(c * dtr);
    cc = Math.cos(c * dtr);
}
