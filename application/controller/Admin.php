<?php

/**
 * Wecms后台管理.
 */
class Controller_Admin extends Controller {

    /**
     * 后台管理首页.
     */
    public function action_index() {
        $this->display('admin/index');
    }

    /**
     * post列表.
     */
    public function action_posts() {
        $page_size = 20;
        $page = $this->getGet('page');
        if (!$page) {
            $page = 1;
        }

        $where = "1=1";
        $keyword = $this->getPost('keyword');

        if ($keyword != '') {
            $where .= " and (`title` like '%$keyword%' || `author` = '$keyword')";
        }

        $total_count = Db::instance()->count("select count(1) from `post` where $where");
        $total_page = ceil($total_count / $page_size);
        
        if ($page > $total_page) {
            $page = $total_page;
        }

        $limit = $page_size;
        $offset = ($page - 1) * $limit;
        $get_list_sql = "select `post_id`,`title`,`author`,`update_time`,`page_view` from `post` where $where order by `update_time` desc limit $limit offset $offset";
        $data_list = Db::instance()->getList($get_list_sql);

        $page_show = Common::pageShow($total_count, $page, '/admin/posts', $page_size);

        $this->assign('page_show', $page_show);
        $this->assign('data_list', $data_list);
        $this->assign('keyword', $keyword);
        $this->display('admin/posts');
    }

    // 添加/编辑文章
    public function post_edit() {

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
    
    /**
     * 注销后台登录.
     */
    public function action_logout() {
        $cookie_key = Backend::$admin['login_flag'];
        $this->setCookie($cookie_key, '', time() - 100);
        Common::redirect('/admin/index');
    }

    /**
     * 判断是否登陆.
     */
    public function _before() {
        if (!$this->adminIslogin()) {
            Common::redirect('/login');
        }
    }

}
