<?php

/**
 * 文章后台管理.
 * 
 * @author aic <41262633@qq.com>
 */

namespace Controller\Admin;

class Post extends \Controller\Admin\Init {

    public function __construct($vals = array()) {
        parent::__construct($vals);
        print_r($this->param);
    }

    public function add() {
        
    }

}
