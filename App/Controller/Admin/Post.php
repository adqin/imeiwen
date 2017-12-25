<?php

/**
 * 文章列表与发布.
 * 
 * @author aic <41262633@qq.com>
 */

namespace Controller\Admin;

/**
 * Class Post.
 */
class Post extends \Controller\Admin\Init {

    /**
     * 构造函数.
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * 文章列表.
     * 
     * @return void
     */
    public function index() {
        $this->display('admin/post/index');
    }

    /**
     * 新发布文章.
     * 
     * @return void
     */
    public function add() {
        if (\Common::isPost()) {
            $param['title'] = $this->getPost('title');
            $param['author'] = $this->getPost('author');
            $param['category'] = $this->getPost('category');
            $param['image_url'] = $this->getPost('image_url');
            $param['content'] = $this->getPost('content');
            $param['long_title'] = $this->getPost('long_title');
            $param['keywords'] = $this->getPost('keywords');
            $param['description'] = $this->getPost('description');
            $param['status'] = $this->getPost('status');
            $param['weixin_post_url'] = $this->getPost('weixin_post_url');
            $param['toutiao_post_url'] = $this->getPost('toutiao_post_url');

            $poster = new \Logic\Poster($id = 0, $param);
            $poster->add();
        } else {
            $this->display('admin/post/add');
        }
    }

    /**
     * 编辑更新文章.
     * 
     * @return void
     */
    public function edit() {
        if (\Common::isPost()) {
            $id = $this->getPost('id');
            if (!$id) {
                \Common::ajaxReturnFalse('id POST参数有误');
            }

            $param['title'] = $this->getPost('title');
            $param['author'] = $this->getPost('author');
            $param['category'] = $this->getPost('category');
            $param['image_url'] = $this->getPost('image_url');
            $param['content'] = $this->getPost('content');
            $param['long_title'] = $this->getPost('long_title');
            $param['keywords'] = $this->getPost('keywords');
            $param['description'] = $this->getPost('description');
            $param['status'] = $this->getPost('status');
            $param['weixin_post_url'] = $this->getPost('weixin_post_url');
            $param['toutiao_post_url'] = $this->getPost('toutiao_post_url');

            $poster = new \Logic\Poster($id, $param);
            $poster->edit();
        } else {
            $id = $this->getGet('id');
            if (!$id) {
                exit("id GET参数有误");
            }

            $info = \Db::instance()->getRow("select * from `post` where `id` = '$id'");
            if (empty($info)) {
                exit("id: {$id}, 文章信息没找到");
            }

            $this->assign('info', $info);
            $this->display('admin/post/edit');
        }
    }

    /**
     * 文章列表搜索数据.
     * 
     * @return void
     */
    public function search() {
        $page = $this->getPost('page');
        $page = $page ? $page : 1;
        $limit = $this->getPost('limit');
        $limit = $limit ? $limit : 20;

        // 查询条件.
        $title = $this->getPost('title');
        $author = $this->getPost('author');
        $category = $this->getPost('category');
        $isWeixinPost = $this->getPost('weixin_post');
        $isToutiaoPost = $this->getPost('toutiao_post');
        $status = $this->getPost('status');

        $where = "1=1";
        $order = "order by `update_time` desc";
        if ($title) {
            $where .= " and `title` like '%$title%'";
        }
        if ($author) {
            $where .= " and `author` = '$author'";
        }
        if ($category) {
            $where .= " and `category` = '$category'";
        }
        if ($status !== '') {
            $where .= " and `status` = '$status'";
        }
        if ($isWeixinPost == 1) {
            $where .= " and `weixin_post_url` = ''";
        }
        if ($isWeixinPost == 2) {
            $where .= " and `weixin_post_url` <> ''";
        }
        if ($isToutiaoPost == 1) {
            $where .= " and `toutiao_post_url` = ''";
        }
        if ($isToutiaoPost == 2) {
            $where .= " and `toutiao_post_url` <> ''";
        }

        // 计算总的条数.
        $count = \Db::instance()->count("select count(`id`) from `post` where $where");
        $return = array(
            'code' => 0,
            'msg' => '',
            'count' => $count,
            'data' => array(),
        );

        if ($count) {
            $offset = ($page - 1) * $limit;
            $sql = "select * from `post` where $where $order limit $limit offset $offset";
            $return['data'] = $this->formatSearchData(\Db::instance()->getList($sql));
        }

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

        $post_ids = array_column($data, 'post_id');
        $where = "`post_id` in('" . implode("','", $post_ids) . "')";
        $views = \Db::instance()->getList("select `post_id`,`views` from `post_view` where $where", 'post_id');

        $status_title = [
            '0' => '隐藏',
            '1' => '显示',
            '2' => '推荐',
            '3' => '首页推荐',
        ];

        $return = array();
        foreach ($data as $d) {
            $return[] = array(
                'id' => $d['id'],
                'thumb' => $d['image_url'] ? '<img src="' . \Config\Qiniu::$domain . $d['image_url'] . '?imageView2/2/w/200/' . $d['image_up_time'] . '" width="200">' : '',
                'title' => '<a href="/post/' . $d['post_id'] . '" class="post_item" target="_blank">' . $d['title'] . '</a>',
                'author' => $d['author'],
                'category' => $d['category'] && isset(\Config\System::$category[$d['category']]) ? \Config\System::$category[$d['category']] : '',
                'keywords' => trim($d['keywords'], ','),
                'status' => $status_title[$d['status']],
                'weixin_post' => $d['weixin_post_url'] ? '已发布' : '未发布',
                'toutiao_post' => $d['toutiao_post_url'] ? '已发布' : '未发布',
                'views' => isset($views[$d['post_id']]) ? $views[$d['post_id']]['views'] : 0,
                'op_string' => '<a href="/admin/post/edit?id=' . $d['id'] . '" class="layui-btn">修改<i class="layui-icon">&#xe642;</i></a>',
            );
        }

        return $return;
    }

}
