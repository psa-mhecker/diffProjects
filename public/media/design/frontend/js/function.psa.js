/**
 * Cookies Banner
 * @author Cyril Pereira / Extreme-sensio 2014
 * @author Antony de Lopez Vallerie / Extreme-sensio 2014
 */

(function($) {
  if (typeof $.removeCookie == 'undefined') {
    throw 'jQuery.cookie is not loaded';
  }

  $.fn.CookiesBanner = function(options) {
    var defaults = {
      'expires'   : 396, // 13 monthes
      'path'      : '/',
      'cookieName':  'CookiesBanner'
    };

    options = $.extend(defaults, options);

    function init(allElem) {
      var $allElem = $(allElem);

      $allElem.each(function(elm) {
        $this = $(this);
        var opt = options;

        if ($this.data('expires'))
          opt.expires = $this.data('expires');
        if ($this.data('path'))
          opt.path    = $this.data('path');
        if ($this.data('cookiename'))
          opt.cookieName    = $this.data('cookiename');

        var optCookie = { path: opt.path, expires: opt.expires };

        function getCookie() {
          return $.cookie(opt.cookieName) ? $.cookie(opt.cookieName) : false;
        }

        function setCookie(v, options) {
          $.cookie(opt.cookieName,v, options ? options : {});
          return $.cookie(opt.cookieName);
        }

        var publicFunctions = {
          accept: function() {
            setCookie('accepted', optCookie);
            this.close();
            $this.trigger('accepted');
            $('body').addClass('cookiesAccepted')
            $('body').removeClass('cookiesNotAccepted')
            $(window).trigger('cookieAccepted')
          },
          refuse: function() {
            setCookie('refused', optCookie);
            this.close();
            $this.trigger('refused');
          },
          display: function() {
            $this.show();
            $this.trigger('displayed');
          },
          close: function() {
            $this.hide();
            $this.trigger('closed');
          },
          reset: function() {
            $.removeCookie(opt.cookieName, { path: opt.path });
            $this.trigger('reset');
            return $.cookie(opt.cookieName);
          }
        };

        $this.click(function(e) {
          if ($(e.target).hasClass('accept')) {
            e.preventDefault();
            publicFunctions.accept();
          } else if ($(e.target).hasClass('refuse')) {
            e.preventDefault();
            publicFunctions.refuse();
          } else if ($(e.target).hasClass('close')) {
            e.preventDefault();
            publicFunctions.close();
          }
        });

        $this.data('instance', publicFunctions);

        var status = getCookie();

        setTimeout(function()
        {
          if (!status) {
            publicFunctions.display();
          } else {
            if (status == 'accepted') {
              publicFunctions.accept();
            } else if (status == 'refused') {
              publicFunctions.refuse();
            }
          }
        }, 100);

      });
    }

    init(this);
    return this;
  };
})(jQuery);
;/*! modernizr 3.1.0 (Custom Build) | MIT *
 * http://modernizr.com/download/?-touchevents !*/
!function(e,n,t){function o(e,n){return typeof e===n}function s(){var e,n,t,s,a,i,r;for(var l in c)if(c.hasOwnProperty(l)){if(e=[],n=c[l],n.name&&(e.push(n.name.toLowerCase()),n.options&&n.options.aliases&&n.options.aliases.length))for(t=0;t<n.options.aliases.length;t++)e.push(n.options.aliases[t].toLowerCase());for(s=o(n.fn,"function")?n.fn():n.fn,a=0;a<e.length;a++)i=e[a],r=i.split("."),1===r.length?Modernizr[r[0]]=s:(!Modernizr[r[0]]||Modernizr[r[0]]instanceof Boolean||(Modernizr[r[0]]=new Boolean(Modernizr[r[0]])),Modernizr[r[0]][r[1]]=s),f.push((s?"":"no-")+r.join("-"))}}function a(e){var n=u.className,t=Modernizr._config.classPrefix||"";if(p&&(n=n.baseVal),Modernizr._config.enableJSClass){var o=new RegExp("(^|\\s)"+t+"no-js(\\s|$)");n=n.replace(o,"$1"+t+"js$2")}Modernizr._config.enableClasses&&(n+=" "+t+e.join(" "+t),p?u.className.baseVal=n:u.className=n)}function i(){return"function"!=typeof n.createElement?n.createElement(arguments[0]):p?n.createElementNS.call(n,"http://www.w3.org/2000/svg",arguments[0]):n.createElement.apply(n,arguments)}function r(){var e=n.body;return e||(e=i(p?"svg":"body"),e.fake=!0),e}function l(e,t,o,s){var a,l,f,c,d="modernizr",p=i("div"),h=r();if(parseInt(o,10))for(;o--;)f=i("div"),f.id=s?s[o]:d+(o+1),p.appendChild(f);return a=i("style"),a.type="text/css",a.id="s"+d,(h.fake?h:p).appendChild(a),h.appendChild(p),a.styleSheet?a.styleSheet.cssText=e:a.appendChild(n.createTextNode(e)),p.id=d,h.fake&&(h.style.background="",h.style.overflow="hidden",c=u.style.overflow,u.style.overflow="hidden",u.appendChild(h)),l=t(p,e),h.fake?(h.parentNode.removeChild(h),u.style.overflow=c,u.offsetHeight):p.parentNode.removeChild(p),!!l}var f=[],c=[],d={_version:"3.1.0",_config:{classPrefix:"",enableClasses:!0,enableJSClass:!0,usePrefixes:!0},_q:[],on:function(e,n){var t=this;setTimeout(function(){n(t[e])},0)},addTest:function(e,n,t){c.push({name:e,fn:n,options:t})},addAsyncTest:function(e){c.push({name:null,fn:e})}},Modernizr=function(){};Modernizr.prototype=d,Modernizr=new Modernizr;var u=n.documentElement,p="svg"===u.nodeName.toLowerCase(),h=d._config.usePrefixes?" -webkit- -moz- -o- -ms- ".split(" "):[];d._prefixes=h;var m=d.testStyles=l;Modernizr.addTest("touchevents",function(){var t;if("ontouchstart"in e||e.DocumentTouch&&n instanceof DocumentTouch)t=!0;else{var o=["@media (",h.join("touch-enabled),("),"heartz",")","{#modernizr{top:9px;position:absolute}}"].join("");m(o,function(e){t=9===e.offsetTop})}return t}),s(),a(f),delete d.addTest,delete d.addAsyncTest;for(var v=0;v<Modernizr._q.length;v++)Modernizr._q[v]();e.Modernizr=Modernizr}(window,document);;var mqDetector;
mqDetector = function () {
    function mqDetector(myBreackPoint) {
        this.breakPoint = myBreackPoint ? myBreackPoint : 1024;
        this.ieOld = $('.ie-old')[0];
    }
    mqDetector.prototype.checkMe = function () {
        var matchMediaAvailable;
        matchMediaAvailable = false;
        if (navigator.userAgent.indexOf('Chrome') !== -1 || navigator.userAgent.indexOf('Firefox') !== -1) {
            matchMediaAvailable = true;
        }
        if (matchMediaAvailable) {
            if (window.matchMedia('(max-width:' + this.breakPoint + 'px)').matches || $(window).width() <= this.breakPoint - 24) {
                return true;
            } else {
                return false;
            }
        } else {
            if ($(window).width() <= this.breakPoint && !this.ieOld) {
                return true;
            } else {
                return false;
            }
        }
    };
    return mqDetector;
}();
window.mqDetector = mqDetector;;var touchDetect;
var bind = function (fn, me) {
    return function () {
        return fn.apply(me, arguments);
    };
};
var indexOf = [].indexOf || function (item) {
    for (var i = 0, l = this.length; i < l; i += 1) {
        if (i in this && this[i] === item) {
            return i;
        }
    }
    return -1;
};
touchDetect = function () {
    function touchDetect() {
        this.checkMe = bind(this.checkMe, this);
        this.touchState = indexOf.call(document.documentElement, 'ontouchstart') >= 0 || this.is_windowstouch_device() ? true : false;
    }
    touchDetect.prototype.is_windowstouch_device = function () {
        return this.temp = 'ontouchstart' in window || navigator.msMaxTouchPoints ? true : false;
    };
    touchDetect.prototype.checkMe = function () {
        return this.touchState;
    };
    return touchDetect;
}();
window.touchDetect = touchDetect;;(function ($) {
  $.fn.easyTab = function (option) {
    var options = {
      tabnav: '.easyTab-nav',
      tabContainer: '.easyTab-container',
      tabs: '.tab',
      navBtnSelector: 'a',
      currentItem: 0,
      defaultOpen: true,
      animTime: 0.5,
      closable: true,
      clickfunction: function () {
      },
      onClose: function () {
      },
      onOpen: function () {
      }
    };
    var timerClose;
    var myOptions;
    if (option)
      myOptions = $.extend(options, option);
    else
      myOptions = options;
    $(this).each(function (i) {
      var disabled = false;
      var scope = $(this);
      var myTabNav = $(this).find(myOptions.tabnav + ' ' + myOptions.navBtnSelector + '[data-tab]');
      var myTabContainer = $(this).find(myOptions.tabContainer);
      var myTabTab = $(this).find(myOptions.tabs);
      var myCurrentItem = $($(this).find(myOptions.tabs)[myOptions.currentItem]);
      var isOpen = false;
      var publicFunction = {
        changeTab: function (index) {
          var tempTime = isOpen ? 0 : myOptions.animTime;
          myTabNav.removeClass('active');
          var tabActive = $($(myTabNav)[index]);
          tabActive.addClass('active');
          myCurrentItem = scope.find('.' + tabActive.data('tab'));
          TweenLite.to(myTabTab, myOptions.animTime, {
            css: { opacity: 0 },
            ease: Power3.easeOut,
            onComplete: function () {
              $(this.target).css({ display: 'none' });
            }
          });
          TweenLite.killTweensOf(myTabTab);
          myCurrentItem.css({ display: 'block' });
          TweenLite.to(myCurrentItem, tempTime, {
            css: { opacity: 1 },
            ease: Power3.easeOut
          });
          isOpen = true;
        },
        closeAll: function (time) {
          myOptions.onClose();
          isOpen = false;
          var myTime = time ? time : 0;
          clearTimeout(timerClose);
          timerClose = setTimeout(function () {
            myTabNav.removeClass('active');
            TweenLite.killTweensOf(myTabTab);
            TweenLite.to(myTabTab, myOptions.animTime, {
              css: { opacity: 0 },
              ease: Power3.easeOut,
              onComplete: function () {
                $(this.target).css({ display: 'none' });
              }
            });
          }, myTime);
        },
        openAll: function () {
          myOptions.onClose();
          isOpen = true;
          myTabNav.removeClass('active');
          TweenLite.killTweensOf(myTabTab);
          myTabTab.attr('style', '');
        },
        init: function () {
          myTabTab.css({ display: 'none' });
          if (myOptions.defaultOpen) {
            $($(myTabNav)[myOptions.currentItem]).addClass('active');
            myCurrentItem.css({ display: 'block' });
          }
        },
        disabled: function () {
          scope.disabled = true;
          myTabNav.removeClass('active');
        },
        enabled: function () {
          scope.disabled = false;
        }
      };
      $(this).data('easyTab', publicFunction);
      myTabNav.click(function (e) {
        if (!scope.disabled) {
          $.proxy(myOptions.clickfunction, this)();
          if ($(this).data('tab')) {
            e.preventDefault();
            myTabContainer.find('.' + $(this).data('tab'));
            if ($(this).hasClass('active') && myOptions.closable === true) {
              publicFunction.closeAll();
              myTabNav.removeClass('active');
            } else {
              var tempTime = isOpen ? 0 : myOptions.animTime;
              myTabNav.removeClass('active');
              $(this).addClass('active');
              myCurrentItem = scope.find('.' + $(this).data('tab'));
              TweenLite.killTweensOf(myTabTab);
              myTabTab.hide();
              myCurrentItem.css({ display: 'block' });
              TweenLite.to(myCurrentItem, tempTime, {
                css: { opacity: 1 },
                ease: Power3.easeOut
              });
              $.proxy(myOptions.onOpen, this)();
              isOpen = true;
            }
          }
        }
      });
      publicFunction.init();
    });
  };
}(jQuery));
;var screenSurveille;
screenSurveille = null;
(function ($) {
    return $(document).ready(function () {
        $('input, textarea').placeholder();
        window.mymqDetector1024 = new mqDetector(1024);
        window.mymqDetector768 = new mqDetector(768);
        window.touchDetect = new touchDetect();
        if (!touchDetect.checkMe() || !mymqDetector1024.checkMe()) {
            $('a, button, input[type=submit]').addClass('activeRoll');
        }
        $.migrateTrace = false;
        $.migrateMute = false;
        $('.cookieBarReviewDesktop, .cookieBarReviewMobile').CookiesBanner();
        if (typeof tooltip !== 'undefined') {
            $(document.body).on('click', function () {
                return tooltip.close();
            });
        }
    });
}(jQuery));;(function ($) {
    $(document).ready(function () {
        var fitLangDorpDown;
        var openState1;
        var openState2;
        var searchBarEngine;
        openState1 = false;
        openState2 = false;
        fitLangDorpDown = function () {
            $('.sliceHeadReviewDesk .headerLvl1 .langWrapper a.lang').attr('style', '');
            $('.sliceHeadReviewDesk .headerLvl1 .langWrapper .tabLang').attr('style', '');
            if ($('.sliceHeadReviewDesk .headerLvl1 .langWrapper a.lang').width() + 40 > $('.sliceHeadReviewDesk .headerLvl1 .langWrapper .tabLang').width()) {
                $('.sliceHeadReviewDesk .headerLvl1 .langWrapper .tabLang>ul').css({ width: Math.ceil($('.sliceHeadReviewDesk .headerLvl1 .langWrapper a.lang').width()) + 40 });
                return $('.sliceHeadReviewDesk .headerLvl1 .langWrapper a.lang').css({ width: Math.ceil($('.sliceHeadReviewDesk .headerLvl1 .langWrapper a.lang').width()) + 40 + 2 });
            } else {
                $('.sliceHeadReviewDesk .headerLvl1 .langWrapper a.lang').css({ width: $('.sliceHeadReviewDesk .headerLvl1 .langWrapper .tabLang').width() + 2 });
                return $('.sliceHeadReviewDesk .headerLvl1 .langWrapper .tabLang>ul').css({ width: $('.sliceHeadReviewDesk .headerLvl1 .langWrapper .tabLang').width() });
            }
        };
        fitLangDorpDown();
        $('.sliceHeadReviewDesk .headerLvl1').easyTab({
            defaultOpen: false,
            tabs: '.tabIn',
            onOpen: function () {
                if (!$('.sliceHeadReviewDesk .headerLvl1 a.lang').hasClass('active')) {
                    openState2 = false;
                    TweenLite.killTweensOf($('.sliceHeadReviewOverlay'));
                    if ($('.sliceHeadReviewDesk .headerWrapperLvl2').data('easyTab')) {
                        openState1 = true;
                        $('.sliceHeadReviewDesk .headerWrapperLvl2').data('easyTab').closeAll(0);
                    }
                    if (!$('.sliceHeadReviewOverlay')[0]) {
                        $('body').append('<div class=\'sliceHeadReviewOverlay\'></div>');
                        $('.sliceHeadReviewOverlay').css({ opacity: 0 });
                        TweenLite.to($('.sliceHeadReviewOverlay'), 0.5, {
                            opacity: 1,
                            ease: Power1.easeOut
                        });
                    } else {
                        TweenLite.to($('.sliceHeadReviewOverlay'), 0.5, {
                            opacity: 1,
                            ease: Power1.easeOut
                        });
                    }
                    return searchBarEngine('close');
                } else {
                    if (!openState2) {
                        openState1 = false;
                        openState2 = false;
                        return TweenLite.to($('.sliceHeadReviewOverlay'), 0.5, {
                            opacity: 0,
                            ease: Power1.easeOut,
                            onComplete: function () {
                                return $('.sliceHeadReviewOverlay').remove();
                            }
                        });
                    }
                }
            },
            onClose: function () {
                if (!openState2) {
                    openState1 = false;
                    openState2 = false;
                    return TweenLite.to($('.sliceHeadReviewOverlay'), 0.5, {
                        opacity: 0,
                        ease: Power1.easeOut,
                        onComplete: function () {
                            return $('.sliceHeadReviewOverlay').remove();
                        }
                    });
                }
            }
        });
        $('.sliceHeadReviewDesk .headerWrapperLvl2').easyTab({
            defaultOpen: false,
            tabs: '.tabIn',
            onOpen: function () {
                openState1 = false;
                TweenLite.killTweensOf($('.sliceHeadReviewOverlay'));
                if ($('.sliceHeadReviewDesk .headerLvl1').data('easyTab')) {
                    openState2 = true;
                    $('.sliceHeadReviewDesk .headerLvl1').data('easyTab').closeAll(0);
                }
                $('body').css('height', $('.tabIn.'+$(this).data('tab')).outerHeight() + 200);
                if (!$('.sliceHeadReviewOverlay')[0]) {
                    $('body').append('<div class=\'sliceHeadReviewOverlay\'></div>');
                    $('.sliceHeadReviewOverlay').css({ opacity: 0 });
                    TweenLite.to($('.sliceHeadReviewOverlay'), 0.5, {
                        opacity: 1,
                        ease: Power1.easeOut
                    });
                } else {
                    TweenLite.to($('.sliceHeadReviewOverlay'), 0.5, {
                        opacity: 1,
                        ease: Power1.easeOut
                    });
                }
                return searchBarEngine('close');
            },
            onClose: function () {
                if (!openState1) {
                    openState1 = false;
                    openState2 = false;
                    $('body').css('height', 'auto');
                    return TweenLite.to($('.sliceHeadReviewOverlay'), 0.5, {
                        opacity: 0,
                        ease: Power1.easeOut,
                        onComplete: function () {
                            return $('.sliceHeadReviewOverlay').remove();
                        }
                    });
                }
            }
        });
        $('.sliceHeadReviewDesk .headerLvl1 .cross').on('click', function (e) {
            e.preventDefault();
            if ($('.sliceHeadReviewDesk .headerLvl1').data('easyTab')) {
                return $('.sliceHeadReviewDesk .headerLvl1').data('easyTab').closeAll(0);
            }
        });
        $('.sliceHeadReviewDesk .headerWrapperLvl2 .cross').on('click', function (e) {
            e.preventDefault();
            if ($('.sliceHeadReviewDesk .headerWrapperLvl2').data('easyTab')) {
                return $('.sliceHeadReviewDesk .headerWrapperLvl2').data('easyTab').closeAll(0);
            }
        });
        $('body').on('click touchstart', function (e) {
            if ($(e.target).hasClass('sliceHeadReviewOverlay') || $(e.target).hasClass('buttonList') || $(e.target).hasClass('headerWrapperLvl1') || $(e.target).hasClass('headerWrapperLvl2') || $(e.target).hasClass('headerLvl1') || $(e.target).hasClass('cookieBarReviewDesktopRow')) {
                if ($('.sliceHeadReviewDesk .headerLvl1').data('easyTab')) {
                    $('.sliceHeadReviewDesk .headerLvl1').data('easyTab').closeAll(0);
                }
                if ($('.sliceHeadReviewDesk .headerWrapperLvl2').data('easyTab')) {
                    $('.sliceHeadReviewDesk .headerWrapperLvl2').data('easyTab').closeAll(0);
                }
            }
            if ($('.sliceHeadReviewDesk .headerLvl1 a.lang').hasClass('active') && !$(e.target).data('tab')) {
                if ($('.sliceHeadReviewDesk .headerLvl1').data('easyTab')) {
                    $('.sliceHeadReviewDesk .headerLvl1').data('easyTab').closeAll(0);
                }
            }
            if (!$(e.target).hasClass('searchBarComponent') && !$(e.target).hasClass('search')) {
                return searchBarEngine('close');
            }
        });
        searchBarEngine = function (state) {
            if (state === 'open') {
                if ($('.sliceHeadReviewDesk .headerLvl1').data('easyTab')) {
                    $('.sliceHeadReviewDesk .headerLvl1').data('easyTab').closeAll(0);
                }
                if ($('.sliceHeadReviewDesk .headerWrapperLvl2').data('easyTab')) {
                    $('.sliceHeadReviewDesk .headerWrapperLvl2').data('easyTab').closeAll(0);
                }
                return TweenLite.to($('.sliceHeadReviewDesk .searchBar'), 0.5, {
                    css: { height: 2 + $('.sliceHeadReviewDesk .searchBar > form').height() + parseInt($('.sliceHeadReviewDesk .searchBar > form').css('padding-bottom'), 10) + parseInt($('.sliceHeadReviewDesk .searchBar > form').css('padding-top'), 10) },
                    ease: Power3.easeOut
                });
            } else {
                return TweenLite.to($('.sliceHeadReviewDesk .searchBar'), 0.5, {
                    css: { height: 0 },
                    ease: Power3.easeOut
                });
            }
        };
        return $('.sliceHeadReviewDesk .search').on('click', function (e) {
            e.preventDefault();
            if ($(this).hasClass('active')) {
                $(this).removeClass('active');
                return searchBarEngine('close');
            } else {
                $(this).addClass('active');
                return searchBarEngine('open');
            }
        });
    });
    return $(document).load(function () {
        return fitLangDorpDown();
    });
}(jQuery));;(function ($) {
    $(document).ready(function () {
        var mytimer;
        var stateToolbar;
        stateToolbar = false;
        mytimer = 0;
        if (!$.cookie('toolbarOpened')) {
            $.cookie('toolbarOpened', 'true', {
                expires: 15,
                path: '/'
            });
            $('.enableAnima .sidebarToolsDesktopReview .buttonList li>a').each(function (i, key) {
                mytimer += 220;
                return setTimeout(function () {
                    return $(key).addClass('active');
                }, mytimer);
            });
        }
        $('.sidebarToolsDesktopReview .buttonList li>a').on('mouseover', function () {
            return $('.sidebarToolsDesktopReview .buttonList li>a').removeClass('active');
        });
        return $(window).on('scroll', function () {
            if (!stateToolbar) {
                stateToolbar = true;
                return $('.sidebarToolsDesktopReview .buttonList li>a').each(function (i, key) {
                    setTimeout(function () {
                        return $(key).removeClass('active');
                    }, mytimer);
                    return mytimer += 220;
                });
            }
        });
    });
}(jQuery));;(function ($) {
    return $(document).ready(function () {
        var checkArrowDown;
        var stateArrowDown;
        stateArrowDown = false;
        checkArrowDown = function () {
            if ($(window).scrollTop() > $(window).height() / 2 && stateArrowDown === false) {
                TweenLite.to($('.arrowBottom'), 0.5, {
                    opacity: 0,
                    ease: Power1.easeOut,
                    onComplete: function () {
                        return $('.arrowBottom').hide();
                    }
                });
                return stateArrowDown = true;
            }
        };
        $(window).on('scroll', function () {
            checkArrowDown();
        });
        $('.arrowBottom').on('click', function (e) {
            return $('html, body').animate({ scrollTop: $(window).height() }, 800);
        });
        checkArrowDown();
        $('.cookieBarReviewDesktop').on('displayed', function () {
            if ($('body').hasClass('cookiesNotAccepted')) {
                return $('.arrowBottom').css({ bottom: 90 });
            }
        });
        return $('.cookieBarReviewDesktop').on('accepted', function () {
            return $('.arrowBottom').attr('style', '');
        });
    });
}(jQuery));