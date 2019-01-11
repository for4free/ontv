/**
 * Created by M&F on 2018-07-08.
 */
$(function () {
    var load = true;
    var counts = 30;
    var footer = $('.footer');

    // 判断滚动条
    function load_data_if_no_has_scrollbar() {
        if(!(document.body.scrollHeight > (window.innerHeight || document.documentElement.clientHeight))){
            if(counts<=240){
                load = false;
                get_data(counts);
                counts += 30;
            }
        }
    }
    load_data_if_no_has_scrollbar();
    $(window).scroll(function(){
        if(counts<=240){
            var $this =$(this),
                viewH =$(this).height(),//可见高度
                contentH =$("ul").get(0).scrollHeight,//内容高度
                scrollTop =$(this).scrollTop();//滚动高度
            if(contentH - viewH - scrollTop <= -110&&load) { //到达底部时,加载新内容
                load = false;
                get_data(counts);
                counts += 30;
                if(counts>=250){
                    counts = 250;
                    footer.html('已经到底部了~');
                }
            }
        }
    });
    function get_data(count) {
        footer.html('正在加载数据...');
        $.ajax({
            url:'global_api/_movie_ajax_get_top250_data_api.php?start='+count,
            type:'GET',
            dataType:'json',
            data:{'con':1}
        })
            .done(function (data) {
                for(var index=0;index<30;index++){
                    $('ul a:last').after('<a href="search.html?wd='+data.subjects[index].title+'"><li>'+data.subjects[index].rating.average.toFixed(1)+'分 '+data.subjects[index].title+'</li></a>');
                    $('.result').html('<span>高分电影</span> 榜单前 '+counts+' 条 ');
                }
                load = true;
                footer.html('');
            })
            .fail(function () {
                console.log('load data fail');
            });
    }
})
