<?php
defined('FIR') OR exit();
/**
 * The template for displaying the Web Results rows
 */
?>
<div class="site-result">
    <div class="site-title"><a href="<?=$data['results']['url']?>"<?=($data['cookie']['new_window'] > 0 ? ' target="_blank"' : '')?> data-nd><?=$data['results']['name']?></a></div>
    <div class="site-url"><a href="<?=$data['results']['url']?>"<?=($data['cookie']['new_window'] > 0 ? ' target="_blank"' : '')?> data-nd><?=$data['results']['displayUrl']?></a></div>
    <div class="site-description"><?=truncate($data['results']['snippet'], 256, ['ellipsis' => $lang['ellipsis'], 'html' => true, 'exact' => false])?></div>

    <?php if(isset($data['results']['deepLinks'])): ?>
        <div class="<?php if(array_key_exists_r('snippet', $data['results']['deepLinks']) == true): ?>deep-links<?php else: ?>deep-links-inline<?php endif ?>">
            <?php for($i = 0, $n = count($data['results']['deepLinks']), $t = array_key_exists_r('snippet', $data['results']['deepLinks']); $i < $n; $i++): ?>
                <?php if($i == 0 || $i == round(($n)/2)): ?>
                    <?php if($t == true): ?>
                        <div class="deep-links-col">
                    <?php endif ?>
                <?php endif ?>
                <div class="deep-link">
                    <div class="site-title"><a href="<?=$data['results']['deepLinks'][$i]['url']?>"<?=($data['cookie']['new_window'] > 0 ? ' target="_blank"' : '')?> data-nd><?=$data['results']['deepLinks'][$i]['name']?></a><?php if(isset($data['results']['deepLinks'][$i]['snippet'])): ?><span class="site-description"><?=truncate($data['results']['deepLinks'][$i]['snippet'], 75, ['ellipsis' => $lang['ellipsis'], 'html' => true, 'exact' => false])?></span><?php endif ?></div>
                </div>
                <?php if($i == (round($n/2)-1) || $i == ($n-1)): ?>
                    <?php if($t == true): ?>
                        </div>
                    <?php endif ?>
                <?php endif ?>
            <?php endfor ?>
        </div>
    <?php endif ?>
</div>