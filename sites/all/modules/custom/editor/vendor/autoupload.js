/*
 * Behavior for the automatic file upload
 */

(function ($) {
  Drupal.behaviors.Editor = {
    attach: function(context, settings) {
      $('.form-item input.form-submit[value=Закачать]', context).hide();
      $('.form-item input.form-file', context).change(function() {
        $parent = $(this).closest('.form-item');

        setTimeout(function() {
          if(!$('.error', $parent).length) {
            $('input.form-submit[value=Закачать]', $parent).mousedown();
          }
        }, 100);

//          $(document).on('change', $parent, function(){
//              setTimeout(function() {
//                  $aimg = $(this).find('a').attr('href');
//                  console.log($aimg);
//                  $.markItUp({replaceWith: '<img src="'+$aimg+'" />'});
//              }, 1500);
//          });

//          $(document).on('change','.image-widget', function(){
//
//              $aimg = $(this).find('.image-widget-data .file a').attr('href');
//              console.log($aimg);
//            //$.markItUp({replaceWith: '<img src="'+$aimg+'" />'});
//          });
      });
    }
  };

})(jQuery);
