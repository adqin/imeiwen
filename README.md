
###### nginx配置.
```
server {
    listen 80;
    root /home/www/wecms/site;
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

###### git常用命令.
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
git branch dev #创建dev分之.
git add . #添加所有更新到待提交.
git commit -a -m 'up' #提交更新.
git push origin dev #本地分之推送到远程dev分之.
git pull --all #拉取最新
git push --all #向仓库推送更新
```
###### composer qiniu sdk.
```
composer require qiniu/php-sdk
```

###### layui.
```
http://www.layui.com/
```

###### url定义.
```
imeiwen.org/index.html = 首页(生成的静态页面)
imeiwen.org/new = 最新发布 （发布时间）
imeiwen.org/hot = 热门文章 （浏览次数）
imeiwen.org/popular = 受欢迎的（根据用户收藏的量来，可后期开发）
imeiwen.org/random = 随机看看（从数据库中取1000条，缓存1小时，然后随机打乱展示20条）
imeiwen.org/meiriyiwen = 每日一文, 根据微信订阅号来处理.
imeiwen.org/a2d3b4f7 = 一篇文章.
imeiwen.org/topic/libaideshi = 一个主题.
imeiwen.org/topic/yu
imeiwen.org/collection 我的收藏, 绑定微信号.

###### 后台管理.
