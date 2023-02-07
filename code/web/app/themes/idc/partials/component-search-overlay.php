<?php
$className = 'c-sidebar-search';
$args = array('className' => 'c-sidebar-search__form', 'size' => 'lg', 'message' => __('Can we help you find something?', 'ghint'));
?>

<div class="<?= $className ?>__inner container--padding-y container--padding-x">
    <button class="<?= $className ?>__button--close text--purple" type="button">
        <i class="far fa-xl fa-times"></i>
    </button>

    <?php get_template_part('partials/component', 'search-nav', $args) ?>
</div>