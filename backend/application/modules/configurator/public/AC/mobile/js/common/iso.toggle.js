var ModuleToggle = function($root) {
  this.init($root);
};
ModuleToggle.prototype = {
  init: function(obj) {
    var oThis = this;
    oThis.root = $(obj);

    oThis.divbody = $('.body');
    oThis.modToggle = oThis.root.find('.mod-toggle');
    oThis.html = $('html');    
    oThis.timer = 250;

    oThis.modToggle.find('.mod-toggle-content').wrapInner('<div></div>');
    oThis.events();
    oThis.initOnlyOne();
    oThis.initAll();  
  },

  initOnlyOne: function(){
    var oThis = this;
    oThis.autoOpen();
  },

  initAll: function(){
    var oThis = this;
    oThis.allOpen();
  },

  events: function(){
    var oThis = this;

    window.addEventListener('resize', oThis.resize);

    oThis.divbody.stop().on('click','.mod-toggle .mod-toggle-click', function(event){
      // event.preventDefault();

      var target = event.target.className;
      var res = target.match(/radio/g);

      if(!res){
        oThis.manage($(this));
      }

    });
  },
  /**
  * manage() Gère ouverture/Fermeture Toggle
  *
  */
  manage: function($that) {
    var oThis = this;    
    var $toggleContent = $that.next('.mod-toggle-content');

    if ($that.parent().hasClass('mod-toggle-open')) {      
      $toggleContent.stop().animate({ height: 0 }, oThis.timer, function(){
          $that.parent().removeClass('mod-toggle-open');
      });

    }else {
      var height = $('> div', $toggleContent).outerHeight();

      $toggleContent.stop().animate({ height: height }, oThis.timer, function(){
          $that.parent().addClass('mod-toggle-open');
      });
    }

  },

  resize: function(){
    var oThis = this;

    setTimeout(function(){

      $('.mod-toggle-open').each(function(i, el){
        var $thisToggle = $('.mod-toggle-open').eq(i);

        var $thisToggleContent = $('.mod-toggle-content', $thisToggle);

        var height = $('> div', $thisToggleContent).outerHeight();
        $thisToggleContent.css({ height: height });

      });

    }, 300);

  },

  autoOpen: function(){
    var oThis = this;

    setTimeout(function(){
      if (oThis.modToggle.length === 1) {
          oThis.modToggle.find('.mod-toggle-click').trigger('click');
      }
    }, 300);
  },

  allOpen: function(){
    var oThis = this;

    setTimeout(function(){
      if (oThis.modToggle.hasClass('toggle-allopen')) {
        $('.toggle-allopen .mod-toggle-click').trigger('click');
      }
    }, 300);
  }
};