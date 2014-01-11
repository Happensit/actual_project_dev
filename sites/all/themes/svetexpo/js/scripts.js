(function ($) {
$(document).ready(function() {

    var formsearch = document.getElementById('edit-search-api-views-fulltext');
    $(formsearch).attr('placeholder', 'Поиск по сайту...');
	
	$('.footer_menu ul li a').hover( function() {
		if ($.browser.msie && ($.browser.version == 7 || $.browser.version == 8)) {
			$(this).parent().addClass('ie_hover');
		} else {
			$(this).parent().addClass('hover');
		}
	}, function() {
		if ($.browser.msie && ($.browser.version == 7 || $.browser.version == 8)) {
			$(this).parent().removeClass('ie_hover');
		} else {
			$(this).parent().removeClass('hover');
		}
	});
	
	$('.list li a').hover( function() {
		$(this).parent().addClass('list_hover');
	}, function() {
		$(this).parent().removeClass('list_hover');
	});
	
	$('.recall').click(function(e) {
		e.stopImmediatePropagation();
		$('.recall_popup').css('margin-top', (Math.floor($('.recall_popup').height()/2))*(-1));
		$('.recall_popup').fadeIn(300);
		$('.overlay').fadeIn(300);
		return false;
	});
	
	$('.one_click_button').click(function(e) {
		e.stopImmediatePropagation();
		$('.one_click_popup').css('left', $(this).parent().offset().left);
		$('.one_click_popup').css('top', $(this).parent().offset().top + $(this).parent().height() + 6);
		$('.one_click_popup').fadeIn(300);
		$('.overlay').fadeIn(300);
		return false;
	});
	
	$('.popup_close').click(function(e) {
		e.stopImmediatePropagation();
		$('.popup').fadeOut(300);
		$('.overlay').fadeOut(300);
	});
	
	$('.popup').click(function(e) {
		e.stopImmediatePropagation();
	});
	
	$('body').click(function() {
		$('.popup').fadeOut(300);
		$('.overlay').fadeOut(300);
	});
	
	$('.count > a').click(function() {
		if($(this).hasClass('count_minus')) {
			if(parseInt($(this).parent().find('input').val()) > 0) {
				$(this).parent().find('input').val(parseInt($(this).parent().find('input').val()) - 1);
			}
		}
		if($(this).hasClass('count_plus')) {
			if(parseInt($(this).parent().find('input').val()) >= 0) {
				$(this).parent().find('input').val(parseInt($(this).parent().find('input').val()) + 1);
			}
		}
		return false;
	});
	
		$('input#edit-submit-lamp-search,input#edit-submit-lamp-search2').replaceWith('<button type="submit" class="button_flex" name="" value="Искать"><span><span>Искать</span></span></button>');

		//	$('#feedback-form').submit(function(){return checkfb();});

});

var ttt
	function checkfb(){
		//data1 = $('.popup_form').find('form').serialize();
		ttt = $('#feedback-form');
		data1 = $('#feedback-form').serialize();
		$.post('/feedback_send.php',data1, function(data) {
			//$('.result').html(data);
			alert (data);
		});
		return false;
	}



}(jQuery));