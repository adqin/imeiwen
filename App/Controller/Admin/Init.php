<?php

/**
 * 后台管理初始化操作.
 * 
 * @author aic <41262633@qq.com>
 */

namespace Controller\Admin;

class Init extends \Controller\Base {

    /**
     * 构造函数, 判断后台是否已登录.
     */
    public function __construct($vals = array()) {
        parent::__construct($vals);
    }

}
