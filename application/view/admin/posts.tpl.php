<!DOCTYPE html>
<html>
    <head>
        <title>文章列表</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="<?=Config::$source['css_url']['back']; ?>" media="all" rel="stylesheet" />
    </head>
    <body>
        <?php include APP_PATH . '/view/admin/nav.tpl.php'; ?>
        <div class="right_content">
            <div class="post_list">
                <h3>文章列表</h3>
                <form name="search" method="post" action="" style="margin: 0 0 30px;">
                    <input type="text" name="keyword" value="<?= $keyword; ?>" style="padding: 4px 0;" />
                    <input type="submit" value="搜索" class="button" />
                </form>
                <?php if (!empty($data_list)): ?>
                    <ul>
                        <?php foreach ($data_list as $data): ?>
                            <li><span class="post_list_title"><a href="/<?= $data['post_id'] ?>" target="_blank"><?= $data['title']; ?></a></span>  
                                <span style="display:inline-block; font-style: italic; color: gray; padding: 0 3px;"><?= $data['author']; ?></span> 
                                （<span style="color: red">浏览：<?= $data['page_view']; ?></span> &nbsp;&nbsp;
                                <?= date('Y-m-d H:i:s', $data['update_time']); ?>)  <a href="/admin/post_edit?id=<?= $data['post_id']; ?>">修改</a> <a href="/delete/<?= $data['post_id']; ?>" todel="<?= $data['post_id']; ?>">删除</a></li>
                            <?php endforeach; ?>
                    </ul>
                    <?php if (!empty($page_show)): ?>
                        <?= $page_show; ?>
                    <?php endif; ?>
                <?php else: ?>
                    <p>没有文章记录</p>
                <?php endif; ?>
            </div>
        </div>
        <script src="<?=Config::$source['js_url']['lib']; ?>"></script>
        <script src="<?=Config::$source['js_url']['form']; ?>"></script>
        <script type="text/javascript">
            $(function () {
                $('a[todel]').click(function () {
                    if (!confirm('确认删除？')) {
                        return false;
                    }

                    var post_id = $(this).attr('todel');
                    $.post('/admin/post_del', {post_id: post_id}, function (re) {
                        re = $.parseJSON(re);
                        if (re.error == 1) {
                            alert(re.message);
                        }
                        if (re.error == 0) {
                            window.location.reload();
                        }
                    });

                    return false;
                });
            });
        </script>
    </body>
</html>
