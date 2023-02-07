<?php

$compClassName = 'c-footer';

do_action(sprintf('ghint/component-render-%s', $compClassName));
?>
<?php //variables
$args = array('type' => 'contact', 'message' => __('Thank you, the form submit was successful!', 'ghint'));
get_template_part('partials/component', 'modal-loading', $args);
?>
<?php
if (is_active_sidebar('footer-form') && get_field('enable_footer_form')) :
    $bgColor = get_field('form_background_color');
?>
    <section class="c-footer-form bg-color--<?= $bgColor ?> container--padding-y-96">
        <?php dynamic_sidebar('footer-form'); ?>
    </section>
<?php endif; ?>
<footer id="footer" class="<?= $compClassName ?>">
    <div class="bg-color bg-color--purple bg-color--dark text--white">
        <div class="container container--padding-x container--padding-yxxl">
            <div class="<?= $compClassName ?>--flex">

                <div class="<?= $compClassName ?>__col <?= $compClassName ?>__col--left">
                    <?php
                    if (is_active_sidebar('footer-col-one')) :
                        dynamic_sidebar('footer-col-one');
                    endif;
                    ?>
                </div>

                <div class="<?= $compClassName ?>__col <?= $compClassName ?>__col--right">
                    <?php
                    if (is_active_sidebar('footer-col-two')) :
                        dynamic_sidebar('footer-col-two');
                    endif;
                    ?>
                    <?php
                    if (is_active_sidebar('footer-col-three')) :
                        dynamic_sidebar('footer-col-three');
                    endif;
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="bg-color--purple">
        <div class="container container--padding-x container--padding-y">
            <div class="<?= $compClassName ?>__copyright">
                <p class="text--white text--uppercase">© <?= date("Y") . ' ' . get_bloginfo('name') ?> All rights
                    reserved.
                </p>

                <nav id="legalNav" class="<?= $compClassName ?>__nav" aria-label="<?php _e('Footer Legal Menu', 'ghint'); ?>">
                    <?php
                    wp_nav_menu(array(
                        'menu' => 'Footer Legal Menu', // This name should match the aria-label name
                        'menu_class' => 'menu menu--legal text--uppercase',
                        'container' => 'ul',
                        'walker' => new IDC_Walker_Nav_Menu(), // Make sure this name matches name in walker.php in lib folder
                        'theme_location' => 'footer_legal_navigation',
                        'fallback_cb' => false // prevents errors on front-end if no menu exist
                    ));
                    ?>
                </nav>
            </div>
        </div>
    </div>
</footer>

<?php wp_footer(); ?>