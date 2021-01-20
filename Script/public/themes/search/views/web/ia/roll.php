<?php
defined('FIR') OR exit();
/**
 * The template for displaying the Roll Instant Answer
 */
?>
<div class="row row-card-result">
    <div class="web-ia web-ia-roll">
        <div class="web-ia-title"><?=$lang['you_have_rolled']?></div>
        <div class="web-ia-content"><div class="web-ia-roll-value"><?=$data['result']?></div><div class="web-ia-roll web-ia-roll-total">/<?=$data['total']?></div></div>
    </div>
</div>