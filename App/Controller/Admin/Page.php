<?php

/**
 * 单页列表与新建更新.
 * 
 * @author aic <41262633@qq.com>
 */

namespace Controller\Admin;

class Page extends \Controller\Admin\Init {

    /**
     * 构造函数.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * 单页列表.
     * 
     * @return void
     */
    public function index() {
        $this->display('admin/page/index');
    }

    /**
     * 新增单页.
     * 
     * @return void
     */
    public function add() {
        if (\Common::isPost()) {
            $param = array(
                'title' => $this->getPost('title'),
                'identify' => $this->getPost('identify'),
                'content' => $this->getPost('content'),
            );
            $pager = new \Logic\Pager($id = 0, $param);
            $pager->add();
        } else {
            $this->display('admin/page/add');
        }
    }

    /**
     * 编辑更新单页.
     * 
     * @return void
     */
    public function edit() {
        if (\Common::isPost()) {
            $id = $this->getPost('id');
            if (!$id) {
                \Common::ajaxReturnFalse("id POST参数有误");
            }

            $param = array(
                'title' => $this->getPost('title'),
                'content' => $this->getPost('content'),
            );
            $pager = new \Logic\Pager($id, $param);
            $pager->edit();
        } else {
            $id = $this->getGet('id');
            if (!$id) {
                exit("id GET参数有误");
            }

            $info = \Db::instance()->getRow("select * from `single_page` where `id` = '$id'");
            if (!$info) {
                exit("id: {$id}, 单页信息没找到");
            }

            $this->assign('info', $info);
            $this->display('admin/page/edit');
        }
    }

    /**
     * 文章列表搜索数据.
     * 
     * @return void
     */
    public function search() {
        $where = "1=1";
        $order = "order by `id` desc";

        $return = array(
            'code' => 0,
            'msg' => '',
            'count' => 100,
            'data' => array(),
        );

        $sql = "select * from `single_page` where $where";
        $return['data'] = $this->formatSearchData(\Db::instance()->getList($sql));

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

        $return = array();
        foreach ($data as $d) {
            $return[] = array(
                'id' => $d['id'],
                'title' => '<a href="/page/' . $d['identify'] . '" class="page_item" target="_blank">' . $d['title'] . '</a>',
                'op_string' => '<a href="/admin/page/edit?id=' . $d['id'] . '" class="layui-btn">修改<i class="layui-icon">&#xe642;</i></a>',
            );
        }

        return $return;
    }

}
