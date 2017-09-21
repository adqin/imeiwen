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
        <div id="main">
            <div id="mobile_wrapper" class="mui--text-center">
                <div class="content_mobile">
                    <h1><?= $post['title']; ?></h1>
                    <div class="author_name"><?= $post['author']; ?></div>
                    <div class="content_text"><?= nl2br($post['content']); ?></div>
                    <div class="content_nav"><a class="mui-btn mui-btn--small mui-btn--primary" href="/<?= $rand_one; ?>">随机看看</a></div>
                </div>
            </div>
        </div>
        <div class="mui-container mui--text-center" id="footer">
            <p id="nav"><a href="/">首篇</a> | <a href="<?= $post['prev']; ?>">上篇</a> | <a href="<?= $post['next']; ?>">下篇</a> | <a href="/f16e48aa" title="关于niwenwen.com">关于</a></p>
            <p>niwenwen.com 我常常问自己. @2011-<?= date('Y', time()); ?></p>
        </div>
    </body>
</html>