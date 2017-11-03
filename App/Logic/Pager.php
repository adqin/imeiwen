<?php

/**
 * 单页面新增与编辑操作.
 * 
 * @author aic <41262633@qq.com>
 */

namespace Logic;

class Pager {

    private $id = 0; // 单页id.
    private $param = array(); // 更新的参数.
    private $info = array(); // 单页的旧信息.
    private $identify = ''; // 单页标识.

    public function __construct($id = 0, $param = array()) {
        $this->id = $id;
        $this->param = $param;

        if ($id) {
            $this->getInfo();
        }

        // 验证param参数.
        $this->validParam();
    }

    /**
     * 新增单页.
     * 
     * @return void
     */
    public function add() {
        $param = array(
            'title' => $this->param['title'],
            'identify' => $this->param['identify'],
            'content' => $this->param['content'],
        );

        $this->id = \Db::instance()->insert('single_page', $param);
        if ($this->id) {
            \Common::ajaxReturnSuccess('更新单页成功', ['id' => $this->id]);
        }

        \Common::ajaxReturnFalse('insert db失败');
    }

    /**
     * 编辑更新单页.
     * 
     * @return void
     */
    public function edit() {
        $param = array(
            'title' => $this->param['title'],
            'identify' => $this->param['identify'],
            'content' => $this->param['content'],
        );

        if (!\Db::instance()->updateById('single_page', $param, $this->id)) {
            \Common::ajaxReturnFalse('update db失败');
        }

        \Common::ajaxReturnSuccess('更新单页成功', ['id' => $this->id]);
    }

    /**
     * 验证单页更新参数.
     * 
     * @return void
     */
    private function validParam() {
        $fields = array(
            'title' => '标题',
            'identify' => '标识',
            'content' => '内容',
        );

        foreach ($fields as $k => $v) {
            if (!isset($this->param[$k]) || empty($this->param[$k])) {
                \Common::ajaxReturnFalse("{$k}:{$v}, 值未设置或是空值");
            }
        }

        // 验证标题是否存在.
        $where = "`title` = '" . $this->param['title'] . "'";
        if ($this->id) {
            $where .= " and `id` <> '$this->id'";
        }
        if (\Db::instance()->exists("select `id` from `single_page` where $where")) {
            \Common::ajaxReturnFalse("title: {$this->param['title']}, 单页标题已存在, 不要重复设置");
        }

        // 验证单页标识是否存在.
        $where = "`identify` = '" . $this->param['identify'] . "'";
        if ($this->id) {
            $where .= " and `id` <> '$this->id'";
        }
        if (\Db::instance()->exists("select `id` from `single_page` where $where")) {
            \Common::ajaxReturnFalse("identify: {$this->param['identify']}, 单页标识已存在, 不要重复设置");
        }
    }

    /**
     * 查询旧的单页信息.
     * 
     * @return void
     */
    private function getInfo() {
        $info = \Db::instance()->getRow("select * from `single_page` where `id` = '$this->id'");
        if (empty($info)) {
            \Common::ajaxReturnFalse("id: {$this->id}, 单页信息为空");
        }

        $this->info = $info;
        $this->identify = $info['identify'];
        $this->param['identify'] = $info['identify'];
    }

    /**
     * 析构函数.
     */
    public function __destruct() {
        unset($this->param, $this->info);
    }

}
