<?php
defined('FIR') OR exit();
/**
 * The main template file
 * This file puts together the three main section of the software, header, content and footer
 */
?>
<!DOCTYPE html>
<html class="<?php if($data['cookie']['dark_mode']): ?>dark<?php else: ?>light<?php endif ?><?php if($data['cookie']['center_content']): ?> cc<?php endif ?> <?=$lang['lang_dir']?>" dir="<?=$lang['lang_dir']?>">
<head>
    <meta charset="UTF-8" />
    <title><?=e($this->docTitle())?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <?php if($data['settings']['search_privacy']): ?><meta name="referrer" content="same-origin"><?php endif ?>
    <link href="<?=$data['url']?>/<?=UPLOADS_PATH?>/brand/<?=$data['settings']['favicon']?>" rel="icon">
    <?php foreach(['jquery.flex-images', 'style'] as $name): ?><link href="<?=$data['url']?>/<?=$data['theme_path']?>/<?=$data['settings']['site_theme']?>/assets/css/<?=$name?>.css" rel="stylesheet" type="text/css">
    <?php endforeach ?>
    <?php foreach(['jquery', 'jquery.flex-images', 'dragscroll', 'functions'] as $name): ?><script type="text/javascript" src="<?=$data['url']?>/<?=$data['theme_path']?>/<?=$data['settings']['site_theme']?>/assets/js/<?=$name?>.js"></script>
    <?php endforeach ?>
    <?=$data['settings']['tracking_code']?>
</head>
<body <?php if($data['settings']['site_backgrounds'] && $data['cookie']['backgrounds'] && $data['backgrounds']): ?> class="background" style="background-image: url('<?=$data['url']?>/uploads/backgrounds/<?=$data['backgrounds'][array_rand($data['backgrounds'])]?>');"<?php endif ?>>
    <div id="loading-bar"></div>
    <?=$data['header_view']?>
    <?=$data['content_view']?>
    <?=$data['footer_view']?>
</body>
</html>