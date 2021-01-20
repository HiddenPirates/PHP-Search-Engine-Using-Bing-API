<?php
defined('FIR') OR exit();
/**
 * The template for displaying the Reverse Text Instant Answer
 */
?>
<div class="row row-card-result">
    <div class="web-ia web-ia-leap-year">
        <div class="web-ia-title"><strong><?=e($data['query'])?></strong></div>
        <div class="web-ia-content"><?=e($lang['leap_year_'.$data['type']])?></div>
    </div>
</div>