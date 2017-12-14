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
        'title' => '爱美文网',
        'keywords' => '爱美文, 爱美文网, 美文, 散文, 诗歌, 古诗文',
        'description' => '爱美文网，精典短篇美文与诗歌收集，每一篇都是精心挑选，每一篇都精心配图，一图一文，每一篇都值得珍藏。',
        'domain' => 'imeiwen.org',
    );
    
    public static $menu = array(
        'index' => ['title' => '首页', 'url' => '/'],
        'recommend' => ['title' => '精选', 'url' => '/recommend'],
        'hot' => ['title' => '热门', 'url' => '/hot'],
        'recent' => ['title' => '最近', 'url' => '/recent'],
        'random' => ['title' => '随机', 'url' => '/random'],
        'meiriyiwen' => ['title' => '每日一文', 'url' => '/meiriyiwen'],
        'weixindingyue' => ['title' => '微信订阅', 'url' => '/page/weixindingyue'],
        'event' => ['title' => '发展历程', 'url' => '/page/event'],
        'contact' => ['title' => '联系方式', 'url' => '/page/contact'],
    );

}
