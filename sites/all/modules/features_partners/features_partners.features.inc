<?php
/**
 * @file
 * features_partners.features.inc
 */

/**
 * Implements hook_views_api().
 */
function features_partners_views_api($module = NULL, $api = NULL) {
  return array("api" => "3.0");
}

/**
 * Implements hook_node_info().
 */
function features_partners_node_info() {
  $items = array(
    'partners' => array(
      'name' => t('Партнеры'),
      'base' => 'node_content',
      'description' => t('Партнеры проекта'),
      'has_title' => '1',
      'title_label' => t('Title'),
      'help' => '',
    ),
  );
  drupal_alter('node_info', $items);
  return $items;
}
