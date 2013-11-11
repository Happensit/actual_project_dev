<?php

function wysiwyg_bootstrap_wysihtml5_editor() {
  $editor['bootstrap_wysihtml5'] = array(
    'title' => 'Bootstrap wysihtml5',
    'vendor url' => 'http://jhollingworth.github.com/bootstrap-wysihtml5/',
    'download url' => 'https://github.com/jhollingworth/bootstrap-wysihtml5/zipball/master',
    'library path' => wysiwyg_get_path('bootstrap-wysihtml5'),
    'libraries' => array(
      'default' => array(
        'title' => 'Default',
        'files' => array(
          'lib/js/wysihtml5-0.3.0.js',
          'src/bootstrap-wysihtml5.js',
        ),
      ),
    ),
    'version callback' => 'wysiwyg_bootstrap_wysihtml5_version',
    'load callback' => 'wysiwyg_bootstrap_wysihtml5_load',
    'versions' => array(
      '0.3.0' => array(
        'js files' => array(
          'bootstrap_wysihtml5.js',
        ),
      ),
    ),
  );

  return $editor;
}

function wysiwyg_bootstrap_wysihtml5_version() {
  return '0.3.0';
}

function wysiwyg_bootstrap_wysihtml5_load($editor, $library) {
  if (variable_get('wysiwyg_bootstrap_wysihtml5_include_bootstrap_css', TRUE)) {
    drupal_add_css($editor['library path'] . '/lib/css/bootstrap.min.css');
  }
  drupal_add_css($editor['library path'] . '/src/bootstrap-wysihtml5.css');
  drupal_add_js($editor['library path'] . '/lib/js/bootstrap.min.js');
  drupal_add_js($editor['library path'] . '/lib/js/wysihtml5-0.3.0.js');
  drupal_add_js($editor['library path'] . '/lib/js/prettify.js');
  drupal_add_js($editor['library path'] . '/src/bootstrap-wysihtml5.js');
}
