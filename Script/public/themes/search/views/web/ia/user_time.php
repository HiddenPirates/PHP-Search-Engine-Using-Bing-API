<?php
defined('FIR') OR exit();
/**
 * The template for displaying the User Time Instant Answer
 */
?>
<div class="row row-card-result">
    <div class="web-ia web-ia-user-time">
        <div class="web-ia-title"><?=$lang['current_time_is']?></div>
        <div class="web-ia-content"></div>
        <div class="web-ia-footer"></div>
    </div>
</div>
<script>iaUserDateTime('<?=$lang['date_format']?>', ['<?=implode('\',\'', $data['months'])?>'], 0);</script>