<?php
/**
 * @file
 * Developer: Happensit
 * http://happensit.ru/
 *
 * Available variables:
 *
 * General utility variables:
 * - $base_path: The base URL path of the Drupal installation. At the very
 *   least, this will always default to /.
 * - $directory: The directory the template is located in, e.g. modules/system
 *   or themes/bartik.
 * - $is_front: TRUE if the current page is the front page.
 * - $logged_in: TRUE if the user is registered and signed in.
 * - $is_admin: TRUE if the user has permission to access administration pages.
 *
 * Site identity:
 * - $front_page: The URL of the front page. Use this instead of $base_path,
 *   when linking to the front page. This includes the language domain or
 *   prefix.
 * - $logo: The path to the logo image, as defined in theme configuration.
 * - $site_name: The name of the site, empty when display has been disabled
 *   in theme settings.
 * - $site_slogan: The slogan of the site, empty when display has been disabled
 *   in theme settings.
 * - $hide_site_name: TRUE if the site name has been toggled off on the theme
 *   settings page. If hidden, the "element-invisible" class is added to make
 *   the site name visually hidden, but still accessible.
 * - $hide_site_slogan: TRUE if the site slogan has been toggled off on the
 *   theme settings page. If hidden, the "element-invisible" class is added to
 *   make the site slogan visually hidden, but still accessible.
 *
 * Navigation:
 * - $main_menu (array): An array containing the Main menu links for the
 *   site, if they have been configured.
 * - $secondary_menu (array): An array containing the Secondary menu links for
 *   the site, if they have been configured.
 * - $breadcrumb: The breadcrumb trail for the current page.
 *
 * Page content (in order of occurrence in the default page.tpl.php):
 * - $title_prefix (array): An array containing additional output populated by
 *   modules, intended to be displayed in front of the main title tag that
 *   appears in the template.
 * - $title: The page title, for use in the actual HTML content.
 * - $title_suffix (array): An array containing additional output populated by
 *   modules, intended to be displayed after the main title tag that appears in
 *   the template.
 * - $messages: HTML for status and error messages. Should be displayed
 *   prominently.
 * - $tabs (array): Tabs linking to any sub-pages beneath the current page
 *   (e.g., the view and edit tabs when displaying a node).
 * - $action_links (array): Actions local to the page, such as 'Add menu' on the
 *   menu administration interface.
 * - $feed_icons: A string of all feed icons for the current page.
 * - $node: The node object, if there is an automatically-loaded node
 *   associated with the page, and the node ID is the second argument
 *   in the page's path (e.g. node/12345 and node/12345/revisions, but not
 *   comment/reply/12345).
 *
 *
 * @see template_preprocess()
 * @see template_preprocess_page()
 * @see template_process()
 * @see html.tpl.php
 */

/**
 * @function drupal_add_css()
 * @function drupal_add_js()
 * @var $node_url string
 * @var $producer_name string
 * @var $serie_name string
 * @var $field_brand array
 * @var $big_image string
 *
 */

$show_icons = false;
/** @var $node object */
if(isset($node->field_brand['und'][0])){
    $field = $node->field_brand['und'][0];
	$producer_name = $field['taxonomy_term']->name;
	$show_icons = svetexpo_show_icons($producer_name);
}

/**
 * @var $serie_name string
 * @var $seria_name object
 * @function taxonomy_term_load()
 */
$show_icons = false;

if(isset ($node->field_brand['und'][0])){
    $producer_name = $node->field_brand['und'][0]['taxonomy_term']->name;
    $show_icons = svetexpo_show_icons($producer_name);
}

if(isset($node->field_serie)){
   $tid = $node->field_serie['und'][0]['tid'];
    /* @var $serie_name string */
   $serie_name = taxonomy_term_load($tid)->name;
}
	
$content['field_product'][0]['label_hidden'] = 1;
$content['field_product'][0]['submit']['#value'] = 'В корзину';
$ost = isset($node->field_ostatki['und'][0]['value'])?$node->field_ostatki['und'][0]['value']:0;

if(isset($node->field_status['und'][0]['value'])){
	$status = end($node->field_status['und']);
	$text_status = $status['value'];
	$status_preorder = false;
}else{
	if ($ost != 0) {
		$text_status = "В наличии";
		$status_preorder = false; 
	} else {
		$text_status = "Нет в наличии";
		$status_preorder = true;
	}
}

$ff_price_amount = $content['product:commerce_price']['#object']->commerce_price['und'][0]['amount'];
if($ff_price_amount == 0){
	$text_status = "Нет в наличии";
	$status_preorder = true;
}	
?>
<div class="catalog_name" id="node-<?php print $node->nid; ?>">
	<span>
		<a href="<?php print $node_url; ?>"><?php print $producer_name.' '.$serie_name.' '.$node->field_artikul_fabriki['und'][0]['value']; ?></a>
	</span>
</div> <!-- end catalog_name -->
<div class="catalog_box">
	<a href="<?php print $node_url; ?>" class="catalog_ilink">
        <?php if(isset($big_image)) print theme('image', array('path' => $big_image, 'alt' => $producer_name, 'attributes' => array('class' => 'catalog_image lazy'))); ?>
	</a>
	<div class="left">
        <?php if ($show_icons) {
            print theme('image', array('path' => path_to_theme() . '/img/at_home.png', 'alt' => 'Home', 'title' => 'Можно примерить у себя дома', 'attributes' => array('class' => 'prim')));
            print theme('image', array('path' => path_to_theme() . '/img/in.png', 'alt' => 'Shop', 'title' => 'Можно посмотреть в нашем магазине'));
        } ?>
	</div> <!-- End .left -->
	<div class="status <?php $status_preorder ? print "no" :  print "yes"; ?>">
		<?php if($text_status == "Под заказ")
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
	</div> <!-- End .status -->
</div> <!-- End catalog_box -->
<div class="catalog_price">
    <?php /** @var $status_preorder boolean */
    if(!$status_preorder): ?>
		<div class="left">
			<p><strong><?php print $content['product:commerce_price'][0]['#markup']; ?></strong></p>
		</div>
		<div class="right">										
			<?php print render($content['field_product']); ?>
		</div>
	<?php endif; ?>
	<div class="clear"></div>
</div> <!-- End .catalog_price -->