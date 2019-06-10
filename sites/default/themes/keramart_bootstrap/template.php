<?php

/**
 * @file
 * template.php
 */
/* * function keramart_bootstrap_menu_link__menu_block(array $variables) {
  $element = $variables['element'];
  $sub_menu = '';

  if ($element['#below']) {
  $sub_menu = drupal_render($element['#below']);
  }
  $output = l($element['#title'], $element['#href'], $element['#localized_options']);
  return '<li' . drupal_attributes($element['#attributes']) . '>' . $output . $sub_menu . "</li>\n";
  }
 */


function keramart_bootstrap_menu_tree__menu_top_menu(&$variables) {
    return '<ul class="menu nav navbar-nav menu-top-menu">' . $variables['tree'] . '</ul>';
}

function keramart_bootstrap_menu_tree__menu_block__2(&$variables) {
    return '<ul class="menu nav nav-tabs">' . $variables['tree'] . '</ul>';
}

function keramart_bootstrap_menu_tree__menu_block__3(&$variables) {
    return '<ul class="menu nav nav-tabs">' . $variables['tree'] . '</ul>';
}

function keramart_bootstrap_preprocess_page(&$vars) {
    drupal_add_library('system', 'ui.dialog');

    // if this is a panel page, add template suggestions
    if ($panel_page = page_manager_get_current_page()) {
        //print $panel_page['name'];
        // add a generic suggestion for all panel pages
        $vars['theme_hook_suggestions'][] = 'page__panels';
        // add the panel page machine name to the template suggestions
            $variables['theme_hook_suggestions'][] = 'page__' . $panel_page['name'];
        /*$object = $panel_page['contexts']['argument_entity_id:node_1'];
        $result_array = get_object_vars($object);
        $value = $result_array['restrictions']['type']['0'];
        if ($panel_page['name'] == 'node_view' AND $value == 'product') {
            $vars['theme_hook_suggestions'][] = 'page__node_view_product';
        }
        if ($panel_page['name'] == 'node_view' AND $value == 'artist') {
            $vars['theme_hook_suggestions'][] = 'page__node_view_artist';
        }*/
    }
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
    if (isset($variables['style_name']) && $variables['style_name'] == 'katalog-kategory') {
        $variables['attributes']['class'][] = 'katalog-kategory';
        $variables['attributes']['class'][] = 'catalog_img_kategory img-responsive';
        unset($variables['attributes']['width']);
        unset($variables['attributes']['height']);
        unset($variables['width']);
        unset($variables['height']);
    }
    if (isset($variables['style_name']) && $variables['style_name'] == 'katalog-w280') {
        //$variables['attributes']['class'][] = 'katalog-w280';
        $variables['attributes']['class'][] = 'katalog-w280 img-responsive';
        unset($variables['attributes']['width']);
        unset($variables['attributes']['height']);
        unset($variables['width']);
        unset($variables['height']);
    }
    if (isset($variables['style_name']) && $variables['style_name'] == '100x100_kvadrat') {
        unset($variables['attributes']['class']);
        $variables['attributes']['class'][] = 'media-object';
        //unset($variables['attributes']['width']);
        //unset($variables['attributes']['height']);
        //unset($variables['width']);
        //unset($variables['height']);
    }
    if (isset($variables['style_name']) && $variables['style_name'] == '-partner-logo') {
        unset($variables['attributes']['class']);
        $variables['attributes']['class'][] = 'center-block';
        unset($variables['attributes']['width']);
        unset($variables['attributes']['height']);
        unset($variables['width']);
        unset($variables['height']);
    }
    if (isset($variables['style_name']) && $variables['style_name'] == 'banner-wide-1140X216') {
        //$variables['attributes']['class'][] = 'katalog-w280';
        $variables['attributes']['class'][] = 'banner-wide-1140X216 img-responsive';
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
        $form['actions']['submit']['#attributes'] = array(
            'class' => array('btn-lg', 'btn-danger',)
        );
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

function keramart_bootstrap_field__field_tax_image_category($variables) {
    $output = '';
    //dvm($variables);
    //Render the label, if it's not hidden.
    if (!$variables['label_hidden']) {
        $output .= '<div class="field-label"' . $variables['title_attributes'] . '>' . $variables['label'] . ':&nbsp;</div>';
    }

    // Render the items.
    //$output .= '<div class="field-items"' . $variables['content_attributes'] . '>';
    foreach ($variables['items'] as $delta => $item) {
        // Generate styled background image.
        $img_uri = $item['#item']['uri'];
        $img_style = $item['#image_style'];
        $item_path = url($item['#path']['path']);
        $bg_img = image_style_url($img_style, $img_uri);
        $classes = 'field-item ' . ($delta % 2 ? 'odd' : 'even');
        //  Write image url to inline css and drop the rendered item.
        $output .= '<a class="kategorii-img"' . $variables['item_attributes'][$delta] . ' style="background-image:url(' . $bg_img . ')" href="' . $item_path . '"></a>';
    }
    //$output .= '</div>';
    // Render the top-level DIV.
    //$output = '<div class="' . $variables['classes'] . '"' . $variables['attributes'] . '>' . $output . '</div>';

    return $output;
    //return $bg_img;
}


function keramart_bootstrap_preprocess_region(&$variables) { //даем дополнительные классы регионам

   if ($variables['region'] == "top_menu") {
    $variables['classes_array'][] = "regiontopmenu";
   }

  }