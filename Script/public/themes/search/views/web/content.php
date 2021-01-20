<?php
defined('FIR') OR exit();
/**
 * The template for displaying Web page content
 */
?>
<div id="content" class="content content-<?=e($this->url[0])?>">
    <?=$data['menu_view']?>
    <?=$data['filters_view']?>
    <div class="results-container">
        <div class="results-content">
            <?=$data['result_ia_view']?>
            <?=$data['search_results_view']?>
        </div>
        <div class="results-sidebar">
            <?=$data['entities_view']?>
        </div>
    </div>
</div>