<?php
defined('FIR') OR exit();
/**
 * The template for displaying the Sort Instant Answer
 */
?>
<div class="row row-card-result">
    <div class="web-ia web-ia-hex-color">
        <div class="web-ia-hex-color-preview" style="background: #<?=$data['hex']?>;"></div>
        <div class="web-ia-hex-color-list" dir="auto">
            <div>#<?=$data['hex']?></div>
            <div>rgb(<?=implode(', ', $data['rgb'])?>)</div>
            <div>hsl(<?=$data['hsl'][0]?>, <?=$data['hsl'][1]?>%, <?=$data['hsl'][2]?>%)</div>
            <div>cmyk(<?=$data['cmyk'][0]?>%, <?=$data['cmyk'][1]?>%, <?=$data['cmyk'][2]?>%, <?=$data['cmyk'][3]?>%)</div>
        </div>
    </div>
</div>