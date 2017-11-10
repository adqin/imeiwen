<?php

/**
 * topic编辑管理.
 * 
 * @author aic <41262633@qq.com>
 */

namespace Logic;

class Topicer {

    private $id = 0; //topic.id
    private $param = []; // 更新的参数.
    private $info = []; // 编辑的旧的topic信息.

    public function __construct($id = 0, $param = []) {
        $this->id = $id;
        $this->param = $param;

        if ($id) {
            $this->getInfo();
        }
    }

    /**
     * 编辑更新.
     * 
     * @return void
     */
    public function edit() {
        if (!$this->info) {
            \Common::ajaxReturnFalse("topic信息为空, 不能编辑");
        }

        // 参数验证.
        $this->valid();

        if ($this->saveDb()) {
            \Common::ajaxReturnSuccess('更新成功');
        } else {
            \Common::ajaxReturnFalse('更新失败');
        }
    }

    /**
     * 保存更新数据库.
     * 
     * @return boolean.
     */
    private function saveDb() {
        $param = [
            'identify' => $this->param['identify'],
            'title' => $this->param['title'],
            'note' => $this->param['note'],
            'status' => $this->param['status'],
        ];

        return \Db::instance()->updateById('topic', $param, $this->id);
    }

    /**
     * 基本参数验证.
     * 
     * @return void
     */
    private function valid() {
        $fields = ['identify', 'title', 'note', 'status'];

        foreach ($fields as $field) {
            if (!isset($this->param[$field])) {
                \Common::ajaxReturnFalse("{$field}字段值必须设置");
            }
        }

        if (!in_array($this->param['status'], array(0, 1))) {
            \Common::ajaxReturnFalse("status字段值不符合规范");
        }

        if ($this->param['status']) {
            if (!$this->param['identify'] || !$this->param['title'] || !$this->param['note']) {
                \Common::ajaxReturnFalse("topic显示时, 标识, 标题, 描述不能为空");
            }
        }

        // 验证identify是否重复.
        $where = "`identify` = '" . $this->param['identify'] . "'";
        if ($this->id) {
            $where .= " and `id` <> '$this->id'";
        }
        if (\Db::instance()->exists("select `id` from `topic` where $where")) {
            \Common::ajaxReturnFalse("{$this->param['identify']}已经存在, 不能重复");
        }
    }

    /**
     * 获取旧的topic信息.
     */
    private function getInfo() {
        $info = \Db::instance()->getRow("select `id`, `identify` from `topic` where `id` = '$this->id'");
        if (!$info) {
            \Common::ajaxReturnFalse("id: {$this->id}, topic信息不存在");
        }

        $this->info = $info;
        if ($info['identify']) {
            $this->param['identify'] = $info['identify'];
        }
    }

}
