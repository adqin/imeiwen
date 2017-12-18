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
        print_r($info);
        
        //$share_title = urlencode($info['description'] . $info['author'] . '-《' . $info['title'] . '》');
        //$share_url = urlencode('http://imeiwen.org/post/' . $info['post_id']);
        //$pic_url = urlencode(\Config\Qiniu::$domain . $info['image_url']);
        //$weibo_share_url = 'http://service.weibo.com/share/share.php?title=' . $share_title . '&url=' . $share_url . '&pic=' . $pic_url . '&appkey=76945915&searchPic=false';
        //echo $weibo_share_url;
        //$share_title = urlencode('《' . $info['title'] . '》- ' . $info['author']);
        //$qq_share_url = 'https://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=' . $share_url . '&title=' . $share_title;
        //echo $qq_share_url;
        $this->assign('info', $info);
        $this->display('home/post');
    }

}
