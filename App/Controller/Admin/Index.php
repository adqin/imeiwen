<?php

/**
 * 后台管理首页.
 * 
 * @author aic <41262633@qq.com>
 */

namespace Controller\Admin;

class Index extends \Controller\Admin\Init {

    /**
     * 构造函数.
     * 
     * @param mixed $vals 附加的参数.
     */
    public function __construct($vals = array()) {
        parent::__construct($vals);
    }

    /**
     * 后台管理首页.
     * 
     * @return void
     */
    public function index() {
        $this->display('admin/index');
    }

}
