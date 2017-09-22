<?php
/**
 * 公共函数库.
 */

/**
 * Class Common Tool.
 */
class Common {

    /**
     * 404 not found.
     */
    public static function noPage() {
        header('HTTP/1.1 404 Not Found');
        header('status: 404 Not Found');

        echo '<div class="noPage"><h3>访问错误, 页面不存在</h3><p><a href="/">返回首页</a></p></div>';
        exit;
    }

    /**
     * ajax输出.
     */
    public static function ajaxOut($input) {
        die(json_encode($input));
    }

    /**
     * ajax返回正确.
     */
    public static function ajaxReturnSuccess($message = '') {
        $return = array(
            'error' => 0,
            'message' => $message,
        );
        static::ajaxOut($return);
    }

    /**
     * ajax返回失败.
     */
    public static function ajaxReturnFalse($message = '') {
        $return = array(
            'error' => 1,
            'message' => $message,
        );
        static::ajaxOut($return);
    }

    /**
     * 页面跳转.
     */
    public static function redirect($url) {
        header("Location: $url");
        // 跳转的后续代码, 强制终止.
        exit(0);
    }

    /**
     * 简单分页.
     */
    public static function pageShow($total_count = 0, $page_now = 1, $base_url = '/', $page_size = 20) {
        if (!$total_count || !$page_size) {
            return '';
        }

        $total_page = ceil($total_count / $page_size);
        if ($total_page == 1) {
            return '';
        }

        if (!$page_now) {
            $page_now = 1;
        }

        if ($page_now > $total_page) {
            $page_now = $total_page;
        }

        $page_show = '<p class="page_show">' . "共计{$total_page}页{$total_count}篇文章 ";
        for ($i = 1; $i <= $total_page; $i++) {
            if ($i == $page_now) {
                $page_show .= '<span class="page_now">' . $i . '</span>';
            } else {
                $page_show .= '<a href="' . $base_url . '?page=' . $i . '">' . $i . '</a>';
            }
        }
        $page_show .= '</p>';
        return $page_show;
    }

    /**
     * 获取新的文章id.
     */
    public static function getPostid() {
        $key = microtime(true);
        return hash('crc32', $key);
    }

    /**
     * 数组以某个键值返回.
     */
    public static function getArrByKey($arr = array(), $key = '') {
        $return = array();
        foreach ($arr as $a) {
            if (isset($a[$key])) {
                $return[$key] = $a;
            } else {
                $return[] = $a;
            }
        }
        return $return;
    }

}
