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
     * 清理下缓存.
     * 
     * @return void.
     */
    public function cleanData() {
        if (file_exists(CACHE_PATH . 'cache.index.post')) {
            unlink(CACHE_PATH . 'cache.index.post');
        }
        if (file_exists(CACHE_PATH . 'cache.random')) {
            unlink(CACHE_PATH . 'cache.random');
        }
        if (file_exists(CACHE_PATH . 'cache.recent')) {
            unlink(CACHE_PATH . 'cache.recent');
        }
        if (file_exists(CACHE_PATH . 'cache.hot')) {
            unlink(CACHE_PATH . 'cache.hot');
        }

        $sql = "delete from `keywords` where `count` = 0";
        \Db::instance()->execute($sql);

        // 尽量后台刷新缓存.
        \Logic\Homer::getCachePosts('index.post', 0, false);
        \Logic\Homer::getCachePosts('hot', 0, false);
        \Logic\Homer::getCachePosts('random', 0, false);
        \Logic\Homer::getCachePosts('recent', 0, false);

        $this->assign('message', '缓存更新完成');
        $this->display('admin/middle');
    }

    /**
     * 主题数据更新.
     */
    public function topic() {
        $topic_list = \Db::instance()->getList("select `id`, `keyword`, `identify`, `title`, `note`, `content` from `topic` where `status` = '1'");
        foreach ($topic_list as $t) {
            \Logic\Homer::updateTopicDetail($t['id']);
        }

        $this->assign('message', '主题数据更新完成');
        $this->display('admin/middle');
    }

    /**
     * 更新推荐阅读缓存.
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
        $total = $total_page > 5 ? 5 : $total_page;
        file_put_contents($cache_dir . 'cache.page.num', $total);

        for ($i = 1; $i <= $total; $i++) {
            $offset = ($i - 1) * $limit;
            $sql = "select `post_id`,`title`,`author`,`image_url`,`image_up_time`,`keywords`,`description` from `post` where `status` in('2','3') order by `update_time` desc limit $limit offset $offset";
            $list = \Db::instance()->getList($sql);

            foreach ($list as $k => $v) {
                // 获取post关联的topic tag.
                $list[$k]['relate_pt'] = \Logic\Homer::getRelatePt($v['post_id']);
            }

            file_put_contents($cache_dir . 'cache.recommend.' . $i, json_encode($list));
        }

        $this->assign('message', '推荐阅读数据更新完成');
        $this->display('admin/middle');
    }

    /**
     * 每日一文数据更新.
     */
    public function meiriyiwen() {
        $list = \Db::instance()->getList("select `post_id`,`title`,`author`,`image_url`,`image_up_time`,`description`,`weixin_up_datetime` from `post` where `weixin_up_datetime` > 0 and `status` in('1','2','3') order by `weixin_up_datetime` desc");
        $dates = $info = $default = [];

        foreach ($list as $k => $v) {
            // 获取post关联的topic tag.
            $list[$k]['relate_pt'] = \Logic\Homer::getRelatePt($v['post_id']);
        }

        $i = 0;
        foreach ($list as $r) {
            if ($i < 30) {
                $default[] = $r; // 默认30天.
            }

            $year = date('Y', $r['weixin_up_datetime']); // 年
            $month = date('m', $r['weixin_up_datetime']); // 月

            $dates[$year . $month] = $year . '年' . $month . '月';
            $info[$year][$month][] = $r;

            $i++;
        }

        $cacheDir = CACHE_PATH . 'mryw';
        if (!file_exists($cacheDir)) {
            mkdir($cacheDir, 0777);
        }

        file_put_contents($cacheDir . '/cache.dates', json_encode($dates));
        file_put_contents($cacheDir . '/cache.default', json_encode($default));
        foreach ($info as $y => $ms) {
            foreach ($ms as $m => $i) {
                file_put_contents($cacheDir . '/cache.' . $y . $m, json_encode($i));
            }
        }

        $this->assign('message', '每日一文数据更新完成');
        $this->display('admin/middle');
    }

    /**
     * 更新全站文章缓存.
     * 
     * @return void
     */
    public function post() {
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
        $this->display('admin/update/post');
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
