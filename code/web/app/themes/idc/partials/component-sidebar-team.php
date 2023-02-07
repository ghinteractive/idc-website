<?php //variables

$className = 'c-team-member-sidebar';
$feat_img = get_the_post_thumbnail();

?>

<div class="<?= $className ?> <?= $className ?>__inner">

    <?php if ($feat_img) : ?>
        <div class="<?= $className ?>__feat-img">
            <a href="<?= the_permalink() ?>" title="<?= the_title() ?>">
                <?= $feat_img ?>
            </a>
        </div>
    <?php endif; ?>

    <a href="<?= the_permalink() ?>">
        <h6 class="text--charcoal"><?= the_title() ?></h6>
    </a>
</div>