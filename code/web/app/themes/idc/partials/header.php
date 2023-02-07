<?php

use Roots\Assets;
?>

<header class="c-header">
    <div class="c-header__inner container--padding-x container--padding-ysm">

        <div class="controls controls--top">
            <div class="c-header__logo">
                <a aria-label="homepage link" href="<?= get_home_url() ?>">
                    <img src="<?= Assets\asset_path('images/idc_logo_full.svg') ?>" alt="Infectious Disease Connect Logo" />
                </a>
            </div>

            <div class="controls__nav controls__nav--open">
                <button class="controls__button controls__button--open" type="button">
                    <i class="fal fa-2xl fa-bars"></i>
                </button>
            </div>
        </div>

        <div class="c-header__nav">
            <div class="controls__nav controls__nav--close">
                <?php
                $args = array('className' => 'c-header__search', 'size' => 'sm', 'message' => __('Can we help you find something?', 'ghint'));
                get_template_part('partials/component', 'search-nav', $args);
                ?>
                <button class="controls__button controls__button--close" type="button">
                    <i class="fal fa-2xl fa-xmark"></i>
                </button>
            </div>
            <?php get_template_part('partials/navigation', 'top') ?>
        </div>

    </div>
</header>

<aside class="c-sidebar-search bg-color--teal">
    <?php get_template_part('partials/component', 'search-overlay') ?>
</aside>

<aside class="c-sidebar-form">
    <?php get_template_part('partials/component', 'sidebar-form') ?>
</aside>