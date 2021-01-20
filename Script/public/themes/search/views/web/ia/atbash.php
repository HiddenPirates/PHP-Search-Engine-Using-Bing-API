<?php
defined('FIR') OR exit();
/**
 * The template for displaying the Atbash Instant Answer
 */
?>
<div class="row row-card-result">
    <div class="web-ia web-ia-atbash-cipher">
        <div class="web-ia-title"><?=sprintf($lang['atbash_for'], '<strong>'.e($data['query']).'</strong>')?></div>
        <div class="web-ia-content"><?=e($data['result'])?></div>
    </div>
</div>