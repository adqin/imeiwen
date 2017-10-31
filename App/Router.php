<?php

/**
 * 路由器控制与跳转.
 * 
 * @author aic <41262633@qq.com>
 */

/**
 * Class router.
 */
class Router {

    /**
     * 自定义路由规则与路由解析.
     * 
     * @return void
     */
    public static function route() {
        $dispatcher = FastRoute\cachedDispatcher(function(FastRoute\RouteCollector $r) {
            $rules = \Config::$routeRules; // 配置的路由规则.
            foreach ($rules as $route => $rule) {
                // 默认为GET提交.
                $methods = isset($rule['method']) && $rule['method'] ? (array) $rule['method'] : ['GET'];
                $handler = $rule['handler'];
                if (!$route || !$handler) {
                    // 如果路由与handler为空, 直接跳过不处理.
                    continue;
                }

                foreach ($methods as $method) {
                    $method = strtoupper($method);
                    $r->addRoute($method, $route, $handler);
                }
            }
        }, [
            'cacheFile' => CACHE_PATH . 'route.cache',
        ]);

        $method = \filter_input(\INPUT_SERVER, 'REQUEST_METHOD', \FILTER_SANITIZE_STRING);
        $uri = \filter_input(\INPUT_SERVER, 'REQUEST_URI', \FILTER_SANITIZE_STRING);
        if (false !== $pos = strpos($uri, '?')) {
            // 如果uri里包含了'?'.
            $uri = substr($uri, 0, $pos);
        }
        $uri = rawurldecode($uri);

        $className = ''; // 类名.
        $methodName = ''; // 方法名.
        $vars = array(); // 附加参数.
        // 返回路由信息.
        $routeInfo = $dispatcher->dispatch($method, $uri);
        switch ($routeInfo[0]) {
            case FastRoute\Dispatcher::NOT_FOUND:
                // 没有找到路由定义, 自己寻址.
                $result = static::getSelfRouteInfo($uri);
                $className = $result['class'];
                $methodName = $result['method'];
                if (!$className || !$methodName) {
                    \Common::showErrorMsg("URI: {$uri}, 类文件或方法未定义, 请联系管理员");
                }
                break;
            case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                // 405 Method Not Allowed.
                \Common::showErrorMsg("URI: {$uri}, 提交操作未被授权, 请联系管理员");
                break;
            case FastRoute\Dispatcher::FOUND:
                $handlerArr = explode('@', $routeInfo[1]);
                $vars = $routeInfo[2];
                $className = "\\Controller\\{$handlerArr[0]}";
                $methodName = $handlerArr[1];
                break;
        }
        
        $class = new $className($vars);
        if (!method_exists($class, $methodName)) {
            // 404 not found.
            \Common::showErrorMsg("URI: {$uri}, ACTION: {$methodName}未定义, 请联系管理员");
        }
        
        $class->$methodName();
    }

    /**
     * 根据uri自动获取路由规则（没有匹配到自定义路由规则时触发）.
     * 
     * @param string $uri URI.
     * 
     * @return array.
     */
    private static function getSelfRouteInfo($uri = '') {

        if (!$uri) {
            return ['class' => '', 'method' => ''];
        }

        $uriArr = explode('/', $uri);
        $method = strtolower(array_pop($uriArr)); // 转化为小写.
        // 首字母大写.
        $tmp = array_map(function($val) {
            return ucfirst($val);
        }, $uriArr);

        $class = str_replace('/', '\\', '/Controller' . implode('/', $tmp));

        return ['class' => $class, 'method' => $method];
    }

}
