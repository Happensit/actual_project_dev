<?php
/**
* @file
* Administration page callbacks for the svetexpo2 module.
*/

function svetexpo2_reserie($form, &$form_state) {	
	$form['svetexpo2_data'] = array(
	  '#weight' => '1',
	  '#type' => 'textarea',  
	  '#title' => 'Данные в формате артикул | значение (цена/остаток)',
//	  '#required' => TRUE,
	);
	$form['#submit'][] = 'svetexpo2_reserie_submit';
	return system_settings_form($form);
}

function svetexpo2_reserie_node ($nid) {	
	static $vid = 4; // vocabulary ID
	static $vname = 'serie';

	$node = node_load($nid);
	$edit = array('vid' => $vid, 'name' => $node->field_serija['und'][0]['value']); 

	$terms = taxonomy_get_term_by_name($edit['name'],$vname);
	
	if (!empty($terms)) {
		$term = current($terms);
		$tid = $term->tid; 
	} else {
		$terms = ((object)$edit);
		$status = taxonomy_term_save($terms);
		$tid = $terms->tid;
		$term = $terms;
	}
		
	$node->field_serie['und'][]['tid'] = $tid;
	node_save($node);
	
}

/** обработка формы */
function svetexpo2_reserie_submit($form, &$form_state) {
//$object = field_collection_item_load(4);
//print_r ($object);


	$vid = 4; // vocabulary ID
	$vname = 'serie';

	$id_start = 56;  //556 
	$id_end = $id_start + 500;
	
	for ($i = $id_start; $i <= $id_end; $i ++) {
		$node = node_load($i);
	
	$edit = array('vid' => $vid, 'name' => $node->field_serija['und'][0]['value']); 

	$terms = taxonomy_get_term_by_name($edit['name'],$vname);
	print_r ($terms);
	
	if (!empty($terms)) {
		$term = current($terms);
		$tid = $term->tid; 
	} else {
		$terms = ((object)$edit);
		$status = taxonomy_term_save($terms);
		$tid = $terms->tid;
		$term = $terms;
	}
		
	$node->field_serie['und'][]['tid'] = $tid;
	node_save($node);
	
	drupal_set_message(" $i - $tid, {$edit['name']} - $term->name ", 'status');
	
	}

}

function svetexpo2_reserie_batch_init($form, &$form_state) {
//	$form['#submit'][] = 'svetexpo2_reserie_batch_init_submit';
	$form['svetexpo2_data'] = array(
	  '#weight' => '1',
//	  '#type' => 'textarea',  
	  '#markup' => '<div>Пакетное преобразование поля Серия в словарь Taxonomy </div> '. @$_SESSION['http_request_count'] ."<br>" ,
//	  '#required' => TRUE,
	);
	$form['svetexpo2_start'] = array(
		'#type'=>'hidden',
		'#value'=>1,
	);
	$form['svetexpo2_submit'] = array(
	  '#weight' => '5',
	  '#type' => 'submit',  
	  '#name' => 'op',  
	  '#title' => 'Import price',
	  '#value' => 'Запустить импорт',
	);
	return $form;//system_settings_form($form); //return $form;
}

function svetexpo2_reserie_batch_init_submit() {
$_SESSION['http_request_count'] = 0;
	$batch = array(
    'title' => t('Изменение ...'),
    'operations' => array(),
    'init_message' => t('Подготовка'),
    'progress_message' => t('Обработано @current из @total.'),
    'error_message' => t('Ошибка при обработке'),
    'finished' => 'svetexpo2_reserie_batch_finished',
	'file'=>drupal_get_path('module', 'svetexpo2').'/svetexpo2.admin.inc',
//    'progressive' => TRUE
  );
  $type = 'lamp';
//  print_r ($batch['file']); die();
  /***
    $nids = SELECT  $query
    ->fields('n',array('nid'))
    ->limit(50)
    ->orderByHeader($header)
    ->execute()
    ->fetchCol();*/

	
	
	
	$nodes = db_query("SELECT nid FROM {node} WHERE type = :type AND nid > 556",array(':type'=>'lamp'));
	
	
	foreach ($nodes as $new_node) {
		//print_r( $new_node); die();
		$batch['operations'][] = array('svetexpo2_reserie_batch_worker', array($new_node->nid));
	}
	
	//print_r ($batch); die();

	batch_set($batch);

	//batch_process();// если используется не из _submit функции
}


function svetexpo2_reserie_batch_finished($success, $results, $operations) {
	//print_r ($results); die();
	if ($success) {
		$message = format_plural($results['processed'], '1 запись обработана.', '@count записей обработано.');
	} else {
		$message = 'some errors';
	}
	drupal_set_message($message);
}

function svetexpo2_reserie_batch_worker ($nid,&$context){
	$_SESSION['http_request_count'] ++ ;
	svetexpo2_reserie_node($nid);
	
	// print_r ($context); die();
	// print_r ($new_node); die();
	@$context['results']['processed']++;
	$context['message'] = "Обновлен $nid";
}