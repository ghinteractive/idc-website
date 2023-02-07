<?php

/**
 * Accordion Block Template.
 *
 * @var   array $block The block settings and attributes.
 * @var   string $content The block inner HTML (empty).
 * @var   bool $is_preview True during AJAX preview.
 * @var   (int|string) $post_id The post ID this block is saved to.
 */

// SET DEFAULT CLASS & GET ADDITIONAL CLASS NAMES & ALIGN CLASSES FROM GUTENBERG
$className = 'c-accordion-block';

// Create id attribute allowing for custom "anchor" value.
$id = apply_filters('acf_gutenberg_block_id', 'c-accordion-', $block);

// ACF Fields
$headline = get_field('headline');

// Load values and assign defaults.
$allowed_blocks = array('core/paragraph', 'core/spacer', 'core/list', 'core/button');

$template = array(
    array('core/paragraph', array(
        'content' => 'Description goes here and will be hidden until the headline text is clicked which will reveal the content.',
        'textColor' => 'charcoal',
        'fontSize' => 'text-mini',
    ))
);
?>

<div id="<?= $id ?>" class="<?= $className ?> container container--padding-ysm container--padding-x">
    <div class="<?= $className ?>__accordion container--padding-x">
        <button class="<?= $className ?>__headline h6">
            <span><?= $headline ?></span>
            <i class="<?= $className ?>__icon text--teal far fa-caret-down"></i>
        </button>

        <div class="<?= $className ?>__panel">
            <InnerBlocks allowedBlocks="<?= esc_attr(wp_json_encode($allowed_blocks)) ?>" template="<?= esc_attr(wp_json_encode($template)) ?>" />
        </div>
    </div>
</div>