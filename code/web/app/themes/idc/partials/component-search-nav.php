<form class="<?= $args['className'] ?> form form--search" id="searchform-overlay" method="get" action="<?php echo home_url('/'); ?>">
    <label class="form__label form__label--hidden" for="search_form_overlay">Search</label>
    <div class="input input--icon-search">
        <input type="text" class="form__input form__input--<?= $args['size'] ?>" id="search_form_overlay" name="s" placeholder="<?= $args['message'] ?>" value="<?php the_search_query(); ?>">
    </div>
    <button aria-label="Search" class="form__icon-search text--purple"><i class="far fa-search"></i></button>
</form>