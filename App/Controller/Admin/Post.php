<?php

/**
 * 文章列表与发布.
 * 
 * @author aic <41262633@qq.com>
 */

namespace Controller\Admin;

/**
 * Class Post.
 */
class Post extends \Controller\Admin\Init {

    /**
     * 构造函数.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * 新发布文章.
     * 
     * @return void
     */
    public function add() {
        if (\Common::isPost()) {
            //print_r($_POST);
            $img_data = base64_decode(explode(',', $_POST['image_url'])[1]);
            print_r(getimagesizefromstring($img_data));
            echo strlen($img_data);
        } else {
            $this->display('admin/post/add');
        }
    }

}
