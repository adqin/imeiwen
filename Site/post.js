layui.use(['jquery', 'element', 'util'], function () {
    var $ = layui.$, element = layui.element, util = layui.util;
    itemSet();

    // 浏览器窗口大小发生变化.
    $(window).resize(function () {
        itemSet();
    });

    // 刷新浏览次数.
    var post_id = $('#post_id').html();
    $.get('/index/postview', {post_id: post_id}, function (re) {
        if (re) {
            $('#page_view').html(re).show();
        }
        if (re > 200) {
            $('.views').show();
        }
    });

    // 回到顶部.
    util.fixbar({
        bar1: false,
        bar2: false,
        bgcolor: '#2F4056',
        click: function () {
        }
    });

    function itemSet() {
        var mw = $('#post-item').width();
        var ww = $(window).width();
        var qrcode_w = ((ww - mw) / 2);
        $('.qrcode').hide();
        if (qrcode_w >= 180) {
            $('.qrcode').css("right", (qrcode_w - 180));
            $('.qrcode').show();
        }

        var w = $('.post-image').width();
        var post_w = w * 0.96;
        $('.post-content').width(post_w);

        $('#post-image-id').width(w);
        var post_img_url = $('#post-image-id').attr('data-attr');
        var img = new Image();
        img.src = post_img_url;
        img.onload = function () {
            $('#post-image-id').attr('src', post_img_url).show();
            $('.image-loading').hide();
        }
    }
});
