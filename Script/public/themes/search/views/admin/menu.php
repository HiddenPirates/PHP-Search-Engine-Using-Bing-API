<?php
defined('FIR') OR exit();
/**
 * The template for displaying the Admin Panel top menu
 */
?>
<div class="content-menu">
    <div class="page-menu page-menu-left" id="page-menu">
        <div class="row row-dragscroll dragscroll filters-fade-<?php if($lang['lang_dir'] == 'rtl'): ?>left<?php else: ?>right<?php endif ?>">
            <?php foreach($data['menu'] as $key => $value): ?>
                <a href="<?=$data['url'].'/admin/'.$key?>"<?=(($value[0] == true) ? ' class="menu-active"' : '')?><?=(($value[1] == true) ? ' data-nd' : '')?>><?=$lang[$key]?></a>
            <?php endforeach ?>
        </div>
    </div>
</div>