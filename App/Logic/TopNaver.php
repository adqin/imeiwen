<?php

/**
 * 网站顶部导航定制.
 */

namespace Logic;

class TopNaver {

    /**
     * 获取顶部导航菜单.
     * 
     * @param string $this_menu 当前菜单项.
     * @param boolean $is_mobile 是否是移动端.
     * 
     * @return array.
     */
    public static function getMenu($this_menu = 'index', $is_mobile = false) {
        $menu_list = \Config\System::$menu;
        $show_count = $is_mobile ? 2 : 6;

        $normal = $more = [];
        $index = 0;

        if ($this_menu != 'index') {
            // 首页.
            $normal[$index] = $menu_list['index'];
            $normal[$index]['selected'] = '';
            unset($menu_list['index']);
            $index++;

            if ($this_menu) {
                $normal[$index] = $menu_list[$this_menu];
                $normal[$index]['selected'] = 'selected';
                unset($menu_list[$this_menu]);
                $index++;
            }
        }

        foreach ($menu_list as $key => $m) {
            if ($index >= $show_count) {
                $more[] = $m;
            } else {
                $normal[$index] = $m;
                $normal[$index]['selected'] = $this_menu == $key ? 'selected' : '';
                $index++;
            }
        }

        $return = [
            'normal' => $normal,
            'more' => $more,
        ];

        return $return;
    }

}
