<?php
the_content();
$category = get_terms('job-categories');
$className = 'c-our-team';
$args = array('type' => 'post', 'message' => __('Loading', 'ghint'));
get_template_part('partials/component', 'modal-loading', $args);
?>

<section class="<?= $className ?> container container--padding-yxxl">
    <div class="<?= $className ?>__wrapper container--padding-x container--padding-y">
        <div class="<?= $className ?>__headline container--padding-yxs">
            <h2 class="text--purple">Meet the IDC team</h2>
        </div>

        <form class="<?= $className ?>__filter-container text-mini" id="team-member-search" action="<?= get_rest_url(null, '/wp/v2/team-members') ?>" method="GET">
            <div class="<?= $className ?>__filter container--padding-yxs">
                <label class="h6 text--charcoal" for="team-dept-filter">Teams</label>
                <div class="<?= $className ?>__input input--icon-arrow-down">
                    <select id="team-dept-filter" class="catFilter" name="job-categories">
                        <option value="">Showing all</option>
                        <?php foreach ($category as $cat) : ?>
                            <option data-category="<?php echo $cat->slug; ?>" value="<?php echo $cat->term_id; ?>">
                                <?php echo $cat->name; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="<?= $className ?>__filter container--padding-yxs">
                <label class="h6 text--charcoal" for="search-team">Who can we help you find?</label>
                <div class="<?= $className ?>__input input--icon-search">
                    <input type="search" class="search-field" placeholder="Enter a first or last name" value="" name="search">
                </div>

            </div>
            <input type="hidden" name="page" value="<?= esc_attr($_GET['page'] ?? 1) ?>" />
            <input type="hidden" name="per_page" value="16" />
            <input type="hidden" name="_embed" value="true" />
        </form>
    </div>

    <div id="team-members" class="<?= $className ?>__posts container--padding-x container--padding-y"></div>
    <div id="team-members-pagination" class="<?= $className ?>__pagination container--padding-y-64 container--padding-x">
    </div>
</section>

<?php
if (is_active_sidebar('join-our-team')) :
    dynamic_sidebar('join-our-team');
endif;
?>