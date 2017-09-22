<?php
/**
 * 系统配置类.
 */
class Config {

    /**
     * 网站信息.
     */
    public static $site = array(
        'name' => '你问问',
        'note' => '常常问自己',
        'domain' => 'niwenwen.com',
    );

    /**
     * js/css资源.
     */
    public static $source = array(
        'js_url' => array(
            'lib' => 'http://st.niwenwen.com/jquery.js',
            'form' => 'http://st.niwenwen.com/jquery.form.js',
        ),
        'css_url' => array(
            'lib' => 'http://st.niwenwen.com/mui.css',
            'style' => '/source/front.css',
            'back' => '/source/backend.css',
        ),
    );

}
