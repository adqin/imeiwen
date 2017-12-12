<?php

/**
 * 文章主题.
 * 
 * @author aic <41262633@qq.com>
 */

namespace Controller;

class Topic extends \Controller\Base {

    /**
     * 主题关联的文章列表页.
     */
    public function index() {
        $identify = isset($this->param['identify']) ? $this->param['identify'] : '';
        if (!$identify) {
            \Common::noPage('主题页url参数有误');
        }

        $cache_dir = CACHE_PATH . 'topic/' . $identify[0] . '/' . $identify[1] . '/';
        $total_page = file_get_contents($cache_dir . 'cache.' . $identify . '.page.num');
        if (!$total_page) {
            \Common::noPage('主题' . $identify . '数据未更新');
        }

        $page = isset($this->param['page']) ? $this->param['page'] : 1;
        $page = (!$page || $page > $total_page) ? 1 : $page;
        $result = file_get_contents($cache_dir . 'cache.' . $identify . '.list.' . $page);
        $result = $result ? json_decode($result, true) : [];
        if (!$result) {
            \Common::noPage('主题' . $identify . '数据未更新');
        }

        $list = isset($result['post_list']) && $result['post_list'] ? $result['post_list'] : [];
        if (!$result) {
            \Common::noPage('主题' . $identify . '数据未更新');
        }
        
        $this->assign('identify', $identify);
        $this->assign('page', $page);
        $this->assign('total_page', $total_page);
        $this->assign('result', $result);
        $this->assign('list', $list);
        $this->assign('this_menu', '');
        $this->display('home/topic_item');
    }

}
