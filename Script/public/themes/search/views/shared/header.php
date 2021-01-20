<?php
defined('FIR') OR exit();
/**
 * The template for displaying the Header section
 */
?>
<div id="header" class="header<?php if(!$this->url[0]): ?> header-home<?php endif ?>">
    <div class="header-content">
        <div class="header-col header-col-logo"><a href="<?=$data['url']?>/"><div class="logo-small"><img src="<?=$data['url']?>/<?=UPLOADS_PATH?>/brand/<?php if($data['cookie']['dark_mode']): ?><?=$data['settings']['logo_small_dark']?><?php else: ?><?php if($data['settings']['site_backgrounds'] && $data['cookie']['backgrounds']): ?><?php if(!$this->url[0]): ?><?=$data['settings']['logo_small_dark']?><?php else: ?><?=$data['settings']['logo_small']?><?php endif ?><?php else: ?><?=$data['settings']['logo_small']?><?php endif ?><?php endif ?>"></div></a></div>
        <div class="header-col header-col-content">
            <?php if(isset($data['search_bar_view'])): ?>
                <?=$data['search_bar_view']?>
            <?php elseif(!$this->url[0]): ?>
                <div class="page-menu header-menu">
                    <?php foreach($data['menu'] as $value): ?>
                        <div class="home-search-menu<?=($value[1] == true ? ' home-search-menu-active' : '')?>" id="path-<?=$value[0]?>" data-new-path="<?=$value[0]?>"><?=$lang[$value[0]]?></div>
                    <?php endforeach ?>
                </div>
            <?php endif ?>
        </div>
        <div class="header-col header-col-menu">
            <div class="header-menu-el">
                <div class="icon header-icon icon-menu menu-button" id="db-menu" data-db-id="menu"></div>
                <div class="menu" id="dd-menu">
                    <div class="menu-content">
                        <?php foreach($data['site_menu'] as $key => $value): ?>
                            <?php if(isset($_SESSION['user']['id'])): ?>
                                <div class="divider"></div>
                            <?php else: ?>
                                <div class="menu-title"><?=$lang[$key]?></div>
                                <div class="divider"></div>
                            <?php endif ?>

                            <?php if(is_array($value)): ?>
                                <?php foreach($value as $list => $meta): ?>
                                    <a href="<?=$data['url']?>/<?=$key?>/<?=$list?>"<?=(($meta[0] == true) ? ' data-nd' : '')?>><div class="menu-icon icon-<?=$list?>"></div><?=$lang[$list]?></a>
                                <?php endforeach ?>
                            <?php endif ?>
                        <?php endforeach ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>