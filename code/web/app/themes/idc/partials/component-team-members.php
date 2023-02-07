<?
$className = 'c-team-member';
$permalink = get_permalink($args['id']);
$title = get_the_title($args['id']);
$feat_img =  get_the_post_thumbnail($args['id']);
$excerpt = get_the_excerpt($args['id']);
$terms = get_the_terms($args['id'], 'job-categories');
$position = get_field('title', $args['id']);
$first_name = strtok($title, " ");
?>

<div class="<?= $className ?> <?= $className ?>--wide text--center">
    <a class="<?= $className ?>__permalink" href="<?= $permalink ?>" title="<?= $title ?>">
        <div class="<?= $className ?>__feat-img">
            <div class="<?= $className ?>__feat-img-wrapper">
                <?= $feat_img ?>
            </div>
        </div>
    </a>
    <div class="<?= $className ?>__title">
        <h3 class="h3 text--charcoal"><?= $title ?></h3>
        <h4 class="h6 text--purple"><?= $position ?></h4>
        <p class="text--small"><?= $excerpt ?></p>
    </div>
    <div class="wp-block-button is-style-idc-button-text-right">
        <a class="wp-block-button__link" href="<?= $permalink ?>">Meet <?= $first_name ?></a>
    </div>
</div>