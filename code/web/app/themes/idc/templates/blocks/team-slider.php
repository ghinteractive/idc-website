<?php

/**
 * Team Slider Block Template.
 *
 * @var   array $block The block settings and attributes.
 * @var   string $content The block inner HTML (empty).
 * @var   bool $is_preview True during AJAX preview.
 * @var   (int|string) $post_id The post ID this block is saved to.
 */

// SET DEFAULT CLASS & GET ADDITIONAL CLASS NAMES & ALIGN CLASSES FROM GUTENBERG
$className = 'c-team-slider';

// Create id attribute allowing for custom "anchor" value.
$id = apply_filters('acf_gutenberg_block_id', 'c-team-slider-', $block);

// Load values and assign defaults.
$allowed_blocks = array('core/heading');
$template = array(
    array('core/heading', array(
        'level' => 2,
        'content' => 'Expert Leadership',
        'textColor' => 'purple'
    )),
);

$auto = array(
    'post_type'      => 'team-members',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'order'          => 'ASC',
    'orderby'        => 'title',
    'post__not_in'   => array(get_the_ID()),
);

$team_options = get_field('team_member_options');

if ($team_options === 'category') :
    $category = get_field('team_member_category');

    $taxQuery[] = array(
        'taxonomy' => 'job-categories',
        'field' => 'id',
        'terms' =>  $category ? implode(',', $category) : '',
    );
    $auto['tax_query'] = $taxQuery;
endif;

$posts = $team_options === 'team_members_manual' ? get_field('team_members_manual') : get_posts($auto);
?>

<section id="<?= $id ?>" class="container--padding-x container--padding-y-64 <?= $className ?>">

    <div class="container <?= $className ?>__headline text--center">
        <InnerBlocks allowedBlocks="<?= esc_attr(wp_json_encode($allowed_blocks)) ?>" template="<?= esc_attr(wp_json_encode($template)) ?>" />
    </div>

    <div class="container <?= $className ?>__inner container--padding-yxxl">
        <div class="<?= $className ?>__swiper swiper">
            <div class="swiper-wrapper">

                <?php
                foreach ($posts ?: [] as $post) :
                    /* Swiper Slides */
                ?>
                    <div class="swiper-slide">
                        <?php
                        $args = array('id' => $post->ID);
                        get_template_part('partials/component', 'team-members', $args);
                        ?>
                    </div>
                <?php endforeach;
                ?>
            </div>
        </div>
        <div class="swiper-button-prev"></div>
        <div class="swiper-button-next"></div>
    </div>

    <!-- End wrapper -->

    <div class="<?= $className ?>__cta-button text--center is-style-idc-button">
        <a class="wp-block-button__link has-teal-background-color has-background" href="/about/our-team/">Meet the Whole Team</a>
    </div>

</section>

<?php if (is_admin()) : ?>
    <style type="text/css">
        /** Disable links in admin view */
        .c-team-slider a {
            pointer-events: none;
        }
    </style>
<?php endif; ?>