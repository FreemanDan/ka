<?php

/**
 * implementation of hook_panels_layouts
 */
// Plugin definition
$plugin = array(
    'title' => t('screens'),
    'category' => t('my own'),
    'icon' => 'screens.png',
    'theme' => 'screens',
    'theme arguments' => array('id', 'content'),
    'css' => 'screens.css',
    'regions' => array(
        'screen-1' => t('Screen-1'),
        'screen-2' => t('Screen-2'),
        'screen-3' => t('Screen-3'),
        'screen-4' => t('Screen-4'),
        'screen-5' => t('Screen-5'),
        'screen-6' => t('Screen-6'),
        'screen-7' => t('Screen-7'),
        'screen-8' => t('Screen-8'),
        'screen-9' => t('Screen-9'),
    ),
);

