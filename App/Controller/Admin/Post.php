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
     * 文章列表.
     * 
     * @return void
     */
    public function index() {
        $this->display('admin/post/index');
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
     * 
     * @return void
     */
    public function edit() {
        if (\Common::isPost()) {
            $id = $this->getPost('id');
            if (!$id) {
                \Common::ajaxReturnFalse('id POST参数有误');
            }

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

            $poster = new \Logic\Poster($id, $param);
            $poster->edit();
        } else {
            $id = $this->getGet('id');
            if (!$id) {
                exit("id GET参数有误");
            }

            $info = \Db::instance()->getRow("select * from `post` where `id` = '$id'");
            if (empty($info)) {
                exit("id: {$id}, 文章信息没找到");
            }

            $this->assign('info', $info);
            $this->display('admin/post/edit');
        }
    }

}
