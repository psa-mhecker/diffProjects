/* Save config */
ISO.moduleCreate('iso-saveConfig', function($el) {
'use strict';

  var $saveConfig  = $el.find('.config-save'),
      $dontSave   = $el.find('.dont-save');

  if( $('html').hasClass('touch') && navigator.userAgent.match(/iPad/i) === null ) {
    $el.find('.form-control').on('click', function(){
      if ($('.popinopen').length) {
        /*$('html, body').animate({
          scrollTop: parseFloat($(this).offset().top) + parseFloat($('.popin--wrapper').scrollTop()) - 28
        }, 1000);*/
      }
      else{
        $('html, body').animate({ scrollTop: $label.offset().top  }, 0);
      }
    });
    $el.find('.form-control input').on('blur', function(){
      if ($('.popinopen').length) {
        /*$('html, body').animate({
          scrollTop: parseFloat($(this).offset().top) + parseFloat($('.popin--wrapper').scrollTop()) - 28
        }, 1000);*/
      }
      else{
        $('html, body').animate({ scrollTop: $label.offset().top  }, 0);
      }
    });
  }

  /* if( navigator.userAgent.match(/iPad/i) != null ) {
    if ($('.popinopen').length) {
      $el.find('.form-control input').on('blur', function(){
        $('html').removeClass('static');
      });
      $el.find('.form-control input').on('focus', function(){
        $('html').addClass('static');
      });
    }
  } */

  $el.find('.confirm-save .label-save input[type="checkbox"] ').each(function(){
    var $this = $(this),
        $label;

    if ($this.parents('.confirm-save')) {
       $label = $this.parents('.confirm-save').find('.label-save');
    }
    else {
      $label = $this.parent('label');
    }

    $label.on('click', function(e){
      e.preventDefault();

      if ($this.attr('checked')) {
        $saveConfig.hide();
        $('.save-config').removeClass('etape2');
        $el.find('.control-box').show();
        $('.valid').removeClass('diselected').addClass('cta-default');
        $this.removeAttr('checked');
        $this.parent().removeClass('checked');
        /*console.trace();*/
      }
      else {
        $saveConfig.show();
        $('.save-config').addClass('etape2');
        $el.find('.control-box').hide();
        $('.valid').addClass('diselected').removeClass('cta-default');
        $this.attr('checked', 'checked');
        $this.parent().addClass('checked');
        /*console.trace();*/
      }
      $('.valid.diselected').on('click', function(e){
        e.preventDefault();
        $('html, body').animate( { scrollTop: $label.offset().top  }, 0) ;
      })
      window.isoPopin.heightPopin();
      /* if ($('.popinopen').length) {
        $('.popin--wrapper').animate({
          scrollTop: $label.offset().top + $('.popin--wrapper').scrollTop()
        }, 0);
      }
      else{ */
        $('html, body').animate( { scrollTop: $label.offset().top  }, 0) ;
      // }

    });
  });

  $dontSave.on('click', function(e) {
    e.preventDefault();
    $el.find('.label-save').trigger('click');
  });


  var  mailPattern = /^[a-z0-9]+([_|\.|-]{1}[a-z0-9]+)*@[a-z0-9]+([_|\.|-]{1}[a-z0-9]+)*[\.]{1}[a-z]{2,6}$/i,
      errors = 0;
  function validation($that, inputValue){
    if( $that.attr('type') === 'email') {
      if( mailPattern.test(inputValue) === false ) {
        $that.parents('.input-text:eq(0)').removeClass('valid');
        $that.parents('.input-text:eq(0)').addClass('error');
        if( !$that.parents('.form-control:eq(0)').find('.error-msg').length ){
          $that.parents('.form-control:eq(0)').append('<span class="error-msg">'+ $that.attr('data-msg-email') +'</span>');
        }
      }
      else {
        $that.parents('.input-text:eq(0)').removeClass('error');
          $that.parents('.input-text:eq(0)').addClass('valid');
        $that.parents('.form-control:eq(0)').find('.error-msg').remove();
      }
      }
      else if( $that.attr('type') === 'text' ) {
        if( inputValue !== '' ) {
          $that.parents('.input-text:eq(0)').removeClass('error');
            $that.parents('.input-text:eq(0)').addClass('valid');
          $that.parents('.form-control:eq(0)').find('.error-msg').remove();
        }
        else {
          $that.parents('.input-text:eq(0)').removeClass('valid');
          $that.parents('.input-text:eq(0)').addClass('error');
          if( !$that.parents('.input-text:eq(0)').find('.error-msg').length ){
            if( $that.attr('data-msg-required') !== '' ){
              $that.parents('.form-control:eq(0)').append('<span class="error-msg">'+ $that.attr('data-msg-required') +'</span>');
            }
          }
        }
      }
      else if( $that.attr('type') === 'checkbox' ) {
      if( !$that.attr('checked')) {
        errors++;
        $that.parent('.input-radio').addClass('erreurbox');
        if( $that.attr('data-msg-required') !== ''){
          $('.confirm-mentions').append('<span class="error-msg">'+ $that.attr('data-msg-required') +'</span>');
        }
      }
      else {
        $that.parent('.input-radio').removeClass('erreurbox');
        $('.confirm-mentions').find('.error-msg').remove();
      }
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
            $('.save-config').addClass('confirmation');
            $('.save-config').removeClass('etape2');
            $('.valid').removeClass('diselected').addClass('cta-default');
            $saveConfirm.hide();
            $saveConfig.hide();
            $infoConfig.hide();

          } else {
            if( $('#configname').hasClass('error') && $('#configemail').hasClass('error') ) {
              // $('.config-box-input .error-msg').remove();
              $('.input-text.last').append('<span class="error-msg">'+ $('.config-box-input').attr('data-msg-required') +'</span>');
            }
          }
        }
        window.isoPopin.heightPopin();
        /* if ($('.popinopen').length) {
          $('.popin--wrapper').animate({
            scrollTop: $('.save-config').offset().top + $('.popin--wrapper').scrollTop()
          }, 0);
        }
        else{ */
          $('html, body').animate( { scrollTop: $('.save-config').offset().top  }, 0 ) ;
        // }
      });
  });

    /* For each required input */
    $('input[data-required="true"]').on('keyup change', function(){
      if( $('.error-msg').length > 0 ) {
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
    $('.confirm-mentions label').on('click', function(e){
      e.preventDefault();
      var $par = $(this).parent('.input-radio:eq(0)');
      if ($('.confirm-mentions input[type="checkbox"]').attr('checked')) {
        $par.addClass('erreurbox');
        $par.removeClass('checked');
        $('.confirm-mentions input[type="checkbox"]').removeAttr('checked');
        $('.confirm-mentions').append('<span class="error-msg">'+ $('.confirm-mentions input[type="checkbox"]').attr('data-msg-required') +'</span>');
      }
      else {
        $(this).parents('.input-radio').addClass('checked');
        $(this).parents('.input-radio').removeClass('erreurbox');
        $('.confirm-mentions').find('.error-msg').remove();
        $('.confirm-mentions input[type="checkbox"]').attr('checked', 'checked');
      }

    });
  }
  formvalide();

});
