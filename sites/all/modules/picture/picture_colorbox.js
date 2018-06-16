(function ($) {
  Drupal.behaviors.pictureLazyloadPictures = {
    attach: function (context, settings) {
      $(context).bind('cbox_load', function () {
        var href = $.colorbox.element()[0].hash;

		        // Calculate the border and interface sizes of the colorbox.
        var borderWidth = $('#cboxMiddleLeft').width() + $('#cboxMiddleRight').width();
        var borderHeight = $('#cboxTopCenter').height() + $('#cboxBottomCenter').height();
        var interfaceHeight = parseInt($('#cboxLoadedContent').css('marginBottom'));
		
        if (href.search('#picture-colorbox-') === 0) {
          href = '.' + href.substr(1, href.length) + ', #' + href.substr(1, href.length);
        }


        // Lazyload picture element when relevant.
        $('span[lazyload]', $(href)).replaceWith(function () {
          // Only execute if there are elements to process.
          if (this.length == 0) {return;}


          var picture = $('<div>').append($(this).clone()).html();

          picture = picture.replace(/<span/ig, '<picture');
          picture = picture.replace(/<\/span>/ig, '</picture>');
          picture = picture.replace(/ data-srcset="/ig, ' srcset="');


          return $(picture).attr('lazyload', null);
        });
        $('picture img', $(href)).load(function() {
          var image = this;
          // Occasionally the load event is called before the image is fully
          // loaded. Let's wait a little while and hope the image gets loaded
          // in the meantime. If it doesn't we just have to accept a wrongly
          // sized colorbox.
          setTimeout(function(){
            var width = image.width;
            var height = image.height;

            // Calculate aspect ratio, max height and max width of the image.
            var aspect = width / height;
            var maxWidth = $(window).width() - borderWidth;
            var maxHeight = $(window).height() - borderHeight - interfaceHeight;

            // If the image is too wide, resize it to fit screen width.
            if (width > maxWidth){
              width = maxWidth;
              height = Math.floor(width / aspect);
            }
            // If the image is (still) too high, resize it to fit screen height.
            if (height > maxHeight) {
              height = maxHeight;
              width = Math.floor(height * aspect);
            }

            $.colorbox.resize({innerHeight: height, innerWidth: width});
          }, 100);
        });
      });
    }
  };
})(jQuery);
