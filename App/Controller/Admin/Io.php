<?php
/**
 * 登录或退出.
 * 
 * @author aic <41262633@qq.com>
 */

namespace Controller\Admin;

/**
 * Class Admin Io.
 */
class Io extends \Controller\Base {
    
    /**
     * 登录后台.
     */
    public function login() {
        if (\Common::isPost()) {
            print_r($_POST);exit;
        } else {
            $this->display('admin/login');
        }
    }
    
    /**
     * 注销登录.
     */
    public function logout() {
        
    }
}
