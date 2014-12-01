<?php
/**
 * 程序App类
 */

class App extends Instance
{
    private $config;

    // 程序开始执行
    public function start()
    {
        Controller::getInstance()->test();
    }
    
    // 下面是工具函数
    private function getRouter()
    {
        
    }
    
    // 打印调试
    public function dump($array)
    {
        echo '<pre>';
        print_r($array);
        echo '</pre>';
    }
}
