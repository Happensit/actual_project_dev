/*
 * Behavior for the automatic file upload
 */

(function ($) {
  Drupal.behaviors.autoUpload = {
    attach: function(context, settings) {
      $('.form-item input.form-submit[value=Закачать]', context).hide();
      $('.form-item input.form-file', context).change(function() {
        $parent = $(this).closest('.form-item');

        setTimeout(function() {
          if(!$('.error', $parent).length) {
            $('input.form-submit[value=Закачать]', $parent).mousedown();
          }
        }, 100);
        setTimeout(function() {
            $aimg = $('span.file').find('a').attr('href');
            $.markItUp({replaceWith: '<img src="'+$aimg+'" />'});

          }, 1300);



      });
    }
  };

})(jQuery);
