<?php

/**
 * 首页.
 * 
 * @author aic <41262633@qq.com>
 */

namespace Controller;

/**
 * Class Controller Index.
 */
class Index extends \Controller\Base {

    /**
     * 首页展示.
     * 
     * @return void
     */
    public function index() {
        $this->display('index');
    }
    
    /**
     * 随机看看.
     */
    public function random() {
        
    }

}
