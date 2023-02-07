<?php

/**
 * News & Insights Block Template.
 *
 * @var   array $block The block settings and attributes.
 * @var   string $content The block inner HTML (empty).
 * @var   bool $is_preview True during AJAX preview.
 * @var   (int|string) $post_id The post ID this block is saved to.
 */

// SET DEFAULT CLASS & GET ADDITIONAL CLASS NAMES & ALIGN CLASSES FROM GUTENBERG
$className = 'c-posts-block';
$background = is_page() ? 'bg-color--white' : 'bg-color--teal-light';
$title = is_page() ? 'News & Resources' : 'You might also like...';
// Create id attribute allowing for custom "anchor" value.
$id = apply_filters('acf_gutenberg_block_id', 'c-posts-', $block);

// Load values and assign defaults.
$allowed_blocks = array('core/heading');
$template = array(
    array('core/heading', array(
        'level' => 2,
        'content' => $title,
        'textColor' => 'purple'
    )),
);

$align = (!empty($block['align'])) ? ' align' . $block['align'] : '';

$post_options = get_field('post_options');

$post_id = get_the_ID();
$manual = array(
    'post_type'      => 'post',
    'post_status'    => 'publish',
    'posts_per_page' => 3,
    'order'          => 'DESC',
    'orderby'        => 'date',
    'post__not_in'   => array(get_the_ID()),
);

if ($post_options === 'category') :
    $category = get_field('posts_category');
    $manual['category'] = $category ? implode(',', $category) : '';
endif;

$posts = $post_options === 'manual' ? get_field('posts_manual') : get_posts($manual);
?>

<section id="<?= $id ?>" class="container--padding-y-80 <?= $className ?><?= $align ?> <?= $background ?>">
    <div class="container container--padding-x">
        <div class="<?= $className ?>__headline">
            <InnerBlocks allowedBlocks="<?= esc_attr(wp_json_encode($allowed_blocks)) ?>" template="<?= esc_attr(wp_json_encode($template)) ?>" />
            <div class="<?= $className ?>___cta <?= $className ?>__cta--desktop is-style-idc-button">
                <a href="/news-resources/" title="More News & Resources" class="wp-block-button__link has-teal-background-color has-background">More News & Resources</a>
            </div>
        </div>

        <div class="c-blog__posts container--padding-y">
            <?php
            foreach ($posts ?: [] as $post) :
                $args = array('id' => $post->ID);
                get_template_part('partials/component-posts', 'blog', $args);
            endforeach;
            ?>
        </div>
        <div class="<?= $className ?>___cta <?= $className ?>__cta--mobile has-custom-width is-style-idc-button wp-block-button__width-100">
            <a href="/news-resources/" title="More News & Resources" class="wp-block-button__link has-teal-background-color has-background">More News & Resources</a>
        </div>
    </div>
</section>

<?php if (is_admin()) : ?>
    <style type="text/css">
        /** Disable links in admin view */
        .c-posts-block a {
            pointer-events: none;
        }
    </style>
<?php endif; ?>