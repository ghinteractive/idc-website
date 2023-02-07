<?php
use Roots\Assets;
/**
 * Partners Block Template.
 *
 * @var   array $block The block settings and attributes.
 * @var   string $content The block inner HTML (empty).
 * @var   bool $is_preview True during AJAX preview.
 * @var   (int|string) $post_id The post ID this block is saved to.
 */

// SET DEFAULT CLASS & GET ADDITIONAL CLASS NAMES & ALIGN CLASSES FROM GUTENBERG
$className = 'c-partners-block';

// Create id attribute allowing for custom "anchor" value.
$id = apply_filters('acf_gutenberg_block_id', 'partners-', $block);

// Load values and assign defaults.
$allowed_blocks = array('core/heading', 'core/button');
$template = array(
    array('core/heading', array(
        'level' => 3,
        'content' => __('Our Partners & Investors', 'ghint'),
        'textColor' => 'purple'
    )),
    array('core/button', array(
        'text' => __('Optional CTA Button', 'ghint'),
        'backgroundColor' => 'teal'
    )),
);

// ACF Fields
$layout_theme = get_field('select_layout_theme') ?: 'align-center';
?>
<section id="<?= esc_attr($id) ?>" class="<?= $className ?> <?= $className ?>--<?= $layout_theme ?>">
    <div class="<?= $className ?>__inner container container--padding-x">

        <div class="<?= $className ?>__intro">
            <InnerBlocks allowedBlocks="<?= esc_attr(wp_json_encode($allowed_blocks)) ?>" template="<?= esc_attr(wp_json_encode($template)) ?>" />
        </div>

        <?php if (have_rows('partner_logos')) : ?>
            <div class="<?= $className ?>__content">
                <?php while (have_rows('partner_logos')) : the_row(); ?>

                    <?php
                        $partnerLogo = get_sub_field('logo_image');
                        $partnerLink = get_sub_field('partner_link');
                    ?>
                    <?php if ($partnerLink): ?>
                        <a class="<?= $className ?>__logo" href="<?= $partnerLink['url'] ?>" target="<?= $partnerLink['target'] ?? '_self' ?>">
                    <?php else: ?>
                        <div class="<?= $className ?>__logo">
                    <?php endif; ?>

                        <img src="<?= $partnerLogo['sizes']['medium'] ?>" alt="<?= $partnerLogo['alt'] ?>">

                    <?php if ($partnerLink): ?>
                        </a>
                    <?php else: ?>
                        </div>
                    <?php endif; ?>
                <?php endwhile; ?>
            </div>
        <?php endif; ?>
    </div>
</section>