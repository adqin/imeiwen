Simple, do something i like.

你问问 - niwenwen.com
常常问自己, 想要些什么？

1图1文, so easy.

1、单入口模式
apache .htaccess
    RewriteEngine On
    RewriteBase /macaw
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php?/$1 [L]

nginx nginx.conf
    autoindex off;
    location / {
        try_files $uri $uri/ /index.php?/$uri;
    }

2、Ubuntu 安装 apache2 + php5 + mysql5
apt-get install apache2 php5 mysql-server libapache2-mod-php5 php5-mysql php5-dev php5-gd

3、php yac缓存
下载: git clone https://github.com/laruence/yac.git
phpize安装:
$/usr/bin/phpize5
$./configure --with-php-config=/usr/bin/php-config5
$make && make install

配置文件:
$nano /etc/php5/mods-available/yac.ini
写入
extension=yac.so

创建连接:
$ln -s ../../mods-available/yac.ini 21-yac.ini

yac配置:
yac.enable = 1
yac.keys_memory_size = 4M ; 4M can get 30K key slots, 32M can get 100K key slots
yac.values_memory_size = 64M
yac.compress_threshold = -1
yac.enable_cli = 0 ; whether enable yac with cli, default 0

yac用法:
$yac = new Yac();
$yac->set('foo', 'bar');
$yac-get('foo');

Yac::set('foo', 'bar');
Yac::get('foo');
Yac::delete('foo');
Yac::flush();
Yac::info();

4、git常用命令与用法
初始配置:
git config --global user.name "Your Name Comes Here"  #配置使用git仓库的人员姓名
git config --global user.email you@yourdomain.example.com  #配置使用git仓库的人员email
git config --global credential.helper cache   #配置到缓存 默认15分钟
git config --global credential.helper 'cache --timeout=3600'    #修改缓存时间
git config --global color.ui true  
git config --global alias.co checkout  
git config --global alias.ci commit  
git config --global alias.st status  
git config --global alias.br branch  
git config --global core.editor "mate -w"    # 设置Editor使用textmate  
git config -1 #列举所有配置
