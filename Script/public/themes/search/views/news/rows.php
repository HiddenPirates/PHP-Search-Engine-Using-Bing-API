<?php
defined('FIR') OR exit();
/**
 * The template for displaying the News Results rows
 */
?>
<div class="news-results">
    <?php foreach($data['results'] as $result): ?>
        <div class="site-result">
            <?php if(isset($result['image']['thumbnail']['contentUrl'])): ?>
                <div class="site-media">
                    <a href="<?=$result['url']?>"<?=($data['cookie']['new_window'] > 0 ? ' target="_blank"' : '')?> data-nd>
                        <div class="news-thumbnail" style="background-image: url('<?=formatImageUrl($result['image']['thumbnail']['contentUrl'], $data['settings']['search_privacy'])?>');"></div>
                    </a>
                </div>
            <?php endif ?>

            <div class="site-content">
                <div class="site-title"><a href="<?=$result['url']?>"<?=($data['cookie']['new_window'] > 0 ? ' target="_blank"' : '')?> data-nd><?=truncate($result['name'], 56, ['ellipsis' => $lang['ellipsis'], 'html' => true, 'exact' => false])?></a></div>
                <div class="site-url"><a href="<?=$result['url']?>"<?=($data['cookie']['new_window'] > 0 ? ' target="_blank"' : '')?> data-nd><?=$result['provider'][0]['name']?></a> - <?=$result['datePublished']?></div>
                <div class="site-description"><?=truncate($result['description'], 160, ['ellipsis' => $lang['ellipsis'], 'html' => true, 'exact' => false])?></div>
            </div>
        </div>
    <?php endforeach ?>
</div>
