### 你问问 - niwenwen.com 常常问自己

######1、单入口模式

nginx nginx.conf
```
server {
    listen 80;
    root /home/www/wecms/public;
    index index.html index.php;
    server_name niwenwen.com;
    location / {
        autoindex off;
        try_files $uri $uri /index.php?$args;
    }
    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        # With php7.0-fpm (or other unix sockets):
        fastcgi_pass unix:/run/php/php7.0-fpm.sock;
    }
}
```

6) yac用法:

```php
$yac = new Yac();
$yac->set('foo', 'bar'，$ttl);
$yac->get('foo');
$yac->delete('foo');
$yac->flush();
$yac->info();
```
7) zlib压缩与解压

```php
$previewData = json_encode($previewData);
$output = rtrim(strtr(base64_encode(gzdeflate($previewData, 9)), '+/', '-_'), '=');

$output = gzinflate(base64_decode(strtr($previewData, '-_', '+/')));
```

######4、git常用命令与用法
1) 初始配置:

```
git config --global user.name "Your Name Comes Here" #配置使用git仓库的人员姓名
git config --global user.email you@yourdomain.example.com #配置使用git仓库的人员email
git config --global credential.helper cache #配置到缓存默认15分钟
git config --global credential.helper 'cache --timeout=3600' #修改缓存时间
git config --global color.ui true  
git config --global alias.co checkout  
git config --global alias.ci commit  
git config --global alias.st status  
git config --global alias.br branch  
git config -1 #列举所有配置
```

2) 基本命令

```
git pull --all #拉取最新
git add . #添加新增
git commit -a -m 'update' #提交所有更改
git push --all #向仓库推送更新
```

######4、简单js框架
http://zeptojs.com/

######5、唯一key计算

```
$key = hash('crc32b', time());
```

######6、邮件发送

https://github.com/PHPMailer/PHPMailer

```
header("Content-type:text/html; charset=utf-8");

require './PHPMailer/PHPMailerAutoload.php';

$mail = new PHPMailer;
$mail->SMTPDebug = 1;
$mail->Charset = 'UTF-8';
$mail->isSMTP();
$mail->Host = 'smtp.exmail.qq.com';
$mail->SMTPAuth = true;
$mail->Username = 'service@niwenwen.com';
$mail->Password = '******';
$mail->SMTPSecure = 'ssl';
$mail->Port = '465';

$mail->From = 'service@niwenwen.com';
$mail->FromName = '你问问';
$mail->addAddress('2208576183@qq.com', '阿D');
$mail->isHTML(true);

$mail->Subject = '开始测试123';
$mail->Body = '内容：123<b>it is ok!</b>';

if (!$mail->send()) {
    echo $mail->ErrorInfo;
} else {
    echo '发送成功';
}
```

######7、qq企业邮箱, exmail.qq.com

1)邮件注册确认，找回密码 service@niwenwen.com

2)联系方式，contact@niwenwen.com
         

######8、会员使用qq邮箱注册


######9、数据表

user_info 用户信息
mail_link 邮件类型
login
用户使用qq邮箱注册，注册后需进入邮箱确认，忘记密码，可通过邮箱找回。
确认注册，可发表日志。

一篇日志，搭配一幅背景图。

系统合理使用内存，热门日志/推荐日志，网站配置，背景图，活跃用户。
