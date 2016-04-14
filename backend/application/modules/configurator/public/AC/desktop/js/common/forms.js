/* CHECKBOX // RADIO */
'use strict';
ISO.moduleCreate('iso-form', function($el) {

    var forms = {
      $el : null,
      init : function($container){

        var app = this,
            $input, $parent;

        app.$el = $container;

        if ($('.slice-cf41-x', $el).length === 1 || $('.slice-cc87', $el).length === 1 || $('.slice-cc95', $el).length === 1 || $('.slice-cf60', $el).length === 1)  {
          return; // hack to avoid initialzing checkbox
        }

        $('[type="radio"], [type="checkbox"]', $el).each(function(){

          $input  = $(this),
          $parent = $input.parent();

          // ajout du span , pour radio ou checkbox custom

          $input
          .hide();

          // pour les lames sur le config, permet de remonter un parent au dessus à cause des div container
          if ($parent.hasClass('labelForConfig') ) {
            $parent = $parent.parents('aside.onConfig');
          }

          $input.on('change', function(event, bool){

            var $label = $(this).parents('.elemnt_input');
            if( $label.hasClass('input-radio') ){
              $('aside.checked').removeClass('checked');
              app.manageRadiostyle($(this));
            }
            else {
              app.manageCheckboxstyle( $(this) );
            }
          });

          if( $input.prop('checked') ){
            $input.trigger('change', [true]);
          }

        });        
      },

      // gestion des boutons radios
      // /!\ les boutons radios qui doivent se comporter ensemble doivent être dans un container  .radio-group
      manageRadiostyle : function($input){
        var $label  = $input.parents('label'),
            $label3 = $input.parents('aside');

        $label.parents('.radio-group:eq(0)').find('[type="radio"]').not($input).removeAttr('checked').prop('checked',false);
        $label.parents('.radio-group:eq(0)').find('.checked').removeClass('checked');

        // Lame Finitions / Motorisations
        $label.addClass('checked');
        $label.parent().addClass('checked');
        if($label3.hasClass('checked')) $label3.removeClass('checked');
        else $label3.addClass('checked');
      },

      // gestion des checkbox
      manageCheckboxstyle : function($input){
        var $label  = $input.parents('label'),
            $label2 = $input.parents('.elemnt_input').find('.label-save'),
            $label3 = $input.parents('aside');

        $label2.toggleClass('checked');
        $label.toggleClass('checked');
        $label3.toggleClass('checked');
        if ($label.hasClass('checked') === true) {
            if ($label.parents('.confirm-mentions')) {
              $label.removeClass('erreurbox');
              $label.parents('.confirm-mentions').find('.error-msg').remove();
            }
        }
        //$label.find('.label').toggleClass("selected");
      }
    };
    forms.init($el);
});

ISO.getmoduleCreate('iso-form')( $('body'), {} );
