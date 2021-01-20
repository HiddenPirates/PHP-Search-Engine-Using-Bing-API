<?php
defined('FIR') OR exit();
/**
 * The template for displaying the Unix Time Instant Answer
 */
?>
<div class="row row-card-result">
    <div class="web-ia web-ia-unix-time">
        <div class="web-ia-title">
            <?php if($data['type'] == 1): ?>
                <?=sprintf($lang['unix_time_for'], '<strong>'.e($data['query']).'</strong>')?>
            <?php else: ?>
                <?=$lang['unix_time']?>
            <?php endif; ?>
        </div>
        <div class="web-ia-content">
            <?php if($data['type'] == 1): ?>
                <?=sprintf($lang['date_format'], $data['date'][0], $lang['month_'.$data['date'][1]], $data['date'][2])?> <?=$data['time']?> <?=$lang['gmt']?>
            <?php else: ?>
                <?=$data['unix_time']?>
            <?php endif; ?>
        </div>
    </div>
</div>