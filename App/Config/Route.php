<?php

namespace Config;

class Route {

    /**
     * 路由规则.
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
    );

}
