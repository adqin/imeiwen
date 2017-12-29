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
        'domain' => 'imeiwen.org',
        'title' => '爱美文网',
        'keywords' => '爱美文网, 美文, 短篇美文, 诗歌, 短诗, 古诗文',
        'note' => '爱美文网，精典美文诗歌收集珍藏。',
    );

    /**
     * 文章分类.
     * 
     * @var array. 
     */
    public static $category = array(
        '1' => '美文',
        '2' => '短篇美文',
        '3' => '诗歌',
        '4' => '短诗',
        '5' => '古诗文',
    );

    /**
     * 导航菜单.
     * 
     * @var array. 
     */
    public static $menu = array(
        'normal' => [
            'index' => ['title' => '首页', 'url' => '/', 'note' => '首页'],
            'recommend' => ['title' => '精选', 'url' => '/recommend', 'note' => '美文精选'],
            'topiclist' => ['title' => '主题', 'url' => '/topiclist', 'note' => '主题'],
            'hot' => ['title' => '热门', 'url' => '/hot', 'note' => '热门美文'],
            'random' => ['title' => '随机看看', 'url' => '/random', 'note' => '随机看看'],
            'recent' => ['title' => '最近发布', 'url' => '/recent', 'note' => '最近发布'],
        ],
        'more' => [
            'weixindingyue' => ['title' => '微信订阅', 'url' => '/page/weixindingyue', 'note' => '微信订阅'],
            'contact' => ['title' => '联系方式', 'url' => '/page/contact', 'note' => '联系方式'],
            'about' => ['title' => '关于我们', 'url' => '/page/about', 'note' => '关于我们']
        ],
    );

}
