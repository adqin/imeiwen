layui.use(['jquery', 'element', 'form', 'util'], function () {
    var $ = layui.$, element = layui.element, form = layui.form, util = layui.util;
    imgResize();
    lazyRender();
    changeMenu();

    // 浏览器窗口大小发生变化.
    $(window).resize(function () {
        changeMenu();
        imgResize();
    });

    // 懒加载.
    $(window).on('scroll', function () {
        lazyRender();
    });

    $('#change-weix-uptime').change(function () {
        date = $(this).val();
        url = date ? '/meiriyiwen/' + date : '/meiriyiwen';
        window.location.href = url;
    });

    // 回到顶部.
    util.fixbar({
        bar1: false,
        bar2: false,
        bgcolor: '#2F4056',
        click: function () {
        }
    });

    function changeMenu() {
        width = $(window).width();
        if (width >= 720) {
            $('#top-nav-big').show();
            $('#top-nav-small').hide();
        } else {
            $('#top-nav-big').hide();
            $('#top-nav-small').show();
        }
    }

    function imgResize() {
        $('.thumb > a > img').each(function() {
            var tw = $(this).parents('.item').width();
            var th = tw * 0.55;
            $(this).parents('.thumb').width(tw).height(th);
            $(this).width(tw);
        });
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
