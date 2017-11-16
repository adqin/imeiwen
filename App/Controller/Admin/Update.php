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

        $type = $this->getGet('type');
        $type = $type ? $type : 'post'; // post or topic.
        // 计算总的条数.
        $totalCount = 0;
        if ($type == 'post') {
            $totalCount = \Db::instance()->count("select count(`id`) from `post`");
        } else {
            $totalCount = \Db::instance()->count("select count(`id`) from `topic`");
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
        $this->assign('rows', $rows);
        $this->display('admin/update/keywords');
    }
    
    /**
     * 清理下缓存.
     * 
     * @return void.
     */
    public function cleancache() {
        if (file_exists(CACHE_PATH . 'cache.random')) {
            unlink(CACHE_PATH . 'cache.random');
        }
        if (file_exists(CACHE_PATH . 'cache.recent')) {
            unlink(CACHE_PATH . 'cache.recent');
        }
        if (file_exists(CACHE_PATH . 'cache.recommend')) {
            unlink(CACHE_PATH . 'cache.recommend');
        }
        
        $this->assign('message', '缓存清理完成');
        $this->display('admin/mid');
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
                \Logic\Updater::upKeywordsToTopic($r['keywords']);
                \Logic\Updater::upAuthorToTopic($r['author']);
                $return[] = [
                    'id' => $r['id'],
                    'title' => $r['keywords'] . '#' . $r['author'],
                ];
            }
        }
        
        if ($table == 'topic') {
            $rows = \Db::instance()->getList("select `id`, `keyword`, `type` from `topic` order by `id` asc limit $limit offset $offset");
            foreach ($rows as $r) {
                \Logic\Updater::updateTopicInfo($r['id'], $r['keyword'], $r['type']);
                $return[] = [
                    'id' => $r['id'],
                    'title' => $r['keyword'] . '#' . $r['type'],
                ];
            }
        }

        return $return;
    }

}
