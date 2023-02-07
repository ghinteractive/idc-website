<?php

/**
 * Solutions Image Block Template.
 *
 * @var   array $block The block settings and attributes.
 * @var   string $content The block inner HTML (empty).
 * @var   bool $is_preview True during AJAX preview.
 * @var   (int|string) $post_id The post ID this block is saved to.
 */

use function GHInt\Helpers\generateClasses;

// SET DEFAULT CLASS & GET ADDITIONAL CLASS NAMES & ALIGN CLASSES FROM GUTENBERG
$className = 'c-solutions-image-block';

// Create id attribute allowing for custom "anchor" value.
$id = apply_filters('acf_gutenberg_block_id', 'solutions-image-', $block);

$technologyStyles = get_field('technology_styles');
$backgroundStyles = get_field('background_styles');
$foreground = get_field('foreground');
$foregroundSize = get_field('foreground_size') ?: 'medium';

$browserImageHeight = $technologyStyles['height'] ?? 250;

$classes = iterator_to_array(generateClasses(array(
    $className => true,
    sprintf('%s--technology-color-%s', $className, $technologyStyles['color'] ?? 'charcoal') => $technologyStyles['enabled'],
    sprintf('%s--background-color-%s', $className, $backgroundStyles['color'] ?? 'charcoal') => $backgroundStyles['enabled'],
)));
?>

<div class="<?= implode(' ', $classes) ?>">

    <?php if ($technologyStyles['enabled']) : ?>
        <div class="<?= $className ?>__technology">
            <svg width="208" height="<?= $browserImageHeight ?>" viewBox="0 0 208 <?= $browserImageHeight ?>" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect x="0.5" y="1.37793" width="207" height="<?= $browserImageHeight - 2 ?>" rx="7.5" />
                <line x1="1" y1="14.3779" x2="208" y2="14.3779" />
                <circle cx="8" cy="7.87793" r="2.5" />
                <circle cx="16" cy="7.87793" r="2.5" />
                <circle cx="24" cy="7.87793" r="2.5" />
            </svg>
        </div>
    <?php endif; ?>

    <?php if ($backgroundStyles['enabled']) : ?>
        <div class="<?= $className ?>__background"></div>
    <?php endif; ?>

    <div class="<?= $className ?>__foreground">
        <div class="viewport">
            <?= wp_get_attachment_image($foreground, $foregroundSize); ?>
        </div>
    </div>

    <div class="<?= $className ?>__border" style="transform: rotate(<?= $backgroundStyles['rotation'] ?? 0 ?>deg);"></div>
</div>