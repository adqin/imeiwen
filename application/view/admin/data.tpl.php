<!DOCTYPE html>
<html>
    <head>
        <title>网站数据</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="<?= $config['css_url']['back'] ?>" media="all" rel="stylesheet" />
    </head>
    <body>
        <?php include APP_PATH . '/view/admin/nav.tpl.php'; ?>
        <div class="right_content">
            <div class="content_pub">
                <h3>网站数据</h3>
                <p>共计文章：<?= $post_count; ?>篇</p>
                <p>共计字数：<?= $word_count; ?>字</p>
                <p>网站浏览次数：<?= $site_view; ?>次</p>
                <p>点赞次数：<?= $zan_num; ?>次</p>
            </div>
        </div>
    </body>
</html>