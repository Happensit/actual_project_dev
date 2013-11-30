<?php

/**
 * $file
 * Add a Term Hierarchy plugin to the feeds tamper module
 */

$plugin = array(
  'form' => 'custom_feeds_tamper_term_hierarchy_form',
  'callback' => 'custom_feeds_tamper_term_hierarchy_callback',
  'validate' => 'custom_feeds_tamper_term_hierarchy_validate',
  'name' => '(Custom) Term Hierarchy',
  'multi' => 'direct',
  //'single' => 'skip',
  'category' => 'List',
);

function custom_feeds_tamper_term_hierarchy_form($importer, $element_key, $settings) {
  $form = array();
  $vocabs = taxonomy_vocabulary_get_names();
  $options = array(t('Select...'));
  foreach ($vocabs as $vocab) {
    $options[$vocab->vid] = $vocab->name;
  }
  $form['vocab'] = array(
    '#type' => 'select',
    '#title' => t('Vocabulary'),
    '#options' => $options,
    '#default_value' => isset($settings['vocab']) ? $settings['vocab'] : 0,
    '#description' => t('This will use an array of strings to build a tree of terms. For example, array("a", "b", "c") will form a hierarchy of terms with "a" as the root and "b" as a final child. The term ID of the ultimate descendant ("b") will be returned.'),
    '#required' => TRUE,
  );

  $form['separator'] = array(
    '#type' => 'textfield',
    '#title' => t('Term hierarchy separator'),
    '#default_value' => isset($settings['separator']) ? $settings['separator'] : ">>",
    '#description' => t('Define the separator used to separate terms levels.'),
    '#required' => TRUE,
  );
  
  return $form;
}

function custom_feeds_tamper_term_hierarchy_validate(&$settings) {
  if (!$settings['vocab']) {
    form_set_error('settings][vocab', t('You must select a vocabulary.'));
  }
}

function custom_feeds_tamper_term_hierarchy_callback($result, $item_key, $element_key, &$field, $settings) {
  if(!is_array($field)){
    $field = array($field);
  }
  $new_fields = array();
  foreach($field as $f){
    $new_fields[] = custom_feeds_tamper_term_hierarchy_fetch_term($f, 0, $settings);
  }
  $field = $new_fields;
}

function custom_feeds_tamper_term_hierarchy_fetch_term($name, $pid, $settings) {
	$vid = $settings['vocab'];
  $separator = $settings['separator'];
  $feed_terms = explode($separator, $name);
  $tax = taxonomy_get_tree($vid);
  $termid = custom_feeds_tamper_term_hierarchy_create_and_fetch_term($tax, $vid, $feed_terms);
  return $termid;
}

function custom_feeds_tamper_term_hierarchy_create_and_fetch_term(&$tax, $vid, $terms_left, $parent = 0){
	if(!count($terms_left)){
		return $parent;
	}else{
		$current = array_shift($terms_left);
		$already_there = false;
		foreach($tax as $term){
			if($term->name == $current){
				foreach($term->parents as $pid){
					if($pid == $parent){
						$already_there = $term->tid;
					}
				}
			}
		}
		if(!$already_there){
			// create term
			$newterm = new stdClass();
			$newterm->name = $current;
			$newterm->vid = $vid;
			$newterm->parent = $parent;
			taxonomy_term_save($newterm);
			$tax[] = taxonomy_term_load($newterm->tid);
			return custom_feeds_tamper_term_hierarchy_create_and_fetch_term($tax, $vid, $terms_left, $newterm->tid);
		}else{
			return custom_feeds_tamper_term_hierarchy_create_and_fetch_term($tax, $vid, $terms_left, $already_there);
		}
	}
}