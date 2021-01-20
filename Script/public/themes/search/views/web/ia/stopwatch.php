<?php
defined('FIR') OR exit();
/**
 * The template for displaying the Stopwatch Instant Answer
 */
?>
<script>var stopwatch = new iaStopwatch();</script>
<div class="row row-card-result">
    <div class="web-ia web-ia-stopwatch">
        <div class="web-ia-title"><?=$lang['stopwatch']?></div>
        <div class="web-ia-content">00:00.00</div>
        <div class="web-ia-footer">
            <div id="sw-start" class="button float-left" onclick="stopwatch.start()"><?=$lang['start']?></div>
            <div id="sw-stop" class="button float-left" onclick="stopwatch.stop()"><?=$lang['stop']?></div>
            <div id="sw-reset" class="button button-neutral button-margin-left float-left" onclick="stopwatch.reset()"><?=$lang['reset']?></div>
        </div>
    </div>
</div>