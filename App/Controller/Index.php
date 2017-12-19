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
     * 首页，缓存有效1周.
     */
    public function index() {

        $num = $this->isMobile ? 3 : 6;
        $post_list = \Logic\Homer::getCachePosts('index.post', 604800, true, $num);
        $this->assign('post_list', $post_list);

        $num = $this->isMobile ? 4 : 8;
        $topic_list = \Logic\Homer::getIndexTopic(false, $num);
        $this->assign('topic_list', $topic_list);

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
        $page = isset($this->param['page']) ? $this->param['page'] : 1;

        $total_page = file_get_contents(CACHE_PATH . 'recommend/cache.page.num');
        $page = (!$page || $page > $total_page) ? 1 : $page;
        
        $result = file_get_contents(CACHE_PATH . 'recommend/cache.recommend.' . $page);
        $result = $result ? json_decode($result, true) : [];
        $list = $result ? $result : [];

        $this->assign('page', $page);
        $this->assign('total_page', $total_page);
        $this->assign('post_list', $list);
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
        $this->assign('post_list', $list);
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
        $this->assign('post_list', $list);
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
        $list = \Logic\Homer::getRandomPost();
        $this->assign('post_list', $list);
        $this->assign('this_menu', 'random');
        $this->display('home/random');
    }

    /**
     * 文章浏览次数.
     * 
     * @return void
     */
    public function postview() {
        $post_id = $this->getGet('post_id');
        if (!$post_id || strlen($post_id) != 8) {
            echo '';
            exit;
        }

        if (!\Db::instance()->exists("select `id` from `post` where `post_id` = '$post_id'")) {
            echo '';
            exit;
        }

        $row = \Db::instance()->getRow("select `id`,`views` from `post_view` where `post_id` = '$post_id'");
        if (!$row) {
            $param = [
                'post_id' => $post_id,
                'views' => 1,
                'latest_time' => time(),
            ];
            \Db::instance()->insert('post_view', $param);
            echo '1';
            exit;
        } else {
            $id = $row['id'];
            $views = $row['views'] + 1;
            $param = [
                'views' => $views,
                'latest_time' => time(),
            ];
            \Db::instance()->updateById('post_view', $param, $id);
            echo $views;
            exit;
        }
    }
    
    /**
     * 主题浏览.
     */
    public function topicview() {
        $topic_id = $this->getGet('topic_id');
        if (!\Db::instance()->exists("select `id` from `topic` where `topic_id` = '$topic_id'")) {
            echo '';
            exit;
        }
        
        $row = \Db::instance()->getRow("select `id`,`topic_id`,`views` from `topic_view` where `topic_id` = '$topic_id'");
        if (!$row) {
            $param = [
                'topic_id' => $topic_id,
                'views' => 1,
                'latest_time' => time(),
            ];
            \Db::instance()->insert('topic_view', $param);
            echo '1';
            exit;
        } else {
            $id = $row['id'];
            $views = $row['views'] + 1;
            $param = [
                'views' => $views,
                'latest_time' => time(),
            ];
            \Db::instance()->updateById('topic_view', $param, $id);
            echo $views;
            exit;
        }
    }

        public function test() {
        $t = new \Logic\TopicItemer('aiqing');
        $t->setCache();
    }

}
