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
                // 如果已经登录, 跳转到后台文章列表页.
                Common::redirect('/admin/posts');
            }
            $this->display('admin/login');
        }

        // 是否被锁定.
        $lock_file = CACHE_PATH . 'lock.lock';
        if (file_exists($lock_file)) {
            Common::ajaxReturnFalse('登陆失败次数过多, 已锁定');
        }

        $user = $this->getPost('user');
        $passwd = $this->getPost('passwd');

        if ($user === '' || $passwd === '') {
            Common::ajaxReturnFalse('账号与密码不能为空');
        }

        // 配置的登录账号与密码.
        $cfg_user = Backend::$admin['username'];
        $cfg_passwd = Backend::$admin['passwd'];

        if (hash('md5', $user) != $cfg_user || hash('md5', $passwd) != $cfg_passwd) {
            $login_times = Cache::getByFile('login.times');
            if (empty($login_times)) {
                $login_times = 0;
            }

            $login_times++;
            if ($login_times > 7) {
                // 登录失败次数操过7次, 锁定登录.
                file_put_contents($lock_file, 'locked@' . date('Y-m-d H:i:s', time()));
            } else {
                // 更新登录失败次数.
                Cache::setByFile('login.times', $login_times);
            }

            Common::ajaxReturnFalse('登陆账号或登录密码有误');
        }

        // 登录失败次数置0.
        Cache::setByFile('login.times', 0);
        // 登陆成功，设置cookie
        $cookie_key = Backend::$admin['login_flag'];
        $cookie_value = Backend::$admin['login_value'];
        $this->setCookie($cookie_key, $cookie_value, time() + 604800);

        Common::ajaxReturnSuccess('登录成功');
    }

}
