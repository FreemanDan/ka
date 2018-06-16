<?php
/**
 * @file
 * Contains the theme's functions to manipulate Drupal's default markup.
 *
 * A QUICK OVERVIEW OF DRUPAL THEMING
 *
 *   The default HTML for all of Drupal's markup is specified by its modules.
 *   For example, the comment.module provides the default HTML markup and CSS
 *   styling that is wrapped around each comment. Fortunately, each piece of
 *   markup can optionally be overridden by the theme.
 *
 *   Drupal deals with each chunk of content using a "theme hook". The raw
 *   content is placed in PHP variables and passed through the theme hook, which
 *   can either be a template file (which you should already be familiary with)
 *   or a theme function. For example, the "comment" theme hook is implemented
 *   with a comment.tpl.php template file, but the "breadcrumb" theme hooks is
 *   implemented with a theme_breadcrumb() theme function. Regardless if the
 *   theme hook uses a template file or theme function, the template or function
 *   does the same kind of work; it takes the PHP variables passed to it and
 *   wraps the raw content with the desired HTML markup.
 *
 *   Most theme hooks are implemented with template files. Theme hooks that use
 *   theme functions do so for performance reasons - theme_field() is faster
 *   than a field.tpl.php - or for legacy reasons - theme_breadcrumb() has "been
 *   that way forever."
 *
 *   The variables used by theme functions or template files come from a handful
 *   of sources:
 *   - the contents of other theme hooks that have already been rendered into
 *     HTML. For example, the HTML from theme_breadcrumb() is put into the
 *     $breadcrumb variable of the page.tpl.php template file.
 *   - raw data provided directly by a module (often pulled from a database)
 *   - a "render element" provided directly by a module. A render element is a
 *     nested PHP array which contains both content and meta data with hints on
 *     how the content should be rendered. If a variable in a template file is a
 *     render element, it needs to be rendered with the render() function and
 *     then printed using:
 *       <?php print render($variable); ?>
 *
 * ABOUT THE TEMPLATE.PHP FILE
 *
 *   The template.php file is one of the most useful files when creating or
 *   modifying Drupal themes. With this file you can do three things:
 *   - Modify any theme hooks variables or add your own variables, using
 *     preprocess or process functions.
 *   - Override any theme function. That is, replace a module's default theme
 *     function with one you write.
 *   - Call hook_*_alter() functions which allow you to alter various parts of
 *     Drupal's internals, including the render elements in forms. The most
 *     useful of which include hook_form_alter(), hook_form_FORM_ID_alter(),
 *     and hook_page_alter(). See api.drupal.org for more information about
 *     _alter functions.
 *
 * OVERRIDING THEME FUNCTIONS
 *
 *   If a theme hook uses a theme function, Drupal will use the default theme
 *   function unless your theme overrides it. To override a theme function, you
 *   have to first find the theme function that generates the output. (The
 *   api.drupal.org website is a good place to find which file contains which
 *   function.) Then you can copy the original function in its entirety and
 *   paste it in this template.php file, changing the prefix from theme_ to
 *   STARTERKIT_. For example:
 *
 *     original, found in modules/field/field.module: theme_field()
 *     theme override, found in template.php: STARTERKIT_field()
 *
 *   where STARTERKIT is the name of your sub-theme. For example, the
 *   zen_classic theme would define a zen_classic_field() function.
 *
 *   Note that base themes can also override theme functions. And those
 *   overrides will be used by sub-themes unless the sub-theme chooses to
 *   override again.
 *
 *   Zen core only overrides one theme function. If you wish to override it, you
 *   should first look at how Zen core implements this function:
 *     theme_breadcrumbs()      in zen/template.php
 *
 *   For more information, please visit the Theme Developer's Guide on
 *   Drupal.org: http://drupal.org/node/173880
 *
 * CREATE OR MODIFY VARIABLES FOR YOUR THEME
 *
 *   Each tpl.php template file has several variables which hold various pieces
 *   of content. You can modify those variables (or add new ones) before they
 *   are used in the template files by using preprocess functions.
 *
 *   This makes THEME_preprocess_HOOK() functions the most powerful functions
 *   available to themers.
 *
 *   It works by having one preprocess function for each template file or its
 *   derivatives (called theme hook suggestions). For example:
 *     THEME_preprocess_page    alters the variables for page.tpl.php
 *     THEME_preprocess_node    alters the variables for node.tpl.php or
 *                              for node--forum.tpl.php
 *     THEME_preprocess_comment alters the variables for comment.tpl.php
 *     THEME_preprocess_block   alters the variables for block.tpl.php
 *
 *   For more information on preprocess functions and theme hook suggestions,
 *   please visit the Theme Developer's Guide on Drupal.org:
 *   http://drupal.org/node/223440 and http://drupal.org/node/1089656
 */


/**
 * Override or insert variables into the maintenance page template.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("maintenance_page" in this case.)
 */
/* -- Delete this line if you want to use this function
function STARTERKIT_preprocess_maintenance_page(&$variables, $hook) {
  // When a variable is manipulated or added in preprocess_html or
  // preprocess_page, that same work is probably needed for the maintenance page
  // as well, so we can just re-use those functions to do that work here.
  STARTERKIT_preprocess_html($variables, $hook);
  STARTERKIT_preprocess_page($variables, $hook);
}
// */

/**
 * Override or insert variables into the html templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("html" in this case.)
 */
/* -- Delete this line if you want to use this function
function STARTERKIT_preprocess_html(&$variables, $hook) {
  $variables['sample_variable'] = t('Lorem ipsum.');

  // The body tag's classes are controlled by the $classes_array variable. To
  // remove a class from $classes_array, use array_diff().
  //$variables['classes_array'] = array_diff($variables['classes_array'], array('class-to-remove'));
}
// */

/**
 * Override or insert variables into the page templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("page" in this case.)
 */
/* -- Delete this line if you want to use this function
function STARTERKIT_preprocess_page(&$variables, $hook) {
  $variables['sample_variable'] = t('Lorem ipsum.');
}
// */

/**
 * Override or insert variables into the node templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("node" in this case.)
 */
/* -- Delete this line if you want to use this function
function STARTERKIT_preprocess_node(&$variables, $hook) {
  $variables['sample_variable'] = t('Lorem ipsum.');

  // Optionally, run node-type-specific preprocess functions, like
  // STARTERKIT_preprocess_node_page() or STARTERKIT_preprocess_node_story().
  $function = __FUNCTION__ . '_' . $variables['node']->type;
  if (function_exists($function)) {
    $function($variables, $hook);
  }
}
// */

/**
 * Override or insert variables into the comment templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("comment" in this case.)
 */
/* -- Delete this line if you want to use this function
function STARTERKIT_preprocess_comment(&$variables, $hook) {
  $variables['sample_variable'] = t('Lorem ipsum.');
}
// */

/**
 * Override or insert variables into the region templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("region" in this case.)
 */
/* -- Delete this line if you want to use this function
function STARTERKIT_preprocess_region(&$variables, $hook) {
  // Don't use Zen's region--sidebar.tpl.php template for sidebars.
  //if (strpos($variables['region'], 'sidebar_') === 0) {
  //  $variables['theme_hook_suggestions'] = array_diff($variables['theme_hook_suggestions'], array('region__sidebar'));
  //}
}
// */

/**
 * Override or insert variables into the block templates.
 *
 * @param $variables
 *   An array of variables to pass to the theme template.
 * @param $hook
 *   The name of the template being rendered ("block" in this case.)
 */
/* -- Delete this line if you want to use this function
function STARTERKIT_preprocess_block(&$variables, $hook) {
  // Add a count to all the blocks in the region.
  // $variables['classes_array'][] = 'count-' . $variables['block_id'];

  // By default, Zen will use the block--no-wrapper.tpl.php for the main
  // content. This optional bit of code undoes that:
  //if ($variables['block_html_id'] == 'block-system-main') {
  //  $variables['theme_hook_suggestions'] = array_diff($variables['theme_hook_suggestions'], array('block__no_wrapper'));
  //}
}
// */


function keramart_preprocess_block(&$vars) {
   global $user;

    drupal_add_library('system', 'ui.dialog');
	
    // Добавляем ссылку, при нажатии на которую будет показываться логин
    $vars['login_button'] = l(t('Login'), 'user', array('attributes' => array('class' => array('user-login'))));
	// добавляем переменную "кнопка отправки заявки"
	$vars['contact_button'] = l(t('Contact us'), 'node/642', array('attributes' => array('class' => array('contactus-form'), 'id' => array('send-order-btm-block'))));
}
function keramart_preprocess_page(&$vars, $hook) {
	// добавляем переменную "кнопка отправки заявки"
	$vars['contact_button'] = l(t('Send order'), 'node/642', array('attributes' => array('class' => array('contactus-form'), 'id' => array('send-order-btm-top'))));

	if (isset($vars['node'])) {
	// If the node type is "blog" the template suggestion will be "page--blog.tpl.php".
	$vars['theme_hook_suggestions'][] = 'page__'. str_replace('_', '--', $vars['node']->type);
		}
		}

function keramart_form_alter(&$form, &$form_state, $form_id) {
	//отключаем визуальный редактор при редактировании некоторых блоков
	if ($form_id == 'block_admin_configure' && $form['delta']['#value'] == '5') { 
		$form['settings']['body_field']['body']['#wysiwyg'] = FALSE;
	} 
	if ($form_id == 'taxonomy_form_term'   &&  $form['vocabulary_machine_name']['#value'] == 'prise_groups' ) {
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
		if ( $current_object = menu_get_object()) {
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
	 
	 
	 if ($form_id == 'webform_client_form_642') { 
	// this is for your developer information and shows you the
  // structure of the form array
  //dpm($form);
  // this will give you the details for my_form_component
 // $a_component = $form['submitted'];
 // dpm($a_component);
  if ( $current_object = menu_get_object()) {
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
    $form['#attributes']['onsubmit'] = "if(this.search_block_form.value=='Search'){ alert('Please enter a search'); return false; }";  */

    // Alternative (HTML5) placeholder attribute instead of using the javascript
    $form['search_block_form']['#attributes']['placeholder'] = t('Search');
  }
} 
function keramart_preprocess_image(&$variables) {
  // If this image is of the type 'thumbnail' then assign additional classes to it:
    if (isset($variables['style_name']) && $variables['style_name'] == 'katalog-w280') {
        $variables['attributes']['class'][] = 'catalog_img_wiev';
        //$variables['attributes']['class'][] = 'yourclass';
        unset($variables['attributes']['width']);
		unset($variables['attributes']['height']);
    }
    if (isset($variables['style_name']) && $variables['style_name'] == 'katalog-kategory') {
        $variables['attributes']['class'][] = 'catalog_img_kategory';
        //$variables['attributes']['class'][] = 'yourclass';
        unset($variables['attributes']['width']);
		unset($variables['attributes']['height']);
    }
}

function keramart_preprocess_html(&$variables) {
    // Add the Page's Parent Menu Item as body class
    $menuParent = menu_get_active_trail();
    // Assign $menuParent to top or "hero" menu item then strip all spaces (replaces them with hyphens) and remove all special characters
    if (isset($menuParent[2]['link_title'])) {  
    $menuParent = drupal_html_class($menuParent[2]['link_title']); 
    $menuParent = transliteration_get($menuParent);
    $variables['classes_array'][] = $menuParent; }
}

function keramart_field__field_tax_image_category($variables) {
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

function keramart_preprocess_node(&$variables, $hook) {
  $breadcrumb = menu_get_active_breadcrumb();
 
  if(is_array($breadcrumb) && $breadcrumb ){
    # Uncomment the following line if your breadcrumb ends with the current page's title.
    #$ignore_this = array_pop($breadcrumb);
    $last_crumb = array_pop($breadcrumb);
  }
  $variables['backlink'] = t('Back to !link', array('!link' => $last_crumb));
}

