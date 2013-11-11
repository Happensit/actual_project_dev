/**
 * @file
 * AJAX functionality for Yandex.Captcha module.
 *
 * Provides some client-side functionality refresh captcha images.
*/

(function ($) {
  Drupal.behaviors.yandexCaptchahRefresh = {
    attach: function (context) {

      $('.reload-captcha', context).not('.processed').bind('click', function () {
        $(this).addClass('processed');
        var $form = $(this).parents('form');
        // send post query for getting new captcha data
        var url = this.href;
        $.get(
          url,
          {},
          function (response) {
            if(response.status == 1) {
              $('.captcha', $form).find('img').attr('src', response.data.url[0]);
              $('input[name=captcha_sid]', $form).val(response.data.sid);
              $('input[name=captcha_token]', $form).val(response.data.token);
            }
            else {
              alert(response.message);
            }
          },
          'json'
          );
        return false;
      });
    }

  };
})(jQuery);
