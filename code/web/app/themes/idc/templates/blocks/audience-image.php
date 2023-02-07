<?php

/**
 * Audience Image Block Template.
 *
 * @var   array $block The block settings and attributes.
 * @var   string $content The block inner HTML (empty).
 * @var   bool $is_preview True during AJAX preview.
 * @var   (int|string) $post_id The post ID this block is saved to.
 */

// SET DEFAULT CLASS & GET ADDITIONAL CLASS NAMES & ALIGN CLASSES FROM GUTENBERG
$className = 'c-audience-image-block';
$color = get_field('color');
$image = get_field('image');
// Create id attribute allowing for custom "anchor" value.
$id = apply_filters('acf_gutenberg_block_id', 'audience-image-', $block);

$allowed_blocks = array('core/heading', 'core/paragraph');

$template = array(
    array('core/heading', array(
        'level' => 3,
        'content' => 'H3 Headline',
        'textColor' => 'charcoal'
    )),
    array('core/paragraph', array(
        'content' => 'Description goes here.',
        'textColor' => 'charcoal',
        'fontSize' => 'text--small',
    ))
)
?>

<div id="<?= $id ?>" class="<?= $className ?> container--padding-yxl">
    <div class="<?= $className ?>__img">
        <div class="<?= $className ?>__background <?= $className ?>__background--<?= $color ?>"></div>
        <div class="<?= $className ?>__foreground">
            <div class="viewport">
                <img src="<?= $image['url'] ?>" alt="<?= $image['alt'] ?: 'IDC Audience Member' ?>">
            </div>
        </div>
    </div>
    <div class="<?= $className ?>__content text--center">
        <InnerBlocks allowedBlocks="<?= esc_attr(wp_json_encode($allowed_blocks)) ?>" template="<?= esc_attr(wp_json_encode($template)) ?>" />
    </div>

</div>

<?php if (is_admin()) : ?>
    <style type="text/css">
        /** Disable links in admin view */
        .c-audience-image-block__img {
            overflow: hidden;
            position: relative;
            z-index: 0;
            opacity: .7;
        }
    </style>
<?php endif; ?>