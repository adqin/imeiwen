<?php

/**
 * 管理后台登录.
 */

/**
 * Class Controller Login.
 */
class Controller_Login extends Controller {

    public function action_index() {

        // 如果非表单提交, 显示页面.
        if (!$this->isPost()) {
            if ($this->adminIslogin()) {
                // 如果已经登录, 跳转到后台首页.
                Common::redirect('/admin');
            }
            $this->display('login');
        }
        
        // 是否被锁定.
        $lock_file = APP_PATH . 'lock/lock.lock';
        if (file_exists($lock_file)) {
            Common::ajaxReturnFalse('登陆失败次数过多, 已被锁定');
        }

        $user = $this->getPost('user');
        $passwd = $this->getPost('passwd');

        if ($user === '' || $passwd === '') {
            Common::ajaxReturnFalse('账号与密码不能为空');
        }

        // 配置的登录账号与密码.
        $cfg_user = Config::$admin('admin_user');
        $cfg_passwd = Config::$admin('admin_pwd');

        if (hash('md5', $user) != $cfg_user || hash('md5', $passwd) != $cfg_passwd) {
            $login_error_times = $this->cache()->get('login.lock');
            if (empty($login_error_times)) {
                $login_error_times = 0;
            }

            $login_error_times++;
            if ($login_error_times > 7) {
                file_put_contents($lock_file, 'locked@' . date('Y-m-d H:i:s', time()));
            } else {
                $this->cache()->set('login.lock', $login_error_times);
            }

            $return['message'] = '登陆账号与秘密错误';
            $this->ajax_out($return);
        }

        // 登陆成功，设置cookie
        $cookie_key = config::instance()->get('login_flag');
        $cookie_value = config::instance()->get('login_value');
        common::set_cookie($cookie_key, $cookie_value, time() + 604800);

        $return['error'] = 0;
        $return['message'] = '登陆成功';
        $this->ajax_out($return);
    }

}
