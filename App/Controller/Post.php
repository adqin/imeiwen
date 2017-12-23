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
        $isShare = $this->getGet('share') ? true : false; // 是否是分享链接.
        
        $share_title = urlencode("{$info['author']}：{$info['long_title']}");
        $wb_post_url = urlencode("http://imeiwen.org/post/{$post_id}?from=weibo");
        $qq_post_url = urlencode("http://imeiwen.org/post/{$post_id}?share=qq");
        $wx_post_url = "http://imeiwen.org/post/{$post_id}?share=wx";
        $post_image_url = \Config\Qiniu::$domain . $info['image_url'] . '?imageView2/2/w/780/' . $info['image_up_time'];
        $share_image_url = urlencode($post_image_url);
        $wb_share_url = 'http://service.weibo.com/share/share.php?title=' . $share_title . '&url=' . $wb_post_url . '&pic=' . $share_image_url . '&appkey=76945915&searchPic=false';
        $qq_share_url  = 'https://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=' . $qq_post_url . '&title=' . $share_title;

        $this->assign('info', $info);
        $this->assign('isShare', $isShare);
        $this->assign('post_image_url', $post_image_url);
        $this->assign('wb_share_url', $wb_share_url);
        $this->assign('qq_share_url', $qq_share_url);
        $this->assign('wx_post_url', $wx_post_url);
        $this->display('home/post');
    }

}
