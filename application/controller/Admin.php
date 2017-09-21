<?php

// 文章后台管理
class Controller_Admin extends Controller {

    // 判断是否登陆
    public function _before() {
        if (!$this->isLogin()) {
            $this->redirect('/login');
        }
    }

    // 文章列表
    public function index($param) {
        $page_size = 20;
        $page = empty($param) ? 1 : $param[0];
        if ($page <= 0) {
            $page = 1;
        }

        $where = "1=1";
        $data = $this->get_post();
        $keyword = isset($data['keyword']) ? $data['keyword'] : '';

        if ($keyword != '') {
            $where .= " and (`title` like '%$keyword%' || `author` = '$keyword')";
        }

        $total_count = $this->db()->get_simple("select count(1) from `post` where $where");
        $total_page = ceil($total_count / $page_size);

        if ($page > $total_page) {
            $page = $total_page;
        }

        $limit = $page_size;
        $offset = ($page - 1) * $limit;
        $sql = "select `post_id`,`title`,`author`,`word_count`,`update_time`,`page_view` from `post` where $where order by `update_time` desc limit $limit offset $offset";
        $data_list = $this->db()->get_list($sql);

        $page_show = common::page_show($total_count, $page, '/admin/index', $page_size);

        $this->assign('page_show', $page_show);
        $this->assign('data_list', $data_list);
        $this->assign('keyword', $keyword);
        $this->display('admin/list');
    }

    // 添加/编辑文章
    public function edit($param) {
        if (empty($param)) {
            $info = array(
                'post_id' => '',
                'title' => '',
                'author' => '',
                'content' => '',
            );
        } else {
            $post_id = $param[0];
            $info = $this->db()->get_row("select * from `post` where post_id='$post_id'");
            if (empty($info)) {
                die('相关数据为空');
            }
        }

        if ($this->is_post()) {
            // 操作结果，type=0表新增，type=1表编辑
            $result = array('error' => 1, 'message' => '', 'type' => 0, 'id' => 'title');
            $info = $data = $this->get_post();

            extract($data);
            if (!empty($post_id)) {
                $result['type'] = 1;
            }
            if (empty($title)) {
                $result['message'] = '请填写标题;';
                $result['id'] = 'title';
                $this->ajax_out($result);
            }
            if (empty($author)) {
                $result['message'] = '请填写作者;';
                $result['id'] = 'author';
                $this->ajax_out($result);
            }
            if (empty($content)) {
                $result['message'] = '请填写文章内容;';
                $result['id'] = 'content';
                $this->ajax_out($result);
            }

            // 判断文章是否已发布
            $where = "`title` = '{$title}' and `author` = '{$author}'";
            if (!empty($post_id)) {
                $where .= " and `post_id` <> '{$post_id}'";
            }
            $is_exist = $this->db()->exists("select `id` from `post` where {$where}");
            if ($is_exist) {
                $result['message'] = '文章已经发布，请确认;';
                $this->ajax_out($result);
            }

            // 计算文字字数
            $word_count = common::count_word($content);
            $now_time = time();

            // 添加文章
            if (empty($post_id)) {
                $post_id = $this->get_post_id();
                $sql = "insert into `post` (`post_id`,`title`,`author`,`content`,`word_count`,`input_time`,`update_time`)"
                        . " values('$post_id','$title','$author','$content','$word_count','$now_time','$now_time')";
            } else {
                $sql = "update `post` set `title` = '$title', `author` = '$author', `content` = '$content', `word_count` = '$word_count', `update_time` = '$now_time'"
                        . " where `post_id` = '$post_id'";
            }

            $re = $this->db()->execute($sql);
            if ($re === false) {
                $result['message'] = '操作失败. db_error: ' . $this->db()->get_error();
            } else {
                $result['error'] = 0;
                $result['message'] = '操作成功';
                $result['post_id'] = $post_id;

                $this->update_prev_next($post_id);
            }

            $this->ajax_out($result);
        }

        $this->assign('info', $info);
        $this->display('admin/edit');
    }

    // 删除文章
    public function delete() {
        $data = $this->get_post();
        $post_id = isset($data['post_id']) ? $data['post_id'] : '';

        $result = array('error' => 0, 'message' => '删除成功');
        if (empty($post_id)) {
            $this->ajax_out($result);
        }

        $re = $this->db()->execute("delete from `post` where `post_id` = '$post_id'");
        if ($re !== false) {

            // 删除缓存
            $cache_key = $post_id . '_post';
            $this->cache()->delete($cache_key);

            // 更新关联的上一篇，下一篇
            $related = $this->db()->get_column("select `post_id` from `post` where `prev_one` = '$post_id' or `next_one` = '$post_id'");
            if (!empty($related)) {
                foreach ($related as $r) {
                    $this->update_prev_next($r);
                }
            }
        } else {
            $result['error'] = 1;
            $result['message'] = '删除失败';
        }

        $this->ajax_out($result);
    }

    // 网站设置
    public function setting($param) {
        // post提交表单
        if ($this->is_post()) {
            $data = $this->get_post();
            $result = array('error' => 1, 'message' => '');
            extract($data);

            if ($set_title === '' || $set_val === '') {
                $result['message'] = '配置名和配置内容不能为空';
                $this->ajax_out($result);
            }

            $set_val = addslashes($set_val);
            $sql = "update `setting` set `set_title` = '$set_title', `set_val` = '$set_val' where `set_key` = '$set_key'";
            $re = $this->db()->execute($sql);
            if ($re === false) {
                $result['message'] = '数据更新失败.' . $this->db()->get_error();
                $this->ajax_out($result);
            }

            // 更新缓存
            common::cache_setting($set_key, $set_val);

            $result['error'] = 0;
            $result['message'] = '更新成功';
            $this->ajax_out($result);
        } else {
            $set_key = isset($param[0]) ? trim($param[0]) : '';
            $set_key = strip_tags($set_key);
            if (empty($set_key)) {
                die('错误页面');
            }

            $info = $this->db()->get_row("select `set_key`,`set_title`,`set_val` from `setting` where `set_key`='$set_key'");
            if (empty($info)) {
                die('没找到对应的配置项');
            }

            $this->assign('info', $info);
            $this->display('admin/setting');
        }
    }

    // 数据统计
    public function data() {
        // 文章数
        $post_count = $this->db()->get_simple("select count(1) from `post`");
        $this->assign('post_count', $post_count);

        // 字数统计
        $word_count = $this->db()->get_simple("select sum(`word_count`) from `post`");
        $this->assign('word_count', $word_count);

        // 网站浏览次数
        $site_view = $this->db()->get_simple("select `val` from `data` where `key` = 'visit_num'");
        $this->assign('site_view', $site_view);

        // 点赞次数
        $zan_num = $this->db()->get_simple("select `val` from `data` where `key` = 'zan_num'");
        $this->assign('zan_num', $zan_num);

        $this->display('admin/data');
    }

    // 全站更新
    public function clean() {
        set_time_limit(0);

        // 清空内存缓存
        $this->cache()->flush();

        // 更新文章上下篇
        $rs = $this->db()->get_column("select `post_id` from `post`");
        $i = 0;
        foreach ($rs as $r) {
            $this->update_prev_next($r);
            $i++;
            if ($i >= 200) {
                usleep(100000);
                $i = 0;
            }
        }

        $this->display('admin/clean');
    }

    // 缓存信息
    public function cacheinfo() {
        $info = $this->cache()->info();
        $this->assign('info', $info);
        $this->display('admin/cacheinfo');
    }

    // 获取网站配置项
    private function get_setting() {
        $sql = "select `set_key`,`set_title` from `setting`";
        $rs = $this->db()->get_list($sql);
        return $rs;
    }

    // 获取文章id
    private function get_post_id() {
        $post_id = common::post_id();
        $is_exist = $this->db()->exists("select `id` from `post` where `post_id` = '$post_id'");
        if ($is_exist) {
            $this->get_post_id();
        } else {
            return $post_id;
        }
    }

    // 更新上一篇下一篇
    private function update_prev_next($post_id) {
        $id = $this->db()->get_simple("select `id` from `post` where `post_id` = '$post_id'");

        $next = $this->db()->get_simple("select `post_id` from `post` where `id` > '$id' order by `id` asc limit 1");
        $prev = $this->db()->get_simple("select `post_id` from `post` where `id` < '$id' order by `id` desc limit 1");

        // 没有下一篇，就是第一篇
        if (empty($next)) {
            $next = $this->db()->get_simple("select `post_id` from `post` order by `id` asc limit 1");
        }

        // 没有上一篇，就是最后一篇
        if (empty($prev)) {
            $prev = $this->db()->get_simple("select `post_id` from `post` order by `id` desc limit 1");
        }

        // 更新数据
        $this->db()->execute("update `post` set `next_one` = '$next', `prev_one` = '$prev' where `post_id` = '$post_id'");
    }

}
