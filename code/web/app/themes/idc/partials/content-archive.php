<?php //variables
$className = 'c-search-results';
?>
<hr class="wp-block-separator has-css-opacity is-style-idc-dots-small">
<article class="container container--padding-y">
    <header>
        <h2 class="<?= $className ?>__entry-title h3 text--charcoal"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
        <p class="<?= $className ?>__link-text text--small"><?php the_permalink(); ?></p>
    </header>
    <div class="<?= $className ?>__entry-summary text--big text--gray">
        <?php the_excerpt(); ?>
    </div>
    <div class="wp-block-button is-style-idc-button-text-right">
        <a href="<?php the_permalink(); ?>" class="wp-block-button__link has-teal-color has-text-color">Visit Page</a>
    </div>
</article>