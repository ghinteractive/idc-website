<?php
$postClass = 'c-blog';
$permalink = get_permalink($args['id']);
$title = get_the_title($args['id']);
$feat_img =  get_the_post_thumbnail($args['id']);
$excerpt = get_the_excerpt($args['id']);
$terms = get_the_terms($args['id'], 'category');
$cat = array();
foreach ($terms as $term) :
    $cat[] = $term->name;
endforeach;

$cat = join(',', $cat);
?>
<div class="<?= $postClass ?> <?= $postClass ?>--post <?= $postClass ?>--show">
    <a href="<?= $permalink ?>" class="<?= $postClass ?>__permalink" title="<?= $title ?>">
        <div class="<?= $postClass ?>__feat-img">
            <?= $feat_img ?>
        </div>
    </a>
    <p class="<?= $postClass ?>__title text--charcoal text--big container--padding-xl">
        <?= $title ?>
    </p>
    <div class="<?= $postClass ?>__tag-button container--padding-xl">
        <div class="<?= $postClass ?>__tag-link"><i class="fa-light fa-tags text--purple"></i>
            <p class="text--teal text--uppercase"><?= $cat ?></p>
        </div>
        <div class="is-style-idc-button-right">
            <a href="<?= $permalink ?>" title="<?= $title ?>" class="wp-block-button__link"></a>
        </div>
    </div>
</div>