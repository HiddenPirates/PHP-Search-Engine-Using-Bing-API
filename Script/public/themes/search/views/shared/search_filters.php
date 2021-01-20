<?php
defined('FIR') OR exit();
/**
 * The template for displaying the Search Filters
 */
?>
<div class="page-menu page-menu-left filters-menu">
    <div class="row row-dragscroll dragscroll filters-fade-<?php if($lang['lang_dir'] == 'rtl'): ?>left<?php else: ?>right<?php endif ?>">
        <?php foreach($data['menu'] as $tKey => $value): ?>
            <div class="filter-element" id="<?=$tKey?>"><?=$lang[$tKey]?>: <strong><?=($value[1][$value[2]])?></strong><div class="down-arrow"></div>
                <div class="filter-list">
                    <?php foreach($value[1] as $fKey => $filter): ?>
                        <a href="<?=$data['url'].'/'.e($this->url[0]).'?q='.e($data['query'])?><?php foreach($data['filters'] as $f): ?><?php if($f == $value[0][0]): ?>&<?=$f?>=<?=$fKey?><?php else: ?><?=(isset($_GET[$f]) ? '&'.$f.'='.e($_GET[$f]) : '')?><?php endif ?><?php endforeach ?>"<?=(($fKey == $value[2]) ? ' class="menu-active"' : '')?>><?=$filter?></a>
                    <?php endforeach ?>
                </div>
            </div>
        <?php endforeach ?>
    </div>
    <div class="divider"></div>
</div>