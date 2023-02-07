<?php

/**
 * Footer Form Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// SET DEFAULT CLASS & GET ADDITIONAL CLASS NAMES & ALIGN CLASSES FROM GUTENBERG
$className = 'w-footer-form';
// Create id attribute allowing for custom "anchor" value.
$id = apply_filters('acf_gutenberg_block_id', 'w-footer-form-', $block);

// Load values and assign defaults.
$allowed_blocks = array('core/heading', 'core/paragraph', 'outermost/icon-block', 'core/columns', 'core/column', 'core/group',);

$template = array(
    array('core/columns', array(
        'className' => $className . '__columns',
    ), array(
        array('core/column', array(
            'className' => $className . '__column--icon',
        ), array(
            array(
                'outermost/icon-block', array(
                    'iconName' => 'idc-icons-conversation',
                    'iconBackgroundColor' => 'teal',
                    'iconBackgroundColorValue' => '#339999',
                    'iconColor' => 'white',
                    'iconColorValue' => '#ffffff',
                    'width' => 88
                )
            ),
        )),
        array('core/column', array(
            'className' => $className . '__column',
        ), array(
            array('core/heading', array(
                'level' => 2,
                'content' => 'Ready to get started?',
                'textColor' => 'purple'
            )),
        )),
    )),
    array('core/paragraph', array(
        'content' => 'Interested in bringing our products and services to your facility?',
        'className' => 'text--bold',
        'textColor' => 'charcoal',
        'fontSize' => 'text-big',
    )),
    array('core/paragraph', array(
        'content' => 'Our ID innovative software and service solutions can help to elevate your practice. Working together we can improve access to world-class, specialized ID expertise, enrich lives and improve outcomes in a cost-effective manner with compassion, respect and integrity.',
        'textColor' => 'charcoal',
        'fontSize' => 'text-small',
    ))
);

?>

<div class="<?= $className ?> <?= $className ?>__inner container container--padding-x container--padding-y">
    <div class="<?= $className ?>__content">
        <InnerBlocks allowedBlocks="<?= esc_attr(wp_json_encode($allowed_blocks)) ?>" template="<?= esc_attr(wp_json_encode($template)) ?>" />
    </div>
    <div class="<?= $className ?>__col <?= $className ?>__form">
        <?php
        $args = array('style' => 'columns', 'checkboxWidth' => 'small', 'alignBtn' => 'right');
        get_template_part('templates/forms/form', 'contact', $args); ?>
    </div>

</div>