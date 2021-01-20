<?php
defined('FIR') OR exit();
/**
 * The template for displaying the User Screen Resolution Instant Answer
 */
?>
<div class="row row-card-result">
    <div class="web-ia web-ia-user-screen-resolution">
        <div class="web-ia-title"><?=sprintf($lang['your_sr_is'])?></div>
        <div class="web-ia-content"><span class="web-ia-user-screen-resolution-width"></span> × <span class="web-ia-user-screen-resolution-height"></span></div>
    </div>
</div>
<script>iaUserScreenResolution();</script>