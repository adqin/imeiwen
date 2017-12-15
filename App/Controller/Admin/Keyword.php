<?php

/**
 * 主题列表与更新.
 * 
 * @author aic <41262633@qq.com>
 */

namespace Controller\Admin;

class Keyword extends \Controller\Admin\Init {

    public function __construct() {
        parent::__construct();
    }

    /**
     * topic列表.
     * 
     * @return void
     */
    public function index() {
        $this->display('admin/keyword/index');
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
        $keyword = $this->getPost('keyword');

        $where = "1=1";
        if ($keyword) {
            $where .= " and `keyword` like '%$keyword%'";
        }
        
        $order = "order by `count` desc";

        $return = array(
            'code' => 0,
            'msg' => '',
            'count' => \Db::instance()->count("select count(`id`) from `keywords` where $where"),
            'data' => array(),
        );

        $offset = ($page -1) * $limit;
        $sql = "select * from `keywords` where $where $order limit $limit offset $offset";
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

        $return = array();
        foreach ($data as $d) {
            $return[] = array(
                'id' => $d['id'],
                'keyword' => $d['keyword'],
                'type' => $d['type'],
                'count' => $d['count'],
            );
        }

        return $return;
    }

}
