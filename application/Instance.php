<?php
// 单例模式
class Instance
{
     private static $_instance = array();
     
     protected function __construct() {}
     final public function __clone()
     {
         trigger_error('Clone method is not allowed.', E_USER_ERROR);
     }
     
     final public static function getInstance()
     {
         $c = get_called_class();
         if (!isset(self::$_instance[$c])) {
             self::$_instance[$c] = new $c;
         }
         return self::$_instance[$c];
     }
     
}