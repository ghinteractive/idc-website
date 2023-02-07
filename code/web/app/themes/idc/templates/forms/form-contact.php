<?php
/*check background color of form background if exists */
$bgColor = (get_field('form_background_color') === 'teal-light') ? 'dark' : 'light';
$inputWidth = $args['checkboxWidth'];
$ctx = uniqid();
?>

<div id="contact-form-<?= $ctx ?>" class="c-contact-form">
    <form name="contact-form" class="form form--vertical form--<?= $bgColor ?>" action="<?= rest_url('/ghint/v1/form/get-started') ?>" method="POST">
        <div class="form__field form__field--wrap">
            <div class="form__field--small">
                <label for="first-name-<?= $ctx ?>" class="hidden"><?= __('First Name', 'ghint') ?></label>
                <input class="form__input" id="first-name-<?= $ctx ?>" name="firstname" placeholder="First Name" />
            </div>
            <div class="form__field--small">
                <label for="last-name-<?= $ctx ?>" class="hidden"><?= __('Last Name', 'ghint') ?></label>
                <input class="form__input" id="last-name-<?= $ctx ?>" name="lastname" placeholder="Last Name" />
            </div>
        </div>
        <div class="form__field form__field--wrap form__field--<?= $args['style'] ?>">
            <div class="form__field--small">
                <label for="company-<?= $ctx ?>" class="hidden"><?= __('Company', 'ghint') ?></label>
                <input class="form__input" id="company-<?= $ctx ?>" name="company" placeholder="Company" />
            </div>
            <div class="form__field--small">
                <label for="title-<?= $ctx ?>" class="hidden"><?= __('Job Title', 'ghint') ?></label>
                <input class="form__input" id="title-<?= $ctx ?>" name="title" placeholder="Job Title" />
            </div>
        </div>
        <div class="form__field form__field--wrap form__field--<?= $args['style'] ?>">
            <div class="form__field--small">
                <label for="email-<?= $ctx ?>" class="hidden"><?= __('Email', 'ghint') ?></label>
                <input class="form__input" id="email-<?= $ctx ?>" name="email" placeholder="Email Address" aria-required="true" type="email" />
            </div>
            <div class="form__field--small">
                <label for="phone-<?= $ctx ?>" class="hidden"><?= __('Phone', 'ghint') ?></label>
                <input class="form__input" id="phone-<?= $ctx ?>" name="phone" value="" placeholder="Phone" type="tel" />
            </div>
        </div>
        <div class="form__field form__field--wrap">
            <div class="form__field--<?= $inputWidth ?>">
                <input id="ilum-inform-<?= $ctx ?>" name="subject[]" value="ILÚM Inform" type="checkbox" />
                <label for="ilum-inform-<?= $ctx ?>"><?= __('ILÚM Inform', 'ghint') ?></label>
                <p class="text--micro"><?= __('A customizable, infectious diseases resource mobile app', 'ghint') ?></p>
            </div>
            <div class="form__field--<?= $inputWidth ?>">
                <input id="ilum-insight-<?= $ctx ?>" name="subject[]" value="ILÚM Insight" type="checkbox" />
                <label for="ilum-insight-<?= $ctx ?>"><?= __('ILÚM Insight®', 'ghint') ?></label>
                <p class="text--micro"><?= __('Antimicrobial decision support software designed by pharmacists for pharmacists', 'ghint') ?></p>
            </div>
            <div class="form__field--<?= $inputWidth ?>">
                <input id="tele-medicine-<?= $ctx ?>" name="subject[]" value="Telemedicine" type="checkbox" />
                <label for="tele-medicine-<?= $ctx ?>"><?= __('Telemedicine', 'ghint') ?></label>
                <p class="text--micro"><?= __('Dedicated ID physician specialist e-consults from academic prationers', 'ghint') ?></p>
            </div>
            <div class="form__field--<?= $inputWidth ?>">
                <input id="virtual_stewardship-<?= $ctx ?>" name="subject[]" value="Virtual Stewardship" type="checkbox" />
                <label for="virtual_stewardship-<?= $ctx ?>"><?= __('Virtual Stewardship', 'ghint') ?></label>
                <p class="text--micro"><?= __('Virtual ID-trained pharmacist and physician services', 'ghint') ?></p>
            </div>
            <div class="form__field--<?= $inputWidth ?>">
                <input name="subject[]" value="Tele-IPC" id="tele-ipc-<?= $ctx ?>" type="checkbox" />
                <label for="tele-ipc-<?= $ctx ?>"><?= __('Tele-IPC', 'ghint') ?></label>
                <p class="text--micro"><?= __('ID expertise, leadership and consulting for all your hospitals IPC needs', 'ghint') ?></p>
            </div>
        </div>
        <div class="form__field">
            <label for="comments-<?= $ctx ?>" class="hidden"><?= __('Additional comments', 'ghint') ?></label>
            <textarea rows="5" class="form__input" id="comments-<?= $ctx ?>" name="comments" placeholder="Additional comments"></textarea>
        </div>
        <input class="form__field--<?= $args['alignBtn'] ?> wp-block-button__link has-teal-background-color has-background" value="Submit" type="submit" />
        <input type="hidden" class="g-recaptcha-response" name="hiddenRecaptcha">
    </form>
</div>