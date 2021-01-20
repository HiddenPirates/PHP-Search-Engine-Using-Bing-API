<?php
defined('FIR') OR exit();
/**
 * The template for displaying the Search Menu
 */
?>
<div class="page-menu page-menu-left" id="page-menu">
    <div class="search-type-menu">
        <div class="row row-dragscroll dragscroll filters-fade-<?php if($lang['lang_dir'] == 'rtl'): ?>left<?php else: ?>right<?php endif ?>">
            <?php foreach($data['menu'] as $key => $value): ?>
                <a href="<?=$data['url'].'/'.e($key).'?q='.e($data['query'])?>"<?=(($value[0] == $this->url[0]) ? ' class="menu-active"' : '')?>><?=$lang[$key]?></a>
            <?php endforeach ?>
            <div class="filters-toggle">
                <?=$lang['filters']?>
            </div>
        </div>
    </div>
    <div class="divider"></div>
</div>