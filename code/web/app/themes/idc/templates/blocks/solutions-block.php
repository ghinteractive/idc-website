<?php

/**
 * Solutions Block Template.
 *
 * @var   array $block The block settings and attributes.
 * @var   string $content The block inner HTML (empty).
 * @var   bool $is_preview True during AJAX preview.
 * @var   (int|string) $post_id The post ID this block is saved to.
 */


// SET DEFAULT CLASS & GET ADDITIONAL CLASS NAMES & ALIGN CLASSES FROM GUTENBERG
$className = 'c-solutions-block';
$image_style = get_field('image_style');
$feat_img = get_field('image_solutions');
$backgroundStyles = get_field('background_styles');
$order = get_field('flip') ? ' ' . $className . '__columns--reverse' : '';

// Create id attribute allowing for custom "anchor" value.
$id = apply_filters('acf_gutenberg_block_id', 'solutions-', $block);

// Load values and assign defaults.
$allowed_blocks = array('core/heading', 'core/paragraph', 'core/separator', 'core/list', 'core/button', 'core/columns', 'core/column', 'core/group', 'acf/feature-image');


$template = array(
    array('core/columns', array(
        'className' => $className . '__columns' .  $order,
    ), array(
        array('core/column', array(
            'className' => $className . '__column ' . $className . '__column--text',
        ), array(
            array('core/heading', array(
                'level' => 2,
                'content' => 'Title Goes Here',
                'textColor' => 'purple'
            )),
            array('core/paragraph', array(
                'content' => 'Description goes here.',
                'textColor' => 'charcoal',
                'fontSize' => 'text-small',
            )),
            array('core/list', array(
                'content' => 'Description goes here.',
                'textColor' => 'charcoal',
                'fontSize' => 'text-small',
                'className' => 'list--checkmark'
            )),
        )),
        array('core/column', array(
            'className' => $className . '__column ' .  $className . '__column--img',
        ), array(
            array(
                'acf/feature-image', array()
            ),
        )),
    ))
);

?>

<section id="<?= $id ?>" class="<?= $className ?> container--padding-y-64">
    <div class="<?= $className ?>__inner<?= $order ?> container container--padding-x">
        <InnerBlocks allowedBlocks="<?= esc_attr(wp_json_encode($allowed_blocks)) ?>" template="<?= esc_attr(wp_json_encode($template)) ?>" />
    </div>
</section>