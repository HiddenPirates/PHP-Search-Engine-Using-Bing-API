<?php
defined('FIR') OR exit();
/**
 * The template for displaying the top menu for the Preferences Page
 */
?>
<div class="content-menu">
    <div class="page-menu page-menu-left" id="page-menu">
        <div class="row row-dragscroll dragscroll filters-fade-<?php if($lang['lang_dir'] == 'rtl'): ?>left<?php else: ?>right<?php endif ?>">
            <?php foreach($data['menu'] as $key => $value): ?>
                <a href="<?=$data['url'].'/preferences/'.e($key)?>"<?=(($value[0] == true) ? ' class="menu-active"' : '')?><?=(($value[1] == true) ? ' data-nd' : '')?>><?=$lang[$value[2]]?></a>
            <?php endforeach ?>
        </div>
    </div>
</div>