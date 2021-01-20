<?php
defined('FIR') OR exit();
/**
 * The template for displaying the MD5 Instant Answer
 */
?>
<div class="row row-card-result">
    <div class="web-ia web-ia-md5">
        <div class="web-ia-title"><?=sprintf($lang['md5_hash_for'], '<strong>'.e($data['query']).'</strong>')?></div>
        <div class="web-ia-content"><?=$data['result']?></div>
    </div>
</div>