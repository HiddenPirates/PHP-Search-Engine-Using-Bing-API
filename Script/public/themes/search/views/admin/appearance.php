<?php
defined('FIR') OR exit();
/**
 * The template for displaying the Admin Panel General Settings section
 */
?>
<?=$this->message()?>
<form action="<?=$data['url']?>/admin/appearance" method="post" enctype="multipart/form-data">
    <?=$this->token()?>

    <label for="i_logo_small"><?=$lang['logo_small']?></label>
    <input type="file" name="logo_small" id="i_logo_small" accept=".png,.svg">

    <label for="i_logo_small_dark"><?=$lang['logo_small']?> (<?=$lang['dark_mode']?>)</label>
    <input type="file" name="logo_small_dark" id="i_logo_small_dark" accept=".png,.svg">

    <label for="i_logo_large"><?=$lang['logo_large']?></label>
    <input type="file" name="logo_large" id="i_logo_large" accept=".png,.svg">

    <label for="i_logo_large_dark"><?=$lang['logo_large']?> (<?=$lang['dark_mode']?>)</label>
    <input type="file" name="logo_large_dark" id="i_logo_large_dark" accept=".png,.svg">

    <label for="i_favicon"><?=$lang['favicon']?></label>
    <input type="file" name="favicon" id="i_favicon" accept=".png">

    <div class="divider"></div>

    <label for="i_backgrounds"><?=$lang['backgrounds']?></label>
    <select name="site_backgrounds" id="i_backgrounds">
        <option value="0"<?=($data['site_settings']['site_backgrounds'] == 0 ? ' selected' : '')?>><?=$lang['off']?></option>
        <option value="1"<?=($data['site_settings']['site_backgrounds'] > 0 ? ' selected' : '')?>><?=$lang['on']?></option>
    </select>

    <label for="i_dark_mode"><?=$lang['dark_mode']?> (<?=$lang['default']?>)</label>
    <select name="site_dark_mode" id="i_dark_mode">
        <option value="0"<?=($data['site_settings']['site_dark_mode'] == 0 ? ' selected' : '')?>><?=$lang['off']?></option>
        <option value="1"<?=($data['site_settings']['site_dark_mode'] > 0 ? ' selected' : '')?>><?=$lang['on']?></option>
    </select>

    <label for="i_center_content"><?=$lang['center_content']?> (<?=$lang['default']?>)</label>
    <select name="site_center_content" id="i_center_content">
        <option value="0"<?=($data['site_settings']['site_center_content'] == 0 ? ' selected' : '')?>><?=$lang['off']?></option>
        <option value="1"<?=($data['site_settings']['site_center_content'] > 0 ? ' selected' : '')?>><?=$lang['on']?></option>
    </select>

    <button type="submit" name="submit"><?=$lang['save']?></button>
</form>