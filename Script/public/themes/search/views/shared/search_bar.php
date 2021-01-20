<?php
defined('FIR') OR exit();
/**
 * The template for displaying the Search Bar
 */
?>
<div class="search-content<?=(isset($data['top_bar']) ? ' search-content-s' : '')?>">
    <div class="search-container">
        <input type="text" name="search" id="search-input" class="search-input" tabindex="1" autocomplete="off" autocapitalize="off" autocorrect="off" data-search-url="<?=$data['url']?>/" data-search-path="<?=$data['query_path']?>" data-suggestions-path="requests/suggestions" value="<?=e(isset($data['query']) ? $data['query'] : '')?>" data-token-id="<?=$_SESSION['token_id']?>" data-autofocus="<?=$data['autofocus']?>" data-suggestions="<?=$data['settings']['search_suggestions']?>">
        <div id="clear-button" class="clear-button"></div>
        <div id="search-button" class="search-button"></div>
        <div class="search-list">
            <div class="search-list-container" id="search-results"></div>
        </div>
    </div>
</div>