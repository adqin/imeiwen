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
        $this->info = \Db::instance()->getRow("select `post_id`, `category`, `keywords`, `author`, `status` from `post` where $where");
        $this->force_reload = $force_reload;
    }

    /**
     * 获取缓存.
     * 
     * @return 返回字符串.
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
            $result = $result ? json_decode($result, true) : '';
            if ($result) {
                $this->return = $result;
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

        // 查询作者与关键词关联的主题.
        $in = "('" . implode("','", $all) . "')";
        $where = "`post_keyword` in{$in} and `status` = '1' and `count` >= 10";
        $rs = \Db::instance()->getList("select `topic_id`,`post_keyword` from `topic` where $where order by `count` asc", 'post_keyword');
        $tplist_keywords = [];
        foreach ($all as $keyword) {
            $tplist_keywords[$keyword] = isset($rs[$keyword]) ? $rs[$keyword] : [];
        }

        $category = ',' . $this->info['category'] . ',';
        $post_status = ',' . $this->info['status'] . ',';
        $where = "`category` like '%$category%' and `post_status` like '%$post_status%' and `status` = 1 and `count` >= 10";
        $tplist_category = \Db::instance()->getList("select `topic_id`, `title` from `topic` where $where order by `count` asc");

        $return = [
            'keyword' => $tplist_keywords,
            'category' => $tplist_category,
        ];

        file_put_contents($this->cache_file, json_encode($return));
        $this->return = $return;
    }

    /**
     * 析构函数.
     */
    public function __destruct() {
        unset($this->info, $this->return);
    }

}
