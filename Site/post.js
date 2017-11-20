layui.use(['jquery', 'element'], function () {
    var $ = layui.$, element = layui.element;
    imgSet();

    // 浏览器窗口大小发生变化.
    $(window).resize(function () {
        imgSet();
    });

    // 刷新浏览次数.
    var post_id = $('#post_id').html();
    $.get('/index/pageview', {post_id: post_id}, function (re) {
        if (re) {
            $('#page_view').html(re);
        }
    });

    function imgSet() {
        var w = $('.post-image').width();
        $('#post-image-id').width(w);
        $('#post-image-id').attr('src', $('#post-image-id').attr('data-attr'));
        $('.image-loading').hide();
    }
});
