<?php

/**
 * Masthead Page Block Template.
 *
 * @var   array $block The block settings and attributes.
 * @var   string $content The block inner HTML (empty).
 * @var   bool $is_preview True during AJAX preview.
 * @var   (int|string) $post_id The post ID this block is saved to.
 */

// SET DEFAULT CLASS & GET ADDITIONAL CLASS NAMES & ALIGN CLASSES FROM GUTENBERG
$className = 'c-career-opportunities-block';

// Create id attribute allowing for custom "anchor" value.
$id = apply_filters('acf_gutenberg_block_id', 'career-opportunities-', $block);

$jobs = get_posts(array('post_type' => 'job-postings'));
$ctaLink = get_field('cta_link');
$taxonomies = get_field('taxonomy_filters') ?: array('job-categories', 'offices');
$filterLabels = array(
    'offices' => [
        'field' => __('Select an Office', 'ghint'),
        'all' => __('Showing All Offices', 'ghint'),
    ],
    'job-categories' => [
        'field' => __('Select a Job Category', 'ghint'),
        'all' => __('Showing All Categories', 'ghint'),
    ],
);

$allowed_blocks = array('core/heading', 'core/paragraph', 'core/separator', 'core/button');
$template = array(
    array('core/heading', array(
        'level' => 2,
        'content' => __('Join Our Team', 'ghint'),
        'textColor' => 'teal',
        'fontSize' => 'text-md',
    )),
    array('core/heading', array(
        'level' => 3,
        'content' => __('Career Opportunities at IDC Telemed', 'ghint'),
        'textColor' => 'purple',
        'fontSize' => 40,
    )),
    array('core/paragraph', array(
        'content' => _x('Description goes here.', 'Placeholder text: Career Opportunities Block', 'ghint'),
        'textColor' => 'charcoal',
    )),
);
?>
<section id="<?= esc_attr($id) ?>" class="<?= $className ?>">
    <div class="<?= $className ?>__inner container">
        <div class="<?= $className ?>__content">
            <InnerBlocks allowedBlocks="<?= esc_attr(wp_json_encode($allowed_blocks)) ?>"
                         template="<?= esc_attr(wp_json_encode($template)) ?>"/>

            <?php foreach(GHInt\Helpers\generateTermGroups($taxonomies) as $tax => $terms) : ?>
                <div class="<?= $className ?>__filter">
                    <label for="<?= esc_attr($id) ?>-filter"><?= $filterLabels[$tax]['field']; ?></label>
                    <div class="field field--select field--icon-right">
                        <select id="<?= esc_attr($id) ?>-filter" name="<?= $tax ?>">
                            <option value=""><?= $filterLabels[$tax]['all']; ?></option>
                            <?php foreach ($terms as $term) : ?>
                                <option value="<?= $term->term_id ?>"><?= $term->name ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if ($ctaLink['url'] ?? false) : ?>
                <a class="<?= $className ?>__secondary-cta"
                   href="<?= $ctaLink['url'] ?>"
                   target="<?= $ctaLink['open_in_new_tab'] ? '_blank' : '_self' ?>">
                    <?= $ctaLink['title'] ?: $ctaLink['url'] ?>
                </a>
            <?php endif ?>
        </div>
        <ul class="<?= $className ?>__results">
            <li class="<?= $className ?>__results-empty">
                <?= _x('No results found', 'Career Opportunities empty results', 'ghint') ?>
            </li>
            <?php foreach ($jobs as $job) : ?>
                <?php
                    $offices = wp_get_post_terms($job->ID, 'offices');
                    $termIDs = iterator_to_array(GHInt\Helpers\generatePostTermGroups(
                      $taxonomies, $job->ID, 'term_id'
                    ));
                ?>
                <li class="job-posting" data-filter-terms="<?= esc_attr(wp_json_encode($termIDs)) ?>">
                    <a href="<?= get_field('external_url', $job->ID) ?>" target="_blank">
                        <h3 class="job-posting__title has-purple-color has-text-color"><?= get_the_title($job) ?></h3>
                        <p class="job-posting__meta"><?= sprintf(
                                '%s <span>|</span> %s',
                                implode(', ', wp_list_pluck($offices, 'name')),
                                get_the_date('M j, Y', $job)
                            ); ?></p>
                        <div class="job-posting__cta">
                            <span class="fa-stack fa-2x">
                                <i class="fa-solid fa-circle fa-stack-2x"></i>
                                <i class="fa-regular fa-chevrons-right fa-stack-1x fa-inverse"></i>
                            </span>
                        </div>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</section>