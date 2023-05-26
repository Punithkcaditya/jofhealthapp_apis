<a class="navbar-brand" href="<?= base_url() ?>">JOF Dashboard</a>

<ul class="side-menu">

    <?php if (!empty($_SESSION['sidebar_menuitems'])) : ?>


    <?php foreach ($_SESSION['sidebar_menuitems'] as $main_menus) : ?>
 
    <li <?php if (strtolower($main_menus->menuitem_link) == strtolower($menuslinks)) { ?>class="active slide"
        <?php } else { ?>class="slide" <?php } ?>>
        <a class="side-menu__item active" data-toggle="slide"
            href="#<?php echo base_url($main_menus->menuitem_text); ?>">
            <i class="<?php echo $main_menus->menu_icon; ?>" style="min-width: 2.25rem;"></i>
            <span class="side-menu__label"> <?php echo $main_menus->menuitem_text; ?></span>
            <i class="angle fa fa-angle-right"></i>
        </a>
        <ul class="slide-menu">
            <?php if (!empty($main_menus->submenus)) : ?>
            <?php foreach ($main_menus->submenus as $submenus) : ?>
            <li class="slide-item" <?php /*if($submenus->menuitem_link==$route){ ?>class="active" <?php } */ ?>>
                <a href="<?php echo base_url($submenus->menuitem_link); ?>">
                    <i class="<?php echo $submenus->menu_icon; ?>"></i>
                    <span class="side-menu__label"><?php echo $submenus->menuitem_text; ?></span>
                </a>
            </li>
            <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </li>
    <?php endforeach; ?>
    <?php endif; ?>

</ul>