<?php
defined('FIR') OR exit();
/**
 * The template for displaying the Morse Code Instant Answer
 */
?>
<div class="row row-card-result">
    <div class="web-ia web-ia-morse-code">
        <div class="web-ia-title"><?=sprintf($lang['morse_code_for'], '<strong>'.e($data['query']).'</strong>')?></div>
        <div class="web-ia-content"><?=e($data['result'])?></div>
    </div>
</div>