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
     * 首页推荐.
     * 
     * @return void
     */
    public function index() {
        $rows = \Db::instance()->getList("select `post_id`, `title`, `author`, `image_url`, `image_up_time`,`description` from `post`");
        $this->assign('rows', $rows);
        $this->display('home/recommend');
    }

    /**
     * 随机看看.
     */
    public function random() {
        
    }

}
