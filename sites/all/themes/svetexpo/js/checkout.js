jQuery(document).ready(function(){
	var old_address = '';
	//current_shipping = $("input [name='commerce_shipping[shipping_service]']").val();//:checked
	
	
	jQuery('input#edit-continue').replaceWith('<button type="submit" class="button_flex" name="op" value="Продолжить оформление"><span><span>Оформить заказ</span></span></button>');
	
	function check_visible_address() {
		current_shipping = jQuery("input[name='commerce_shipping[shipping_service]']:checked").val();//:checked
		hidden_vields = 'div.form-item-customer-profile-shipping-commerce-customer-address-und-0-thoroughfare,'
		if (current_shipping == 'shipping_pickup') {
			jQuery('div.form-item-customer-profile-shipping-commerce-customer-address-und-0-thoroughfare').hide();
			old_address = jQuery('input#edit-customer-profile-shipping-commerce-customer-address-und-0-thoroughfare').val();
			jQuery('input#edit-customer-profile-shipping-commerce-customer-address-und-0-thoroughfare').val('-');
		} else {
			jQuery('div.form-item-customer-profile-shipping-commerce-customer-address-und-0-thoroughfare').show();
			jQuery('input#edit-customer-profile-shipping-commerce-customer-address-und-0-thoroughfare').val(old_address);
		}	
	}
	
	jQuery("input[name='commerce_shipping[shipping_service]']").change(check_visible_address);
	jQuery("input[name='commerce_shipping[shipping_service]']").change();
	
});

//html.js > body.mainpage > div.container > div.light > div.site > div.content > form#commerce-checkout-form-checkout > div > div#commerce-shipping-service-ajax-wrapper > fieldset#edit-commerce-shipping.commerce_shipping.form-wrapper > div.fieldset-wrapper > div#edit-commerce-shipping-shipping-service.form-radios > div.form-item.form-type-radio.form-item-commerce-shipping-shipping-service

