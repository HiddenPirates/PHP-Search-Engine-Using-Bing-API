<?php
defined('FIR') OR exit();
/**
 * The template for displaying the News Results page
 */
?>
<div class="row row-news">
    <?=($data['show_ads'] ? $data['settings']['ads_2'] : false)?>
    <?=$this->render(['results' => $data['response']['value']], 'news/rows')?>
    <?=($data['show_ads'] ? $data['settings']['ads_3'] : false)?>
</div>
<div class="row">
    <?=$data['pagination_view']?>
</div>