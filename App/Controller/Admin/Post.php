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

    /**
     * 文章列表搜索数据.
     * 
     * @return void
     */
    public function search() {
        $page = $this->getPost('page');
        $page = $page ? $page : 1;
        $limit = $this->getPost('limit');
        $limit = $limit ? $limit : 20;

        // 查询条件.
        $title = $this->getPost('title');
        $author = $this->getPost('author');
        $isMryw = $this->getPost('isMryw');
        $status = $this->getPost('status');

        $where = "1=1";
        $order = "order by `update_time` desc";
        if ($title) {
            $where .= " and `title` like '%$title%'";
        }
        if ($author) {
            $where .= " and `author` = '$author'";
        }
        if ($isMryw == 1) {
            $where .= " and `weixin_up_datetime` > 0";
            $order = "order by `weixin_up_datetime` desc";
        }
        if ($isMryw == 2) {
            $where .= " and `weixin_up_datetime` = 0";
        }
        
        if ($status !== '') {
            $where .= " and `status` = '$status'";
        }

        // 计算总的条数.
        $count = \Db::instance()->count("select count(`id`) from `post` where $where");
        $return = array(
            'code' => 0,
            'msg' => '',
            'count' => $count,
            'data' => array(),
        );

        if ($count) {
            $offset = ($page - 1) * $limit;
            $sql = "select * from `post` where $where $order limit $limit offset $offset";
            $return['data'] = $this->formatSearchData(\Db::instance()->getList($sql));
        }

        \Common::ajaxOut($return);
    }

    /**
     * 文章列表搜索数据格式化.
     * 
     * @param array $data 源数据.
     * 
     * @return array.
     */
    private function formatSearchData($data = array()) {
        if (!$data) {
            return $data;
        }

        $status_title = [
            '0' => '隐藏',
            '1' => '显示',
            '2' => '推荐',
            '3' => '首页推荐',
        ];
        $return = array();
        foreach ($data as $d) {
            $return[] = array(
                'id' => $d['id'],
                'thumb' => $d['image_url'] ? '<img src="' . \Config\Qiniu::$domain . $d['image_url'] . '?imageView2/2/w/200/' . $d['image_up_time'] . '" width="200">' : '',
                'title' => '<a href="/post/' . $d['post_id'] . '" class="post_item" target="_blank">' . $d['title'] . '</a>',
                'author' => $d['author'],
                'keywords' => trim($d['keywords'], ','),
                'status' => $status_title[$d['status']],
                'weixin_string' => $d['weixin_up_datetime'] ? '<a href="' . $d['weixin_url'] . '" class="weixin_url" target="_blank">' . date('Y-m-d', $d['weixin_up_datetime']) : '',
                'op_string' => '<a href="/admin/post/edit?id=' . $d['id'] . '" class="layui-btn">修改<i class="layui-icon">&#xe642;</i></a>',
            );
        }

        return $return;
    }

}
