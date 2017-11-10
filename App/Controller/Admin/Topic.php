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
            
            $param['identify'] = $this->getPost('identify');
            $param['title'] = $this->getPost('title');
            $param['note'] = $this->getPost('note');
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

        $sql = "select * from `topic` where $where $order";
        $return['data'] = $this->formatSearchData(\Db::instance()->getList($sql));

        \Common::ajaxOut($return);
    }

    /**
     * topic列表搜索数据格式化.
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
                'keyword' => $d['identify'] ? '<a href="/topic/' . $d['identify'] . '" class="topic_item" target="_blank">' . $d['keyword'] . '|' . $d['identify'] . '</a>' : $d['keyword'],
                'type' => $d['type'],
                'count' => $d['count'],
                'status' => $d['status'] ? '显示' : '隐藏',
                'op_string' => '<a href="/admin/topic/edit?id=' . $d['id'] . '" class="layui-btn">修改<i class="layui-icon">&#xe642;</i></a>',
            );
        }

        return $return;
    }

}
