<?php
defined('FIR') OR exit();
/**
 * The template for displaying the Base64 Instant Answer
 */
?>
<div class="row row-card-result">
    <div class="web-ia web-ia-base64">
        <div class="web-ia-title"><?=sprintf($lang['base64_'.$data['type']], '<strong>'.e($data['query']).'</strong>')?></div>
        <div class="web-ia-content"><?=e($data['result'])?></div>
    </div>
</div>