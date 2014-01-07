<?php

/**
 * Implements hook_html_head_alter().
 * This will overwrite the default meta character type tag with HTML5 version.
 */
function redprice_html_head_alter(&$head_elements) {
   //dsm($head_elements);
    $head_elements['system_meta_content_type']['#attributes'] = array(
        'charset' => 'utf-8'
    );
    unset($head_elements['system_meta_generator']);
}

/**
 * Отключаем @import url.
 * Implements hook_css_alter().
 */
function redprice_css_alter(&$css) {
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

function redprice_js_alter(&$js) {
    uasort($js, 'drupal_sort_css_js');
    $i = 0;
    foreach ($js as $name => $script) {
        $js[$name]['weight'] = $i++;
        $js[$name]['group'] = JS_DEFAULT;
        $js[$name]['every_page'] = FALSE;
    }
    $js['settings']['scope'] = 'footer';
}

function redprice_preprocess_html(&$variables) {
  $variables['base_url'] = $GLOBALS['base_url'];
  // Add body classes if certain regions have content.
  if (!empty($variables['page']['featured'])) {
    $variables['classes_array'][] = 'featured';
  }

  if (!empty($variables['page']['triptych_first'])
    || !empty($variables['page']['triptych_middle'])
    || !empty($variables['page']['triptych_last'])) {
    $variables['classes_array'][] = 'triptych';
  }

  if (!empty($variables['page']['footer_firstcolumn'])
    || !empty($variables['page']['footer_secondcolumn'])
    || !empty($variables['page']['footer_thirdcolumn'])
    || !empty($variables['page']['footer_fourthcolumn'])) {
    $variables['classes_array'][] = 'footer-columns';
  }
}

/**
 * Override or insert variables into the page template.
 */
function redprice_process_page(&$variables) {
  $variables['hide_site_slogan'] = theme_get_setting('toggle_slogan') ? FALSE : TRUE;
  if ($variables['hide_site_slogan']) {
    $variables['site_slogan'] = filter_xss_admin(variable_get('site_slogan', ''));
  }
  if (!empty($variables['title_suffix']['add_or_remove_shortcut']) && $variables['title']) {
    $variables['title_prefix']['shortcut_wrapper'] = array(
      '#markup' => '<div class="shortcut-wrapper clearfix">',
      '#weight' => 100,
    );
    $variables['title_suffix']['shortcut_wrapper'] = array(
      '#markup' => '</div>',
      '#weight' => -99,
    );
    $variables['title_suffix']['add_or_remove_shortcut']['#weight'] = -100;
  }

  if (isset($variables['is_front'])) {
         unset($variables['page']['content']['system_main']['default_message']);
      }
}

/**
 * Implements hook_preprocess_maintenance_page().
 */
function redprice_preprocess_maintenance_page(&$variables) {
  // By default, site_name is set to Drupal if no db connection is available
  // or during site installation. Setting site_name to an empty string makes
  // the site and update pages look cleaner.
  // @see template_preprocess_maintenance_page
  if (!$variables['db_is_active']) {
    $variables['site_name'] = '';
  }
  drupal_add_css(drupal_get_path('theme', 'redprice') . '/css/maintenance-page.css');
}

/**
 * Override or insert variables into the maintenance page template.
 */
function redprice_process_maintenance_page(&$variables) {
  // Always print the site name and slogan, but if they are toggled off, we'll
  // just hide them visually.
  $variables['hide_site_name']   = theme_get_setting('toggle_name') ? FALSE : TRUE;
  $variables['hide_site_slogan'] = theme_get_setting('toggle_slogan') ? FALSE : TRUE;
  if ($variables['hide_site_name']) {
    // If toggle_name is FALSE, the site_name will be empty, so we rebuild it.
    $variables['site_name'] = filter_xss_admin(variable_get('site_name', 'Drupal'));
  }
  if ($variables['hide_site_slogan']) {
    // If toggle_site_slogan is FALSE, the site_slogan will be empty, so we rebuild it.
    $variables['site_slogan'] = filter_xss_admin(variable_get('site_slogan', ''));
  }
}

/**
 * Implements hook_page_alter().
 */
function redprice_page_alter(&$page) {
    // Remove all the region wrappers.
    foreach (element_children($page) as $key => $region) {
        if (!empty($page[$region]['#theme_wrappers'])) {
            $page[$region]['#theme_wrappers'] = array_diff($page[$region]['#theme_wrappers'], array('region'));
        }
    }
    // Remove the wrapper from the main content block.
    if (!empty($page['content']['system_main'])) {
        $page['content']['system_main']['#theme_wrappers'] = array_diff($page['content']['system_main']['#theme_wrappers'], array('block'));
    }
}

/**
 * Override or insert variables into the node template.
 */
function redprice_preprocess_node(&$variables) {
  if ($variables['view_mode'] == 'full' && node_is_page($variables['node'])) {
    $variables['classes_array'][] = 'node-full';
  }
}

/**
 * Override or insert variables into the block template.
 */
function redprice_preprocess_block(&$variables) {
  // In the header region visually hide block titles.
  if ($variables['block']->region == 'header') {
    $variables['title_attributes_array']['class'][] = 'element-invisible';
  }
}

/**
 * Implements theme_menu_tree().
 */
function redprice_menu_tree__main_menu($variables) {
  return '<ul id="main-menu-links" class="menu clearfix">' . $variables['tree'] . '</ul>';
}

/**
 * theme_menu_link()
 * unique class for menu items
 */
function redprice_menu_link(array $variables) {
    $element = $variables['element'];
    $sub_menu = '';
    $element['#attributes']['class'] = preg_grep('/^leaf/', $element['#attributes']['class'], PREG_GREP_INVERT);
   if ($element['#below']) {
        $sub_menu = drupal_render($element['#below']);
    }
    $output = l($element['#title'], $element['#href'], $element['#localized_options']);
    return '<li' . drupal_attributes($element['#attributes']) . '>' . $output . $sub_menu. "</li>\n";
}

function redprice_menu_link__secondary_menu(array $variables){
    dprint_r($variables);
}

/**
 * Implements theme_field__field_type().
 */
function redprice_field__taxonomy_term_reference($variables) {
  $output = '';

  // Render the label, if it's not hidden.
  if (!$variables['label_hidden']) {
    $output .= '<h3 class="field-label">' . $variables['label'] . ': </h3>';
  }

  // Render the items.
  $output .= ($variables['element']['#label_display'] == 'inline') ? '<ul class="links inline">' : '<ul class="links">';
  foreach ($variables['items'] as $delta => $item) {
    $output .= '<li class="taxonomy-term-reference-' . $delta . '"' . $variables['item_attributes'][$delta] . '>' . drupal_render($item) . '</li>';
  }
  $output .= '</ul>';

  // Render the top-level DIV.
  $output = '<div class="' . $variables['classes'] . (!in_array('clearfix', $variables['classes_array']) ? ' clearfix' : '') . '"' . $variables['attributes'] .'>' . $output . '</div>';

  return $output;
}

/**
 * Implements hook_form_alter().
 */
function redprice_form_alter(&$form, &$form_state, $form_id) {
    $form['#attributes']['class'][] = drupal_clean_css_identifier($form['#id']);
}
