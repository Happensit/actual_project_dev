<?php 

drupal_add_css('/highslide/highslide-min.css', array('every_page' => FALSE));
drupal_add_js('highslide/highslide-full.min.js');
drupal_add_css(path_to_theme() . '/style/skin.css');
drupal_add_js(path_to_theme() . '/js/jquery.jcarousel.min.js');
drupal_add_library('system', 'ui.tabs');
drupal_add_js('jQuery(document).ready(function(){jQuery("#tabs").tabs();});', 'inline');


$js = <<<JS

jQuery(document).ready(function() {
    jQuery('#second_image_carousel').jcarousel({
		// visible: 5, //количество видимых картинок
		// scroll: 1  //количество элементов, которые одновременно прокручиваются при щелчке по кнопке
	});
});

hs.showCredits = false;
hs.align = 'center';
hs.dimmingOpacity = 0.75;
hs.lang={cssDirection:'ltr',loadingText:'Загрузка...',loadingTitle:'Кликните чтобы отменить',focusTitle:'Кликните чтобы перенести вперёд',fullExpandTitle:'Развернуть до размеров оригинала',fullExpandText:'Полноэкранный',previousText:'Предыдущий',previousTitle:'Назад (стрелка влево)',nextText:'Далее',nextTitle:'Далее (стрелка вправо)',moveTitle:'Передвинуть',moveText:'Передвинуть',closeText:'Закрыть',closeTitle:'Закрыть (Esc)',resizeTitle:'Восстановить размер',playText:'Слайд-шоу',playTitle:'Слайд-шоу (пробел)',pauseText:'Пауза',pauseTitle:'Приостановить слайд-шоу (пробел)',number:'Изображение %1/%2',restoreTitle:'Кликните чтобы посмотреть картинку, используйте мышь для перетаскивания. Используйте клавиши вперёд и назад'};

hs.registerOverlay({
	html: '<div class="closebutton" onclick="return hs.close(this)" title="Close"><\/div>',
	position: 'top right',
	fade: 2 // fading the semi-transparent overlay looks bad in IE
});
hs.graphicsDir = '/highslide/graphics/';
hs.wrapperClassName = 'borderless';

JS;
drupal_add_js($js,'inline');

$show_icons = false;
if (isset ($node->field_brand) && $field = $node->field_brand)
if ($field['und'][0]['taxonomy_term']) {		
	$producer_name = $field['und'][0]['taxonomy_term']->name;//['name']
	//$show_icons = svetexpo_show_icons($producer_name);	
}	

hide($content['field_ostatki']);
hide($content['field_artikul_fabriki']);
hide($content['field_strana_proishozhdenija']);
hide($content['field_obshhee_opisanie']);
hide($content['field_serie']);
hide($content['field_product']);
$ost = isset($node->field_ostatki['und'][0]['value'])?$node->field_ostatki['und'][0]['value']:0;
$content['field_product'][0]['label_hidden'] = 1;	
$content['field_product'][0]['submit']['#value'] = 'В корзину'; 
if(isset($node->field_status['und'][0]['value'])){
	$status = end($node->field_status['und']);
	$text_status = $status['value'];
	$status_preorder = false;
}else{
	if ($ost != 0) {
		$text_status = 'В наличии';
		$status_preorder = false; 
	} else {
		$text_status = 'Нет в наличии';
		$status_preorder = true;
	}
}

$ff_price_amount = $content['product:commerce_price']['#object']->commerce_price['und'][0]['amount'];
if($ff_price_amount == 0){
	$text_status = 'Нет в наличии';
	$status_preorder = true;
}	
$product = $node->field_product;	

?>	
				
<div class="item">
	<div class="item_image">
		<a href="<?php print $big_image; ?>" onclick="return hs.expand(this);">
			<img src="<?php print $big_image; ?>" alt="<?php print $producer_name; ?>">
		</a>	
		<div class="left big_pic">
			<?php if ($show_icons): ?>
				<img class="prim" src="<?php echo path_to_theme();?>/img/at_home.png" alt="" title="Можно примерить у себя дома">
				<img src="<?php echo  path_to_theme();?>/img/in.png" alt="" title="Можно посмотреть в нашем магазине">
			<?php endif; ?>
		</div>
		<div class="status <?php $status_preorder ? print 'no' :  print 'yes'; ?>">
			<?php 
				if($text_status == "Под заказ")
					print '<span style = "color:red;">'.$text_status.'</span>'; 
				else
					if($text_status == "В пути")
						print '<span style = "color:#0275c6;">'.$text_status.'</span>'; 
					else
						if($text_status == "Уточняйте наличие")
							print '<span style = "color:#FF4D00;">'.$text_status.'</span>'; 
						else
							print $text_status; 
			?>
		</div>
	</div>
	<div class="item_content">
		
		<h1><?php print $title; ?></h1>

		<?php if(!$status_preorder): ?>
			<div class="item_price">
				Цена: <span><?php print $content['product:commerce_price'][0]['#markup'] ?></span>
			</div>
		
			<div class="item_buttons">
				<?php print render($content['field_product']); ?>
				<div class="clear"></div>
			</div>
		<?php endif; ?>

		<?php if ($show_icons) echo "<p> Можно посмотреть в нашем магазине или примерить дома. Если не подойдет, то можно легко вернуть</p>";  ?>	

		<p>Появились вопросы? Мы с радостью ответим на них по телефону 8-495-961-73-84 с 10-00 до 20-00. Мы работаем без выходных.</p>	

		<div class="second_img">
		<?php if(!empty($second_image)) {?>

                    <ul id="second_image_carousel" class="jcarousel-skin-tango">	 	 

               <?php foreach ($second_image as $gallery) : ?>
				
                        <li><a href="<?php echo $gallery;?>" onclick="return hs.expand(this);"><img src="<?php echo $gallery; ?>"></img></a></li>
               

			<?php  endforeach; } ?>
			
			</ul>

		</div>

	</div>
	<div class="clear"></div>

</div>

<div class="item_info">
	<div class="block_header">Технические характеристики</div>
	<div class="props" style="float:left;margin-right:25px;">
		<ul>
			<?php
				echo render(field_view_field('node', $node, 'field_artikul_fabriki', array('default')));
				echo render(field_view_field('node', $node, 'field_serie', array('default')));
				echo render(field_view_field('node', $node, 'field_brand', array('default')));
				echo render(field_view_field('node', $node, 'field_strana_proishozhdenija', array('default')));
			?>							
		</ul>
	</div>
	<div class="props" >
		<ul>
			<?php					
				hide($content['comments']);
				hide($content['links']);
				hide($content['product:commerce_price']);
				unset($content['field_brand']);
				print render($content);
			?>
		</ul>
	</div>
</div>	

<div style = "position: relative;"><!-- Тут были табы --></div>	

<div id="tabs">
	<ul>
		<li><a href="#tabs-1">Товары из этой же серии</a></li>
		<li><a href="#tabs-2">Товары из этой же категории</a></li>
	</ul>
 
	<div id="tabs-1">
		<?php // Товары из этой же серии
		$block = module_invoke('views', 'block_view', 'lamp_related-block_2');
		print render ($block['content']);
		?>
	</div>
	<div id="tabs-2">
		<?php  // Товары из этой же категории
		$block = module_invoke('views', 'block_view', 'lamp_related-block');
		print render($block['content']);
	   ?>
	</div>
</div>