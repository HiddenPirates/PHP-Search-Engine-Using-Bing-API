<?php
defined('FIR') OR exit();
/**
 * The template for displaying the Images Results rows
 */
?>
<div class="preview-pane">
    <div class="preview-pane-arrow"></div>

    <div class="pane-close"></div>

    <div class="preview-buttons">
        <div class="button button-pagination button-neutral button-margin-right pagination-prev pane-prev"></div>
        <div class="button button-pagination button-neutral pagination-next pane-next"></div>
    </div>

    <div class="pane-content">
        <div class="pane-image">
            <a id="pane-url-image"<?=($data['cookie']['new_window'] > 0 ? ' target="_blank"' : '')?> data-nd>
                <img id="pane-thumb">
                <img id="pane-image" onload="updatePaneImage()" onerror="updatePaneImage(1)">
            </a>
        </div>
        <div class="pane-description">
            <div class="pane-image-name"><a id="pane-url-name"<?=($data['cookie']['new_window'] > 0 ? ' target="_blank"' : '')?> data-nd></a></div>
            <div class="pane-image-url"><a id="pane-url-url"<?=($data['cookie']['new_window'] > 0 ? ' target="_blank"' : '')?> data-nd></a></div>
            <div class="pane-image-size" id="pane-image-size"></div>
            <div class="pane-buttons">
                <a id="preview-button-host"<?=($data['cookie']['new_window'] > 0 ? ' target="_blank"' : '')?> data-nd><div class="button button-neutral"><?=$lang['source']?></div></a>
                <a id="preview-button-image"<?=($data['cookie']['new_window'] > 0 ? ' target="_blank"' : '')?> data-nd><div class="button button-neutral button-margin-left"><?=$lang['image']?></div></a>
            </div>
        </div>
    </div>
</div>
<div id="images-results" class="images-results flex-images">
    <?php foreach($data['results'] as $result): ?>
        <div class="item image-frame" data-w="<?=$result['thumbnail']['width']?>" data-h="<?=$result['thumbnail']['height']?>" data-image-size="<?=e($result['width'])?> × <?=e($result['height'])?>" data-image-url="<?=e(formatImageUrl($result['contentUrl'], $data['settings']['search_privacy']))?>" data-image-source-url="<?=e($result['contentUrl'])?>" data-image-name="<?=e($result['name'])?>" data-image-display-url="<?=e($result['displayUrl'])?>" data-image-host-url="<?=e($result['hostPageUrl'])?>">
            <img src="<?=formatImageUrl($result['thumbnailUrl'], $data['settings']['search_privacy'])?>">
            <div class="thumb-icon"><div class="image-view"></div></div>
            <div class="image-description"><?=$result['width']?> × <?=$result['height']?></div>
        </div>
    <?php endforeach ?>
</div>