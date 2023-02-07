<?php

/**
 * Author Block Template.
 *
 * @var   array $block The block settings and attributes.
 * @var   string $content The block inner HTML (empty).
 * @var   bool $is_preview True during AJAX preview.
 * @var   (int|string) $post_id The post ID this block is saved to.
 */

// Set default class
$className = 'c-author-block';

// Create id attribute allowing for custom "anchor" value.
$id = apply_filters('acf_gutenberg_block_id', 'c-author-', $block);

// Load values and assign defaults.
$allowed_blocks = array('core/heading', 'core/button');

$template = array(
    array('core/heading', array(
        'level' => 3,
        'content' => __('About the Author', 'ghint'),
        'textColor' => 'purple'
    )),
    array('core/paragraph', array(
        'content' => __('Add a short text blurb about the author. If no text is provided here, the author\'s name and title will display.', 'ghint'),
        'textColor' => 'charcoal',
        'fontSize' => 'text-md',
    )),
    array('core/button', array(
        'text' => __('Optional CTA Button', 'ghint'),
        'backgroundColor' => 'teal'
    ))
);

// ACF Fields
$authors = get_field('author');

if ($authors):
    foreach($authors as $author):
?>
    <div id="<?= $id ?>" class="<?= $className ?>">

        <div class="<?= $className ?>__intro">
            <?php if ($authorImage = get_the_post_thumbnail($author->ID)) : ?>
                <div class="<?= $className ?>__feat-img">
                    <div class="<?= $className ?>__feat-img-wrapper"><?= $authorImage ?></div>
                </div>
            <?php endif; ?>

            <div class="<?= $className ?>__title">
                <InnerBlocks allowedBlocks="<?= esc_attr(wp_json_encode($allowed_blocks)) ?>" template="<?= esc_attr(wp_json_encode($template)) ?>" />
            </div>
        </div>
    </div>
<?php
    endforeach;
endif;
?>