<?php //variables
$className = 'c-search-results';
$alignClass = have_posts() ? '--wide' : '--small';
$havePosts = have_posts() ? true : false;
?>

<?php get_template_part('partials/page', 'header'); ?>

<div class="container container--padding-x container--padding-y-96">
    <div class="<?= $className ?>__inner">
        <div class="container--padding-y <?= $className ?>__content <?= $className ?>__content<?= $alignClass ?>">
            <?php if (!have_posts()) : ?>
                <div class="alert alert-warning">
                    <h2 class="h5 text--uppercase text--teal"><?php _e('No Results', 'ghint'); ?>.</h2>
                    <h1 class="text--purple"><?php _e('Can we help you find something else?', 'ghint'); ?></h1>
                </div>
                <?php
                $args = array('className' => 'c-search-results__form', 'size' => 'sm', 'message' => __('Search again ...', 'ghint'));
                get_template_part('partials/component', 'search-nav', $args);
                ?>
            <?php endif; ?>

            <?php if (have_posts()) : ?>
                <div class="container--padding-y">
                    <h1 class="h5 text--purple text--uppercase"><?= sprintf(__('%d Search Results for \'%s\'', 'ghint'), $wp_query->found_posts, $_GET['s']); ?></h1>
                </div>
                <?php
                while (have_posts()) : the_post();
                    get_template_part('partials/content', 'search');
                endwhile;
                ?>
            <?php endif; ?>

            <?php if (get_previous_posts_link() || get_next_posts_link()) : ?>
                <div class="<?= $className ?>__nav">
                    <?php if (get_previous_posts_link()) : ?>
                        <div class="wp-block-button is-style-idc-button">
                            <?= get_previous_posts_link("Previous Page"); ?>
                        </div>
                    <?php endif; ?>
                    <?php if (get_next_posts_link()) : ?>
                        <div class="wp-block-button is-style-idc-button">
                            <?= get_next_posts_link("Next Page"); ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <?php if (is_active_sidebar('search-results')) : ?>
            <aside class="<?= $className ?>__sidebar <?= $className ?>__sidebar<?= $alignClass ?>">
                <?php if ($havePosts) : ?>
                    <h2 class="text--purple"><?php _e('Don\'t see what you\'re looking for? Try these instead..', 'ghint'); ?>.</h2>
                <?php endif; ?>
                <?php dynamic_sidebar('search-results'); ?>
            </aside>
        <?php endif; ?>
    </div>
</div>


<?php
if (is_active_sidebar('search-results-news-resources')) :
    dynamic_sidebar('search-results-news-resources');
endif;
?>