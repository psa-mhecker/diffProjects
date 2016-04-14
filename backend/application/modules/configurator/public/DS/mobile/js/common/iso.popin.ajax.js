var PopinAjax = function($root) {
  this.init($root);
};
PopinAjax.prototype = {
  init: function($root) {
    var oThis = this;

    // SELECTORS && ELEMENTS 
    oThis.root = $root; // THE LINK
    oThis.htmlTag = $('html'); // ITSELF
    oThis.bodyTag = $('body'); // ITSELF
    oThis.bodyBlock = oThis.bodyTag.find('.body');  // MAIN CONTENT CONTAINER
    oThis.popinSelector = '.popinContainer'; // POPIN CONTAINER SELECTOR
    oThis.popinContainer = $(oThis.popinSelector); // POPIN CONTAINER ELEMENT
    oThis.popinContainerIscrollbarContent = oThis.popinContainer.find('.iscrollScrollbar .iscrollscroller'); // POPIN CONTAINER ISCROLL
    oThis.globalCore = $('.slice-n14bis')[0].N14;

console.log(oThis.globalCore);

    // GETTING CONTROLLER URL
    oThis.url = $root.attr('data-url');

    // EVENT ON THE LINK
    oThis.root.on('click', function(e) {
      e.preventDefault();
      e.stopImmediatePropagation();
      oThis.openIt()
    });
  },
  openIt: function() {
    var oThis = this;

    // COLLECTING GLOBAL DATAS TO FEED AJAX WITH USER'S CHOICES AND MAKE A CONTEXT POSSIBLE FOR CONTROLLER
    oThis.datas = '';

    if (oThis.popinContainer.find('.close').length == 0) {
      oThis.btnClose = $('<a class="close"></a>').on('click', function(e) {
        oThis.closeIt();
      }).prependTo(oThis.popinContainer);
    }
    oThis.bodyBlock.addClass('bodyAnimLeft');
    oThis.htmlTag.addClass('popopen');

    // AJAX CALL HERE

    $.ajax({
      type: 'GET',
      url: oThis.url,
      data: oThis.data,
      success: function(resp) {
        oThis.popinContainerIscrollbarContent.html(resp);
        oThis.setIscroll = new iscrollScrollbars(oThis.popinContainer, true);
      },
      error: function(err) {
        // console.log(err);
      }
    });


  },

  closeIt: function() {
    var oThis = this;

    oThis.htmlTag.removeClass('popopen');
    oThis.bodyBlock.removeClass('bodyAnimLeft');

    $(document).trigger('closepopin');
    oThis.btnClose.remove();
    oThis.popinContainerIscrollbarContent.html('');
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
