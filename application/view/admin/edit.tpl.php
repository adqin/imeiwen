<!DOCTYPE html>
<html>
    <head>
        <title><?php if (empty($info['post_id'])): ?>文章发布<?php else: ?>文章编辑<?php endif; ?></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="<?= $config['css_url']['back']; ?>" media="all" rel="stylesheet" />
    </head>
    <body>
        <?php include APP_PATH . '/view/admin/nav.tpl.php'; ?>
        <div class="right_content">
            <div class="content_pub">
                <form name="post_edit" method="post" action="/admin/edit">
                    <h3><?php if (empty($info['post_id'])): ?>文章发布<?php else: ?>文章编辑<?php endif; ?></h3>
                    <p>标题：<input type="text" class="input" id="title" name="title" size="36" value="<?= $info['title']; ?>" /></p>
                    <p>作者：<input type="text" class="input" id="author" name="author" value="<?= $info['author']; ?>" /></p>
                    <p>内容：<textarea rows="25" cols="45" id="content" name="content"><?= $info['content']; ?></textarea></p>
                    <p class="post_note" style="display: none"></p>
                    <p>
                        <input type="hidden" name="post_id" value="<?= $info['post_id']; ?>" />
                        <input type="submit" value="保存发布" class="button2" id="content_pub" />
                    </p>
                </form>
            </div>
        </div>
        <script src="<?= $config['js_url']['lib']; ?>"></script>
        <script src="<?= $config['js_url']['form']; ?>"></script>
        <script type="text/javascript">
            $(function () {
                $('form').submit(function () {
                    $(this).ajaxSubmit(function (re) {
                        re = $.parseJSON(re);
                        if (re.error == 1) {
                            $('#' + re.id).focus();
                            $('.post_note').html(re.message).fadeIn().fadeOut(5000);
                            return false;
                        }
                        if (re.error == 0) {
                            if (re.type == 0) {
                                alert(re.message);
                                window.location.href = '/admin/edit/' + re.post_id;
                            }
                            if (re.type == 1) {
                                $('.post_note').html(re.message).fadeIn().fadeOut(5000);
                            }
                        }
                    });
                    return false;
                });
            });
        </script>
    </body>
</html>
