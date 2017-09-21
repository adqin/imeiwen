<div class="nav">
    <ul>
        <li><a href="/admin/index">文章列表</a></li>
        <li><a href="/admin/edit">文章发布</a></li>
        <?php if (!empty($setting)):?>
        <?php foreach($setting as $val):?>
        <li><a href="/admin/setting/<?=$val['set_key']?>"><?=$val['set_title']?></a></li>
        <?php endforeach;?>
        <?php endif;?>
        <li><a href="/admin/data">数据统计</a></li>
        <li><a href="/admin/clean">全站更新</a></li>
        <li><a href="/admin/cacheinfo">缓存信息</a></li>
        <li><a href="/" target="_blank">网站首页</a></li>
    </ul>
</div>