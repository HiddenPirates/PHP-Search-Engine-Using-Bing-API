<?php
defined('FIR') OR exit();
/**
 * The template for displaying the Videos Results rows
 */
?>
<div class="videos-results">
    <?php foreach($data['results'] as $result): ?>
        <div class="site-result">
            <div class="site-media">
                <a href="<?=$result['hostPageUrl']?>"<?=($data['cookie']['new_window'] > 0 ? ' target="_blank"' : '')?> data-nd>
                    <div class="video-frame" style="background-image: url('<?=formatImageUrl($result['thumbnailUrl'], $data['settings']['search_privacy'])?>');">
                        <div class="thumb-icon">
                            <div class="video-play"></div>
                        </div>
                        <?php if(isset($result['duration'])): ?>
                            <div class="video-duration"><?=$result['duration']?></div>
                        <?php endif ?>
                    </div>
                </a>
            </div>

            <div class="site-content">
                <div class="site-title"><a href="<?=$result['hostPageUrl']?>"<?=($data['cookie']['new_window'] > 0 ? ' target="_blank"' : '')?> data-nd><?=truncate($result['name'], 56, ['ellipsis' => $lang['ellipsis'], 'html' => true, 'exact' => false])?></a></div>
                <div class="site-url"><a href="<?=$result['hostPageUrl']?>"<?=($data['cookie']['new_window'] > 0 ? ' target="_blank"' : '')?> data-nd><?=$result['publisher'][0]['name']?></a><?php if(isset($result['datePublished'])): ?><span class="site-date"> - <?=$result['datePublished']?></span><?php endif ?><?php if(isset($result['viewCount']['count']) && $result['viewCount']['count'] > 0): ?><span class="site-views"> - <?=sprintf($lang['views_x'], $result['viewCount']['count'].$lang['views_'.$result['viewCount']['decimals']])?></span><?php endif ?></div>
                <?php if(isset($result['description'])): ?>
                    <div class="site-description"><?=truncate($result['description'], 160, ['ellipsis' => $lang['ellipsis'], 'html' => true, 'exact' => false])?></div>
                <?php endif ?>
            </div>
        </div>
    <?php endforeach ?>
</div>
