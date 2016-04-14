var openpopingab = function($root) {
  this.init($root);
};
openpopingab.prototype = {
  init: function(obj) {
    var oThis = this;
    oThis.root = $(obj);

    oThis.htmltag = $('html');
    oThis.bodytag = $('body');
    oThis.bodyblock = oThis.bodytag.find('.body');
    oThis.popinLink = oThis.root.parents('.popincontext').find(".popinlink");
    oThis.slickslideshow = oThis.root.parents('.popincontext').find(".slickslideshow");
    oThis.popinSelector = '.popinContainer';
    oThis.popincontainer = $(oThis.popinSelector);
    oThis.popincontainerIscrollbarContent = oThis.popincontainer.find('.iscrollScrollbar .iscrollscroller');
    oThis.root.on('click', function(e) {
      e.preventDefault();
      e.stopImmediatePropagation();
      if ($(this).parents(oThis.popinSelector).length == 0) {
        if ($(this).attr('data-popinname') != 'popinvideo') {
          oThis.openpopin();
        }
      }
      if ($(this).attr('data-popinname') == 'popinvideo') {
        oThis.openvideo(this);
      }
    });
  },
  openvideo: function(obj) {
    var elem = $(obj).find('video')[0];
    elem.play();
    if (elem.requestFullscreen) {
      elem.requestFullscreen();
    } else if (elem.mozRequestFullScreen) {
      elem.mozRequestFullScreen();
    } else if (elem.webkitRequestFullscreen) {
      elem.webkitRequestFullscreen();
    }
  },
  openpopin: function() {
    var oThis = this;
    oThis.popinname = oThis.root.attr('data-popinname');
    oThis.codetotransfert = oThis.root.parents('.popincontext').find('.' + oThis.popinname);
    if (oThis.popincontainer.find('.close').length == 0) {
      oThis.btnClose = $('<a class="close"></a>').on('click', function(e) {
        oThis.closepopin();
      }).prependTo(oThis.popincontainer);
    }
    oThis.bodyblock.addClass('bodyAnimLeft');
    oThis.htmltag.addClass('popopen');
    oThis.initialposition = oThis.codetotransfert.parent();
    oThis.popincontainer.addClass(oThis.root.attr('data-popinname'));
    oThis.codetotransfert.appendTo(oThis.popincontainerIscrollbarContent);
    oThis.initialposition.addClass('codetransfert');
    oThis.setIscroll = new iscrollScrollbars(oThis.popincontainer, true);
  },

  closepopin: function() {
    var oThis = this;

    oThis.htmltag.removeClass('popopen');
    oThis.bodyblock.removeClass('bodyAnimLeft');
    oThis.popincontainerIscrollbarContent.find('.' + oThis.popinname).appendTo(oThis.initialposition);
    if (oThis.popinLink) oThis.popinLink.insertAfter(oThis.slickslideshow);
    oThis.initialposition.removeClass('codetransfert');
    oThis.popincontainer.removeClass(oThis.popinname);

    $(document).trigger('closepopin');
    oThis.btnClose.remove();
    oThis.popincontainerIscrollbarContent.html('');
  }
}



$( document ).ready(function() {

  /*$('a').each(function() {
    var linkGlobal = this;
    if ($(this).is('[data-popinname]')) {
      var popTrigger = this;
      this.openpopingabCheck = new openpopingab($(popTrigger));
    }
  });*/
  
  /*$.subscribe('configurator.stepsLoaded', function () {
    $('a').each(function() {
      var linkGlobal = this;
      if ($(this).is('[data-popinname]')) {
        var popTrigger = this;
        this.openpopingabCheck = new openpopingab($(popTrigger));
      }
    });
  })*/
});
