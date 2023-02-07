/*
  Name:               contact-form.js
  Description:        The Contact Form is for the Get Started forms Script
  Version:            1.0.0
  Author:             Garrison Hughes
*/

(($) => {
  $(() => {
    const $allForms = $('.c-contact-form form');

    $allForms.each(function (key, form) {
      $(this).validate({
        errorClass: 'form__label--error fail-alert',
        validClass: 'valid success-alert',
        // Specify validation rules
        rules: {
          firstname: 'required',
          lastname: 'required',
          company: 'required',
          title: 'required',
          email: {
            required: true,
            // Specify that email should be validated
            // by the built-in "email" rule
            email: true,
          },
        },
        // Specify validation error messages
        messages: {
          firstname: 'Please enter your first name',
          lastname: 'Please enter your last name',
          company: 'Please enter your company name',
          title: 'Please enter your title',
          email: 'Please enter a valid email address',
        },
        // Make sure the form is submitted to the destination defined
        // in the "action" attribute of the form when valid
        submitHandler: function (form) {
          $('.c-modal-loading--contact').fadeIn();
          grecaptcha.ready(function () {
            // do request for recaptcha token
            // response is promise with passed token
            grecaptcha.execute(serverVars.recaptchaSiteKey, { action: 'getstarted' }).then(function (token) {
              // add token value to form
              form.hiddenRecaptcha.value = token;
              $.ajax({
                url: form.action,
                method: form.method,
                data: $(form).serialize(),
                success: (data) => {
                  setTimeout(function () {
                    $('.c-modal-loading--contact').fadeOut();
                    form.hiddenRecaptcha.value = '';
                  }, 3000);
                  $(form).trigger('reset');
                },
                error: (a, b, c) => {
                  console.log('Error: ', a, b, c);
                },
              });
            });
          });
        },
      });
    });
  });
})(jQuery);
