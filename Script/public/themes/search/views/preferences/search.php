<?php
defined('FIR') OR exit();
/**
 * The template for displaying the Search settings for Preferences Page
 */
?>
<?=$this->message()?>
<form action="<?=$data['url']?>/preferences/search" method="post">
    <?=$this->token()?>

    <label for="i_market"><?=$lang['region']?></label>
    <select name="market" id="i_market" dir="auto">
        <?php foreach($data['markets'] as $key => $value): ?>
            <option value="<?=e($key)?>"<?=($data['user_market'] == $key ? ' selected' : '')?>><?=e($value[0].' ('.$value[1].')')?></option>
        <?php endforeach ?>
    </select>

    <label for="i_new_window"><?=$lang['new_window']?></label>
    <select name="new_window" id="i_new_window">
        <option value="0"<?=($data['cookie']['new_window'] == 0 ? ' selected' : '')?>><?=$lang['off']?></option>
        <option value="1"<?=($data['cookie']['new_window'] > 0 ? ' selected' : '')?>><?=$lang['on']?></option>
    </select>

    <label for="i_safe_search"><?=$lang['safe_search']?></label>
    <select name="safe_search" id="i_safe_search">
        <option value="Off"<?=($data['cookie']['safe_search'] == 'Off' ? ' selected' : '')?>><?=$lang['off']?></option>
        <option value="Moderate"<?=($data['cookie']['safe_search'] == 'Moderate' ? ' selected' : '')?>><?=$lang['moderate']?></option>
        <option value="Strict"<?=($data['cookie']['safe_search'] == 'Strict' ? ' selected' : '')?>><?=$lang['strict']?></option>
    </select>

    <label for="i_highlight"><?=$lang['highlight']?></label>
    <select name="highlight" id="i_highlight">
        <option value="false"<?=($data['cookie']['highlight'] == 'false' ? ' selected' : '')?>><?=$lang['no']?></option>
        <option value="true"<?=($data['cookie']['highlight'] == 'true' ? ' selected' : '')?>><?=$lang['yes']?></option>
    </select>

    <button type="submit" name="submit" class="float-left"><?=$lang['save']?></button>
</form>