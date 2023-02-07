<?php

/**
 * Masthead for News & Insights Block Template.
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

// Load values and assign defaults.
$feat_post = get_field('featured_post');

if ($feat_post) :
    foreach ($feat_post as $post) :
        $permalink = get_permalink($post->ID);
        $title = get_the_title($post->ID);
        $feat_img =  get_the_post_thumbnail($post->ID);
        $excerpt = get_the_excerpt($post->ID);
        $terms = get_the_terms($post->ID, 'category');
        $cat = array();
        foreach ($terms as $term) :
            $cat[] = $term->name;
        endforeach;

        $cat = join(',', $cat);

        $args = array('id' => $id, 'feat_img' => $feat_img, 'category' => $cat, 'title' => $title, 'permalink' => $permalink, 'excerpt' => $excerpt, 'share' => false);
        //grab masthead-blog
        get_template_part('partials/component-masthead', 'blog', $args);
    endforeach;
endif;
