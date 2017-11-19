<?php

/**
 * 文章详情.
 */

namespace Logic;

class PostItemer {

    private $post_id = ''; // 文章的post_id.
    private $cache_dir = ''; // 缓存目录.
    private $cache_file = ''; // 对应的缓存文件名.
    private $info = array(); // 文章信息.
    private $force_reload = false; // 是否强制更新缓存.

    public function __construct($post_id = '', $force_reload = false) {
        $this->post_id = $post_id;

        // 判断参数.
        if (!$post_id) {
            \Common::showErrorMsg('post_id参数为空');
        }

        // 判断post_id是否存在.
        if (!\Db::instance()->exists("select `id` from `post` where `post_id` = '$post_id'")) {
            \Common::showErrorMsg("post_id: {$post_id}, 文章不存在");
        }

        $this->force_reload = $force_reload;

        // post缓存目录.
        $this->cache_dir = CACHE_PATH . 'post/' . $this->post_id[0] . '/' . $this->post_id[1] . '/';
        if (!file_exists($this->cache_dir)) {
            mkdir($this->cache_dir, 0777, true);
        }

        $this->cache_file = $this->cache_dir . 'cache.' . $this->post_id;
    }

    /**
     * 获取内容.
     * 
     * @return array.
     */
    public function get() {
        if ($this->force_reload) {
            // 如果强制更新缓存.
            $this->set();
        } elseif (!file_exists($this->cache_file)) {
            // 如果缓存文件不存在.
            $this->set();
        } else {
            // 从缓存中取文件.
            $cache_info = file_get_contents($this->cache_file);
            $info = $cache_info ? json_decode($cache_info, true) : [];
            if (!$info) {
                $this->set();
            } else {
                $this->info = $info;
            }
        }

        return $this->info;
    }

    /**
     * 设置缓存.
     * 
     * @return void
     */
    private function set() {
        // 查询post信息.
        $info = \Db::instance()->getRow("select * from `post` where `post_id` = '$this->post_id'");
        // 查询浏览信息.
        $view = \Db::instance()->getRow("select * from `page_view` where `post_id` = '$this->post_id'");

        // 查询关联文章.
        $relation = $this->getRelation($info);

        $return = [
            'post_id' => $info['post_id'],
            'title' => $info['title'],
            'author' => $info['author'],
            'image_url' => $info['image_url'],
            'image_up_time' => $info['image_up_time'],
            'content' => $this->formatContent($info['content']),
            'keywords' => trim($info['keywords'], ','),
            'description' => $info['description'],
            'input_time' => $info['input_time'],
            'update_time' => $info['update_time'],
            'weixin_url' => $info['weixin_url'],
            'weixin_up_datetime' => $info['weixin_up_datetime'],
            'page_view' => $view ? $view['views'] : 0,
            'relation' => $relation ? $relation : [],
        ];

        $this->info = $return;

        // 更新文件缓存.
        file_put_contents($this->cache_file, json_encode($return));
    }

    /**
     * 根据关键词和作者查询关联的文章.
     * 
     * @param array $info 文章信息.
     * 
     * @return array.
     */
    private function getRelation($info = array()) {
        $keywords = $info['keywords'];
        $keywords = trim($keywords, ',');
        $author = $info['author'];

        $arr = [];
        if ($keywords) {
            $arr = explode(',', $keywords);
        }
        $arr[] = $author;

        $where = "keyword in" . "('" . implode("','", $arr) . "')";
        $rs = \Db::instance()->getColumn("select `content` from `topic` where $where");

        $tmp = [];
        foreach ($rs as $r) {
            $t = explode(',', $r);
            if ($t) {
                $tmp = array_merge($tmp, $t);
            }
        }
        $post_ids = array_unique($tmp);

        // 查询文章.
        $return = [];
        if ($post_ids) {
            $where = "`post_id` in('" . implode("','", $post_ids) . "') and `status` in('1','2') and `post_id` <> '$this->post_id'";
            $rs = \Db::instance()->getList("select `post_id`,`title`,`author`,`image_url`,`image_up_time`,`description`,`status` from `post` where $where");
            foreach ($rs as $r) {
                $return['all'][] = $r;
                if ($r['status'] == 2) {
                    $return['tj'][] = $r;
                }
            }
        }
        return $return;
    }

    /**
     * 格式化处理文章内容, 将换行替换为p标签.
     * 
     * @param string $content 原内容.
     * 
     * @return string.
     */
    private function formatContent($content = '') {
        $return = '';

        if ($content) {
            $tmp = explode("\n", $content);
            foreach ($tmp as $t) {
                $t = trim($t);
                if ($t) {
                    $return .= "<p>" . $t . "</p>";
                }
            }
        }

        return $return;
    }

    /**
     * 析构函数.
     */
    public function __destruct() {
        unset($this->info);
    }

}