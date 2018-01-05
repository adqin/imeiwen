<?php

/**
 * 网站更新.
 * 
 * @author aic <41262633@qq.com>
 */

namespace Controller\Admin;

class Update extends \Controller\Admin\Init {

    public function __construct() {
        parent::__construct();
    }

    /**
     * 网站关键词记录刷新.
     * 
     * @return void
     */
    public function keywords() {
        // 更新的当前页.
        $page = $this->getGet('page');
        $page = $page ? $page : 1;
        $nextPage = $page + 1;

        $type = $this->getGet('type');
        $type = $type ? $type : 'post'; // post or keywords.
        // 计算总的条数.
        $totalCount = 0;
        if ($type == 'post') {
            $totalCount = \Db::instance()->count("select count(`id`) from `post`");
        } else {
            $totalCount = \Db::instance()->count("select count(`id`) from `keywords`");
        }

        // 每页20条.
        $perCount = 20;

        // 总的页数.
        $totalPage = ceil($totalCount / $perCount);

        // 处理的内容.
        $rows = [];
        if ($page <= $totalPage) {
            $rows = $this->upKeywords($page, $perCount, $type);
        }

        $this->assign('page', $page);
        $this->assign('type', $type);
        $this->assign('totalPage', $totalPage);
        $this->assign('totalCount', $totalCount);
        $this->assign('nextPage', $nextPage);
        $this->assign('rows', $rows);
        $this->display('admin/update/keywords');
    }

    /**
     * 更新全站文章缓存.
     * 
     * @return void
     */
    public function postitem() {
        $page = $this->getGet('page');
        $page = $page ? $page : 1;

        $limit = 20;

        $totalCount = \Db::instance()->count("select count(`id`) from `post`");

        $totalPage = ceil($totalCount / $limit);
        $nextPage = $page + 1;

        $offset = ($page - 1) * $limit;
        $rows = \Db::instance()->getList("select `post_id`, `title`,`author` from `post` order by `id` desc limit $limit offset $offset");
        foreach ($rows as $r) {
            $itemer = new \Logic\PostItemer($r['post_id'], true);
            $itemer->get();
        }

        $this->assign('page', $page);
        $this->assign('totalPage', $totalPage);
        $this->assign('totalCount', $totalCount);
        $this->assign('nextPage', $nextPage);
        $this->assign('rows', $rows);
        $this->display('admin/update/postitem');
    }

    /**
     * 主题详情数据更新.
     */
    public function topicitem() {
        $list = \Db::instance()->getList("select `topic_id` from `topic` where `status` = 1");
        foreach ($list as $v) {
            $r = new \Logic\TopicItemer($v['topic_id']);
            $r->setCache();
        }
        $this->assign('message', '主题详情更新完成');
        $this->display('admin/middle');
    }

    /**
     * 主题列表数据更新.
     */
    public function topiclist() {
        $cache_dir = CACHE_PATH . 'topiclist/';
        if (!file_exists($cache_dir)) {
            mkdir($cache_dir, 0777);
        }

        $limit = 20;
        $where = "`status` = '1' and `count` >= 3";
        $count = \Db::instance()->count("select count(1) from `topic` where $where");
        $total_page = ceil($count / $limit);
        file_put_contents($cache_dir . 'cache.topiclist.num', $total_page);

        for ($i = 1; $i <= $total_page; $i++) {
            $offset = ($i - 1) * $limit;
            $sql = "select `id`, `topic_id`, `title`, `long_title` from `topic` where $where order by `id` desc limit $limit offset $offset";
            $list = \Db::instance()->getList($sql);
            file_put_contents($cache_dir . 'cache.topiclist.' . $i, json_encode($list));
        }

        $this->assign('message', '主题列表更新完成');
        $this->display('admin/middle');
    }

    /**
     * 精选美文列表更新.
     */
    public function recommend() {
        // 缓存保存目录.
        $cache_dir = CACHE_PATH . 'recommend/';
        if (!file_exists($cache_dir)) {
            mkdir($cache_dir, 0777);
        }

        $limit = 12;
        $tc = \Db::instance()->count("select count(1) from `post` where `status` in('2','3')");
        $total_page = ceil($tc / $limit);

        // 最多更新五页.
        file_put_contents($cache_dir . 'cache.page.num', $total_page);

        for ($i = 1; $i <= $total_page; $i++) {
            $offset = ($i - 1) * $limit;
            $sql = "select `post_id`,`title`,`author`,`image_url`,`image_up_time`,`long_title` from `post` where `status` in('2','3') order by `update_time` desc limit $limit offset $offset";
            $list = \Db::instance()->getList($sql);

            foreach ($list as $k => $v) {
                // 获取post关联的topic tag.
                $list[$k]['relate_pt'] = \Logic\Homer::getRelatePt($v['post_id']);
            }

            file_put_contents($cache_dir . 'cache.recommend.' . $i, json_encode($list));
        }

        $this->assign('message', '精选美文列表数据更新完成');
        $this->display('admin/middle');
    }

    /**
     * 随机列表更新.
     */
    public function random() {
        $cache_dir = CACHE_PATH . 'random/';
        if (!file_exists($cache_dir)) {
            mkdir($cache_dir, 0777);
        }

        $where = "`status` in('1','2','3')";
        $total_count = \Db::instance()->count("select count(1) from `post` where $where");
        $limit = 20;

        $total = $total_page = ceil($total_count / $limit);

        for ($i = 1; $i <= $total_page; $i++) {
            $offset = ($i - 1) * $limit;
            $sql = "select `post_id`,`title`,`author`,`image_url`,`image_up_time`,`long_title` from `post` where $where order by `update_time` desc limit $limit offset $offset";
            $list = \Db::instance()->getList($sql);
            if (count($list) < 12) {
                $total = $total - 1;
                continue;
            }

            foreach ($list as $k => $v) {
                $list[$k]['relate_pt'] = \Logic\Homer::getRelatePt($v['post_id']);
            }
            file_put_contents($cache_dir . 'cache.random.' . $i, json_encode($list));
        }

        file_put_contents($cache_dir . 'cache.random.num', $total);
        $this->assign('message', '随机列表数据更新完成');
        $this->display('admin/middle');
    }

    /**
     * 微信文章列表缓存.
     */
    public function wxPostList() {
        $cache_dir = CACHE_PATH . 'wx/';
        if (!file_exists($cache_dir)) {
            mkdir($cache_dir, 0777);
        }

        $where = "p.post_id = v.post_id and p.status in('2','3')";
        $sql = "select count(1) from `post` as p, `post_view` as v where $where";
        $total_count = \Db::instance()->count($sql);
        $limit = 12;

        $total_page = ceil($total_count / $limit);
        for ($i = 1; $i <= $total_page; $i++) {
            $offset = ($i - 1) * $limit;
            $sql = "select p.`post_id`,p.`title`,p.`author`,p.`image_url`,p.`image_up_time`,p.`long_title` from `post` as p, `post_view` as v where $where order by v.`views` asc limit $limit offset $offset";
            $list = \Db::instance()->getList($sql);
            file_put_contents($cache_dir . 'post.list.' . $i, json_encode($list));
        }

        file_put_contents($cache_dir . 'total.page', $total_page);
        $this->assign('message', '微信文章列表数据更新完成');
        $this->display('admin/middle');
    }

    /**
     * 清理缓存.
     * 
     * @return void.
     */
    public function cleanData() {
        if (file_exists(CACHE_PATH . 'cache.index.post')) {
            unlink(CACHE_PATH . 'cache.index.post');
        }
        if (file_exists(CACHE_PATH . 'cache.index.topic')) {
            unlink(CACHE_PATH . 'cache.index.topic');
        }
        if (file_exists(CACHE_PATH . 'cache.recent')) {
            unlink(CACHE_PATH . 'cache.recent');
        }
        if (file_exists(CACHE_PATH . 'cache.hot')) {
            unlink(CACHE_PATH . 'cache.hot');
        }
        if (file_exists(CACHE_PATH . 'route.cache')) {
            unlink(CACHE_PATH . 'route.cache');
        }

        $sql = "delete from `keywords` where `count` = 0";
        \Db::instance()->execute($sql);

        // 尽量后台刷新缓存.
        \Logic\Homer::getCachePosts('index.post', 0, false);
        \Logic\Homer::getIndexTopic(true);
        \Logic\Homer::getCachePosts('hot', 0, false);
        \Logic\Homer::getCachePosts('recent', 0, false);

        $this->assign('message', '缓存更新完成');
        $this->display('admin/middle');
    }

    /**
     * 更新Db.
     * 
     * @param integer $page 当前页.
     * @param integer $perCount 每页记录.
     * @param string $type 更新类型, post or topic.
     * 
     * @return array.
     */
    private function upKeywords($page = 1, $perCount = 20, $type = 'post') {
        $return = [];

        $table = $type;
        $limit = $perCount;
        $offset = ($page - 1) * $perCount;
        if ($table == 'post') {
            $rows = \Db::instance()->getList("select `id`, `author`, `keywords` from `post` order by `id` asc limit $limit offset $offset");
            foreach ($rows as $r) {
                \Logic\Updater::upKeywords($r['keywords']);
                \Logic\Updater::upAuthorToKeywords($r['author']);
                $return[] = [
                    'id' => $r['id'],
                    'title' => $r['keywords'] . '#' . $r['author'],
                ];
            }
        }

        if ($table == 'keywords') {
            $rows = \Db::instance()->getList("select `id`, `keyword`, `type` from `keywords` order by `id` asc limit $limit offset $offset");
            foreach ($rows as $r) {
                \Logic\Updater::updateKeywordsInfo($r['id'], $r['keyword'], $r['type']);
                $return[] = [
                    'id' => $r['id'],
                    'title' => $r['keyword'] . '#' . $r['type'],
                ];
            }
        }

        return $return;
    }

}
