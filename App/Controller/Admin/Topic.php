<?php

/**
 * 主题列表与更新.
 * 
 * @author aic <41262633@qq.com>
 */

namespace Controller\Admin;

class Topic extends \Controller\Admin\Init {

    public function __construct() {
        parent::__construct();
    }

    /**
     * topic列表.
     * 
     * @return void
     */
    public function index() {
        $this->display('admin/topic/index');
    }

    /**
     * 新增主题.
     * 
     * @return void
     */
    public function add() {
        if (\Common::isPost()) {
            // 提交表单.
            $param['topic_id'] = $this->getPost('topic_id');
            $param['title'] = $this->getPost('title');
            $param['long_title'] = $this->getPost('long_title');
            $param['keywords'] = $this->getPost('keywords');
            $param['description'] = $this->getPost('description');
            $param['category'] = $this->getPost('category');
            $param['post_keyword'] = $this->getPost('post_keyword');
            $param['post_status'] = $this->getPost('post_status');
            $param['status'] = $this->getPost('status');
            $topicer = new \Logic\Topicer(0, $param);
            $topicer->add();
        } else {
            $this->display('admin/topic/add');
        }
    }

    /**
     * topic编辑更新.
     * 
     * @return void
     */
    public function edit() {
        if (\Common::isPost()) {
            $id = $this->getPost('id');
            if (!$id) {
                \Common::ajaxReturnFalse('id POST参数有误');
            }

            $param['topic_id'] = $this->getPost('topic_id');
            $param['title'] = $this->getPost('title');
            $param['long_title'] = $this->getPost('long_title');
            $param['keywords'] = $this->getPost('keywords');
            $param['description'] = $this->getPost('description');
            $param['category'] = $this->getPost('category');
            $param['post_keyword'] = $this->getPost('post_keyword');
            $param['post_status'] = $this->getPost('post_status');
            $param['status'] = $this->getPost('status');
            $topicer = new \Logic\Topicer($id, $param);
            $topicer->edit();
        } else {
            $id = $this->getGet('id');
            if (!$id) {
                exit("id GET参数有误");
            }

            $info = \Db::instance()->getRow("select * from `topic` where `id` = '$id'");
            
            if (empty($info)) {
                exit("id: {$id}, 文章信息没找到");
            }

            $this->assign('info', $info);
            $this->display('admin/topic/edit');
        }
    }

    /**
     * 主题列表搜索.
     * 
     * @return void
     */
    public function search() {
        $page = $this->getPost('page');
        $page = $page ? $page : 1;
        $limit = $this->getPost('limit');
        $limit = $limit ? $limit : 20;
        $status = $this->getPost('status');

        $where = "1=1";
        if ($status !== '') {
            $where .= " and `status` = '$status'";
        }

        $order = "order by `count` desc";

        $return = array(
            'code' => 0,
            'msg' => '',
            'count' => \Db::instance()->count("select count(`id`) from `topic` where $where"),
            'data' => array(),
        );

        $offset = ($page - 1) * $limit;
        $sql = "select * from `topic` where $where $order limit $limit offset $offset";
        $return['data'] = $this->formatSearchData(\Db::instance()->getList($sql));

        \Common::ajaxOut($return);
    }

    /**
     * keywords列表搜索数据格式化.
     * 
     * @param array $data 源数据.
     * 
     * @return array.
     */
    private function formatSearchData($data = array()) {
        if (!$data) {
            return $data;
        }

        $status_title = array(
            0 => '隐藏',
            1 => '显示',
        );
        $return = array();
        foreach ($data as $d) {
            $return[] = array(
                'id' => $d['id'],
                'topic_id' => $d['topic_id'],
                'title' => '<a href="/topic/' . $d['topic_id'] . '" target="_blank">' . $d['title'] . '</a>',
                'long_title' => $d['long_title'],
                'category' => trim($d['category'], ','),
                'post_keyword' => $d['post_keyword'],
                'post_status' => trim($d['post_status'], ','),
                'count' => $d['count'],
                'status' => $status_title[$d['status']],
                'op_string' => '<a href="/admin/topic/edit?id=' . $d['id'] . '" class="layui-btn">修改<i class="layui-icon">&#xe642;</i></a>',
            );
        }

        return $return;
    }

}
