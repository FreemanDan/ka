/**
 * @file
 * A JavaScript file for the theme.
 *
 * In order for this JavaScript to be loaded on pages, see the instructions in
 * the README.txt next to this file.
 */

// JavaScript should be made compatible with libraries other than jQuery by
// wrapping it with an "anonymous closure". See:
// - http://drupal.org/node/1446420
// - http://www.adequatelygood.com/2010/3/JavaScript-Module-Pattern-In-Depth
(function ($) {

  Drupal.behaviors.themeName= {
    attach:function (context, settings) {
 
      // Здесь указываем ID блока с логином.
      var $login_block = $('#block-user-login');
	  var $conact_block = $('#block-webform-client-block-642');
	  var $callback_block = $('#block-webform-client-block-658');
      if ($login_block.length > 0) {
	$('#login_spl').hide();
        // Далее при загрузке страницы
        // блок переместится в попап, но не покажется.
        $login_block.dialog({
          autoOpen: false,
          title: Drupal.t('Login'),
          resizable: false,
          modal: true
        });
 
        // По клику на ссылку логина - показываем попап.
        $('.user-login', context).click(function() {
          $login_block.dialog('open');
          return false;
        });
 
      }
	if ($conact_block.length > 0) {
 $('#client-block-642_spl').hide();
        // Далее при загрузке страницы
        // блок переместится в попап, но не покажется.
        $conact_block.dialog({
          autoOpen: false,
          title: Drupal.t('Contact us'),
          resizable: false,
          modal: true
        });
 
        // По клику на ссылку логина - показываем попап.
        $('.contactus-form', context).click(function() {
          $conact_block.dialog('open');
          return false;
        });
 
      }
	  // -------всплывающий диалог - заказ обратного звонка
	  if ($callback_block.length > 0) {
 $('#client-block-658_spl').hide();
        // Далее при загрузке страницы
        // блок переместится в попап, но не покажется.
        $callback_block.dialog({
          autoOpen: false,
          title: Drupal.t('Call Back'),
          resizable: false,
          modal: true
        });
 
        // По клику на ссылку логина - показываем попап.
        $('.callback-form', context).click(function() {
          $callback_block.dialog('open');
          return false;
        });
 
      }
	  
    }
  };
 
  
})(jQuery);
