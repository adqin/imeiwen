<?php

/**
 * 主题详情.
 */

namespace Logic;

class TopicItemer {

    private $topic_id = '';
    private $info = [];
    private $post_ids = [];
    private $count = 0;
    private $topic_view = 0;

    public function __construct($topic_id = '') {
        $this->topic_id = $topic_id;
        // 查询topic信息.
        $this->getInfo();
        // 查询浏览次数.
        $this->getTopicView();
    }

    /**
     * 设置缓存.
     */
    public function setCache() {
        $this->getPostIds();
        // 更新topic数据库.
        \Db::instance()->updateById('topic', ['post_ids' => implode(',', $this->post_ids), 'count' => $this->count], $this->info['id']);
        $this->upCache();
    }

    /**
     * 开始更新.
     */
    private function upCache() {
        $cache_dir = CACHE_PATH . 'topiclist/' . $this->topic_id . '/';
        if (!file_exists($cache_dir)) {
            mkdir($cache_dir, 0777, true);
        }

        $info = [
            'topic_id' => $this->topic_id,
            'title' => $this->info['title'],
            'long_title' => $this->info['long_title'],
            'keywords' => $this->info['keywords'],
            'description' => $this->info['description'],
            'views' => $this->topic_view,
        ];
        file_put_contents($cache_dir . 'cache.topic.info', json_encode($info));

        $limit = 12;
        $where = "`status` in('1','2','3') and `post_id` in('" . implode("','", $this->post_ids) . "')";
        $total_count = \Db::instance()->count("select count(1) from `post` where $where");
        $total_page = ceil($total_count / $limit);
        file_put_contents($cache_dir . 'cache.topic.num', $total_page);

        for ($i = 1; $i <= $total_page; $i++) {
            $offset = ($i - 1) * $limit;
            $sql = "select `post_id`,`title`,`author`,`image_url`,`image_up_time`,`long_title` from `post` where $where order by `update_time` desc limit $limit offset $offset";
            $list = \Db::instance()->getList($sql);

            foreach ($list as $k => $v) {
                $list[$k]['relate_pt'] = $this->getRelatePt($v['post_id']);
            }

            file_put_contents($cache_dir . 'cache.topic.' . $i, json_encode($list));
        }
    }

    /**
     * 查询关联文章的post_ids.
     */
    private function getPostIds() {
        $where = "1=1";
        if ($this->info['category']) {
            $where .= " and `category` in('" . implode("','", $this->info['category']) . "')";
        }

        if ($this->info['post_keyword']) {
            $word = $this->info['post_keyword'];
            $type = \Db::instance()->getSimple("select `type` from `keywords` where `keyword` = '$word'");
            if ($type == 'keyword') {
                $where .= " and `keywords` like '%$word%'";
            } elseif ($type == 'author') {
                $where .= " and `author` = '$word'";
            }
        }

        if ($this->info['post_status']) {
            $where .= " and `status` in('" . implode("','", $this->info['post_status']) . "')";
        } else {
            $where .= " and `status` in('1','2','3')";
        }

        $this->post_ids = \Db::instance()->getColumn("select `post_id` from `post` where $where");
        $this->count = count($this->post_ids);
    }

    /**
     * 查询topic浏览次数.
     */
    private function getTopicView() {
        $sql = "select `views` from `topic_view` where `topic_id` = '$this->topic_id'";
        $views = \Db::instance()->getSimple($sql);
        $this->topic_view = $views ? $views : 0;
    }

    /**
     * 查询信息.
     */
    private function getInfo() {
        $where = "`topic_id` = '$this->topic_id'";
        $sql = "select `id`,`topic_id`,`title`,`long_title`,`keywords`,`description`,`category`,`post_keyword`,`post_status` from `topic` where $where";
        $this->info = \Db::instance()->getRow($sql);
        if ($this->info['category']) {
            $t = trim($this->info['category'], ',');
            $arr = explode(',', $t);
            $this->info['category'] = $arr;
        }

        if ($this->info['post_status']) {
            $t = trim($this->info['post_status'], ',');
            $arr = explode(',', $t);
            $this->info['post_status'] = $arr;
        }
    }

    /**
     * 查询relate_pt.
     * 
     * @param string $post_id 文章id.
     * 
     * @return array.
     */
    private function getRelatePt($post_id = '') {
        $return = ['keyword' => []];
        $relate_pt = \Logic\Homer::getRelatePt($post_id);
        if ($relate_pt['keyword']) {
            foreach ($relate_pt['keyword'] as $kw => $tid) {
                if ($tid && $this->info['post_keyword'] == $kw) {
                    $return['keyword'][$kw] = [];
                } else {
                    $return['keyword'][$kw] = $tid;
                }
            }
        }

        return $return;
    }

    /**
     * 析构函数.
     */
    public function __destruct() {
        unset($this->info, $this->post_ids);
    }

}
