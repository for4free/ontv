$(function(){

    var btn_list = ['#total','#query','#config'];
    var btn_list_lemgth = btn_list.length;

    var total_list = ['#total_last','#total_time','#total_key','#total_area','#total_hot'];
    var total_list_length = total_list.length;
    var totalNum= 0;
    var totalMonNum= 0;

    var bak = 0;

    function init(){
        for(var index=0;index < btn_list_lemgth;index++){
            $(btn_list[index]).show();
        }
        for(var index=0;index<total_list_length;index++){
            $(total_list[index]).hide();
        }
        $('.search_form').hide();
        bak = 0;
    }

    function main_nav(name){
        for(var index=0;index < btn_list_lemgth;index++){
            if(btn_list[index] != name){
                $(btn_list[index]).hide();
            }      
        }
        bak = 1;
    }

    init(); //初始化导航栏
    get_data(7); // 初始化统计数据
    get_data(1); //初始化内容数据

    $('#total').click(function(){
        if(bak==0){
            for(var index=0;index<total_list_length;index++){
                $(total_list[index]).show();
            }
            main_nav('#total')
        }else{
            init();
        }
    });
    $('#query').click(function(){
        if(bak==0){
            $('.search_form').show();
            main_nav('#query')
        }else{
            init();
        }
    });
    $('#config').click(function(){
        //配置文件
        $('#table_div').hide();
        $('.config_div').show();
    });
    //统计 子选项
    $nav_item = $('.span_child');
    $nav_item.click(function () {
        get_data($(this).index() - 2)
    });
    // 查询
    $("form").submit(function(){
        var key = $('.search_txt').val();
        get_data(6,key);
        return false;
    });
    //提交数据
    $('#sum_data').click(function () {
        var $api = $('#web_api');
        var $url = $('#web_url');
        var $content_index = $('#index_content');
        var $content_switch = $('#content_switch');
        var $word_filter = $('#word_filter');
        var $word_list = $('#word_list');
        var $tags_cloud = $('#tags_cloud');
        var $tags_cloud_list = $('#tags_cloud_list');
        var $tag_01 = 0;
        var $tag_02 = 0;
        var $tag_03 = 0;

        if($content_switch.is(':checked')){
            $tag_01 = 1;
        }
        if($word_filter.is(':checked')){
            $tag_02 = 1;
        }
        if($tags_cloud.is(':checked')){
            $tag_03 = 1;
        }

        $.ajax({
            url: '/global_api/_admin_write_api.php',
            type: 'GET',
            dataType: 'json',
            data:{'api':$api.val(),'url':$url.val(),'switch':$tag_01+','+$tag_02+','+$tag_03,'content':$content_index.val(),'word':$word_list.val(),'tags_cloud':$tags_cloud_list.val()}
        })
            .done(function(dat) {

                alert('提交成功');
                $api.val(dat[0][1]);
                $url.val(dat[1][1]);
                $content_index.val(dat[3][1]);
                $word_list.val(dat[4][1]);
                $tags_cloud_list.val(dat[5][1]);

                if(dat[2][1]==1){
                    $content_switch.prop('checked',true);
                }else {
                    $content_switch.prop('checked',false);
                }
                if(dat[2][2]==1){
                    $word_filter.prop('checked',true);
                }else {
                    $word_filter.prop('checked',false);
                }
                if(dat[2][3]==1){
                    $tags_cloud.prop('checked',true);
                }else {
                    $tags_cloud.prop('checked',false);
                }
            })
            .fail(function() {
                console.log('error');
            });
        
    });

    //异步加载数据完成后调用的函数
    function get_return_data(data,type) {
        if(type==7){
            data = data[0]
            totalNum = data.total;
            totalMonNum = data.month;
            $('.result').eq(0).html('<span>搜索次数共:</span>'+totalNum+'<span>次,年:</span>'+data.year+'<span>次,' +
                '月:</span>'+totalMonNum+'<span>次,周:</span>'+data.week+'<span>次</span>');
        }else{
            var $td ='',$th='';
            if(type==1){
                $th = '<tr><th>搜索词</th> <th>来源地址</th> <th>时间</th></tr>';
                for(var index=0,len = data.length;index<len;index++){
                    var $ip = data[index].ip;
                    $td += '<tr><td><a href="/actions.php?ip='+$ip+'" target="_blank">'+data[index].content+'</a></td> <td><a href="/map.php?ip='+$ip+'" target="_blank">'+data[index].ip_ad+'('+$ip+')</a></td> <td>'+data[index].date+'</td> </tr>';
                }
            }else if(type==2){
                $th = '<tr><th>搜索词</th> <th>热度</th> <th>热度指数</th></tr>';
                for(var index=0,len = data.length;index<len;index++){
                    $td += '<tr><td>'+data[index].content+'</td> <td>'+data[index].counts+'</td> <td>'+((data[index].counts)/totalMonNum).toFixed(5)+'</td> </tr>';
                }
            }else if(type==3){
                $th = '<tr><th>搜索词</th> <th>热度</th> <th>热度指数</th></tr>';
                for(var index=0,len = data.length;index<len;index++){
                    $td += '<tr><td>'+data[index].content+'</td> <td>'+data[index].counts+'</td> <td>'+((data[index].counts)/totalNum).toFixed(5)+'</td> </tr>';
                }
            }else if(type==4){
                $th = '<tr><th>时段</th> <th>热度</th> <th>热度指数</th></tr>';
                var $time = 0;
                for(var index=0,len = data.length;index<len;index++){
                    function PrefixInteger(num, n) {
                        return (Array(n).join(0) + num).slice(-n);
                    };
                    time_next = PrefixInteger((parseInt(data[index].date_time) + 1),2);
                    $time = data[index].date_time+':00 - '+time_next+':00';
                    $td += '<tr><td>'+$time+'</td> <td>'+data[index].counts+'</td> <td>'+((data[index].counts)/totalNum).toFixed(5)+'</td> </tr>';
                }
            }else if(type==5){
                $th = '<tr><th>区域</th> <th>热度</th> <th>热度指数</th></tr>';
                for(var index=0,len = data.length;index<len;index++){
                    $td += '<tr><td>'+data[index].ip_ad_pri+'</td> <td>'+data[index].counts+'</td> <td>'+((data[index].counts)/totalNum).toFixed(5)+'</td> </tr>';
                }
            }else if(type==6){
                $th = '<tr><th>搜索词</th> <th>来源地址</th> <th>时间</th></tr>';
                for(var index=0,len = data.length;index<len;index++){
                    var $ip = data[index].ip;
                    $td += '<tr><td><a href="/actions.php?ip='+$ip+'" target="_blank">'+data[index].content+'</a></td> <td><a href="/map.php?ip='+$ip+'" target="_blank">'+data[index].ip_ad+'('+$ip+')</a></td> <td>'+data[index].date+'</td> </tr>';
                }
            }
            $th += $td;
            $('#table_div').show();
            $('table').html($th);
            $('.config_div').hide();
        }
    }

    function get_data(type,key) {
        $.ajax({
            url: '/global_api/_admin_api.php',
            type: 'GET',
            dataType: 'json',
            data:{'type':type,'key':key}
        })
            .done(function(dat) {
                get_return_data(dat,type);
            })
            .fail(function() {
                console.log('error');
            });
    }
})