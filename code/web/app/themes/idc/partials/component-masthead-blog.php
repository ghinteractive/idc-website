<?php

// SET DEFAULT CLASS & GET ADDITIONAL CLASS NAMES & ALIGN CLASSES FROM GUTENBERG
$className = 'c-masthead-block';

?>
<section id="<?= $args['id'] ?>" class="<?= $className ?> <?= $className ?>--bg">
    <div class="<?= $className ?>__inner container container--padding-x container--padding-yxxl">

        <?php if ($args['feat_img']) : ?>
            <div class="<?= $className ?>__col <?= $className ?>__col--wide">
                <?php
                // if id is NOT c-masthead-blog-block-post then render
                if ($args['id'] !== 'c-masthead-blog-block-post') : ?>
                    <a class="<?= $className ?>__img--link" href="<?= $args['permalink'] ?>" title="<?= $args['title'] ?>">
                    <?php endif; ?>
                    <div class="<?= $className ?>__square-container">
                        <div class="<?= $className ?>__img-square">
                            <?= $args['feat_img'] ?>
                        </div>
                    </div>
                    <?php
                    // if id is NOT c-masthead-blog-block-post then render
                    if ($args['id'] !== 'c-masthead-blog-block-post') : ?>
                    </a>
                <?php endif; ?>
                <div class="<?= $className ?>__border <?= $className ?>__border--square"></div>
            </div>
        <?php endif; ?>
        <div class="<?= $className ?>__content <?= $className ?>__content--wide">
            <?php if ($args['category']) : ?>
                <h2 class="h5 text--teal"><?= $args['category'] ?></h2>
            <?php endif; ?>
            <h1 class="text--purple"><?= $args['title'] ?></h1>

            <?php
            // if id is NOT c-masthead-blog-block-post then render
            if ($args['id'] !== 'c-masthead-blog-block-post') : ?>
                <hr class="wp-block-separator is-style-idc-dots-small">
                <p class="text--big text--charcoal">
                    <?= $args['excerpt'] ?>
                </p>
                <div class="wp-block-button is-style-idc-button">
                    <a class="wp-block-button__link has-teal-background-color has-background" href="<?= $args['permalink'] ?>" title="<?= $args['title'] ?>">Read More</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php if ($args['share'] === true) : ?>
        <?php get_template_part('partials/component', 'social-share'); ?>
    <?php endif; ?>
</section>