(function ($) {

  // Increase/decrease quantity
  Drupal.commerce_extra_quantity_quantity = function(selector, way, amount) {

    // Find out current quantity and figure out new one
    var quantity = parseFloat($(selector).val());
    if (way == 1) {
      // Increase
      var new_quantity = quantity+amount;
    }
    else if (way == -1) {
      // Decrease
      var new_quantity = quantity-amount;
    }
    else {
      var new_quantity = quantity;
    }

    // Set new quantity
    if (new_quantity >= 1) {
      $(selector).val(new_quantity).change();
    }

    // Set disabled class depending on new quantity
    if (new_quantity <= 1) {
      $(selector).prev('span').addClass('commerce-quantity-plusminus-link-disabled');
    }
    else {
      $(selector).prev('span').removeClass('commerce-quantity-plusminus-link-disabled');
    }

  }

}(jQuery));
