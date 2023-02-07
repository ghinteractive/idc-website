/*
  Name:               gated-media.js
  Description:        Gated Media Script
  Version:            1.0.0
  Author:             Garrison Hughes
*/

function ValidationError(type, target) {
  this.type = type;
  this.target = target;
}

ValidationError.prototype.getMessage = function () {
  return serverVars.validationMessages[this.type];
};

function fieldIsSet(value) {
  return !!value;
}

function fieldIsEmail(value) {
  return /\S+@\S+\.\S+/.test(value);
}

function validateForm($form) {
  return new Promise((resolve, reject) => {
    const rejected = [];

    $form.find('.form__input').each((i, el) => {
      const $field = jQuery(el);
      if ($field.is('[aria-required=true]') && !fieldIsSet($field.val())) {
        rejected.push(new ValidationError('required', el));
        return;
      }
      if ($field.is('[type=email]') && !fieldIsEmail($field.val())) {
        rejected.push(new ValidationError('email', el));
      }
    });

    if (rejected.length) {
      reject(rejected);
    }

    resolve(true);
  });
}

(($, [cookieName, cookieOpts], containerId) => {
  $(() => {
    const $popup = $(containerId);
    const $form = $popup.find('form');
    const formSettings = {
      items: {
        src: $popup,
        type: 'inline',
      },
    };

    if (serverVars.redirectToMedia) {
      $.magnificPopup.open(formSettings);
    }

    $form.on('submit', (e) => {
      e.preventDefault();

      const $self = $(e.currentTarget);
      const email = $self.find('input[name="email"]').val();

      validateForm($form)
        .then((isValid) => {
          if (isValid === true) {
            $form.find('.form__input').removeClass('form__input--error');

            grecaptcha.ready(() => {
              grecaptcha.execute(serverVars.recaptchaSiteKey, {action: 'newuser'}).then((token) => {
                $self[0].hiddenRecaptcha.value = token;

                $.ajax({
                  url: $self.attr('action'),
                  method: $self.attr('method'),
                  data: $self.serialize(),
                  success: (data) => {
                    const auth = btoa(`${email}:${data.secret}`);
                    $.cookie(cookieName, auth, cookieOpts);
                    $.magnificPopup.close();
                    $popup.remove();

                    if (serverVars.redirectToMedia) {
                      window.location.href = serverVars.redirectToMedia;
                    } else {
                      window.location.reload();
                    }
                  },
                  error: (a, b, c) => {
                    console.log('Error: ', a, b, c);
                  },
                });
              });
            });
          }
        })
        .catch((messages) => {
          const targets = messages.map((msg) => msg.target);
          $form
            .find('.form__input')
            .not(targets)
            .removeClass('form__input--error');
          $(targets).addClass('form__input--error');
        });
    });
  });
})(
  jQuery,
  [
    '_idc_gate_credentials',
    { expires: parseInt(serverVars.cookieExpiry, 10), path: '/' },
  ],
  '#gated-media-form'
);
