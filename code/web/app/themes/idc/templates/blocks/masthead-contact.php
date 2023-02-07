<?php

/**
 * Masthead Contact Page Block Template.
 *
 * @var   array $block The block settings and attributes.
 * @var   string $content The block inner HTML (empty).
 * @var   bool $is_preview True during AJAX preview.
 * @var   (int|string) $post_id The post ID this block is saved to.
 */

// SET DEFAULT CLASS & GET ADDITIONAL CLASS NAMES & ALIGN CLASSES FROM GUTENBERG
$className = 'c-masthead-block';

// Create id attribute allowing for custom "anchor" value.
$id = apply_filters('acf_gutenberg_block_id', 'masthead-', $block);

// Load values and assign defaults.
$allowed_blocks = array('core/paragraph', 'core/separator');

$company_info = get_field('add_company_info');
$form = get_field('contact_form');

if (have_rows('company_info')) :
    while (have_rows('company_info')) : the_row();
        $phone = get_sub_field('phone');
        $callablePhone = preg_replace('/[^0-9]/', '', $phone);
        $email = get_sub_field('email');
        $address = get_sub_field('address');
        $linkableAddress = 'https://www.google.com/maps?q=' . urlencode($address);
    endwhile;
endif;
?>
<section id="<?= esc_attr($id) ?>" class="<?= $className ?> <?= $className ?>--contact">
    <div class="<?= $className ?>__inner <?= $className ?>__inner--reverse container container--padding-x container--padding-y-80">
        <?php if ($form) : ?>
            <div class="<?= $className ?>__col--wide <?= $className ?>__form">
                <?php
                $args = array('style' => 'columns', 'checkboxWidth' => 'small', 'alignBtn' => 'full');
                get_template_part('templates/forms/form', 'contact', $args); ?>
            </div>
        <?php endif; ?>
        <div class="<?= $className ?>__content">
            <h1 class="text--purple"><?= the_title() ?></h1>
            <hr class="wp-block-separator is-style-idc-dots-small">
            <?php if ($phone) : ?>
                <p class="<?= $className ?>__info text--small">
                    <a class="text--charcoal" href="tel:+1<?= $callablePhone ?>"><i class="icon text--purple far fa-phone-alt"></i> <?= $phone ?></a>
                </p>
            <?php endif; ?>
            <?php if ($email) : ?>
                <p class="<?= $className ?>__info text--small">
                    <a class="text--charcoal" href="mailto:<?= $email ?>"><i class="icon text--purple far fa-mailbox"></i> <?= $email ?></a>
                </p>
            <?php endif; ?>
            <?php if ($address) : ?>
                <p class="<?= $className ?>__info text--small"> <a class="text--charcoal" target="_blank" href="<?= $linkableAddress ?>"><i class="icon text--purple far fa-map-marker-alt"></i> <?= $address ?></a>
                </p>
            <?php endif; ?>
            <InnerBlocks allowedBlocks="<?= esc_attr(wp_json_encode($allowed_blocks)) ?>" />
        </div>

    </div>
</section>