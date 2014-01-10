<?php

function svetexpo_html_head_alter(&$head_elements) {
  unset($head_elements['system_meta_generator']);
  foreach (preg_grep('/^drupal_add_html_head_link:shortlink:/', array_keys($head_elements)) as $key) {
      unset($head_elements[$key]);
    }
}

/**
 * Implements hook_js_alter().
 */
function svetexpo_js_alter(&$js) {
    $js['settings']['scope'] = 'footer';
}


/**
 * Отключаем @import url.
 * Implements hook_css_alter().
 */
function svetexpo_css_alter(&$css) {
    //dsm($css);
    foreach ($css as $path => $value) {
        if ($css[$path]['media'] == 'all') {
            $css[$path]['media'] = 'screen';
        }
    }
    //grap the css and punch it into one file
    //credits to metaltoad http://www.metaltoad.com/blog/drupal-7-taking-control-css-and-js-aggregation
    uasort($css, 'drupal_sort_css_js');
    $i = 0;
    foreach ($css as $name => $style) {
        $css[$name]['weight'] = $i++;
        $css[$name]['group'] = CSS_DEFAULT;
        $css[$name]['every_page'] = FALSE;
    }
}

// блок с корзиной пустой
function svetexpo_commerce_cart_empty_block() {
  return t('Корзина пуста');
}

/**
 * блок с корзиной полный
 */
function svetexpo_commerce_cart_block($variables = array()) {
	global $user;
	$suff = "ов";
	$order = commerce_cart_order_load($user->uid);
	$wrapper = entity_metadata_wrapper('commerce_order', $order);
	$line_items = $wrapper->commerce_line_items;
	$total = commerce_line_items_total($line_items);
	$quantity = commerce_line_items_quantity($line_items, commerce_product_line_item_types());
	if($quantity == 1) $suff = null;
	if($quantity > 1 && $quantity < 5) $suff = "а";
	$cart_link = t("<span style = 'color: #0275c6;'>{$quantity}</span> товар".$suff);
	//$checkout_link = l(t('Перейти к оформлению'), 'checkout');
	return "{$cart_link} на сумму <span style = 'color: #0275c6;'>".commerce_currency_format($total['amount'], $total['currency_code'])."</span>";//. "<br>$checkout_link";
}


function svetexpo_field__lamp(&$variables) {
    $items = $variables['items'];
    $view_mode = $variables['element']['#view_mode'];
    $label = $variables['label'];

    switch ($view_mode) {
        case 'full':
        case 'default':
        case '_custom_display':

        global $value;
        $output = '';
		foreach ($items as $key => $item) {
              if($key > 0) {
                   $value .= (', '.$items);
             }
			$value = $item;

            if(!empty($value)){
                if ($label == 'Товар') $label = '';

                $output .= '<li>
                                <div class="double">
                                    <div class="double_box">
                                        <div class="double_box_content">
                                            <div class="double_box">
                                                '.$label.'
                                            </div>
                                            <div class="double_box_description">
                                                '.drupal_render($value).'
                                            </div>
                                            <div class="clear"></div>
                                        </div>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                            </li>';
                }
            return $output;
        }
        break;
	default:
		$output = '';

		// Render the label, if it's not hidden.
		if (!$variables['label_hidden']) {
			$output .= '<div class="field-label"' . $variables['title_attributes'] . '>' . $variables['label'] . ':&nbsp;</div>';
		}

		// Render the items.
		$output .= '<div class="field-items"' . $variables['content_attributes'] . '>';
		foreach ($items as $delta => $item) {
			$classes = 'field-item ' . ($delta % 2 ? 'odd' : 'even');
			if (is_array($item))
				$output .= '<div class="' . $classes . '"' . $variables['item_attributes'][$delta] . '>' . drupal_render($item) . '</div>';
		}
		$output .= '</div>';

		// Render the top-level DIV.
		$output = '<div class="' . $variables['classes'] . '"' . $variables['attributes'] . '>' . $output . '</div>';

		return $output;
	}

}


function svetexpo_show_icons(&$producer){
	return (in_array(trim($producer),array('Leds C4','L\'arte Luce','Mirada De Cristal','Berliner Messinglampen')));
}

function svetexpo_breadcrumb($variables) {
	$breadcrumb = $variables['breadcrumb'];
    if ( arg(0) == 'node' && is_numeric(arg(1)) ) {
        $node = node_load(arg(1));
        if ($node->type=='lamp') {            
            if ($current = taxonomy_term_load($node->field_category['und'][0]['tid'])) { // taxonomy_term_load()) {
				$breadcrumb = array();
				$breadcrumb[] = $node->title;
                $breadcrumb[] = l($current->name, 'taxonomy/term/' . $current->tid);
                while ($parents = taxonomy_get_parents($current->tid)) {
                    $current = array_shift($parents);
                    $breadcrumb[] = l($current->name, 'taxonomy/term/' . $current->tid);
                }
                $breadcrumb[] = l(t('Home'), NULL);
                $breadcrumb = array_reverse($breadcrumb);
            }
        }
    } else if (arg(0) == 'taxonomy' && (arg(1) == 'term') && is_numeric(arg(2))) {
		$term = taxonomy_term_load(arg(2));
		//if ($term->vid == 3) // brand
			$breadcrumb[] = $term->name;
	}
    if (!empty($breadcrumb)) {
        $breadcrumb[0] = l('<img src="'.base_path() . drupal_get_path('theme', 'svetexpo').'/img/home.gif" alt="'.t('Home').'">'
							,NULL,array('html'=>true));
        return '<div class="breadcrumb">'. implode('&nbsp;&nbsp;-&nbsp;&nbsp;', $breadcrumb) .'</div>';
    }
}

function svetexpo_form_views_form_commerce_cart_form_default_alter(&$form, &$form_state) {
  $form['output']['#markup'] = "<div class='basket1 cart'>".$form['output']['#markup'].'</div>';
 
  if (is_array($form['edit_delete']))
  foreach($form['edit_delete'] as $k=> $item) {
     if($item['#type'] == 'submit') {
		$form['edit_delete'][$k] ['#value'] = ' ';
	 }
  }
  
  $form['actions']['submit']['#value'] = 'Пересчитать';
 
  $form['actions']['checkout']['#value'] = 'Оформить';

  $form['output']['#markup'] .= <<<HTML
  <script>
 jQuery(document).ready(function(){
   jQuery("input#edit-submit").replaceWith("<button type='submit' class='button_flex bf2' onclick='window.history.back();return false;'><span><span>Вернуться к покупкам</span></span></button> <button type='submit' name='op' value='Пересчитать' class='button_flex bf2 refresh'><span><span>Пересчитать</span></span></button> ");
   jQuery("input#edit-checkout").replaceWith("<button type='submit' name='op' class='button_flex checkout' value='Оформить'><span><span>Оформить заказ</span></span></button>");
  });
  </script>
HTML;

 drupal_add_js('(function ($) {
  Drupal.behaviors.changeQuantity = {
    attach : function(context, settings) {
      $("input.form-text").change(function() {
       setTimeout(function(){ $("button.button_flex.bf2.refresh").click();}, 1100);
     });
    }
  };
})(jQuery);', array('type' => 'inline'));

$order = commerce_order_load($form_state['order']->order_id);
$total_price = $order->commerce_order_total['und'][0]['amount']/100;
if($total_price < 3000):
  drupal_set_message(('<span style="color:red;">По правилам нашего магазина минимальная сумма заказа составляет 3000 рублей.</span>'), $type = 'warning', FALSE);
  //form_set_status('order', ('По правилам нашего магазина минимальная сумма заказа составляет 3000 рублей.'));
  drupal_add_js('jQuery(document).ready(function(){
     jQuery(".button_flex.checkout").css("display","none");

  });', array('type' => 'inline', 'scope' => 'footer'));

  //echo ('<p style="color:red; font-weight: bold">По правилам нашего магазина минимальная сумма заказа составляет 3000 рублей.</p>');
  endif;
}

function svetexpo_shipping_commerce_customer_profile_type_info() {
  $profile_types = array();

  $profile_types['shipping'] = array(
    'type' => 'shipping',
    'name' => t('Информация о доставке'),
    'description' => t('The profile used to collect shipping information on the checkout and order forms.'),
    'help' => '',
  );

  return $profile_types;
}

function svetexpo_form_alter(&$form, &$form_state, $form_id){
   if ($form_id=='commerce_checkout_form_checkout'){
    $form['customer_profile_shipping']['#attached']['js'] = array(
      drupal_get_path('theme', 'svetexpo') . '/js/checkout.js',
    );
  
	  $form['customer_profile_shipping']['#title'] = 'Контактные данные';
	  $form['commerce_shipping']['#title'] = 'Стоимость доставки';
	  
	  $form['customer_profile_shipping']['commerce_customer_address']['und'][0]['street_block']['thoroughfare']['#title'] = 'Адрес доставки';

  } else if ($form_id =='views_exposed_form' ) {
	$form['submit']['#value'] = 'Подобрать';
	$form['#info']['filter-keys']['label'] = '';
  }
}

function svetexpo_callback(){
//this stays for the ajax effect
}

function svetexpo_views_pre_execute(&$view) {
	var_dump($_GET);
  if ($view->name == 'lamp_search') {
	var_dump($_GET);
    //there seems to be a bug in views that use the search term as a filter
    // which adds the score field to the groupby 
    // since each score is unique the rows are never grouped 
    // this prevents any aggregation so the unique search results are not counted properly
    $group_by = &$view->build_info['query']->getGroupBy();
    unset($group_by['score']);
    $group_by = &$view->build_info['count_query']->getGroupBy();
    unset($group_by['score']);
  }
}


function svetexpo_pager($variables) {
  $tags = $variables['tags'];
  $element = $variables['element'];
  $parameters = $variables['parameters'];
  $quantity = $variables['quantity'];

  global $pager_page_array, $pager_total, $pager_total_items;
  $pager_middle = ceil($quantity / 2);
  $pager_current = $pager_page_array[$element];
  $pager_first = ($pager_current - $pager_middle)+1;
  $pager_last = ($pager_current + $quantity - $pager_middle);
 // max is the maximum page number
  $pager_max = $pager_total[$element]-1;
  // End of marker calculations.
  // Prepare for generation loop.
  $i = $pager_first;
  if ($pager_last > $pager_max) {
    // Adjust "center" if at end of query.
    $i = $i + ($pager_max - $pager_last);
    $pager_last = $pager_max;
  }


  if ($i <= 0) {
    // Adjust "center" if at start of query.
    $pager_last = $pager_last + (1 - $i);
    $i = 1;
  }
  // End of generation loop preparation.

	if($pager_total > 25){
		$tags[4] = $pager_max;
		$tags[0] = 1;
	}

  $li_first = theme('pager_first', array('text' => (isset($tags[0]) ? $tags[0] : t('« first')), 'element' => $element, 'parameters' => $parameters));
  $li_previous = theme('pager_previous', array('text' => (isset($tags[1]) ? $tags[1] : t('‹ previous')), 'element' => $element, 'interval' => 1, 'parameters' => $parameters));
  $li_next = theme('pager_next', array('text' => (isset($tags[3]) ? $tags[3] : t('next ›')), 'element' => $element, 'interval' => 1, 'parameters' => $parameters));
  $li_last = theme('pager_last', array('text' => (isset($tags[4]) ? $tags[4] : t('last »')), 'element' => $element, 'parameters' => $parameters));

  $count_element = 'Товаров: '. $pager_total_items[0];


  if ($pager_total[$element] > 1) {
    $items[] = array(
      'class'=> array('count_element'),
      'data' => $count_element,
      );

    // if($pager_current == '0') {
    // 		 $items[] = array(
		  //       'class' => array('pager-current'),
		  //       'data' => 1,
		  //     );

    // 		 $i = 2;
    // 	}


    if ($li_first && $pager_total[$element] > 8 && $pager_current - 2 > 1 ) {
      $items[] = array(
        'class' => array('pager-first'),
        'data' => $li_first,
      );
    }
    if ($li_previous) {
      $items[] = array(
        'class' => array('pager-previous'),
        'data' => $li_previous,
      );
    }

    // When there is more than one page, create the pager list.
    if ($i != $pager_max) {
      if ($i > 1 && $pager_current - 3 > 1) {
      	$items[] = array(
          'class' => array('pager-ellipsis'),
          'data' => '…',
          //'data' => '»',
        );
      }
      // Now generate the actual pager piece.
      for (; $i <= $pager_last && $i <= $pager_max; $i++) {
        if ($i < $pager_current) {
          $items[] = array(
            'class' => array('pager-item'),
            'data' => theme('pager_previous', array('text' => $i, 'element' => $element, 'interval' => ($pager_current - $i), 'parameters' => $parameters)),
          );
        }
       if ($i == $pager_current) {
	          $items[] = array(
	            'class' => array('pager-current'),
	            'data' => $i,
	          );
        }
        if ($i > $pager_current) {
          $items[] = array(
            'class' => array('pager-item'),
            'data' => theme('pager_next', array('text' => $i, 'element' => $element, 'interval' => ($i - $pager_current), 'parameters' => $parameters)),
          );
        }
      }
      if ($i < $pager_max) {
        $items[] = array(
          'class' => array('pager-ellipsis'),
          'data' => '…',
        );
      }
    }
    // End generation.
    if ($li_next) {
      $items[] = array(
        'class' => array('pager-next'),
        'data' => $li_next,
      );
    }
      if ($li_last){  //&& $pager_total[$element] > 5 && $pager_current + 3 < $pager_total[$element]) {
      $items[] = array(
        'class' => array('pager-last'),
        'data' => $li_last,
      );
    }

    return theme('item_list', array(
      'items' => $items,
      'attributes' => array('class' => array('pager')),
    ));
  }
}
//
//function svetexpo_pager_first($variables) {
//    $text = 1;
//    $element = $variables['element'];
//    $parameters = $variables['parameters'];
//    global $pager_page_array;
//    $output = '';
//
//    // If we are anywhere but the first page
//    if ($pager_page_array[$element] > 0) {
//        $output = theme('pager_link', array('text' => $text, 'page_new' => pager_load_array(0, $element, $pager_page_array), 'element' => $element, 'parameters' => $parameters));
//    }
//
//    return $output;
//}
//
//function svetexpo_pager_last($variables) {
//    $element = $variables['element'];
//    $parameters = $variables['parameters'];
//    global $pager_page_array, $pager_total;
//    $text = $pager_total[$element]-1;
//    $pager_current = $pager_page_array[$element];
//    $output = '';
//
//    // If we are anywhere but the last page
//    if ($pager_page_array[$element] < ($pager_total[$element] - 1) && $pager_total[$element] > 5 && $pager_current + 3 < $pager_total[$element]) {
//        $output = theme('pager_link', array('text' => $text, 'page_new' => pager_load_array($pager_total[$element] - 1, $element, $pager_page_array), 'element' => $element, 'parameters' => $parameters));
//    }
//
//    return $output;
//}

function svetexpo_preprocess_node(&$variables) {
    $node = $variables['node'];
    if ($node->type == 'lamp' && $variables['view_mode']== 'full') {
        /* Грузим скрипты */
        drupal_add_js(drupal_get_path('theme', 'svetexpo'). '/js/jquery.mousewheel-3.0.6.pack.js', array(
                'type' => 'file',
                'scope' => 'header',
                'group' => JS_DEFAULT,
                'weight' => -1,
                'preprocess' => TRUE,
                'cache' => TRUE,
            ));
        drupal_add_css(drupal_get_path('theme', 'svetexpo'). '/js/fancybox/jquery.fancybox.css');
        drupal_add_js(drupal_get_path('theme', 'svetexpo'). '/js/fancybox/jquery.fancybox.pack.js', array(
                'type' => 'file',
                'scope' => 'header',
                'group' => JS_DEFAULT,
                'preprocess' => TRUE,
                'cache' => TRUE,
            ));
        drupal_add_js(drupal_get_path('theme', 'svetexpo') . '/js/jquery.jcarousel.min.js');
        drupal_add_css(drupal_get_path('theme', 'svetexpo') . '/style/bunners.css', array(
                'group' => CSS_DEFAULT,
                'preprocess' => TRUE
            ));

        drupal_add_js('jQuery(document).ready(function(){

            jQuery("a.fancy").fancybox({
                titleShow : false,
                //closeBtn: false,
                //padding: 0,
                afterShow: function(){
                    jQuery(".fancybox-image").on("click", function() {
                            jQuery.fancybox.close();
                    });
                }
            });

            jQuery("a[rel=second_fancy]").fancybox({
                titleShow : false,
                //padding	: 0,
                loop: false,
                afterShow: function(){
                    var groupLength = this.group.length;
                    var thisIndex = this.index + 2; // index starts at "0"
                    jQuery(".fancybox-image").on("click", function() {
                        if (thisIndex > groupLength) {
                            jQuery.fancybox.close();
                        } else {
                            thisIndex++;
                        }
                    });
                }
            });

             jQuery(".gallery_img").jcarousel({
                    wrap: "circular",
             });

                jQuery(".gallery_img").jcarouselAutoscroll({ autostart: true,  interval: 5000 });

                jQuery(".gallery_img-control-prev")
                    .on("jcarouselcontrol:active", function() {
                        jQuery(this).removeClass("inactive");
                    })
                    .on("jcarouselcontrol:inactive", function() {
                        jQuery(this).addClass("inactive");
                    })
                    .jcarouselControl({
                        target: "-=1"
                    });

                jQuery(".gallery_img-control-next")
                    .on("jcarouselcontrol:active", function() {
                        jQuery(this).removeClass("inactive");
                    })
                    .on("jcarouselcontrol:inactive", function() {
                        jQuery(this).addClass("inactive");
                    })
                    .jcarouselControl({
                        target: "+=1"
                    });

            jQuery("dl.tabs dt").click(function(){
                jQuery(this).siblings().removeClass("active").end()
                    .next("dd").andSelf().addClass("active");
            });
    });', 'inline');

    }
}