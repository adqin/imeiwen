<?php

/**
 * 首页.
 * 
 * @author aic <41262633@qq.com>
 */

namespace Controller;

/**
 * Class Controller Index.
 */
class Index extends \Controller\Base {

    /**
     * 推荐文章.
     * 
     * @return void
     */
    public function recommend() {
        // 推荐文章.
        $list = \Logic\Homer::getCachePosts('recommend', 43200, true);
        $this->assign('list', $list);
        $this->assign('menu_key', 'recommend');
        $this->display('home/recommend');
    }

    /**
     * 最近更新文章.
     * 
     * @return void
     */
    public function recent() {
        // 最近更新.
        $list = \Logic\Homer::getCachePosts('recent', 43200, false);
        $this->assign('list', $list);
        $this->assign('menu_key', 'recent');
        $this->display('home/recent');
    }

    /**
     * 最近更新文章.
     * 
     * @return void
     */
    public function popular() {
        // 最近更新.
        $list = \Logic\Homer::getCachePosts('popular', 43200, true);
        $this->assign('list', $list);
        $this->assign('menu_key', 'popular');
        $this->display('home/popular');
    }

    /**
     * 最近更新文章.
     * 
     * @return void
     */
    public function random() {
        // 最近更新.
        $list = \Logic\Homer::getCachePosts('random', 43200, true);
        $this->assign('list', $list);
        $this->assign('menu_key', 'random');
        $this->display('home/random');
    }

}
