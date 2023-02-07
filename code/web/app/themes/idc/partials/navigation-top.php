<nav class="c-nav c-nav--primary" id="mainNavigation" aria-label="<?php _e('Main Menu', 'ghint'); ?>">
    <?php
    wp_nav_menu(array(
        'menu'              => 'Main Menu', // This name should match the aria-label name
        'menu_class'        => 'c-nav__menu menu',
        'container'         => 'ul',
        'walker'            => new IDC_Walker_Nav_Menu(), // Make sure this name matches name in walker.php in lib folder
        'theme_location'    => 'primary_navigation',
        'fallback_cb'       => false // prevents errors on front-end if no menu exist
    ));
    ?>
</nav>