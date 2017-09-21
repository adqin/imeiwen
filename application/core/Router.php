<?php
/**
 * 路由器控制与跳转.
 */

/**
 * Class router.
 */
class Router {

    private $controller = 'Index'; // controller name.
    private $action = 'index'; // action name.
    
    /**
     * 构造函数.
     */
    public function __construct() {
        // 初始化controller与action.
        $this->getRouter();
    }

    /**
     * 路由跳转.
     */
    public function route() {
        // 加载控制器文件.
        $controller_file = APP_PATH . 'controller/' . $this->controller . '.php';
        if (!file_exists($controller_file)) {
            // 没有找到类文件.
            Common::noPage();
        }
        require $controller_file;

        // class controller.
        $class_name = 'Controller_' . $this->controller;
        $c = new $class_name();
        
        // method action.
        $action_name = 'action_' . $this->action;
        if (!method_exists($c, $action_name)) {
            // 没有找到action方法.
            Common::noPage();
        }

        // 有前置操作, 优先执行前置.
        if (method_exists($c, '_before')) {
            $before = 'before';
            $c->$before();
        }

        // 调用执行action.
        $c->$action_name();
    }

    /**
     * 根据url初始化controller与action.
     */
    private function getRouter() {
        // 获取uri参数.
        $uri = \filter_input(INPUT_SERVER, 'REQUEST_URI', \FILTER_SANITIZE_STRING);
        // 斜线分隔.
        $tmp = explode('/', $uri);
        
        // url参数分析.
        $param = array();
        foreach ($tmp as $t) {
            $t = trim($t);
            if ($t === '') {
                continue;
            }
            $param[] = $t;
        }
        
        // param.0=controller name.
        if (isset($param[0])) {
            $this->controller = ucfirst($param[0]);
        }
        
        // param.1=action name.
        if (isset($param[1])) {
            $this->action = $param[1];
        }
    }

}
