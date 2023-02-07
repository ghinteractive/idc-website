<?php //variables

$className = 'c-team-member';
$feat_img = get_the_post_thumbnail();
$title = get_field('title') ?: '';
$job_type = wp_get_post_terms(get_the_ID(), 'job-categories', ['fields' => 'slugs']);

$args = array(
    'post_type' => 'team-members',
    'post_status' => 'publish',
    'posts_per_page'  => 5,
    'order' => 'DESC',
    'tax_query' => array(
        array(
            'taxonomy' => 'job-categories',
            'field' => 'slug',
            'terms' => $job_type,
        )
    ),
    'post__not_in' => array(get_the_ID())
);
$related_dept = get_posts($args);

?>
<article class="<?= $className ?> container container--padding-x">

    <div class="<?= $className ?>__intro">
        <?php if ($feat_img) : ?>
            <div class="<?= $className ?>__feat-img">
                <div class="<?= $className ?>__feat-img-wrapper">
                    <?= $feat_img ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="<?= $className ?>__title">
            <h1 class="text--purple"><?php the_title(); ?></h1>
            <?php if ($title) : ?>
                <h2 class="h6 text--teal"><?= $title ?></h2>
            <?php endif; ?>
        </div>
    </div>

    <div class="<?= $className ?>__content container--padding-yl">

        <div class="<?= $className ?>__post container--padding-yxl">
            <?php the_content(); ?>

            <div class="wp-block-button is-style-idc-button">
                <button class="btn--sidebar-form wp-block-button__link has-teal-background-color has-background">Contact IDC</button>
            </div>
        </div>

        <?php if ($related_dept) : ?>
            <div class="<?= $className ?>__sidebar container--padding-yxl">
                <h3 class="text--purple">More Department Team Members</h3>

                <?php
                foreach ($related_dept as $post) :
                    setup_postdata($post);
                    get_template_part('partials/component', 'sidebar-team');
                endforeach;
                wp_reset_postdata();
                ?>
                <div class="wp-block-button is-style-idc-button-text-right">
                    <a class="wp-block-button__link" href="/about/our-team/">View the whole team</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</article>
<?php
if (is_active_sidebar('join-our-team')) :
    dynamic_sidebar('join-our-team');
endif;
?>