<?php

namespace Config;

class Route {

    /**
     * 路由规则.
     * 
     * @var array. 
     */
    public static $routeRules = array(
        '/' => ['handler' => 'Index@index'], // 首页
        '/recommend[/{page:\d+}]' => ['handler' => 'Index@recommend'], // 推荐阅读.
        '/hot' => ['handler' => 'Index@hot'], // 热门浏览.
        '/recent' => ['handler' => 'Index@recent'], // 最近发布.
        '/random' => ['handler' => 'Index@random'], // 随机看看.
        '/meiriyiwen[/{date:\w+}]' => ['handler' => 'Index@meiriyiwen'], // 每日一文.
        '/post/{post_id:\w+}' => ['handler' => 'Post@item'], // 文章详情页.
        '/topiclist[/{page:\d+}]' => ['handler' => 'Topic@index'], // 主题列表.
        '/topic/{topic_id:\w+}[/{page:\d+}]' => ['handler' => 'Topic@item'], // 主题.
        '/page/{id:\w+}' => ['handler' => 'Page@index'], // 单页.
    );

}
