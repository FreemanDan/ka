<?php

/**
 * @file
 * template.php
 */
function keramart_bootstrap_preprocess_page(&$vars) {
    drupal_add_library('system', 'ui.dialog');
}

function keramart_bootstrap_preprocess_image(&$variables) {
    // If this image is of the type 'thumbnail' then assign additional classes to it:
    if (isset($variables['style_name']) && $variables['style_name'] == 'katalog-medium') {
//        print_r($variables);
        $variables['attributes']['class'][] = 'katalog-medium';
        unset($variables['attributes']['width']);
        unset($variables['attributes']['height']);
        unset($variables['width']);
        unset($variables['height']);
    }
    if (isset($variables['style_name']) && $variables['style_name'] == 'katalog-medium') {
        $variables['attributes']['class'][] = 'katalog-medium';
        unset($variables['attributes']['width']);
        unset($variables['attributes']['height']);
        unset($variables['width']);
        unset($variables['height']);
    }
}

function keramart_bootstrap_form_alter(&$form, &$form_state, $form_id) {
    //отключаем визуальный редактор при редактировании некоторых блоков
    if ($form_id == 'block_admin_configure' && $form['delta']['#value'] == '5') {
        $form['settings']['body_field']['body']['#wysiwyg'] = FALSE;
    }
    if ($form_id == 'taxonomy_form_term' && $form['vocabulary_machine_name']['#value'] == 'prise_groups') {
        $form['description']['#wysiwyg'] = FALSE;
        $form['description']['#format'] = 'plain_text';
    }
    // Настраиваем блок со спецпредложением - форма заказа
    if ($form_id == 'webform_client_form_635') {
        // this is for your developer information and shows you the
        // structure of the form array
        //dpm($form);
        // this will give you the details for my_form_component
        // $a_component = $form['submitted'];
        // dpm($a_component);
        if ($current_object = menu_get_object()) {
            $object_title = $current_object->title;
            $form['submitted']['stranica']['#default_value'] = $object_title;
        }
        //drupal_set_message($object_title, 'status', $repeat = true);
        $form['submitted']['costumer_name']['#size'] = FALSE;
        $form['submitted']['e_mail']['#size'] = FALSE;
        $form['submitted']['telefon']['#size'] = FALSE;
        $form['submitted']['stranica']['#size'] = FALSE;
        $form['submitted']['costumer_name']['#attributes']['placeholder'] = 'Введите имя';
        $form['submitted']['e_mail']['#attributes']['placeholder'] = 'Введите e-mail';
        $form['submitted']['telefon']['#attributes']['placeholder'] = 'Введите телефон';
        $form['#attributes']['onsubmit'][] = 'yaCounter823781.reachGoal(\'ORDFRMS\'); return true;'; //Метрика
    }

    if ($form_id == 'webform_client_form_658') {
        $form['submitted']['vashe_imya']['#size'] = FALSE;
        $form['submitted']['telefon']['#size'] = FALSE;
        $form['submitted']['vashe_imya']['#attributes']['placeholder'] = 'Введите имя';
        $form['submitted']['telefon']['#attributes']['placeholder'] = 'Введите телефон';
        $form['#attributes']['onsubmit'][] = 'yaCounter823781.reachGoal(\'CALLBACK\'); return true;'; //Метрика
    }
    if ($form_id == 'webform_client_form_688') {
        $form['submitted']['imya']['#size'] = FALSE;
        $form['submitted']['e_mail']['#size'] = FALSE;
        $form['submitted']['telefon']['#size'] = FALSE;
        $form['submitted']['imya']['#attributes']['placeholder'] = 'Введите имя';
        $form['submitted']['e_mail']['#attributes']['placeholder'] = 'Введите e-mail';
        $form['submitted']['telefon']['#attributes']['placeholder'] = 'Введите телефон';
        $form['submitted']['soobshchenie']['#size'] = FALSE;
        $form['submitted']['soobshchenie']['#attributes']['placeholder'] = 'Ваше сообщение';
        $form['#attributes']['onsubmit'][] = 'yaCounter823781.reachGoal(\'PRFRM\'); return true;'; //Метрика
    }

    if ($form_id == 'webform_client_form_642') {
        // this is for your developer information and shows you the
        // structure of the form array
        //dpm($form);
        // this will give you the details for my_form_component
        // $a_component = $form['submitted'];
        // dpm($a_component);
        if ($current_object = menu_get_object()) {
            $object_title = $current_object->title;
            $form['submitted']['stranica']['#default_value'] = $object_title;
        }
        $form['submitted']['webf_zak_name']['#size'] = FALSE;
        $form['submitted']['webf_zak_email']['#size'] = FALSE;
        $form['submitted']['telefon']['#size'] = FALSE;
        $form['submitted']['soobshchenie']['#size'] = FALSE;
        $form['submitted']['webf_zak_name']['#attributes']['placeholder'] = 'Ваше имя *';
        $form['submitted']['webf_zak_email']['#attributes']['placeholder'] = 'E-mail';
        $form['submitted']['telefon']['#attributes']['placeholder'] = 'Телефон';
        $form['submitted']['coobshchenie']['#attributes']['placeholder'] = 'Ваше сообщение (необязательно)';
        $form['#attributes']['onsubmit'][] = 'yaCounter823781.reachGoal(\'ORDFRM\'); return true;'; //Метрика
    }
    // настраиваем форму поиска -------------------------------
    if ($form_id == 'search_block_form') {
        $form['search_block_form']['#title'] = FALSE; // Change the text on the label element
        //$form['search_block_form']['#title_display'] = 'invisible'; // Toggle label visibilty
        $form['search_block_form']['#size'] = FALSE;  // define size of the textfield
        //$form['search_block_form']['#default_value'] = t('Search'); // Set a default value for the textfield
        //$form['actions']['submit']['#value'] = t('GO!'); // Change the text on the submit button
        //$form['actions']['submit'] = array('#type' => 'image_button', '#src' => base_path() . path_to_theme() . '/images/search-button.png');
        // Add extra attributes to the text box
        /*     $form['search_block_form']['#attributes']['onblur'] = "if (this.value == '') {this.value = 'Search';}";
          $form['search_block_form']['#attributes']['onfocus'] = "if (this.value == 'Search') {this.value = '';}";
          // Prevent user from searching the default text
          $form['#attributes']['onsubmit'] = "if(this.search_block_form.value=='Search'){ alert('Please enter a search'); return false; }"; */

        // Alternative (HTML5) placeholder attribute instead of using the javascript
        $form['search_block_form']['#attributes']['placeholder'] = t('Search');
    }
}
