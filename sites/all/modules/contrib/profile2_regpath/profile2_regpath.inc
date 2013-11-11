<?php

/**
 * @file
 * Modifications to the user registration form.
 */

/*
 * Attach profile fields to form.
 */
function profile2_regpath_attach_profile_fields(&$form, &$form_state, $form_id, $profile_types = NULL) {

  // Check to see if the form is being rebuilt after an AJAX request.
  // If so, we will have lost the $profile_types parameter, and need to reacquire it.
  $menu_item = menu_get_item();

  if (!$profile_types && ($menu_item['path'] == 'system/ajax' || $menu_item['path'] == 'file/ajax')) {
    $url = drupal_parse_url($form['#action']);
    $path = end(explode('/', str_replace('/register', '', $url['path'])));
    $profile_types = profile2_regpath_regpath_load_multiple(array('path' => $path));
  }

  if ($profile_types != NULL) {

    // Prepare variables for roles.
    $user_roles = user_roles(TRUE);
    $roles = array();

    // Attach profile(s) to user/register form.
    foreach ($profile_types as $type_name => $value) {
      // Get profile object.
      $profile_type = profile2_get_types($type_name);

      // If this profile has not already been attached.
      if (empty($form_state['profiles'][$type_name])) {
        $profile = profile_create(array('type' => $type_name));
        $form_state['profiles'][$type_name] = $profile;

        $misc = unserialize($profile_types[$type_name]->misc);
        // Wrap each profile form in a fieldset.
        if ($misc['fieldset_wrap']) {
          $form['profile_' . $type_name] = array(
            '#type' => 'fieldset',
            '#title' => check_plain($profile_type->label),
          );
        }

        // Set Form API #weight attribute for profile.
        $form['profile_' . $type_name]['#weight'] = $profile_type->weight;

        // Attach custom confirmation message to form for later display.
        if (isset($misc['confirmation_display'])) {
          $_SESSION['profile2_regpath']['confirmation_message'] = $misc['confirmation_message'];
        }
      }

      // Add appropriate user roles.
      $profile_roles = unserialize($value->roles);
      foreach ($profile_roles as $rid => $value) {
        // Add role to roles array if it isn't already there.
        if ($value != 0 && !array_key_exists($rid, $form['account']['roles'])) {
          $form['account']['roles'][$rid] = array(
            '#type' => 'checkbox',
            '#title' => check_plain($user_roles[$rid]),
            '#default_value' => TRUE,
            '#disabled' => (user_access('administer users') ? FALSE : TRUE),
          );
        }
      }
    }

    // Attach the profile fields via profile2.
    // @todo Add test for AJAX fields after profile2_attach_form() has been called.
    profile2_attach_form($form, $form_state);
  }
}

/**
 * @file
 * Builds profile-specific register, login, and password forms.
 */

/**
 * Page callback: Displays a user login form.
 *
 * Path: [path-setting]/login
 *
 * @param object $regpath
 *   Object containing single row from profile2_regpath_get_profiles() database
 *   result.
 *
 * @see profile2_regpath_menu()
 */
function _profile2_regpath_user_login($regpath) {
  module_load_include('pages.inc', 'user', 'user');
  $output = user_page();
  _profile2_regpath_set_title($regpath, 'login_title');
  return $output;
}

/**
 * Page callback: Displays a user registration form.
 *
 * Path: [path-setting]/register
 *
 * @param object object $regpath
 *   Object containing single row from profile2_regpath_get_profiles() database
 *   result.
 *
 * @see profile2_regpath_menu()
 */
function _profile2_regpath_user_register($regpath) {
  module_load_include('pages.inc', 'user', 'user');
  $output = drupal_get_form('user_register_form');
  _profile2_regpath_set_title($regpath, 'register_title');
  return $output;
}

/**
 * Page callback: Displays the forgot password form.
 *
 * Path: [path-setting]/password
 *
 * @param object $regpath
 *   Object containing single row from profile2_regpath_get_profiles() database
 *   result.
 *
 * @see profile2_regpath_menu()
 */
function _profile2_regpath_user_password($regpath) {
  module_load_include('pages.inc', 'user', 'user');
  $output = drupal_get_form('user_pass');
  _profile2_regpath_set_title($regpath, 'password_title');
  return $output;
}

/**
 * Sets page title for registration, login, and forgot password pages.
 *
 * @param object $regpath
 *   Object containing single row from profile2_regpath_get_profiles() database
 *   result.
 *
 * @param string $key
 *   Array key for 'misc' array. This will determine the title settings.
 */
function _profile2_regpath_set_title($regpath, $key) {
  // Look for custom title in foremost profile, according to weight.
  if (isset($regpath->misc) && $misc = unserialize($regpath->misc)) {
    if (array_key_exists($key, $misc)) {
      $title = $misc[$key];
    }
  }
  // Set default title.
  else {
    $title = 'User account';
  }
  // Set title. See http://drupal.org/node/1800116 for use of t().
  drupal_set_title(t($title));
}

