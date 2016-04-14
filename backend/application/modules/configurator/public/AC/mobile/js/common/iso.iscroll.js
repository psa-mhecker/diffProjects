/**
* iscrollScrollbars() init iscroll
*	@param {Element} obj 	 : Element where iscroll will be initialize
*	@param {Boolean} click : True for active click event on iscroll context
*/
var iscrollScrollbars = function(obj, click) {
  this.init(obj, click);
};

iscrollScrollbars.prototype = {

	//Param for iscroll click
	pClick : false,

  init: function(obj, click) {
    var oThis = this;
    oThis.iscrollscroller = obj.find('.iscrollScrollbar');
    oThis.myScroll = '';

	if (typeof click !== 'undefined') {
		oThis.pClick = click;
	}

    oThis.checkinit();
    //document.addEventListener('touchmove', function (e) { e.preventDefault(); }, false);
  },

  checkinit: function() {
    var oThis = this;
    /*if(!oThis.iscrollscroller.hasClass('iscroll-ok')){*/
    oThis.myScroll = new IScroll(oThis.iscrollscroller[0], {
      mouseWheel: true,
      scrollbars: false,
      click: oThis.pClick,
      interactiveScrollbars: true,
	  disableMouse: true
    });
    /*oThis.iscrollscroller.addClass('iscroll-ok');*/
    /*}else{
    	oThis.refreshpopin();
    }*/

    if (oThis.iscrollscroller.find('.iScrollVerticalScrollbar .iScrollIndicator').css('display') === 'block') {
      oThis.iscrollscroller.removeClass('iscroll-vertical');
    } else {
      oThis.iscrollscroller.addClass('iscroll-vertical');
    }
  },

  refreshpopin: function() {
    var oThis = this;
    setTimeout(function() {
      oThis.myScroll.refresh();

      if (oThis.iscrollscroller.find('.iScrollVerticalScrollbar .iScrollIndicator').css('display') === 'block') {
        oThis.iscrollscroller.removeClass('iscroll-vertical');
      } else {
        oThis.iscrollscroller.addClass('iscroll-vertical');
      }
    }, 200);
  }

};
