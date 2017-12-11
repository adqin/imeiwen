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

    public function test2() {
        $pt = new \Logic\Pter('4f61e8f8', true);
        $pt->getCache();
    }

        /**
     * 首页，缓存有效1周.
     */
    public function index() {
        $list = \Logic\Homer::getCachePosts('index.post', 604800, false);
        $this->assign('list', $list);
        $this->assign('this_menu', 'index');
        $this->display('home/index');
    }

    /**
     * 推荐文章，缓存有效1周.
     * 
     * @return void
     */
    public function recommend() {
        // 推荐文章.
        $list = \Logic\Homer::getCachePosts('recommend', 604800, true);
        $this->assign('list', $list);
        $this->assign('this_menu', 'recommend');
        $this->display('home/recommend');
    }

    /**
     * 热门浏览，缓存有效1周.
     * 
     * @return void
     */
    public function hot() {
        // 热门文章.
        $list = \Logic\Homer::getCachePosts('hot', 604800, false);
        $this->assign('list', $list);
        $this->assign('this_menu', 'hot');
        $this->display('home/hot');
    }

    /**
     * 最近更新文章，缓存有效1周.
     * 
     * @return void
     */
    public function recent() {
        // 最近更新.
        $list = \Logic\Homer::getCachePosts('recent', 604800, false);
        $this->assign('list', $list);
        $this->assign('this_menu', 'recent');
        $this->display('home/recent');
    }

    /**
     * 最近更新文章，缓存有效1周.
     * 
     * @return void
     */
    public function random() {
        // 最近更新.
        $list = \Logic\Homer::getCachePosts('random', 604800, true);
        $this->assign('list', $list);
        $this->assign('this_menu', 'random');
        $this->display('home/random');
    }

    /**
     * 每日一文.
     */
    public function meiriyiwen() {
        $date = isset($this->param['date']) && $this->param['date'] ? $this->param['date'] : '';
        $cacheDir = CACHE_PATH . 'mryw';
        $dates = file_get_contents($cacheDir . '/cache.dates');
        $dates = $dates ? json_decode($dates, true) : array();

        $list = $date ? file_get_contents($cacheDir . '/cache.' . $date) : file_get_contents($cacheDir . '/cache.default');
        $list = $list ? json_decode($list, true) : array();

        $this->assign('date', $date);
        $this->assign('dates', $dates);
        $this->assign('list', $list);
        $this->assign('this_menu', 'meiriyiwen');
        $this->display('home/meiriyiwen');
    }

    /**
     * 每日一文(adqin.github.io).
     */
    public function mryw() {
        $sql = "select * from `post` where `weixin_up_datetime` > 0 and `status` in('1','2') order by `weixin_up_datetime` desc";
        $list = \Db::instance()->getList($sql);
        $this->assign('list', $list);
        $this->display('home/mryw');
    }

    /**
     * 文章浏览次数.
     * 
     * @return void
     */
    public function pageview() {
        $post_id = $this->getGet('post_id');
        if (!$post_id || strlen($post_id) != 8) {
            echo '';
            exit;
        }

        if (!\Db::instance()->exists("select `id` from `post` where `post_id` = '$post_id'")) {
            echo '';
            exit;
        }

        $row = \Db::instance()->getRow("select `id`,`views` from `page_view` where `post_id` = '$post_id'");
        if (!$row) {
            $param = [
                'post_id' => $post_id,
                'views' => 1,
                'latest_time' => time(),
            ];
            \Db::instance()->insert('page_view', $param);
            echo '1';
            exit;
        } else {
            $id = $row['id'];
            $views = $row['views'] + 1;
            $param = [
                'views' => $views,
                'latest_time' => time(),
            ];
            \Db::instance()->updateById('page_view', $param, $id);
            echo $views;
            exit;
        }
    }

    /**
     * test.
     */
    public function test() {
        $this->display('home/test');
    }

}
