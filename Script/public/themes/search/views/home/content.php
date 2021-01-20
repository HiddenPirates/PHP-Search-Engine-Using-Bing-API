<?php
defined('FIR') OR exit();
/**
 * The template for displaying Home page content
 */
?>
<div id="content" class="content content-home">
    <div class="home-center">
        <div class="home-logo"><div class="logo-large"><a href="<?=$data['url']?>/"><img src="<?=$data['url']?>/<?=UPLOADS_PATH?>/brand/<?php if($data['cookie']['dark_mode']): ?><?=$data['settings']['logo_large_dark']?><?php else: ?><?php if($data['settings']['site_backgrounds'] && $data['cookie']['backgrounds']): ?><?=$data['settings']['logo_large_dark']?><?php else: ?><?=$data['settings']['logo_large']?><?php endif ?><?php endif ?>"></a></div></div>
        <?=$data['search_bar_view']?>
        <div class="home-description"><?=e($data['settings']['site_tagline'])?></div>
        <?=($data['show_ads'] ? $data['settings']['ads_1'] : false)?>
    </div>
</div>