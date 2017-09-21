<!doctype html>
<html>
    <head>
        <title><?= $post['title']; ?> - <?= $post['author']; ?></title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="<?= $config['css_url']['lib']; ?>" rel="stylesheet" type="text/css" />
        <link href="<?= $config['css_url']['style']; ?>" rel="stylesheet" type="text/css" />
    </head>
    <body>
        <header></header>
        <div id="main">
            <div id="content-wrapper" class="mui--text-center">
                <div class="content">
                    <h1><?= $post['title']; ?></h1>
                    <div class="author_name"><?= $post['author']; ?></div>
                    <div class="content_text"><?= nl2br($post['content']); ?></div>
                    <div class="content_nav"><a class="mui-btn mui-btn--primary" href="/<?= $rand_one; ?>">随机看看</a></div>
                </div>
            </div>
        </div>
        <img src="<?= $back_img; ?>" style="display: none" id="back_img" />
        <input type="hidden" id="post_id" value="<?= $post['post_id']; ?>" />
        <footer>
            <div class="mui-container mui--text-center">
                niwenwen.com 我常常问自己. 
                <a href="/">首篇</a> | <a href="<?= $post['prev']; ?>">上篇</a> | <a href="<?= $post['next']; ?>">下篇</a> | <a class="back_post" data="back">背景</a>
                 | <a href="/f16e48aa" title="关于niwenwen.com">关于</a> | <a class="zan" title="喜欢本站，就赞一下吧！">点赞</a>

                <span id="note">@2011-<?= date('Y', time()); ?></span>
            </div>
        </footer>
        <script src="<?= $config['js_url']['lib']; ?>"></script>
        <script type="text/javascript">
            $(function () {
                $.post('/index/backimg', {}, function(re){
                    if (re != '') {
                        var bg = 'url(' + re + ')';
                        $("body").css("background-image", bg);
                    }
                });
                
                var content_height = ($('#content-wrapper').height());
                $('#main').height(content_height + 70);

                // pageview
                var post_id = $('#post_id').val();
                $.post('/index/pageview', {post_id: post_id});
                
                // back or post
                $('.back_post').click(function(){
                    var data = $(this).attr('data');
                    if (data == 'back') {
                        $(this).attr('data', 'post');
                        $(this).html('文字');
                        $('#content-wrapper').hide();
                    }
                    
                    if (data == 'post') {
                        $(this).attr('data', 'back');
                        $(this).html('背景');
                        $('#content-wrapper').show();
                    }
                });
                
                // 点赞
                $('.zan').click(function(){
                   $.post('/index/zan', {}, function(re) {
                      alert(re); 
                   });
                });
            });
        </script>
    </body>
</html>