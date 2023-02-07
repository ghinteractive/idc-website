<?php
global $post;
$terms = get_the_terms($post->ID, 'category');
$permalink = get_permalink();
$title = get_the_title();
$feat_img =  get_the_post_thumbnail();
$excerpt = get_the_excerpt();
$cat = array();
foreach ($terms as $term) :
    $cat[] = $term->name;
endforeach;

$cat = join(',', $cat);

$args = array('id' => 'c-masthead-blog-block-post', 'feat_img' => $feat_img, 'category' => $cat, 'title' => $title, 'permalink' => $permalink, 'excerpt' => $excerpt,  'share' => true);
//grab masthead-blog
get_template_part('partials/component-masthead', 'blog', $args);

$padding_y = 'container--padding-y-64';
if (has_block('acf/news-resources')) : $padding_y = 'container--padding-top-64';
endif;
?>
<article class="content content-post-single container container--padding-x <?= $padding_y ?>">
    <?= apply_filters('ghint/protected-content/hide-gated-content', get_the_content(), $post); ?>
</article>