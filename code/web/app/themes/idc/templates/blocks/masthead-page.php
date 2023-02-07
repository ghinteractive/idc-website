<?php

/**
 * Masthead Page Block Template.
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
$title = get_the_title();
// Load values and assign defaults.
$allowed_blocks = array('core/columns', 'core/column', 'core/heading', 'core/paragraph', 'core/separator', 'core/button', 'acf/feature-image');

$template = array(
    array('core/columns', array(
        'className' => 'c-solutions-block__columns',
    ), array(
        array('core/column', array(
            'className' => 'c-solutions-block__column c-solutions-block__column--text',
            'verticalAlignment' => 'center',
        ), array(
            array('core/heading', array(
                'level' => 2,
                'content' => $title,
                'textColor' => 'teal',
                'className' => 'h5'
            )),
            array('core/heading', array(
                'level' => 1,
                'placeholder' => 'Solving infectious disease challenges.',
                'textColor' => 'purple',
            )),
            array('core/separator', array(
                'className' => 'is-style-idc-dots-small',
            )),
            array('core/paragraph', array(
                'placeholder' => 'At ID Connect, we strive to make people healthy, improve outcomes, and keep our communities free of infectious diseases. ',
                'textColor' => 'charcoal',
                'fontSize' => 'text-md',
            )),
            array('core/button', array(
                'placeholder' => 'Optional Button',
                'backgroundColor' => 'teal'
            )),
        )),
        array('core/column', array(
            'className' => 'c-solutions-block__column c-solutions-block__column--img',
            'verticalAlignment' => 'center',
        ), array(
            array(
                'acf/feature-image', array(),
            ),
        )),
    ))
);

$color_theme = get_field('select_color_theme');
?>

<section id="<?= esc_attr($id) ?>" class="<?= $className ?> c-solutions-block <?= $className ?>--<?= $color_theme ?>">
    <div class="container container--padding-x">
        <InnerBlocks allowedBlocks="<?= esc_attr(wp_json_encode($allowed_blocks)) ?>" template="<?= esc_attr(wp_json_encode($template)) ?>" />
    </div>
</section>