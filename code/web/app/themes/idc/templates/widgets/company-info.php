<?php

/**
 * Company Information Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

use Roots\Assets;

// use function Roots\Assets\asset_path;

// SET DEFAULT CLASS & GET ADDITIONAL CLASS NAMES & ALIGN CLASSES FROM GUTENBERG
$className = 'w-company-info';

// Create id attribute allowing for custom "anchor" value.
$id = apply_filters('acf_gutenberg_block_id', 'w-company-info', $block);

// Load values and assign defaults.
$logo = get_field('logo');
$logoUse = wp_get_attachment_image($logo, 'full') ?: '<img src="' . Assets\asset_path('images/idc_logo_white.svg') . '" alt="' . get_bloginfo('name') . ' logo"/>';
$content = get_field('tagline') ?: '';
$address = get_field('address') ?: '2009 Mackenzie Way. Suite 100, Cranberry Twp. PA 16066';
$linkableAddress = 'https://www.google.com/maps?q=' . urlencode($address);
$phone_numbs = get_field('phone_numbers');
$phone = get_field('phone') ?: '(833) 271-2408';
$callablePhone = preg_replace('/[^0-9]/', '', $phone);
$email = get_field('email') ?: 'connect@idctelemed.com';
?>

<div id="<?= esc_attr($id); ?>" class="<?= esc_attr($className); ?> text--big text--white">
    <div class="<?= $className ?>__logo">
        <?= $logoUse ?>
    </div>

    <div class="<?= $className ?>__tagline">
        <?= $content ?>
    </div>
    <div class="<?= $className ?>__contact">
        <p class="h6 address">
            <a target="_blank" href="<?= $linkableAddress ?>"><i class="<?= $className ?>__icon far fa-map-marker-alt"></i> <?= $address ?></a>
        </p>
        <p class="h6 phone-email">
            <?php if (have_rows('phone_numbers')) : ?>
                <?php while (have_rows('phone_numbers')) : the_row();
                    $phone = get_field('phone') ?: '(833) 271-2408';
                    $callablePhone = preg_replace('/[^0-9]/', '', $phone);
                ?>
                    <a class="phone" href="tel:+1<?= $callablePhone ?>"><i class="<?= $className ?>__icon far fa-phone-alt"></i> <?= $phone ?></a>
                <?php endwhile; ?>
            <?php endif; ?>
            <a class="email" href="mailto:<?= $email ?>"><i class="<?= $className ?>__icon far fa-mailbox"></i> <?= $email ?></a>
        </p>
    </div>
    <?php
    if (have_rows('social_media')) :
        while (have_rows('social_media')) : the_row();
            $linkedIn = get_sub_field('linkedin');
            $twitter = get_sub_field('twitter');
    ?>
            <div class="<?= $className ?>__social">
                <?php if ($linkedIn) : ?>
                    <a class="<?= $className ?>__social-icon" aria-label="LinkedIn" target="_blank" href="<?= $linkedIn ?>"><i class="fab fa-linkedin-in"></i></a>
                <?php endif; ?>

                <?php if ($twitter) : ?>
                    <a class="<?= $className ?>__social-icon" aria-label="Twitter" target="_blank" href="<?= $twitter ?>"><i class="fab fa-twitter"></i></a>
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    <?php endif; ?>

</div>