<?php

/**
 * 文章更新业务逻辑处理.
 * 
 * @author aic <41262633@qq.com>
 */

namespace Logic;

/**
 * Class Poster.
 */
class Poster {

    private $param = array(); // 编辑更新的文章数据.
    private $info = array(); // 旧的文章信息.
    private $id = 0; // 文章自增id.
    private $post_id = ''; // 文章post_id.
    private $image_data = ''; // 配图数据, base64_encode.
    private $image_url = ''; // 配图url.

    public function __construct($id = 0, $param = array()) {
        $this->id = $id;
        $this->param = $param;

        if ($id) {
            // 查询旧的文章信息.
            $this->getInfo();
        }
    }

    /**
     * 新增文章.
     * 
     * @return void
     */
    public function add() {
        // 新增文章, 先获取新的post_id.
        $this->post_id = $this->param['post_id'] = $this->getPostid();

        // 对参数进行验证.
        $this->validParam();

        // 验证与获取image_data.
        $this->getImageData();

        // 保存.
        $this->save();
    }

    /**
     * 编辑更新.
     * 
     * @return void
     */
    public function edit() {
        // 如果编辑的信息为空.
        if (!$this->info) {
            \Common::ajaxReturnFalse('编辑的文章信息为空');
        }

        // 对参数进行验证.
        $this->validParam();

        // 验证与获取image_data.
        $this->getImageData();

        // 保存.
        $this->save();
    }

    /**
     * 保存更新数据.
     * 
     * @return void
     */
    private function save() {
        if (!\Db::instance()->startTransactions()) {
            \Common::ajaxReturnFalse("数据更新, 开启事务失败");
        }

        try {
            if (!$this->saveImage()) {
                throw new \Exception('数据更新, 配图保存失败');
            }

            if (!$this->saveDb()) {
                throw new \Exception('数据更新, 保存DB失败');
            }

            if (!\Db::instance()->commit()) {
                throw new Exception('数据更新, 提交事务失败');
            }

            // 清理本地缓存图片.
            $this->cleanLocalImage();
            // 返回成功提示.
            \Common::ajaxReturnSuccess("更新文章成功", ['id' => $this->id]);
        } catch (\Exception $e) {
            // 回滚事务.
            \Db::instance()->rollback();
            // 清理本地缓存图片.
            $this->cleanLocalImage();
            // 返回错误提示.
            \Common::ajaxReturnFalse($e->getMessage());
        }
    }

    /**
     * 保存更新Db.
     * 
     * @return boolean.
     */
    private function saveDb() {
        $param = array(
            'post_id' => $this->post_id,
            'title' => $this->param['title'],
            'author' => $this->param['author'],
            'content' => $this->param['content'],
            'long_title' => $this->param['long_title'],
            'keywords' => $this->param['keywords'] ? $this->formatKeywords($this->param['keywords']) : '', // 替换中文逗号为英文.
            'description' => $this->param['description'],
            'weixin_url' => $this->param['weixin_url'],
            'weixin_up_datetime' => $this->param['weixin_up_datetime'] ? strtotime($this->param['weixin_up_datetime']) : 0, // 转换为时间戳, 方便排序.
            'status' => $this->param['status'],
        );

        if ($this->id) {
            $param['update_time'] = time();
        } else {
            $param['input_time'] = $param['update_time'] = time();
        }

        if ($this->image_url) {
            $param['image_url'] = $this->image_url;
            $param['image_up_time'] = time();
        }

        if ($this->id) {
            return \Db::instance()->updateById('post', $param, $this->id);
        } else {
            $this->id = \Db::instance()->insert('post', $param);
            return $this->id;
        }
    }

    /**
     * 图片更新上传.
     * 
     * @return boolean.
     */
    private function saveImage() {
        if (!$this->image_data) {
            // 没有新传图, 直接返回.
            return true;
        }

        $local = CACHE_PATH . $this->post_id;
        file_put_contents($local, $this->image_data);
        $save = $this->post_id . '.jpg';

        $uploader = new \Logic\Uploader();
        if (!$uploader->upload($local, $save)) {
            // 返回的文件名为空, 表示上传失败.
            return false;
        }

        $this->image_url = $save;
        return true;
    }

    /**
     * 对入参做基本的验证.
     * 
     * @return void
     */
    private function validParam() {
        $fields = array(
            'post_id' => '文章id',
            'title' => '标题',
            'author' => '作者',
            'image_url' => '配图',
            'content' => '内容',
            'long_title' => '长标题',
            'keywords' => '关键词',
            'description' => '简要描述',
            'weixin_url' => '微信文章URL',
            'weixin_up_datetime' => '微信文章发布日期',
            'status' => '状态',
        );

        $required = array('post_id', 'title', 'author', 'content');

        foreach ($fields as $k => $v) {
            if (!isset($this->param[$k])) {
                \Common::ajaxReturnFalse("{$k}:{$v} 参数未设置");
            }

            if (in_array($k, $required) && empty($this->param[$k])) {
                \Common::ajaxReturnFalse("{$k}:{$v} 值不能为空");
            }
        }

        $status = $this->param['status'];
        if (!in_array($status, array('0', '1', '2'))) {
            \Common::ajaxReturnFalse("status:状态 值有误, 不符合规范");
        }

        if ($status) {
            // 文章状态是展示, 需验证内容是否为空.
            if (!$this->info && empty($this->param['image_url'])) {
                // 如果是新增文章.
                \Common::ajaxReturnFalse('文章配图必须上传');
            }

            if ($this->info && empty($this->info['image_url']) && empty($this->param['image_url'])) {
                // 如果编辑更新文章.
                \Common::ajaxReturnFalse('文章配图必须上传');
            }

            $required = array('long_title', 'keywords', 'description');
            foreach ($required as $k) {
                if (empty($this->param[$k])) {
                    \Common::ajaxReturnFalse("{$k}:{$fields[$k]} 值不能为空");
                }
            }
        }

        // 验证文章是否重复(标题+作者).
        $title = $this->param['title'];
        $author = $this->param['author'];
        $where = "`title` = '$title' and `author` = '$author'";
        if ($this->id) {
            $where .= " and `id` <> '$this->id'";
        }

        if (\Db::instance()->exists("select `id` from `post` where $where")) {
            \Common::ajaxReturnFalse("author: {$author}, title: {$title} 文章已发布过, 不要重复发布");
        }

        // 验证微信文章发布日期.
        if ($this->param['weixin_up_datetime']) {
            $time = strtotime($this->param['weixin_up_datetime']);
            if (date('Y-m-d', $time) != $this->param['weixin_up_datetime']) {
                \Common::ajaxReturnFalse('微信文章发布日期格式有误');
            }

            // 验证微信文章发布日期是否重复, 每日一文, 每天只能一篇文章.
            $where = "`weixin_up_datetime` = '$time'";
            if ($this->id) {
                $where .= " and `id` <> '$this->id'";
            }

            if (\Db::instance()->exists("select `id` from `post` where $where")) {
                \Common::ajaxReturnFalse("每日一文/微信文章发布日期: {$this->param['weixin_up_datetime']}已发布过, 不要重复发布");
            }
        }
    }

    /**
     * 查询旧的文章信息.
     * 
     * @return void
     */
    private function getInfo() {
        $this->info = \Db::instance()->getRow("select `id`, `post_id`, `title`, `author`, `image_url`, `content`, `long_title`, `keywords`, `description` from `post` where `id` = '$this->id'");
        if (empty($this->info)) {
            \Common::ajaxReturnFalse("post: {$this->id}, 更新的文章不存在");
        }
        $this->post_id = $this->info['post_id'];
        $this->param['post_id'] = $this->info['post_id'];
    }

    /**
     * 验证配图与格式化数据.
     * 
     * @return void
     */
    private function getImageData() {
        if ($this->param['image_url']) {
            $image_data = base64_decode(explode(',', $this->param['image_url'])[1]);
            if (!$image_data) {
                \Common::ajaxReturnFalse("文章配图数据有误, 请重新上传");
            }

            $image_info = getimagesizefromstring($image_data);
            if (!$image_info || !isset($image_info['mime']) || !in_array($image_info['mime'], array('image/jpg', 'image/jpeg', 'image/png'))) {
                \Common::ajaxReturnFalse('文章配图不符合要求, 请重新上传');
            }

            $len = strlen($image_data);
            if (($len / 1024) > 200) {
                \Common::ajaxReturnFalse("文章配图最大不能超过200K");
            }

            $this->image_data = $image_data;
        }
    }
    
    /**
     * 格式化处理关键词.
     * 
     * @param string $keywords 录入的关键词.
     * 
     * @return string.
     */
    private function formatKeywords($keywords = '') {
        $keywords = str_replace('，', ',', $keywords);
        
        $return = [];
        $arr = explode(',', $keywords);
        
        foreach ($arr as $r) {
            $r = trim($r);
            if ($r) {
                $return[] = $r;
            }
        }
        
        $return = array_unique($return);
        
        return $return ? ',' . implode(',', $return) . ',' : '';
    }

    /**
     * 获取新的文章id.
     * 
     * @return string.
     */
    private function getPostid() {
        $key = microtime(true);
        $post_id = hash('crc32b', $key); // crc32b 快于crc32.
        if (\Db::instance()->exists("select `id` from `post` where `post_id` = '$post_id'")) {
            // 如果数据库里已存在, 递归调用重新生成post_id.
            return $this->getPostid();
        } else {
            return $post_id;
        }
    }

    /**
     * 清理本地的临时图片文件.
     * 
     * @return void
     */
    private function cleanLocalImage() {
        $local = CACHE_PATH . $this->post_id;
        if (file_exists($local)) {
            unlink($local);
        }
    }

    /**
     * 析构函数.
     */
    public function __destruct() {
        unset($this->param, $this->info, $this->image_data);
    }

}
