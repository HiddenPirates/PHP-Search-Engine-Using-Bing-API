<?php
defined('FIR') OR exit();
/**
 * The template for displaying the Reverse Text Instant Answer
 */
?>
<div class="row row-card-result">
    <div class="web-ia web-ia-reverse-text">
        <div class="web-ia-title"><?=$lang['reversed_text']?></div>
        <div class="web-ia-content"><?=e($data['result'])?></div>
    </div>
</div>