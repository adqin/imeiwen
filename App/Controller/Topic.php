<?php

/**
 * 文章主题.
 * 
 * @author aic <41262633@qq.com>
 */

namespace Controller;

class Topic extends \Controller\Base {

    /**
     * 主题列表.
     */
    public function index() {
        $page = isset($this->param['page']) ? $this->param['page'] : 1;
        $total_page = file_get_contents(CACHE_PATH . 'topiclist/cache.topiclist.num');
        $page = !$page || $page > $total_page ? 1 : $page;

        $topic_list = json_decode(file_get_contents(CACHE_PATH . 'topiclist/cache.topiclist.' . $page), true);
        $this->assign('page', $page);
        $this->assign('total_page', $total_page);
        $this->assign('topic_list', $topic_list);
        $this->assign('this_menu', 'topiclist');
        $this->display('home/topic_list');
    }

    /**
     * 主题详情页.
     */
    public function item() {
        $topic_id = isset($this->param['topic_id']) ? $this->param['topic_id'] : '';
        if (!$topic_id) {
            \Common::noPage('主题页url参数有误');
        }
        $page = isset($this->param['page']) ? $this->param['page'] : 1;

        $cache_dir = CACHE_PATH . 'topiclist/' . $topic_id . '/';
        $file_num = $cache_dir . 'cache.topic.num';
        $file_info = $cache_dir . 'cache.topic.info';
        if (!file_exists($file_info) || !file_exists($file_num)) {
            \Common::noPage("主题页未更新");
        }

        $info = json_decode(file_get_contents($file_info), true);
        $total_page = file_get_contents($file_num);
        if (!$page || $page > $total_page) {
            $page = 1;
        }
        $file_list = $cache_dir . 'cache.topic.' . $page;
        if (!file_exists($file_list)) {
            \Common::noPage("主题页未更新");
        }

        $post_list = json_decode(file_get_contents($file_list), true);

        $this->assign('topic_id', $topic_id);
        $this->assign('page', $page);
        $this->assign('total_page', $total_page);
        $this->assign('info', $info);
        $this->assign('post_list', $post_list);
        $this->assign('this_menu', 'topiclist');
        $this->display('home/topic_item');
    }

}
