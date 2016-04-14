(function($, win, doc, ISO) {

jQuery.fx.off = false;

  var ele = document.createElement("meta");
  ele.name = "viewport";

  if( window.screen.width < 1024 ){
    ele.content = 'width=1020,user-scalable=no';
  }
   $('head')[0].appendChild(ele);

  var ua = navigator.userAgent.toLowerCase();
  var isAndroid = ua.indexOf('android') > -1; //&& ua.indexOf("mobile");

  function create_responsive_viewport(){
    $(window).on("orientationchange",function(){
      setTimeout(function(){
        if( $(window).outerWidth(true) < 1024 ){
          $('[name="viewport"]').attr('content', 'width=1020,user-scalable=no');
        }
        else {
          $('[name="viewport"]').attr('content', 'width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no');
        }
      }, 300);
    });
  }
  create_responsive_viewport();

})(jQuery, window, document, window.ISO = window.ISO || {});

