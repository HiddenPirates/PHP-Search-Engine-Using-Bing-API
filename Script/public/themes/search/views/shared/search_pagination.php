<?php
defined('FIR') OR exit();
/**
 * The template for displaying the pagination buttons
 */
?>
<div class="results-pagination">
    <?php if($data['prev_button']): ?>
        <a href="<?=$data['url'].'/'.e($this->url[0]).'?q='.e($data['query'])?><?php foreach($data['filters'] as $filter): ?><?=(isset($_GET[$filter]) ? '&'.$filter.'='.e($_GET[$filter]) : '')?><?php endforeach ?>"><div class="button button-pagination pagination-home"></div></a>
    <?php else: ?>
        <div class="button button-pagination button-margin-right button-disabled pagination-home"></div>
    <?php endif ?>

    <?php if($data['prev_button']): ?>
        <a href="<?=$data['url'].'/'.e($this->url[0]).'?q='.e($data['query'])?><?php foreach($data['filters'] as $filter): ?><?=(isset($_GET[$filter]) ? '&'.$filter.'='.e($_GET[$filter]) : '')?><?php endforeach ?>&offset=<?=e($data['current_page']-$data['per_page'])?>"><div class="button button-pagination pagination-prev"></div></a>
    <?php else: ?>
        <div class="button button-pagination button-margin-right button-disabled pagination-prev"></div>
    <?php endif ?>

    <?php if($data['next_button']): ?>
        <a href="<?=$data['url'].'/'.e($this->url[0]).'?q='.e($data['query'])?><?php foreach($data['filters'] as $filter): ?><?=(isset($_GET[$filter]) ? '&'.$filter.'='.e($_GET[$filter]) : '')?><?php endforeach ?>&offset=<?=e($data['current_page']+$data['per_page'])?>"><div class="button button-pagination pagination-next"></div></a>
    <?php else: ?>
        <div class="button button-pagination button-disabled pagination-next"></div>
    <?php endif ?>

    <div class="pagination-details">
        <div class="results-by"><a href="https://privacy.microsoft.com/en-us/privacystatement" target="_blank" data-nd>Results by Bing</a></div>
        <div class="x-results"><?=sprintf($lang['x_results'], number_format($data['estimated_results'], 0, $this->lang['decimals_separator'], $this->lang['thousands_separator']))?></div>
    </div>
</div>