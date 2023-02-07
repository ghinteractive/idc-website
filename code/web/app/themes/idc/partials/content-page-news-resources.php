<?php
the_content();
$category = get_terms('category');
$className = 'c-blog';

$args = array('type' => 'post', 'message' => __('Loading', 'ghint'));
get_template_part('partials/component', 'modal-loading', $args);
?>

<section class="<?= $className ?> container container--padding-yxxl">
    <div class="<?= $className ?>__wrapper container--padding-x container--padding-y">
        <form class="<?= $className ?>__filter-container text-mini" id="blog-search" action="<?= get_rest_url(null, '/wp/v2/posts') ?>" method="GET">
            <div class="<?= $className ?>__filter <?= $className ?>__filter--small container--padding-yxs">
                <label class="hidden h6 text--charcoal" for="blog-category-filter">Category</label>
                <div class="<?= $className ?>__input input--icon-arrow-down">
                    <select id="blog-category-filter" class="catFilter" name="categories">
                        <option value="">Select a Category</option>
                        <?php foreach ($category as $cat) : ?>
                            <option data-category="<?php echo $cat->slug; ?>" value="<?php echo $cat->term_id; ?>">
                                <?php echo $cat->name; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="<?= $className ?>__filter <?= $className ?>__filter--small container--padding-yxs">
                <label class="hidden h6 text--charcoal" for="search-blog">Search</label>
                <div class="<?= $className ?>__input input--icon-search">
                    <input type="search" class="search-field" placeholder="Search by keyword" value="" name="search">
                </div>
            </div>
            <input type="hidden" name="page" value="<?= esc_attr($_GET['page'] ?? 1) ?>" />
            <input type="hidden" name="per_page" value="12" />
            <input type="hidden" name="_embed" value="true" />
        </form>
    </div>
    <div id="news-resources" class="<?= $className ?>__posts container--padding-x container--padding-y"></div>
    <div class="text--center container--padding-y-64 container--padding-x"> <button id="load-posts" class="<?= $className ?>__showMoreButton wp-block-button__link has-teal-background-color has-background" type="button" title="Show More <?php the_title(); ?>">Show More Posts</button>
    </div>
</section>