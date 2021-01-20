<?php
defined('FIR') OR exit();
/**
 * The template for displaying the No Results Error page
 */
?>
<div class="row row-no-results">
    <?=$this->message()?>
    <div class="no-results">
        <?=sprintf($this->lang['no_results_found'], '<strong>'.e($_GET['q']).'</strong>')?>
        <p><?=$lang['suggestions']?></p>
        <ul>
            <li><?=$lang['suggestion_1']?></li>
            <li><?=$lang['suggestion_2']?></li>
            <li><?=$lang['suggestion_3']?></li>
        </ul>
    </div>
</div>