<?php

/**
 * @file
 * This template is used to print a single field in a view.
 *
 * It is not actually used in default Views, as this is registered as a theme
 * function which has better performance. For single overrides, the template is
 * perfectly okay.
 *
 * Variables available:
 * - $view: The view object
 * - $field: The field handler object that can process the input
 * - $row: The raw SQL result that can be used
 * - $output: The processed output that will normally be used.
 *
 * When fetching output from the $row, this construct should be used:
 * $data = $row->{$field->field_alias}
 *
 * The above will guarantee that you'll always get the correct data,
 * regardless of any changes in the aliasing that might happen if
 * the view is modified.
 */
?>
<?php
    $name = strtolower($row->field_field_brand[0]['rendered']['#markup']);
	$sku = $row->commerce_product_field_data_commerce_product_sku; 
    $img = str_replace(array('/', '.', '(', ')', ' '), '_', trim($sku));
	$img = strtolower($img);
	print '<div style = "display: table-cell; text-align: center; width: 100px; vertical-align: middle;"><img src = "/goods/images/'.$name.'/'.$img.'.jpg" style ="height: 80px;" /></div>';
?>
<?php
// @todo Протестировать на сервере! Добавить в views поле brand

?>