/* Save config */
ISO.moduleCreate('iso-FormSaveConfig', function($el) {
'use strict';
 //  $( "input" ).toggleClass( "checked" );
  var $saveConfig  = $el.find('.config-save'),
      $dontSave   = $el.find('.dont-save');

  if( $('html').hasClass('touch') ) {
    $el.find('.form-control').on('click', function(){
      if ($('.popinopen').length) {
          $('.popin--wrapper').animate({
            scrollTop: parseFloat($(this).offset().top) + parseFloat($('.popin--wrapper').scrollTop())
          }, 1000);
      }
      else{
        $('html, body').animate( { scrollTop: $label.offset().top  }, 0) ;
      }
    });
  }

  $el.find('.confirm-save .input-checkbox input[type="checkbox"]').each(function(){

    var $input = $(this) ;
      $input.on('change', function(e){
      // e.preventDefault();

      var $label = $input.siblings('label');

      if( $input.prop('checked') === false ){
        $saveConfig.hide();
        $el.find('.control-box').show();
        $('.valid').removeClass('disabled').addClass('validate isoPopinClose');
        $input.parents('.input-checkbox').removeClass('checked');
      }
      else{
        $saveConfig.show();
        $el.find('.control-box').hide();
        $('.valid').removeClass('validate isoPopinClose').addClass('disabled');
        $input.parents('.input-checkbox').addClass('checked');
      }
      $('.valid.disabled').on('click', function(e){
        e.preventDefault();
      })
      window.isoPopin.heightPopin();
      if ($('.popinopen').length) {
        $('.popin--wrapper').animate({
          scrollTop: $label.offset().top + $('.popin--wrapper').scrollTop()
        }, 0);
      }
      else{
        $('html, body').animate( { scrollTop: $label.offset().top  }, 0) ;
      }
    });

  });

  $dontSave.on('click', function(e) {
    e.preventDefault();
    $el.find('.confirm-save .input-checkbox input[type="checkbox"]').trigger('click');
  });

  $el.find('.config-save button').attr('id', 'submit');

  var  mailPattern = /^[a-z0-9]+([_|\.|-]{1}[a-z0-9]+)*@[a-z0-9]+([_|\.|-]{1}[a-z0-9]+)*[\.]{1}[a-z]{2,6}$/i,
       namePattern = /^[A-Z][-a-zA-Z]+$/i,
       errors = 0;

  function validation($that, inputValue){
    if( $that.attr('type') === 'email') {
      if( mailPattern.test(inputValue) === false ) {
        $that.parents('.input-text:eq(0)').removeClass('valid');
        $that.parents('.input-text:eq(0)').addClass('error');
        if( !$that.parents('.input-text:eq(0)').find('.error-msg').length ){
          $that.parents('.input-text:eq(0)').append('<span class="error-msg">'+ $that.attr('data-msg-email') +'</span>');
        }
      }
      else {
        $that.parents('.input-text:eq(0)').removeClass('error');
          $that.parents('.input-text:eq(0)').addClass('valid');
        $that.parents('.input-text:eq(0)').find('.error-msg').remove();
      }
      }
      else if( $that.attr('type') === 'text' ) {
        if( inputValue !== '') {
          $that.parents('.input-text:eq(0)').removeClass('error');
            $that.parents('.input-text:eq(0)').addClass('valid');
          $that.parents('.input-text:eq(0)').find('.error-msg').remove();
        }
        else {
          $that.parents('.input-text:eq(0)').removeClass('valid');
          $that.parents('.input-text:eq(0)').addClass('error');
          if( !$that.parents('.input-text:eq(0)').find('.error-msg').length ){
            if( $that.attr('data-msg-required') !== '' ){
              $that.parents('.input-text:eq(0)').append('<span class="error-msg">'+ $that.attr('data-msg-required') +'</span>');
            }
          }
        }
      }
      else if( $that.attr('type') === 'checkbox' ) {
        if ($that.parents('.input-checkbox').length !== 0 ) {
          if ($that.parents('.input-checkbox').hasClass('checked') === false) {
            errors++;
            $that.parents('.input-checkbox').addClass('erreurbox');
            if( $that.attr('data-msg-required') !== '' && $that.parents('.confirm-mentions').find('.error-msg').length === 0){
              $('.confirm-mentions').append('<span class="error-msg">'+ $that.attr('data-msg-required') +'</span>');
            }
          }
        }
      /*  else {
            if( $that.parent('label').hasClass('checked') === false ) {
          errors++;
          $that.parent('label').addClass('erreurbox');
          if( $that.attr('data-msg-required') !== '' ){

            $('.confirm-mentions').append('<span class="error-msg">'+ $that.attr('data-msg-required') +'</span>');
          }
        }
        }*/
      }
  }

  function formvalide(){
    var $form = $el.find('form'),
        inputLength = $form.find('input').length;

    $('#submit').on('click', function(e){
      var i = 0;
      errors = 0;
      e.preventDefault();

      $('.error-msg').remove();

      $('input[data-required="true"]').each(function(){
        i++;
        var $that      = $(this),
            inputValue = $that.val();
            validation($that, inputValue);

        if( i === inputLength ) { /* CallBack */
          if( $('.error-msg').length === 0 ) {
            // TODO : appel AJAX pour enregistrer les données du formulaire au back
            var $saveConfirm = $el.find('.confirm-save'),
            $saveConfig = $el.find('.config-save'),
            $infoConfig = $el.find('.etape1');
            $el.find('.etape3').show();
            $el.find('.etape3 .notification').show();
            $saveConfirm.hide();
            $saveConfig.hide();
            $infoConfig.hide();

            if( $('.vignettes-row').length) {
                $('.valid').removeClass('disabled').addClass('validate isoPopinOpenLink');
            }else {
                $('.valid').removeClass('disabled').addClass('validate close isoPopinClose');
            }
        } else {
            if( $('#configname').hasClass('error') && $('#configemail').hasClass('error') ) {
              // $('.config-box-input .error-msg').remove();
              $('.input-text.last').append('<span class="error-msg">'+ $('.config-box-input').attr('data-msg-required') +'</span>');
            }
          }
        }
          window.isoPopin.heightPopin();
          if ($('.popinopen').length) {
            $('.popin--wrapper').animate({
              scrollTop: $('.save-config').offset().top + $('.popin--wrapper').scrollTop()
            }, 0);
          }
          else{
            $('html, body').animate( { scrollTop: $('.save-config').offset().top  }, 0 ) ;
          }

        });
    });
    /* For each required input */
    $('input[data-required="true"]').on('keyup change', function(){

      if( $(this).find('.error-msg').length  > 0 ) {
        var $that       = $(this),
            inputValue = $that.val();
            validation($that, inputValue);
      }
    });
    /* For each required input */
    $('input[data-required="true"]').on('blur', function(){
      var $that       = $(this),
          inputValue  = $that.val();
          validation($that, inputValue);
    });

    /* Case accepter mention legale */
    $('.confirm-mentions label').on('click', function(){

      if ( $('.confirm-mentions').find('.error-msg').length !== 0 ){
        $('.confirm-mentions').find('.error-msg').remove();
      }
      if ($('.confirm-mentions .elemnt_input.input-checkbox').length !== 0) {

        if(!$('.confirm-mentions input[type="checkbox"]').is(':checked')) {

           $(this).parents('.input-checkbox').addClass('checked');
           $(this).parents('.input-checkbox').removeClass('erreurbox');

         }else{

           $(this).parents('.input-checkbox').addClass('erreurbox');
           $(this).parents('.input-checkbox').removeClass('checked');

            if( $(this).attr('data-msg-required') !== '' ) {
              var html = '<span class="error-msg">'+ $(this).siblings('.form-control').attr('data-msg-required') +'</span>';
              $(this).parents('.input-checkbox').after(html);
            }
         }
      }
   /*   else {

        if( $(this).hasClass('checked') === false ) {
        $(this).removeClass('erreurbox');
      }
      else {
        $(this).addClass('erreurbox');

        if( $(this).attr('data-msg-required') !== '' ) {
         $(this).after('<span class="error-msg">'+ $(this).find('input').attr('data-msg-required') +'</span>');
        }
      }
      }*/

    });
  }
  formvalide();

});
