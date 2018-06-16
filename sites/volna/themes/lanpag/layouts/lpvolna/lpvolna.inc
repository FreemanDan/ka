<?php

/**
 * implementation of hook_panels_layouts
 */
// Plugin definition
$plugin = array(
    'title' => t('lpvolna'),
    'category' => t('my own'),
    'icon' => 'lpvolna.png',
    'theme' => 'lpvolna',
    'theme arguments' => array('id', 'content'),
    'css' => 'lpvolna.css',
    'regions' => array(
        'screen1' => t('Screen1'),
        'screen2' => t('Screen2'),
        'screen3' => t('Screen3'),
    ),
);

