<?php
defined('FIR') OR exit();
/**
 * The template for displaying the Entities Instant Answer
 */
?>
<?php foreach($data['response']['entities']['value'] as $entity): ?>
<div class="row">
    <div class="entity-results">
        <div class="entity-section">
            <div class="entity-media">
                <div class="entity-content">
                    <div class="entity-title"><?=$entity['name']?></div>

                    <?php if(isset($entity['entityPresentationInfo']['entityTypeDisplayHint'])): ?>
                        <div class="entity-type"><?=$entity['entityPresentationInfo']['entityTypeDisplayHint']?></div>
                    <?php endif ?>

                    <?php if(isset($entity['description'])): ?>
                    <div class="entity-description">
                        <?php if(mb_strlen($entity['description']) > 125): ?>
                            <?=str_limit($entity['description'], 0, 125, null)?><span class="read-content"><?=str_limit($entity['description'], 125)?></span><span class="read-more"><?=$lang['ellipsis']?> <span><?=$lang['read_more']?></span></span>
                        <?php else: ?>
                            <?=$entity['description']?>
                        <?php endif ?>
                    </div>
                    <?php endif ?>

                    <?php if(isset($entity['helper']['contract']['description']['attribution']) || isset($entity['helper']['contract']['description']['license'])): ?>
                        <div class="entity-contract">
                            <?php if(isset($entity['helper']['contract']['description']['attribution'])): ?>
                                <?php foreach($entity['contractualRules'] as $rule): ?>
                                    <?php if($rule['_type'] == 'ContractualRules/LinkAttribution' && $rule['targetPropertyName'] == 'description'): ?>
                                        <a href="<?=$rule['url']?>"<?=($data['cookie']['new_window'] > 0 ? ' target="_blank"' : '')?> rel="nofollow" data-nd><?=$rule['text']?></a>
                                    <?php endif ?>
                                <?php endforeach ?>
                            <?php endif ?>

                            <?php if(isset($entity['helper']['contract']['description']['license'])): ?>
                                <?php foreach($entity['contractualRules'] as $rule): ?>
                                    <?php if($rule['_type'] == 'ContractualRules/LicenseAttribution' && $rule['targetPropertyName'] == 'description'): ?>
                                        <?=sprintf($lang['text_under'], '<a href="'.$rule['license']['url'].'"'.($data['cookie']['new_window'] > 0 ? ' target="_blank"' : '').' rel="nofollow" data-nd>'.e($rule['license']['name']).'</a>')?>
                                    <?php endif ?>
                                <?php endforeach ?>
                            <?php endif ?>
                        </div>
                    <?php endif ?>
                </div>

                <?php if(isset($entity['image']['thumbnailUrl'])): ?>
                    <div class="entity-image">
                        <?php if(isset($entity['helper']['contract']['media']['attribution'])): ?>
                            <?php foreach($entity['contractualRules'] as $rule): ?>
                                <?php if($rule['_type'] == 'ContractualRules/MediaAttribution' && $rule['targetPropertyName'] == 'image'): ?>
                                    <a href="<?=$rule['url']?>"<?=($data['cookie']['new_window'] > 0 ? ' target="_blank"' : '')?> rel="nofollow" data-nd><img src="<?=formatImageUrl(str_replace(['w=50', 'h=50'], ['w=200', 'h=200'], $entity['image']['thumbnailUrl']), $data['settings']['search_privacy'])?>"></a>
                                    <?php break; ?>
                                <?php endif ?>
                            <?php endforeach ?>
                        <?php elseif(isset($entity['image']['provider'])): ?>
                            <?php foreach($entity['image']['provider'] as $provider): ?>
                                <a href="<?=$provider['url']?>"<?=($data['cookie']['new_window'] > 0 ? ' target="_blank"' : '')?> rel="nofollow" data-nd><img src="<?=formatImageUrl(str_replace(['w=50', 'h=50'], ['w=200', 'h=200'], $entity['image']['thumbnailUrl']), $data['settings']['search_privacy'])?>"></a>
                            <?php endforeach ?>
                        <?php else: ?>
                            <img src="<?=formatImageUrl(str_replace(['w=50', 'h=50'], ['w=200', 'h=200'], $entity['image']['thumbnailUrl']), $data['settings']['search_privacy'])?>">
                        <?php endif ?>
                    </div>
                <?php endif ?>
            </div>
        </div>

        <?php if(isset($entity['url'])): ?>
        <div class="entity-section">
            <div class="entity-url">
                <div class="entity-url-icon"></div><a href="<?=$entity['url']?>"<?=($data['cookie']['new_window'] > 0 ? ' target="_blank"' : '')?> rel="nofollow" data-nd><?=$entity['displayUrl']?></a>
            </div>
        </div>
        <?php endif ?>

        <?php if(isset($entity['helper']['contract']['footer']['attribution'])): ?>
            <div class="entity-footer">
                <div class="contract-data">
                    <?=$lang['data_from']?>
                    <?php foreach($entity['contractualRules'] as $rule): ?>
                        <?php if($rule['_type'] == 'ContractualRules/LinkAttribution' || $rule['_type'] == 'ContractualRules/TextAttribution'): ?>
                            <a href="<?=$rule['url']?>"<?=($data['cookie']['new_window'] > 0 ? ' target="_blank"' : '')?> rel="nofollow" data-nd><?=$rule['text']?></a>
                        <?php endif ?>
                    <?php endforeach ?>
                </div>
            </div>
        <?php endif ?>
    </div>
</div>
<?php break ?>
<?php endforeach ?>

<?php if(array_slice($data['response']['entities']['value'], 1)): ?>
<div class="row">
    <div class="entity-results see-also">
        <div class="entity-section">
            <div class="see-also-title"><?=$lang['see_results_for']?></div>
        </div>
        <?php foreach(array_slice($data['response']['entities']['value'], 1) as $entity): ?>
            <div class="entity-section">
                <div class="entity-media">
                    <div class="entity-content">
                        <div class="entity-title">
                            <a href="<?=$data['url'].'/'.e($this->url[0]).'?q='.urlencode($entity['name']).'&filters=sid:'.urlencode('"'.$entity['bingId'].'"')?>"><?=$entity['name']?></a>
                        </div>

                        <?php if(isset($entity['description'])): ?>
                            <div class="entity-description">
                                <?=str_limit($entity['description'], 0, 75, ['ellipsis' => $lang['ellipsis']])?>
                            </div>
                        <?php endif ?>
                    </div>

                    <div class="entity-image">
                        <?php if(isset($entity['image']['thumbnailUrl'])): ?>
                            <a href="<?=$data['url'].'/'.e($this->url[0]).'?q='.urlencode($entity['name']).'&filters=sid:'.urlencode('"'.$entity['bingId'].'"')?>"><img src="<?=formatImageUrl(str_replace(['w=50', 'h=50'], ['w=100', 'h=100'], $entity['image']['thumbnailUrl']), $data['settings']['search_privacy'])?>"></a>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        <?php endforeach ?>
    </div>
</div>
<?php endif ?>