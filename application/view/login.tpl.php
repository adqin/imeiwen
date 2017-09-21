<!DOCTYPE html>
<html>
    <head>
        <title>管理登陆</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="/source/back.css" media="all" rel="stylesheet" />
    </head>
    <body>
        <div class="loginFrame"><h3>管理登陆</h3>
            <form name="login_form" method="post" action="">
            <p>账号：<input type="text" name="user" class="input" /></p>
            <p>密码：<input type="password" name="passwd" class="input" /></p>
            <p class="post_note" style="display: none"></p>
            <p><input type="submit" name="login" value="确认登陆" class="button" /></p>
            </form>
        </div>
        <script src="<?=$config['js_url']['lib']?>"></script>
        <script src="<?=$config['js_url']['form']?>"></script>
        <script>
            $(function(){
               $('form').submit(function(){
                  $('.post_note').hide();
                  $(this).ajaxSubmit(function(re){
                      re = $.parseJSON(re);
                      if (re.error == 1) {
                          $('.post_note').html(re.message).show();
                          return false;
                      }
                      if (re.error == 0) {
                          window.location.href = '/admin/index';
                      }
                  });
                  return false;
               });
            });
        </script>
    </body>
</html>
