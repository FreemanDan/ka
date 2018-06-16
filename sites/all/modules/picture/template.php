<?php

/**
 * @file
 * template.php
 */
function lanpag_form_alter(&$form, &$form_state, $form_id) {
    if ($form_id == 'webform_client_form_11') {
		//dpm($_REQUEST);
	if (array_key_exists('event', $_REQUEST)) {
		
			$event_name = $_REQUEST['event'];
			$form['submitted']['event']['#default_value'] =  $event_name;
	}
        $form['submitted']['imya']['#size'] = FALSE;
        $form['submitted']['telefon']['#size'] = FALSE;
        $form['submitted']['e_mail']['#size'] = FALSE;
        
        //$form['#attributes']['onsubmit'][] = 'yaCounter36494045.reachGoal(\'REGISTR\'); return true;'; //Метрика
         
        $form['submitted']['imya']['#attributes']['placeholder'] = 'Введите имя';
        $form['submitted']['e_mail']['#attributes']['placeholder'] = 'Введите e-mail';
        $form['submitted']['telefon']['#attributes']['placeholder'] = 'Введите телефон';
		
        $form['actions']['submit']['#attributes'] = array(
		'class' => array('btn-lg', 'btn-danger', 'btn-block1', 'col-md-3', 'col-sm-6', 'col-xs-12'),
		'onclick' => array('yaCounter36494045.reachGoal(\'REGISTR\'); return true;') //Метрика
		);
    }
}

/**
 * Implementation of hook_preprocess_page().
 */
function lanpag_preprocess_page(&$variables) {
  // if this is a panel page, add template suggestions
  if($panel_page = page_manager_get_current_page()) {
    // add a generic suggestion for all panel pages
    $variables['theme_hook_suggestions'][] = 'page__panel';

    // add the panel page machine name to the template suggestions
    $variables['theme_hook_suggestions'][] = 'page__' . $panel_page['name'];

    //add a body class for good measure
    $body_classes[] = 'page-panel';
  }
}
function lanpag__preprocess_image(&$variables) {
    // If this image is of the type 'thumbnail' then assign additional classes to it:
	    if (isset($variables['style_name']) && $variables['style_name'] == '3col-264wide') {
        //$variables['attributes']['class'][] = 'katalog-medium';
        unset($variables['attributes']['width']);
        unset($variables['attributes']['height']);
        unset($variables['width']);
        unset($variables['height']);
    }
}
