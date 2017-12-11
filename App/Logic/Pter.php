<?php

/**
 * 更新文章的tag，根据post的作者，关键词来组合.
 */

namespace Logic;

class Pter {

    private $post_id = ''; // post主键id.
    private $return = []; // 返回.
    private $info = []; // 文章信息.
    private $force_reload = false; // 是否强制更新.
    private $cache_file = ''; // 缓存保存的文件.

    public function __construct($post_id = '', $force_reload = false) {
        $this->post_id = $post_id;
        $where = "`post_id` = '$post_id' and `status` in('1','2','3')";
        $this->info = \Db::instance()->getRow("select `post_id`,`keywords`,`author` from `post` where $where");
        $this->force_reload = $force_reload;
    }

    /**
     * 获取缓存.
     */
    public function getCache() {
        if (!$this->info) {
            // 文章不存在, 直接返回.
            return $this->return;
        }

        $cache_dir = CACHE_PATH . 'post/' . $this->post_id[0] . '/' . $this->post_id[1];
        if (!file_exists($cache_dir)) {
            mkdir($cache_dir, 0777, true);
        }

        // 缓存的文件.
        $this->cache_file = $cache_dir . '/cache.' . $this->post_id . '.pt';
        if (!file_exists($this->cache_file)) {
            // 文件不存在，查询并更新缓存.
            $this->setCache();
        } elseif ($this->force_reload) {
            // 如果要求强制更新缓存.
            $this->setCache();
        } else {
            // 文件存在，并且没有要求强制要更新缓存.
            $result = file_get_contents($this->cache_file);
            $return = $result ? json_decode($result, true) : [];
            if ($return) {
                $this->return = $return;
            } else {
                // 文件内容为空.
                $this->setCache();
            }
        }

        return $this->return;
    }

    /**
     * 查询并更新缓存.
     */
    private function setCache() {
        $author = $this->info['author'];
        $keywords = explode(',', $this->info['keywords']);
        $all[] = $author;
        foreach ($keywords as $keyword) {
            if ($keyword) {
                $all[] = $keyword;
            }
        }
        
        $in = "('" . implode("','", all) . "')";
        $where = "`keyword` in{$in} and `status` = 2";
        $tplist = \Db::instance()->getList("select `keyword`, `identify` from `topic` where $where order ");
    }

    /**
     * 析构函数.
     */
    public function __destruct() {
        unset($this->info, $this->return);
    }

}
