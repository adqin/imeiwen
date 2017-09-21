<!DOCTYPE html>
<html>
    <head>
        <title>网站配置</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="<?= $config['css_url']['back'] ?>" media="all" rel="stylesheet" />
    </head>
    <body>
        <?php include APP_PATH . '/view/admin/nav.tpl.php'; ?>
        <div class="right_content">
            <div class="content_pub">
                <form name="setting" method="post" action="/admin/setting">
                    <h3><?= $info['set_title']; ?></h3>
                    <p>配置名：<input type="text" name="set_title" value="<?= $info['set_title']; ?>" />
                    <p>内容：<textarea rows="26" cols="60" name="set_val""><?= stripslashes($info['set_val']); ?></textarea></p>
                    <p class="post_note" style="display: none; color:red;">设置成功</p>
                    <p>
                        <input type="hidden" name="set_key" value="<?= $info['set_key']; ?>" />
                        <input type="submit" value="保存设置" class="button2" id="save_setting" />
                    </p>
                </form>
            </div>
        </div>
        <script src="<?= $config['js_url']['lib']; ?>"></script>
        <script src="<?= $config['js_url']['form']; ?>"></script>
        <script type="text/javascript">
            $(function () {
                $('form').submit(function(){
                    $(this).ajaxSubmit(function(re){
                        re = $.parseJSON(re);
                        $('.post_note').html(re.message).fadeIn().fadeOut(5000);
                    });
                    return false;
                });
            });
        </script>
    </body>
</html>