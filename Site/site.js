layui.use(['jquery', 'element', 'form', 'util'], function () {
    var $ = layui.$, element = layui.element, form = layui.form, util = layui.util;
    windowResize();
    lazyRender();

    // 浏览器窗口大小发生变化.
    $(window).resize(function () {
        windowResize();
    });

    // 懒加载.
    $(window).on('scroll', function () {
        lazyRender();
    });

    $('.topiclist-change-page').change(function () {
        page = $(this).val();
        url = '/topiclist/' + page;
        window.location.href = url;
    });

    $('.topicitem-change-page').change(function () {
        topic_id = $('#topic_id').val();
        page = $(this).val();
        url = '/topic/' + topic_id + '/' + page;
        window.location.href = url;
    });

    $('.recommend-change-page').change(function () {
        page = $(this).val();
        url = '/recommend/' + page;
        window.location.href = url;
    });
    
    var topic_item_id = $('#topic_item_id').val();
    if (topic_item_id) {
        $.get('/index/topicview', {topic_id: topic_item_id}, function(re){});
    }

    // 回到顶部.
    util.fixbar({
        bar1: false,
        bar2: false,
        bgcolor: '#2F4056',
        click: function () {
        }
    });

    function windowResize() {
        $('.thumb > a > img').each(function () {
            var tw = $(this).parents('.item').width();
            var th = tw * 0.6;
            $(this).parents('.thumb').width(tw).height(th);
            $(this).width(tw);
        });
        /*var win_w = $(window).width();
        if (win_w < 400) {
            $('#logo').hide();
        } else {
            $('#logo').show();
        }*/
    }

    function lazyRender() {
        $('.thumb > a > img').each(function () {
            if (checkShow($(this)) && !isLoaded($(this))) {
                loadImg($(this));
            }
        });
    }

    function checkShow($img) { // 传入一个img的jq对象
        var scrollTop = $(window).scrollTop();  //即页面向上滚动的距离
        var windowHeight = $(window).height(); // 浏览器自身的高度
        var offsetTop = $img.offset().top;  //目标标签img相对于document顶部的位置

        if (offsetTop < (scrollTop + windowHeight) && offsetTop > scrollTop) { //在2个临界状态之间的就为出现在视野中的
            return true;
        }
        return false;
    }

    function isLoaded($img) {
        return $img.attr('data-src') === $img.attr('src'); //如果data-src和src相等那么就是已经加载过了
    }

    function loadImg($img) {
        $img.attr('src', $img.attr('data-src')); // 加载就是把自定义属性中存放的真实的src地址赋给src属性
    }

});
