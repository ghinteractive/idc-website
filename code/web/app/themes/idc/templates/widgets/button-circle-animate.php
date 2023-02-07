<?php

/**
 * Button Circle Animated Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

use Roots\Assets;

// SET DEFAULT CLASS & GET ADDITIONAL CLASS NAMES & ALIGN CLASSES FROM GUTENBERG
$className = 'w-btn-circle';

// Create id attribute allowing for custom "anchor" value.
$id = apply_filters('acf_gutenberg_block_id', 'w-btn-circle-', $block);

$allowed_blocks = array('core/button');

?>

<div id="<?= esc_attr($id); ?>" class="<?= esc_attr($className); ?>">
    <?= '<InnerBlocks allowedBlocks="' . esc_attr(wp_json_encode($allowed_blocks)) . '" />'; ?>
</div> 