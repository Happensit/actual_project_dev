<?php
$show_icons = false;

if(isset ($node->field_brand['und'][0]) && $field = $node->field_brand['und'][0]){
	if ($field['taxonomy_term']) {		
		$producer_name = $field['taxonomy_term']->name;
		$show_icons = svetexpo_show_icons($producer_name);	
	}
}

if(isset($node->field_serie['und'][0]))
	if($tid = $node->field_serie['und'][0]['tid'])		
		$serie_name = taxonomy_term_load($tid)->name;
	
$content['field_product'][0]['label_hidden'] = 1;
$content['field_product'][0]['submit']['#value'] = 'В корзину';
$ost = isset($node->field_ostatki['und'][0]['value'])?$node->field_ostatki['und'][0]['value']:0;

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

?>
<div class="catalog_name" id="node-<?php print $node->nid; ?>">
	<span>
		<a href="<?php print $node_url; ?>"><?php print $producer_name.' '.$serie_name.' '.$node->field_artikul_fabriki['und'][0]['value']; ?></a>
	</span>
</div>

<div class="catalog_box">
	<a href="<?php print $node_url; ?>" class="catalog_ilink">
		<img src="<?php print $big_image; ?>" alt="<?php print $producer_name; ?>" title="" class="catalog_image">
	</a>
	<div class="left">
		<?php if ($show_icons): ?>
			<img src="/sites/all/themes/svetexpo/img/at_home.png" alt="" title="Можно примерить у себя дома">
			<img src="/sites/all/themes/svetexpo/img/in.png" alt="" title="Можно посмотреть в нашем магазине">
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
						
<div class="catalog_price">
	<?php if(!$status_preorder): ?>
		<div class="left">
			<p><strong><?php print $content['product:commerce_price'][0]['#markup']; ?></strong></p>
		</div>
		<div class="right">										
			<?php print render($content['field_product']); ?>
		</div>
	<?php endif; ?>
	<div class="clear"></div>
</div>

