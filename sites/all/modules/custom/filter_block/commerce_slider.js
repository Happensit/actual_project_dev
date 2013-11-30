(function($) {
  Drupal.behaviors.commerce_slider = {
    attach: function(context, settings) {        
      $('.slider-widget-wrapper', context).each(function() {
        var $sliderWrapper = $(this);
        var $minInput = $sliderWrapper.find('input:first');
        var $maxInput = $sliderWrapper.find('input:last');
 
        // Setup default values
        if ($minInput.val() == '') {
          $minInput.val($sliderWrapper.data('min'));
        }
        if ($maxInput.val() == '') {
          $maxInput.val($sliderWrapper.data('max'));
        }
 
        // Create slider
        var $slider = $('<div class="slider-widget" />').appendTo($sliderWrapper).slider({
          range: true,
          min: $sliderWrapper.data('min'),
          max: $sliderWrapper.data('max'),
          step: 100,
          values: [$minInput.val(), $maxInput.val()],
          slide: function(event, ui) {
            $minInput.val(ui.values[0]).change();
            $maxInput.val(ui.values[1]).change();
          }
        });
 
        // Min and max fields behaviors
        $minInput.keyup(function() {
          $slider.slider('values', 0, this.value);
        });
        $maxInput.keyup(function() {
          $slider.slider('values', 1, this.value);
        });
      });
    }
  };
})(jQuery);
