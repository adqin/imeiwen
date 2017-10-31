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
    public function __construct() {
        parent::__construct();

        if (!$this->isLogin()) {
            // 如果未登录, 跳转到登录页.
            \Common::redirect('/admin/login');
        }
    }

    /**
     * 判断后台系统是否登录.
     * 
     * @return boolean.
     */
    protected function isLogin() {
        $adminId = $this->getCookie('admin_id');
        if (!$adminId) {
            return false;
        }

        $token = $this->getCookie(hash('md5', $adminId));
        if (!$token) {
            return false;
        }

        $row = \Db::instance()->getRow("select `id`, `salt` from `admin` where `id` = '$adminId' limit 1");
        if (empty($row)) {
            return false;
        }

        if ($token != hash('md5', $row['id'] . '+' . $row['salt'])) {
            return false;
        }

        return true;
    }

}
