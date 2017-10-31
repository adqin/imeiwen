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
     * 
     * @return void
     */
    public function login() {
        
        if (\Common::isPost()) {
            $name = $this->getPost('admin_name');
            $passwd = $this->getPost('admin_passwd');
            if (!$name || !$passwd) {
                \Common::ajaxReturnFalse('请填写登录账号和密码');
            }
            
            $passwd = hash('md5', $passwd);
            $row = \Db::instance()->getRow("select `id`, `salt` from `admin` where `admin_name` = '$name' and `admin_pwd` = '$passwd' limit 1");
            if (empty($row)) {
                \Common::ajaxReturnFalse('登录账号或密码输入有误');
            }
            
            // 保存cookie.
            $adminId = $row['id'];
            $token = hash('md5', $row['id'] . '+' . $row['salt']);
            $this->setCookie('admin_id', $adminId, 2592000); // cookie admin_id.
            $this->setCookie(hash('md5', $adminId), $token, 2592000); // cookie token, 有效期1个月.
            \Common::ajaxReturnSuccess('登录成功');
            
        } else {
            $this->display('admin/login');
        }
    }
    
    /**
     * 注销登录.
     * 
     * @return void
     */
    public function logout() {
        $this->setCookie('admin_id', '', time() - 3600); // cookie admin_id置空并过期, 使cookie失效.
        \Common::redirect('/admin/index');
    }
}
