<?php

/**
 * implementation of hook_panels_layouts
 */
// Plugin definition
$plugin = array(
  'title' => t('katalog'),
  'category' => t('my own'),
  'icon' => 'katalog.png',
  'theme' => 'katalog',
  'theme arguments' => array('id', 'content'),
  'css' => 'katalog.css',
  'regions' => array(
      'bann' => t('Banner - top'),
      'lptopform' => t('Top - form'),
    'middle' => t('Middle column'),
  ),
);

