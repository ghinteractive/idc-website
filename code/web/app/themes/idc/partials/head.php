<?php

use Roots\Assets;
use function Env\env;
?>

<head>
    <!-- Google Tag Manager -->
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src = 'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-NZGVVZF');
    </script>
    <!-- End Google Tag Manager -->
    <!-- Chemistry Snippet -->
    <script type="text/javascript" src="https://secure.tube0mark.com/js/210115.js"></script>
    <noscript><img src="https://secure.tube0mark.com/210115.png" style="display:none;" /></noscript>
    <!-- End of Chemistry Snippet -->
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="" />

    <?php // Feel free to rip this chunk out if you are not using the Dotenv environment variable package
    ?>
    <?php if (env('WP_ENV') === 'production') : ?>
        <meta name="robots" content="index, follow" />
    <?php else : ?>
        <meta name="robots" content="noindex, nofollow" />
    <?php endif; ?>

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <?php // You may also want to remove the <title> tag if you are using an SEO plugin as it is automatically injected into the template
    ?>
    <title><?php wp_title('|', true, 'right'); ?></title>

    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo Assets\asset_path('meta/apple-touch-icon.png'); ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php echo Assets\asset_path('meta/favicon-32x32.png'); ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo Assets\asset_path('meta/favicon-16x16.png'); ?>">
    <link rel="manifest" href="<?php echo Assets\asset_path('meta/site.webmanifest'); ?>">
    <link rel="shortcut icon" href="<?php echo Assets\asset_path('meta/favicon.ico'); ?>">
    <link rel="mask-icon" href="<?php echo Assets\asset_path('meta/safari-pinned-tab.svg" color="#3cafab'); ?>">
    <meta name="msapplication-TileColor" content="#00aba9">
    <meta name="msapplication-config" content="<?php echo Assets\asset_path('meta/browserconfig.xml'); ?>">
    <meta name="theme-color" content="#ffffff">

    <?php wp_head(); ?>
</head>