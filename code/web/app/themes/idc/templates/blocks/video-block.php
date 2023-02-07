<?php

/**
 * Video Block Template.
 *
 * @var   array $block The block settings and attributes.
 * @var   string $content The block inner HTML (empty).
 * @var   bool $is_preview True during AJAX preview.
 * @var   (int|string) $post_id The post ID this block is saved to.
 */

// SET DEFAULT CLASS & GET ADDITIONAL CLASS NAMES & ALIGN CLASSES FROM GUTENBERG
$className = 'c-testimonials-block'; /* Using same classname as testimonial to use the same styles */

// Create id attribute allowing for custom "anchor" value.
$id = apply_filters('acf_gutenberg_block_id', 'testimonials', $block);

// Load values and assign defaults.
$videoID = "testimonial_" . uniqid();
$video = get_field('video_url');
$caption = get_field('video_caption');
?>

<section id="<?= $id ?>" class="<?= $className ?>">
    <div class="<?= $className ?>__inner">
        <div class="<?= $className ?> <?= $className ?>--video container--padding-y-96" data-testimonial-id="<?= $videoID ?>">
            <div class="container container--padding-x">
                <?php $args = array('id' => $videoID, 'width' => 'full');
                get_template_part('partials/component-video', 'block', $args); ?>
                <div class="<?= $className ?>__caption text--mini text--teal container--padding-y">
                    <?= $caption ?>
                </div>
            </div>
        </div>
    </div>
</section>