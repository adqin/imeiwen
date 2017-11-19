<?php

/**
 * 文章详情页.
 * 
 * @author aic <41262633@qq.com>
 */

namespace Controller;

/**
 * Class Post.
 */
class Post extends \Controller\Base {

    /**
     * 文章详情页.
     * 
     * @return void
     */
    public function item() {
        $post_id = isset($this->param['post_id']) && $this->param['post_id'] ? $this->param['post_id'] : '';        
        $itemer = new \Logic\PostItemer($post_id);
        $info = $itemer->get();
        //var_dump($info);
        
        $this->assign('info', $info);
        $this->display('home/post');
    }

}
