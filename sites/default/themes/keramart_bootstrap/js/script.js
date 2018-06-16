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

  Drupal.behaviors.keramart_bootstrap= {
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
          modal: true,
        });
 
        // По клику на ссылку логина - показываем попап.
        $('.user-login', context).click(function() {
          $login_block.dialog('open');
          return false;
        });
 
      }
	if ($conact_block.length > 0) {
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
          
         /*ссылки с LP*/
        // $("li").find("a").attr('target','blank');
        /*Navigator*/
	$('a[href^="#"]').click(function () {
		var target = $(this).attr('href');
		$('html, body').animate({
			scrollTop: ($(target).offset().top)
		}, 800);
		return false;
	});
	/*phone maska*/
	jQuery(function($){
		$('input[name="submitted[telefon]"]').mask("+7 (999) 999-99-99");
	});



	$('#portfolio').find('ul li' ).click(function(){
		$('#portfolio').find('ul .active' ).removeClass('active');
		$(this).addClass('active');
		var elem =  ($(this).index("li"));
		elem = elem - 5;
		$("#portfolio").find(".porfolio_block > div" ).fadeOut(600);
		setTimeout(function(){
			$("#portfolio" ).find(".porfolio_block_slider_" + elem ).fadeIn();
		}, 600);
	});
	$(window ).load(function(){
		//$("#portfolio").find(".porfolio_block > div" ).css({
		//	"display": "none"
		//});
		//$("#portfolio").find(".porfolio_block_slider_1" ).css({
		//	"display": "block"
		//});
		//$("body" ).css('opacity', "1");
	});
        

})(jQuery);
