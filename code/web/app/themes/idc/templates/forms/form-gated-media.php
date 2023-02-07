<?php //variables
$headline = get_field('gated_headline', 'option');
$content = get_field('gated_content', 'option');
?>
<div id="gated-media-form" class="c-form-popup mfp-hide">
    <h3 class="c-form-popup__heading">
        <?= $headline ?>
    </h3>
    <p class="c-form-popup__description">
        <?= $content ?>
    </p>
    <form class="form form--vertical form--dark" action="<?= rest_url('/ghint/v1/gate/user') ?>" method="POST" novalidate>
        <div class="form__field form__field--columns">
            <input class="form__input" name="first-name" value="" placeholder="First Name" aria-required="true" />
            <input class="form__input" name="last-name" value="" placeholder="Last Name" aria-required="true" />
        </div>
        <div class="form__field">
            <input class="form__input" name="email" value="" placeholder="Email Address" aria-required="true" type="email" />
        </div>
        <div class="form__field">
            <input class="form__input" name="company" value="" placeholder="Company" />
        </div>
        <div class="form__field">
            <input class="form__input" name="phone" value="" placeholder="Phone" type="tel" />
        </div>
        <input type="hidden" class="g-recaptcha-response" name="hiddenRecaptcha">
        <input class="wp-block-button__link has-teal-background-color has-background" type="submit" />
    </form>
</div>