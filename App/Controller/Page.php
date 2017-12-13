<?php

/**
 * 单页面.
 * 
 * @author aic <41262633@qq.com>
 */

namespace Controller;

class Page extends \Controller\Base {

    /**
     * 展示单页面.
     * 
     * @return void.
     */
    public function index() {
        $id = isset($this->param['id']) ? $this->param['id'] : '';
        if (!$id) {
            \Common::showErrorMsg('错误的页面id参数');
        }
        
        $info = \Logic\Homer::getCachePage($id);
        
        $this->assign('this_menu', $id);
        $this->assign('info', $info);
        $this->display('home/page');
    }

}
