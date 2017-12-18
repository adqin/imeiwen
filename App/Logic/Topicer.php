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
    private $post_ids = []; // 主题关联的post_ids.
    private $count = 0; // 主题关联的文章数.

    public function __construct($id = 0, $param = []) {
        $this->id = $id;

        if (isset($param['category'])) {
            $param['category'] = $this->formatString($param['category']);
        }
        if (isset($param['keywords'])) {
            $param['keywords'] = $this->formatString($param['keywords']);
        }
        if (isset($param['post_status'])) {
            $param['post_status'] = $this->formatString($param['post_status']);
        }

        $this->param = $param;

        if ($id) {
            $this->getInfo();
        }
    }

    /**
     * 新增主题.
     * 
     * @return void
     */
    public function add() {
        // 参数验证.
        $this->valid();

        // 保存新增.
        $this->saveDb();
    }

    /**
     * 编辑更新.
     * 
     * @return void
     */
    public function edit() {
        if (!$this->info) {
            \Common::ajaxReturnFalse("主题信息为空, 不能编辑");
        }

        // 参数验证.
        $this->valid();

        // 保存更新.
        $this->saveDb();
    }

    /**
     * 保存更新数据库.
     * 
     * @return boolean.
     */
    private function saveDb() {
        $param = [
            'topic_id' => $this->param['topic_id'],
            'title' => $this->param['title'],
            'long_title' => $this->param['long_title'],
            'keywords' => $this->param['keywords'] ? implode(',', $this->param['keywords']) : '',
            'description' => $this->param['description'],
            'category' => $this->param['category'] ? ',' . implode(',', $this->param['category']) . ',' : '',
            'post_keyword' => $this->param['post_keyword'],
            'post_status' => $this->param['post_status'] ? ',' . implode(',', $this->param['post_status']) . ',' : '',
            'post_ids' => $this->post_ids ? implode(',', $this->post_ids) : '',
            'count' => $this->count,
            'status' => $this->param['status'],
        ];

        $return = true;
        if ($this->id) {
            $result = \Db::instance()->updateById('topic', $param, $this->id);
            if ($result === false) {
                $return = $result;
            }
        } else {
            $return = \Db::instance()->insert('topic', $param);
            $this->id = $return;
        }

        if ($return) {
            \Common::ajaxReturnSuccess("主题更新成功", ['id' => $this->id]);
        } else {
            \Common::ajaxReturnFalse("主题更新失败");
        }
    }

    /**
     * 格式化参数.
     * 
     * @param array $param 参数.
     * 
     * @return array.
     */
    private function formatString($string = '') {
        if (!$string) {
            return [];
        }

        $string = str_replace("，", ',', $string);
        $tmp = explode(',', $string);
        $return = [];

        foreach ($tmp as $t) {
            $t = trim($t);
            if ($t) {
                $return[] = $t;
            }
        }

        if ($return) {
            $return = array_unique($return);
        }

        return $return;
    }

    /**
     * 基本参数验证.
     * 
     * @return void
     */
    private function valid() {
        $fields = [
            'topic_id' => '主题id',
            'title' => '主题标题',
            'long_title' => '长标题',
            'keywords' => '关键词描述',
            'description' => '主题描述',
            'category' => '关联分类',
            'post_keyword' => '关联关键词',
            'post_status' => '文章筛选状态',
            'status' => '主题状态',
        ];

        foreach ($fields as $field => $title) {
            if (!isset($this->param[$field])) {
                \Common::ajaxReturnFalse("{$title}字段值必须设置");
            }
        }

        if (!$this->param['topic_id']) {
            \Common::ajaxReturnFalse("{$fields['topic_id']}必须填写");
        }

        if (!ctype_alpha($this->param['topic_id'])) {
            \Common::ajaxReturnFalse("{$fields['topic_id']}应填写字母组合");
        }

        if (!$this->param['title']) {
            \Common::ajaxReturnFalse("{$fields['title']}必须填写");
        }

        if (!in_array($this->param['status'], array(0, 1))) {
            \Common::ajaxReturnFalse("status字段值不符合规范");
        }

        if ($this->param['status']) {
            if (!$this->param['long_title'] || !$this->param['keywords'] || !$this->param['description']) {
                \Common::ajaxReturnFalse("主题为显示, {$fields['long_title']}, {$fields['keywords']}, {$fields['description']}不能为空");
            }

            if (!$this->param['category'] && !$this->param['post_keyword'] && !$this->param['post_status']) {
                \Common::ajaxReturnFalse("主题为显示, {$fields['category']}, {$fields['post_keyword']}, {$fields['post_status']}需要至少设置1项");
            }
        }

        if ($this->param['long_title']) {
            if (strlen($this->param['long_title']) > 200) {
                \Common::ajaxReturnFalse("{$fields['long_title']}长度应在200字符内");
            }
        }

        if ($this->param['description']) {
            if (strlen($this->param['description']) > 300) {
                \Common::ajaxReturnFalse("{$fields['description']}长度应在300字符内");
            }
        }

        if ($this->param['category'] && $ret = array_diff($this->param['category'], array_keys(\Config\System::$category))) {
            \Common::ajaxReturnFalse("{$fields['category']}有错误的分类ID");
        }

        if ($this->param['post_keyword']) {
            if (!\Db::instance()->exists("select `id` from `keywords` where `keyword` = '" . $this->param['post_keyword'] . "'")) {
                \Common::ajaxReturnFalse("{$fields['post_keyword']}值不存在");
            }
        }

        if ($this->param['post_status'] && array_diff($this->param['post_status'], array(1, 2, 3))) {
            \Common::ajaxReturnFalse("{$fields['post_status']}有错误的状态值");
        }
        
        // 查询下主题关联的真实的post_ids.
        $this->getRealPost();
        
        if ($this->param['status']) {
            if ($this->count < 10) {
                \Common::ajaxReturnFalse("主题关联的有效文章数量小于10条，不能显示");
            }
        }

        // 验证topic_id是否重复.
        $where = "`topic_id` = '" . $this->param['topic_id'] . "'";
        if ($this->id) {
            $where .= " and `id` <> '$this->id'";
        }
        if (\Db::instance()->exists("select `id` from `topic` where $where")) {
            \Common::ajaxReturnFalse("{$this->param['topic_id']}已经存在, 不能重复");
        }
    }

    /**
     * 返回符合条件的主题关联的post_id.
     * 
     * @return array.
     */
    private function getRealPost() {
        $where = "1=1";
        if ($this->param['category']) {
            $where .= " and `category` in('" . implode("','", $this->param['category']) . "')";
        }

        if ($this->param['post_keyword']) {
            $word = $this->param['post_keyword'];
            $type = \Db::instance()->getSimple("select `type` from `keywords` where `keyword` = '$word'");
            if ($type == 'keyword') {
                $where .= " and `keywords` like '%$word%'";
            } else {
                $where .= " and `author` = '$word'";
            }
        }

        if ($this->param['post_status']) {
            $where .= " and `status` in('" . implode("','", $this->param['post_status']) . "')";
        } else {
            $where .= " and `status` in('1','2','3')";
        }

        $this->post_ids = \Db::instance()->getColumn("select `post_id` from `post` where $where");
        $this->count = count($this->post_ids);
    }

    /**
     * 获取旧的topic信息.
     */
    private function getInfo() {
        $info = \Db::instance()->getRow("select `id`, `topic_id` from `topic` where `id` = '$this->id'");
        if (!$info) {
            \Common::ajaxReturnFalse("id: {$this->id}, 主题信息不存在");
        }

        $this->info = $info;
        if ($info['topic_id']) {
            $this->param['topic_id'] = $info['topic_id'];
        }
    }

    /**
     * 析构函数.
     */
    public function __destruct() {
        unset($this->info, $this->param, $this->post_ids);
    }

}
