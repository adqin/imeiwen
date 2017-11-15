<?php

/**
 * 系统配置.
 * 
 * @author aic <41262633@qq.com>
 */

namespace Config;

/**
 * Class Config.
 */
class System {

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
    
    public static $menu = array(
        'recommend' => ['title' => '推荐', 'url' => '/'],
        'recent' => ['title' => '最新', 'url' => '/recent'],
        //'popular' => ['title' => '热门', 'url' => '/popular'],
        'random' => ['title' => '随机', 'url' => '/random'],
        'meiriyiwen' => ['title' => '每日一文', 'url' => '/meiriyiwen'],
        //'topiclist' => ['title' => '主题', 'url' => '/topiclist'],
        'weixindingyue' => ['title' => '微信订阅', 'url' => '/page/weixindingyue'],
    );

}
