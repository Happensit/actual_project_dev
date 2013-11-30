<?php


function svetexposhop_html_head_alter(&$head_elements) {
    unset($head_elements['system_meta_generator']);
}

/**
 * Implements hook_js_alter().
 */
function svetexposhop_js_alter(&$js) {
    if (isset($js['misc/jquery.js'])) {
        $jquery_path = '//ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js';
        $js['misc/jquery.js']['data'] = $jquery_path;
        $js['misc/jquery.js']['version'] = '1.7.2';
        $js['misc/jquery.js']['type'] = 'external';
    }
}

/**
 * блок с корзиной пустой
 */
function svetexpo_commerce_cart_empty_block() {
    return t('Корзина пуста');
}

/**
 * @param $producer
 * @return bool
 */
function svetexpo_show_icons(&$producer){
    return (in_array(trim($producer),array('Leds C4','L\'arte Luce','Mirada De Cristal','Berliner Messinglampen')));
}

/**
 * @param $variables
 * @return string
 */
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
