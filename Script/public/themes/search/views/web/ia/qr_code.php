<?php
defined('FIR') OR exit();
/**
 * The template for displaying the QR Code Instant Answer
 */
?>
<div class="row row-card-result">
    <div class="web-ia web-ia-qr-code">
        <div class="web-ia-title"><?=sprintf($lang['qr_code_for'], '<strong>'.e($data['result']).'</strong>')?></div>
        <div class="web-ia-content"><img src="https://chart.apis.google.com/chart?chs=230x230&chld=L|1&choe=UTF-8&cht=qr&chl=<?=e($data['result'])?>"></div>
    </div>
</div>