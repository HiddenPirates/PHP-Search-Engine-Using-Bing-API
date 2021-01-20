<?php
defined('FIR') OR exit();
/**
 * The template for displaying Images page content
 */
?>
<div id="content" class="content content-<?=e($this->url[0])?>">
    <?=$data['menu_view']?>
    <?=$data['filters_view']?>
    <?=$data['search_results_view']?>
</div>