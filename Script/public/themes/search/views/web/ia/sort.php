<?php
defined('FIR') OR exit();
/**
 * The template for displaying the Sort Instant Answer
 */
?>
<div class="row row-card-result">
    <div class="web-ia web-ia-sort">
        <div class="web-ia-title"><?=$lang['sorted_'.$data['direction']]?></div>
        <div class="web-ia-content"><?=implode(', ', $data['result'])?></div>
    </div>
</div>