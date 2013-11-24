<?php
/**
 * @package    Molajo
 * @copyright  2013 Amy Stephen. All rights reserved.
 * @license    http://www.opensource.org/licenses/mit-license.html MIT License
 */
?>
<header id="header-page" class="contain-to-grid not-small-device" role="banner">
    <div class="row">
        <div class="page-menu">
            <ul class="page-menu">
                <li>
                    <a href="<?php $parameters->page->urls['page']; ?>">
                        <?php echo $parameters->page->heading1; ?>
                    </a>
                </li>
                <?php
                $pageSubmenu = $parameters->page->menu['PageSubmenu'];
                if (count($pageSubmenu) > 0) {
                    foreach ($pageSubmenu as $menu) {
                        if ((int)$menu->current == 1) {
                            $active = ' class="active"';
                        } else {
                            $active = '';
                        }
                        ?>
                        <li>
                            <a<?php echo $active; ?> href="<?php echo $menu->link; ?>">
                                [ <?php echo $menu->link_text; ?> ]
                            </a>
                        </li>
                    <?php } ?>
                <?php } ?>
            </ul>
        </div>
    </div>
    <?php
    $pageArray = $parameters->page->menu['SectionSubmenu'];
    $count = count($pageArray);
    if ($count > 0) {
        ?>
        <div class="row">
            <div class="section-submenu">
                <ul class="section-submenu">
                    <li>
                        <strong><?php echo $this->language->translate('Options') . ': '; ?></strong>
                    </li>
                    <?php
                    foreach ($pageArray as $pages) {
                        if ((int)$pages->current == 1) {
                            $active = ' class="active"';
                        } else {
                            $active = '';
                        } ?>
                        <li>
                            <a<?php echo $active; ?> id="<?php echo $pages->id; ?>" href="<?php echo $pages->url; ?>">
                                <?php echo $pages->title; ?>
                            </a>
                        </li>
                    <?php
                    }
                    ?>
                </ul>
            </div>
        </div>
    <?php } ?>
</header>
<header id="header-page" class="small-device" role="banner">
    <div class="row">
        <div class="page-menu">
            <ul class="page-menu">
                <?php
                $pageSubmenu = $parameters->page->menu['PageSubmenu'];
                $i = 0;
                if (count($pageSubmenu) > 0) {
                    foreach ($pageSubmenu as $menu) {
                        if ((int)$menu->current == 1) {
                            $active = ' class="active"';
                        } else {
                            $active = '';
                        }
                        if ($i == 0) {
                            $text = $parameters->page->heading1;
                        } else {
                            $text = '[ ' . $menu->link_text . '] ';
                        }
                        $i ++;
                        ?>
                        <li>
                            <a<?php echo $active; ?> href="<?php echo $menu->link; ?>">
                                <?php echo $text; ?>
                            </a>
                        </li>
                    <?php } ?>
                <?php } ?>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="section-submenu">
            <ul class="section-submenu">
                <?php
                $pageArray = $parameters->page->menu['SectionSubmenu'];
                $count = count($pageArray);

                if ($count > 0) {
                    foreach ($pageArray as $pages) {
                        if ((int)$pages->current == 1) {
                            $active = ' class="active"';
                        } else {
                            $active = '';
                        }
                        ?>
                        <li>
                            <a<?php echo $active; ?> id="<?php echo $pages->url; ?>" href="<?php echo $pages->url; ?>">
                                <?php echo $pages->title; ?>
                            </a>
                        </li>
                    <?php
                    }
                }
                ?>
            </ul>
        </div>
    </div>
</header>
