<?php

$databases = array (
  'default' => 
  array (
    'default' => 
    array (
      'database' => 'drupal',
      'username' => 'root',
      'password' => 'root',
      'host' => 'localhost',
      'port' => '',
      'driver' => 'mysql',
      'prefix' => 'dr_',
    ),
  ),
);


$update_free_access = FALSE;
$drupal_hash_salt = 'sK31Jx4SXzZz8KqKod0ycNxx6vkR9Xa_XboRakkI4yM';

ini_set('session.gc_probability', 1);
ini_set('session.gc_divisor', 100);

ini_set('session.gc_maxlifetime', 200000);
ini_set('session.cookie_lifetime', 2000000);