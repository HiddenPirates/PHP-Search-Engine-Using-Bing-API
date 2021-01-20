<?php
defined('FIR') OR exit();
/**
 * The template for displaying the Case Instant Answer
 */
?>
<div class="row row-card-result">
    <div class="web-ia web-ia-case">
        <div class="web-ia-title"><?=sprintf($lang['case_'.$data['type']])?></div>
        <div class="web-ia-content"><?=e($data['result'])?></div>
    </div>
</div>