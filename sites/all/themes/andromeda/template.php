<?php
// $Id$

/**
 * Add body classes if certain regions have content.
 */
function andromeda_preprocess_html(&$variables) {
  
  $settings = variable_get('theme_andromeda_settings', '') ? variable_get('theme_andromeda_settings', '') : array();
  
  $variables['classes_array'] = array();
  
  if ($variables['is_front']) {
    $variables['classes_array'][] = 'front';
  } else {
    $variables['classes_array'][] = 'not-front';
  }
  
  if (!empty($variables['page']['banner'])) {
    $variables['classes_array'][] = 'with-banner';
  }
  
  if (!empty($variables['page']['sidebar'])) {
    $variables['classes_array'][] = 'with-sidebar';
  }
  
  if (!empty($settings['toggle_slogan'])) {
    $variables['classes_array'][] = 'with-slogan';
  }
  
  if (theme_get_setting('andromeda_show_front_page_title') && $variables['is_front']) {
    $variables['classes_array'][] = 'with-front-page-title';
  }
  
  if(theme_get_setting('andromeda_logo_position') == 'right') {
    $variables['classes_array'][] = 'logo-on-right';
  }
  
  $variables['classes_array'][] = 'wrapper-body';
}

/**
 * Override or insert variables into the block templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("block" in this case.)
 */
function andromeda_preprocess_block(&$variables, $hook) {
  // Classes describing the position of the block within the region.
  if ($variables['block_id'] == 1) {
    $variables['classes_array'][] = 'first';
  }
  // The last_in_region property is set in zen_page_alter().
  if (isset($variables['block']->last_in_region)) {
    $variables['classes_array'][] = 'last';
  }
  $variables['classes_array'][] = $variables['block_zebra'];

  $variables['title_attributes_array']['class'][] = 'block-title';
}

/**
 * Override or insert variables into the node templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered.
 */
function andromeda_preprocess_node(&$variables) {
  switch ($variables['type']) {
    case 'blog' :
      if (array_key_exists('field_blog_picture', $variables['content'])) {
        $variables['classes_array'][] = 'node-blog-has-picture';
      }
      $variables['title_classes'] = 'node-title';
      break;
  }
}

/**
 * Implementation of hook_page_alter()
 */
function andromeda_page_alter(&$page) {
  //match pages and unset sidebar on matched pages
  $pages = drupal_strtolower(theme_get_setting('andromeda_sidebar_visibility'));
  $path = drupal_strtolower(drupal_get_path_alias($_GET['q']));
  if (drupal_match_path($path, $pages)) {
    unset($page['sidebar']);
  }
}

/**
 * Implementation of template_preprocess_search_block_form()
 */
function andromeda_preprocess_search_block_form(&$variables) {
  $variables['show_follow_links'] = theme_get_setting('andromeda_show_follow_links');
}