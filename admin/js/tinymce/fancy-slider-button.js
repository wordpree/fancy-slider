(function() {
     /* Register the buttons */
     tinymce.create('tinymce.plugins.fancySliderButton', {
          init : function(ed, url) {
               /**
               * Inserts shortcode content
               */
               ed.addButton( 'fancy_slider_button', {
                    title : 'Fancy Slider',
                    image : url+'/icon/slider-icon.svg',
                    onclick : function() {
                         ed.selection.setContent('[fancy_slider_short]');
                    }
               });
          },
          createControl : function(n, cm) {
               return null;
          },
     });
     /* Start the buttons */
     tinymce.PluginManager.add( 'fancySlider', tinymce.plugins.fancySliderButton );
})();