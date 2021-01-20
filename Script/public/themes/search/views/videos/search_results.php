<?php
defined('FIR') OR exit();
/**
 * The template for displaying the Videos Results page
 */
?>
<div class="row row-videos">
    <?=($data['show_ads'] ? $data['settings']['ads_2'] : false)?>
    <?=$this->render(['results' => $data['response']['value']], 'videos/rows')?>
    <?=($data['show_ads'] ? $data['settings']['ads_3'] : false)?>
</div>
<div class="row">
    <?=$data['pagination_view']?>
</div>