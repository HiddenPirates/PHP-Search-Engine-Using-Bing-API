<?php
defined('FIR') OR exit();
/**
 * The template for displaying Video page content
 */
?>
<div id="content" class="content content-<?=e($this->url[0])?>">
    <?=$data['menu_view']?>
    <?=$data['filters_view']?>
    <div class="results-container">
        <div class="results-content">
            <?=$data['search_results_view']?>
        </div>
    </div>
</div>