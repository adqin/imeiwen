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
            $param['title'] = $this->getPost('title');
            $param['author'] = $this->getPost('author');
            $param['image_url'] = $this->getPost('image_url');
            $param['content'] = $this->getPost('content');
            $param['long_title'] = $this->getPost('long_title');
            $param['keywords'] = $this->getPost('keywords');
            $param['description'] = $this->getPost('description');
            $param['weixin_url'] = $this->getPost('weixin_url');
            $param['weixin_up_datetime'] = $this->getPost('weixin_up_datetime');
            $param['status'] = $this->getPost('status');

            $poster = new \Logic\Poster($id = 0, $param);
            $poster->add();
        } else {
            $this->display('admin/post/add');
        }
    }
    
    /**
     * 编辑更新文章.
     */
    public function edit() {
        $id = $this->getGet('id');
        var_dump($id);
    }

}
