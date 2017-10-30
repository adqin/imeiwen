<?php
/**
 * 基于文件的缓存.
 */
class Cache {

    /**
     * 以key查询缓存.
     */
    public static function get($key = '') {
        $base_dir = static::getDirByKey($key);
        $cache_file = $base_dir . '/' . hash('md5', $key);
        return static::getByFile($cache_file);
    }

    /**
     * 以key设置缓存.
     */
    public static function set($key = '', $data) {
        $base_dir = static::getDirByKey($key);
        if (!file_exists($base_dir) && !mkdir($base_dir, 0777, true)) {
            Common::ajaxReturnFalse($base_dir . '缓存目录创建失败');
        }

        $cache_file = $base_dir . '/' . hash('md5', $key);
        static::setByFile($cache_file, $data);
    }

    /**
     * 以文件名查询缓存.
     */
    public static function getByFile($fileName = '') {
        $file = CACHE_PATH . $fileName;
        if (!file_exists($file)) {
            // 缓存文件不存在.
            return '';
        }

        $content = file_get_contents($file);
        $content = trim($content);
        $content = strip_tags($content);

        return json_decode($content, true);
    }

    /**
     * 直接保存缓存到缓存文件.
     */
    public static function setByFile($fileName = '', $data) {
        $file = CACHE_PATH . $fileName;
        $content = json_encode($data);

        if (file_put_contents($file, $content, LOCK_EX) === false) {
            Common::ajaxReturnFalse('缓存更新失败');
        }
    }

    /**
     * 以key返回对应的缓存文件.
     */
    public static function getDirByKey($key = '') {
        $string = hash('md5', $key);
        return CACHE_PATH . substr($string, 0, 1) . substr($string, -1, 1) . '/' . substr($string, 1, 1) . substr($string, -2, 1);
    }

}
