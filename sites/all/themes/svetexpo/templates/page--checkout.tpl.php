<div class="overlay"></div>

<div class="popup recall_popup">
	<div class="popup_top"></div>
	<div class="popup_content">
		<div class="popup_close"></div>
		<div class="popup_box">
			<div class="popup_header">
				Заказать обратный звонок
			</div>
			<div class="popup_form">
				<div class="popup_form_section">
					<span>
						Ваше имя:
					</span>
					<input type="text" value="">
				</div>
				<div class="popup_form_section">
					<span>
						Номер телефона:
					</span>
					<input type="text" value="">
				</div>
				<div class="popup_form_button">
					<button type="submit" class="but"><span>Заказать</span></button>
				</div>
			</div>
		</div>
	</div>
	<div class="popup_bottom"></div>
</div>

<div class="container">
	<div class="light">
		<div class="site">
			<header>
				<a href="/" class="logo"></a>
				<div class="header_box">
					<div class="header_box_phone">
						<span>
							8-495-961-73-84
						</span>
					</div>
					<div class="header_box_sep"></div>
					<div class="header_box_worktime">
						Мы работаем каждый день <span>с 10-00 до 20-00</span><br>без обеда и выходных
					</div>
				</div>
				<div class="cart_box">
					<a href = "/cart">
						<img src = "/sites/all/themes/svetexpo/img/cart.png"/><span>Корзина</span>
						<div class="cart_content">
							<?php print render($page['top_cart']); ?>
						</div>			
					</a>
				</div>
				<div class="login_box">
					<form method="post" action="/user">
					<input name="name" type="text" placeholder="Введите логин" value="" >
					<input name="pass" type="password" placeholder="Введите пароль" value="" >
					<input name="form_id" type="hidden" value="user_login" >
					<input type="submit" value="Войти">
					</form>
				</div>
				<a href="#" class="recall">
					<span>
						Заказать<br>обратный звонок
					</span>
				</a>
			</header>
			<div class="page_sep"></div>
<?php if ($page): ?>
  <h1 class="title"><?php print $title ?></h1>
  <?php endif; ?>			
			<?php print render($page['content']); ?>
			
			<script type = "text/javascript">
			jQuery(document).ready(function(){
				jQuery('body .messages.error:first').remove();
				if(jQuery('#edit-commerce-shipping-shipping-service-shipping-pickup').attr('checked') == 'checked')
					jQuery('#edit-customer-profile-shipping-field-address').hide();
				else
					jQuery('#edit-customer-profile-shipping-field-address').show();
				jQuery('#edit-commerce-shipping-shipping-service').click(function(e){
						if(jQuery(e.target).attr('id') == 'edit-commerce-shipping-shipping-service-shipping-pickup')
							jQuery('#edit-customer-profile-shipping-field-address').hide();
						if(jQuery(e.target).attr('id') == 'edit-commerce-shipping-shipping-service-shipping-moscow')
							jQuery('#edit-customer-profile-shipping-field-address').show();
						if(jQuery(e.target).attr('id') == 'edit-commerce-shipping-shipping-service-shipping-moscow-free')
							jQuery('#edit-customer-profile-shipping-field-address').show();
						if(jQuery(e.target).attr('for') == 'edit-commerce-shipping-shipping-service-shipping-pickup')
							jQuery('#edit-customer-profile-shipping-field-address').hide();
						if(jQuery(e.target).attr('for') == 'edit-commerce-shipping-shipping-service-shipping-moscow')
							jQuery('#edit-customer-profile-shipping-field-address').show();
						if(jQuery(e.target).attr('for') == 'edit-commerce-shipping-shipping-service-shipping-moscow-free')
							jQuery('#edit-customer-profile-shipping-field-address').show();
					}
					//'checked', true
				);
       });
			</script>

<? /*
			<div class="form_header">
				Мы предлагаем два варианта оформления заказа on-line.
			</div>
			<div class="form_tabs">
				<label>
					<input type="radio" checked>Быстрое оформление
				</label>
				<label>
					<input type="radio">Полное оформление
				</label>
			</div>
			<table class="form">
				<tr>
					<td>
						Имя:
					</td>
					<td>
						<div class="textfield">
							<div>
								<input type="text">
							</div>
						</div>
					</td>
					<td>
						&nbsp;
					</td>
				</tr>
				<tr>
					<td>
						Телефон:
					</td>
					<td>
						<div class="textfield alert">
							<div>
								<input type="text">
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<td colspan="2" class="form_button">
						<button type="submit" class="button_flex">
							<span>
								<span>
									Оформить заказ
								</span>
							</span>
						</button>
					</td>
				</tr>
			</table>
 */ ?>
			</div>
	</div>
</div>
<footer>
	<div class="copyright">
		<span>SvetExpo</span> - магазин люстр и светильников
		<br>
		Все права защищены, 2012-2013
	</div>
	<div class="footer_box">
		<div class="footer_menu">
			<?php print render($page['header_menu']); ?>			
		</div>
		<div class="footer_contacts">
			<div class="footer_phone">
				<span>Телефон:</span> 8 (495) 961-73-84, 8 (495) 779-12-69
			</div>
			<span>Адрес:</span> Нахимовский проспект, дом 24, павильон № 3, этаж 1, сектор B, место 338
		</div>
	</div>
</footer>
