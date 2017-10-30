<?php

/**
 * 系统配置.
 * 
 * @author aic <41262633@qq.com>
 */

/**
 * Class Config.
 */
class Config {

    /**
     * 网站信息.
     * 
     * @var array.
     */
    public static $site = array(
        'name' => '爱美文网',
        'note' => '美文/诗歌/古诗词精品收藏',
        'title' => '',
        'keywords' => '',
        'description' => '',
        'domain' => 'imeiwen.org',
    );

    /**
     * 数据库配置.
     * 
     * @var array.
     */
    public static $database = array(
        'host' => 'localhost',
        'user' => 'root',
        'passwd' => 'adqin1001',
        'dbname' => 'wecms',
        'charset' => 'utf8',
    );
    
    /**
     *路由规则.
     * 
     * @var array. 
     */
    public static $routeRules = array(
        '/index' => ['handler' => 'Index@index'], // 动态首页.
        '/new' => ['handler' => 'Index@new'], // 最近发布.
        '/hot' => ['handler' => 'Index@hot'], // 热门文章.
        '/popular' => ['handler' => 'Index@popular'], // 受欢迎的.
        '/random' => ['handler' => 'Index@random'], // 随机看看.
        '/meiriyiwen[/{page:\d+}]' => ['handler' => 'Index@meiriyiwen'], // 每日一文.
        '/{post_id:\w+}.html' => ['handler' => 'Item@index'], // 文章页.
        '/pageView' => ['handler' => 'Index@view'], // 文章浏览次数更新.
        '/topic/{identify:\w+}' => ['handler' => 'Topic@index'], // 主题.
        '/collection' => ['handler' => 'Collection@index', 'method' => ['GET', 'POST']], // 我的收藏.
        '/page/{id:\w+}' => ['handler' => 'Page@index'], // 单页.
        '/admin/login' => ['handler' => 'Admin\\Io@login', 'method' => ['GET', 'POST']], // 后台登录.
        '/admin/logout' => ['handler' => 'Admin\\Io@logout'], // 后台注销登录.
    );

}
