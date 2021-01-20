<?php
defined('FIR') OR exit();
/**
 * The template for displaying the Search Requests
 */
?>
<?php if(isset($data['response']['suggestionGroups']) && !empty($data['response']['suggestionGroups'])): ?>
    <?php foreach($data['response']['suggestionGroups'][0]['searchSuggestions'] as $result): ?>
        <div class="search-list-item">
            <a href="<?=$data['url']?>/<?=e($data['searchType'])?>?q=<?=e($result['query'])?>"><?=preg_replace('/'.preg_quote($data['query'], '/').'/ui', '<span class="search-query">'.e($data['query']).'</span>', $result['displayText'], 1)?></a>
        </div>
    <?php endforeach ?>
<?php endif ?>