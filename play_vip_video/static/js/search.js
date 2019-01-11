/**
 * Created by M&F on 2018-07-13.
 */
$(function () {
    $.post('/global_api/_search_update_about_word_result.php',{'keyword':$('.result span').html()})
})