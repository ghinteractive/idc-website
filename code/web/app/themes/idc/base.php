<?php

use GHInt\Setup;
use Roots\Sage\Wrapper;

$bg = (has_block('acf/masthead-page') || has_block('acf/masthead-blog') || has_block('acf/masthead-contact') || (is_single() && 'team-members' !== get_post_type())) ? 'bg--none' : 'bg--default';
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<?php get_template_part('partials/head'); ?>

<body <?php body_class(); ?>>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-NZGVVZF" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <?php get_template_part('partials/header'); ?>
    <main id="main" class="<?= $bg ?>">
        <?php include Wrapper\template_path(); ?>
    </main>
    <?php get_template_part('partials/footer'); ?>
</body>

</html>