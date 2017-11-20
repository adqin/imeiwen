layui.use(['jquery', 'element'], function () {
    var $ = layui.$, element = layui.element;
    itemSet();

    // 浏览器窗口大小发生变化.
    $(window).resize(function () {
        itemSet();
    });

    // 刷新浏览次数.
    var post_id = $('#post_id').html();
    $.get('/index/pageview', {post_id: post_id}, function (re) {
        if (re) {
            $('#page_view').html(re);
        }
    });

    function itemSet() {
        var w = $('.post-image').width();
        var post_w = w * 0.96;
        $('#post-image-id').width(w);
        $('#post-image-id').attr('src', $('#post-image-id').attr('data-attr'));
        $('.image-loading').hide();
        $('.post-content').width(post_w);
    }
});
