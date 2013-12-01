<?php
/**
 * Created by PhpStorm.
 * Developer: Happensit
 * Date: 01.12.13
 * Time: 1:36
 */

function filter_foreach_termins(&$array) {
    foreach ($array as $term_key => $term) {
        $term_value = reset($term->option);
        if($term_value[0] == '-') {
            unset($array[$term_key]);
        }
    }
}

function filter_foreach_colors(&$array) {
    /** @var $terms array */
    $term_color = array();
    $terms = taxonomy_term_load_multiple(array_keys($array));

    foreach ($terms as $term){
        if (isset($term->field_hex_color)) {
            $rgb = $term->field_hex_color['und'][0]['rgb'];
        } elseif (isset($term->field_hex_metall)) {
            $rgb = $term->field_hex_metall['und'][0]['rgb'];
        }

        if(!empty($rgb)){
           $term_color[$term->tid] = $rgb.'||'.$term->name;
        }
    }
    return $term_color;
}