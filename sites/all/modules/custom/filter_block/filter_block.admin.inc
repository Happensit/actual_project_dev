<?php
/**
 * Created by PhpStorm.
 * Developer: Happensit
 * Date: 01.12.13
 * Time: 3:54
 */

/**
 * @file
 * Config form.
 */

function filter_block_config_form($form, &$form_state) {
    $form = array();

    $form['brands'] = array(
        '#type' => 'textarea',
        '#title' => 'Описание для фильтра Производитель',
        '#default_value' => variable_get('brands', '')
    );

    $form['dlina'] = array(
        '#type' => 'textarea',
        '#title' => 'Описание для фильтра Длина',
        '#default_value' => variable_get('dlina', '')
    );

    $form['shirina'] = array(
        '#type' => 'textarea',
        '#title' => 'Описание для фильтра Ширина',
        '#default_value' => variable_get('shirina', '')
    );

    $form['diametrs'] = array(
        '#type' => 'textarea',
        '#title' => 'Описание для фильтра Диаметр',
        '#default_value' => variable_get('diametrs', '')
    );

    $form['count_lamp'] = array(
        '#type' => 'textarea',
        '#title' => 'Описание для фильтра Количество лампочек',
        '#default_value' => variable_get('count_lamp', '')
    );

    $form['styles'] = array(
        '#type' => 'textarea',
        '#title' => 'Описание для фильтра Стиль',
        '#default_value' => variable_get('styles', '')
    );

    $form['color_plafons'] = array(
        '#type' => 'textarea',
        '#title' => 'Описание для фильтра Цвет плафона/абажура',
        '#default_value' => variable_get('color_plafons', '')
    );

    $form['color_metalls'] = array(
        '#type' => 'textarea',
        '#title' => 'Описание для фильтра Цвет каркаса',
        '#default_value' => variable_get('color_metalls', '')
    );

    $form['strana'] = array(
        '#type' => 'textarea',
        '#title' => 'Описание для фильтра Страна происхождения',
        '#default_value' => variable_get('strana', '')
    );

    $form['type_lamp'] = array(
        '#type' => 'textarea',
        '#title' => 'Описание для фильтра Тип лампы',
        '#default_value' => variable_get('type_lamp', '')
    );

    $form['tsokol'] = array(
        '#type' => 'textarea',
        '#title' => 'Описание для фильтра Тип цоколя',
        '#default_value' => variable_get('tsokol', '')
    );

    $form['material_abajura'] = array(
        '#type' => 'textarea',
        '#title' => 'Описание для фильтра Материал абажура',
        '#default_value' => variable_get('material_abajura', '')
    );

    $form['material_karkasa'] = array(
        '#type' => 'textarea',
        '#title' => 'Описание для фильтра Материал каркаса',
        '#default_value' => variable_get('material_karkasa', '')
    );

    $form['voltage'] = array(
        '#type' => 'textarea',
        '#title' => 'Описание для фильтра Напряжение',
        '#default_value' => variable_get('voltage', '')
    );

    $form['vistups'] = array(
        '#type' => 'textarea',
        '#title' => 'Описание для фильтра Расстояние от стены',
        '#default_value' => variable_get('vistups', '')
    );

    $form['visota'] = array(
        '#type' => 'textarea',
        '#title' => 'Описание для фильтра Высота',
        '#default_value' => variable_get('visota', '')
    );

    $form['leds'] = array(
        '#type' => 'textarea',
        '#title' => 'Описание для фильтра Светодиодные',
        '#default_value' => variable_get('leds', '')
    );

    $form['dotteds'] = array(
        '#type' => 'textarea',
        '#title' => 'Описание для фильтра Точечные',
        '#default_value' => variable_get('dotteds', '')
    );

    $form['christals'] = array(
        '#type' => 'textarea',
        '#title' => 'Описание для фильтра Хрустальные',
        '#default_value' => variable_get('christals', '')
    );

    $form['childrens'] = array(
        '#type' => 'textarea',
        '#title' => 'Описание для фильтра Детские',
        '#default_value' => variable_get('childrens', '')
    );

    $form['forgeds'] = array(
        '#type' => 'textarea',
        '#title' => 'Описание для фильтра Кованые',
        '#default_value' => variable_get('forgeds', '')
    );

    $form['solars'] = array(
        '#type' => 'textarea',
        '#title' => 'Описание для фильтра На солнечных батареях',
        '#default_value' => variable_get('solars', '')
    );

    $form['protects'] = array(
        '#type' => 'textarea',
        '#title' => 'Описание для фильтра Влагозащищённые',
        '#default_value' => variable_get('protects', '')
    );

    $form['floristics'] = array(
        '#type' => 'textarea',
        '#title' => 'Описание для фильтра Флористика',
        '#default_value' => variable_get('floristics', '')
    );

    return system_settings_form($form);
}