<?php //variables
$className = 'c-search-results';
?>
<div class="container container--padding-x container--padding-y-96">
  <div class="<?= $className ?>__inner">

    <div class="container--padding-y <?= $className ?>__content <?= $className ?>__content--small">
      <h2 class="h5 text--teal"><?php _e('404', 'ghint'); ?></h2>
      <h1 class="text--purple"><?php _e('You look lost...', 'ghint'); ?></h1>
      <hr class="wp-block-separator is-style-idc-dots-small">
      <p class="text--big text--charcoal"><?php _e('It appears you have found your way here in error. That’s okay. Let’s get you where you need to be.', 'ghint'); ?></p>
      <div class="wp-block-button is-style-idc-button">
        <a href="/" title="Back to the Home Page" class="wp-block-button__link has-teal-background-color has-background">Back to the Home Page</a>
      </div>

    </div>

    <?php if (is_active_sidebar('search-results')) : ?>
      <aside class="<?= $className ?>__sidebar <?= $className ?>__sidebar--small">
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