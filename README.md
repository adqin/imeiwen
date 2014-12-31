### 你问问 - niwenwen.com
##### 常常问自己, 想要些什么？

1图1文, 参考meiriyiwen.com

简单
--------------------------

######1、单入口模式

apache .htaccess

```
RewriteEngine On
RewriteBase /
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?/$1 [L]
```
nginx nginx.conf

```
autoindex off;
location / {
    try_files $uri $uri/ /index.php?/$uri;
}
```
######2、Ubuntu 安装 apache2 + php5 + mysql5

```
apt-get install apache2 php5 mysql-server libapache2-mod-php5 php5-mysql php5-dev php5-gd
```

######3、php yac缓存
1) 下载: git clone https://github.com/laruence/yac.git

2) phpize安装:

```
$/usr/bin/phpize5
$./configure --with-php-config=/usr/bin/php-config5
$make && make install
```

3) 配置文件:
$nano /etc/php5/mods-available/yac.ini 写入extension=yac.so

4) 创建连接: $ln -s ../../mods-available/yac.ini 21-yac.ini

5) yac配置:

```
yac.enable = 1
yac.keys_memory_size = 4M #4M can get 30K key slots, 32M can get 100K key slots
yac.values_memory_size = 64M
yac.compress_threshold = -1
yac.enable_cli = 0 #whether enable yac with cli, default 0

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
git add . #添加新增
git commit -a -m 'update' #提交所有更改
git push --all #向仓库推送更新
```

######5、简单框架
网站功能简单，包括简单的路由与分发、数据库操作、表单get/post、简单的视图模板、Yac缓存系统、 数据压缩。

1) 页面与路由。 首页=/， 日志页=/d3k9a4n8,  日志编辑与发布=/publish,  背景图上传=/upload, 会员主页=/u/adqin, 登陆=/login, 注册=/reg

2) Yac缓存， 配置，数据都保存到内存中。

3) 数据压缩，缓存到内存数据需要压缩存储。 


######6、简单js框架
http://zeptojs.com/

######7、茫茫的世界中容易迷失
