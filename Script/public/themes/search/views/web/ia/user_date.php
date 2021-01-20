<?php
defined('FIR') OR exit();
/**
 * The template for displaying the User Date Instant Answer
 */
?>
<div class="row row-card-result">
    <div class="web-ia web-ia-user-date">
        <div class="web-ia-title"><?=$lang['current_date_is']?></div>
        <div class="web-ia-content"></div>
        <div class="web-ia-footer"></div>
    </div>
</div>
<script>iaUserDateTime('<?=$lang['date_format']?>', ['<?=implode('\',\'', $data['months'])?>'], 1);</script>