<?php
defined('FIR') OR exit();
/**
 * The template for displaying the Related Searches results
 */
?>
<div class="related-searches">
    <div class="related-title">
        <?=$lang['related_searches']?>
    </div>
    <div class="related-links">
        <?php for($i = 0, $n = count($data['results']); $i < $n; $i++): ?>
            <?php if($i == 0 || $i == round(($n)/2)): ?>
                <div class="related-col">
            <?php endif ?>
            <div>
                <a href="<?=$data['url']?>/web?q=<?=e($data['results'][$i]['text'])?>"><?=preg_replace('/'.preg_quote($data['query'], '/').'/ui', '<span class="related-query">'.e($data['query']).'</span>', $data['results'][$i]['displayText'], 1)?></a>
            </div>
            <?php if($i == (round($n/2)-1) || $i == ($n-1)): ?>
                </div>
            <?php endif ?>
        <?php endfor ?>
    </div>
</div>