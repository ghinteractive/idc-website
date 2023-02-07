<?php

$title = get_field('title', 'option') ?: 'Get Started';
$content = get_field('content', 'option');
$form = get_field('form', 'option');

$className = 'c-sidebar-form';
?>

<div class="<?= $className ?>__inner bg-color--white container--padding-y container--padding-x">
    <div class="<?= $className ?>__controls">
        <h2 class="text--purple"><?= $title ?></h2>
        <span class="<?= $className ?>__controls--close text--teal h2"><i class="fas fa-xmark"></i></span>
    </div>

    <p class="text--md"><?= $content ?></p>
    <div class="<?= $className ?>__form">
        <?php
        $args = array('style' => 'rows', 'checkboxWidth' => 'wide', 'alignBtn' => 'full');
        get_template_part('templates/forms/form', 'contact', $args); ?>
    </div>
</div>