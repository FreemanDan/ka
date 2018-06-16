<?php

/**
 * implementation of hook_panels_layouts
 */
// Plugin definition
$plugin = array(
    'title' => t('katalogbt1'),
    'category' => t('my own'),
    'icon' => 'katalogbt1.png',
    'theme' => 'katalogbt1',
    'theme arguments' => array('id', 'content'),
    'css' => 'katalogbt1.css',
    'regions' => array(
        'left' => t('left'),
        'righttop' => t('RightTop'),
        'lpbann' => t('lpbann'),
        'right' => t('right'),
        'bottom' => t('bottom'),
    ),
);

