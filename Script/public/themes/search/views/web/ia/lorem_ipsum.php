<?php
defined('FIR') OR exit();
/**
 * The template for displaying the Lorem Ipsum Instant Answer
 */
?>
<div class="row row-card-result">
    <div class="web-ia web-ia-lorem-ipsum">
        <div class="web-ia-title"><strong><?=sprintf($lang['x_lorem_ipsum'], $data['count'])?></strong></div>
        <div class="web-ia-content">
            <?php foreach($data['result'] as $paragraph): ?>
                <div><?=$paragraph?></div>
            <?php endforeach; ?>
        </div>
    </div>
</div>