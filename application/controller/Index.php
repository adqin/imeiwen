<?php
class Controller_Index extends Controller
{   
    // 展示一篇文章
    public function action_index($param = array())
    {
        $rs = Db::instance()->getList('select id,title from post limit 10');
        print_r($rs);
    }
}