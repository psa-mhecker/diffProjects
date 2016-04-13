// CITROEN MAIN.JS
// LAST EDITED : 2015 / 08 / 26

/* CONTAINS :
1. - GENERAL SETTERS
2. - CLASSIC FUNCTIONS
	2.1 - shareThisButton // ADD THIS
	2.2 - iOSversion // iOS VERSION CHECK
	2.3 - isJQMGhostClick // GHOSTCLICK HANDLER FOR ANDROID NATIVE
	2.4 - clickzone // CLICKZONE
	2.5 - lazy // LAZY LOADING
	2.6 - Loader // LOADER
	2.7 - homepushCta // HEIGHT SETTER
	2.8 - magicCta // HEIGHT SETTER
	2.9 - $(window).scroll // BUTTON UP SCROLL
	2.10 - FooterPipes // FOOTER PIPES HANDLER
3. - SPECIFIC FUNCTIONS
	3.1 - SelectFeeder // MY PROJECT -> VEHICLES SELECTION -> FEEDER
	3.2 - swipeContent // SLIDER FOR DETAILS PAGES V1.0
	3.3 - SlidePages // SLIDER FOR DETAILS PAGES V2.0
	3.4 - iniTables // SCROLLABLE TABLES AND ASSOCIATED NOTES DISPLAYING
	3.5 - $container = $('.masonry') // SOCIAL WALL
	3.6 - menu // MENU
	3.7 - dropDownMenu // MENU -> DROPDOWN FOR LVL1 NAVIGATION
	3.8 - Accordion // ACCORDION - TOGGLE
	3.9 - bxSlider // SLIDER - USING BXSLIDER
	3.10 - checkboxSkin // FORMS -> CHECKBOXES
	3.11 - CarSelector // CAR SELECTOR AND FILTERS
	3.12 - nUiSlider // Minified source file
	3.13 - if( $( "#slider" ).length ) // CAR SELECTOR -> SLIDER FILTER
	3.14 - selectSkin // CAR SELECTOR -> SELECT SKIN
	3.15 - if($('#edit-vehicle-finish').length > 0) // CAR DETAILS -> FINISH CHECKBOXES
	3.16 - if($('.btn.dynscript').length > 0) // CAR DETAILS -> DISPLAY EQUIPMENTS & TECHNICAL NOTICE
	3.17 - colors // VEHICLE'S COLOR SELECTION
	3.18 - addSliderThumb // COLOR VEHICLE SELECTOR SLIDER
	3.19 - loadmore // LOADMORE
	3.20 - intMosaic // INTERACTIVE MOSAIC
	3.21 - if($('.hb').length > 0) // NOTES CLOSE BUTTON
	3.22 - mediaPop // MEDIA POPIN
	3.23 - noticePop // LEGAL NOTICES POPIN
	3.24 - if($('.connection-block-popin').length > 0) // LOGIN POPIN
	3.25 - if($('.citroenid-block-popin').length > 0) // CITROEN ID POPIN
	3.26 - if($('.subscribe-block-popin').length > 0) // SUBSCRIBE POPIN
	3.27 - readCookie // COOKIES READING
	3.28 - if($.fn.gLocator) // STORE LOCATOR
	3.29 - gtm_listener // GOOGLE TAG MANAGER
	3.30 - addThisScript // ADDTHIS SCRIPT CALL
	3.31 - shareThisButton // ADDTHIS INIT
	3.32 - linkMyCar // ADDTHIS INIT // 26 / 12 / 2014

*/

/* ***************************************************************************************************
 ** ***************************************************************************************************
 ** 1. - GENERAL SETTERS */

$('body').addClass('script');
window.scrollTo(0, 1);
var myScroll;
var scrollSwitch = 0;
var ie = (-1 != navigator.userAgent.indexOf('MSIE'));
var scrollTopV = $(window).scrollTop();

/* ***************************************************************************************************
 ** ***************************************************************************************************
 ** 2. - CLASSIC FUNCTIONS */

// ADDTHIS SCRIPT CALL
var addThisScript = function() {
    $.getScript("//s7.addthis.com/js/300/addthis_widget.js#username=ra-521f4a58354d5213&domready=1&async=1", function() {
        addthis.init();
        addthis.toolbox('.addthis_toolbox');
    });
}

// ADDTHIS INIT
var shareThisButton = function(addThisBtns) {
    if ($('.sharerm').length > 0) {
        var $addThisLayout = $('<div class="addthis_toolbox addthis_default_style addthis_32x32_style">' + addThisBtns + '</div>');
        $('.sharerm').append($addThisLayout);
        addThisScript();
    }

    $('.shareButton').each(function(indexb) {
        if(addThisBtns == "" && $(this).next() != undefined && $(this).next().val() != undefined){
            addThisBtns = $(this).next().val();
        }
        $(this).unbind('click');
        $(this).bind('click', function(e) {

            var layTitle = $(this).attr('data-text');

            var $addThisLayout = $('<div class="addthismask" id="addthismask' + indexb + '"></div><div class="addthislayer" id="addthislayer' + indexb + '"><div class="close"></div><span class="title">' + layTitle + '</span><div class="addthis_toolbox addthis_default_style addthis_32x32_style">' + addThisBtns + '</div></div>');
            $('.container').append($addThisLayout);


            $('#addthislayer' + indexb).css({
                top: parseInt(($(window).height() - $('#addthislayer' + indexb).height()) / 2) + 'px'
            });

            $('.content').css({
                top: -scrollTopV,
                left: 0
            });

            $('.container').addClass('popopen');
            $('.content').addClass('popopen');

            $('.container').css({
                height: $(window).height()
            });

            $('#addthismask' + indexb).css({
                width: $(window).width(),
                height: $(window).height()
            });

            $('#addthismask' + indexb).show();
            $('#addthislayer' + indexb).show()
            $('#addthislayer' + indexb + ' .close, #addthismask' + indexb).bind('click', function(e) {

                $('.container').removeClass('popopen');
                $('.content').removeClass('popopen');
                $('.content').css({
                    top: 0,
                    left: 0
                });
                $('.container').css({
                    height: 'auto'
                });
                $(window).scrollTo(scrollTopV, 0);
                $('#addthismask' + indexb).remove();
                $('#addthislayer' + indexb).remove();
            });

            addThisScript();
        });

    });
}

// iOS VERSION CHECK
function iOSversion() {
    if (/iP(hone|od|ad)/.test(navigator.platform)) {
        // supports iOS 2.0 and later: <http://bit.ly/TJjs1V>
        var v = (navigator.appVersion).match(/OS (\d+)_(\d+)_?(\d+)?/);
        return [parseInt(v[1], 10), parseInt(v[2], 10), parseInt(v[3] || 0, 10)];
    }
}

// GHOSTCLICK HANDLER FOR ANDROID NATIVE
var lastTapTime;

function isJQMGhostClick(event) {
    var currTapTime = new Date().getTime();
    if (lastTapTime == null || currTapTime > (lastTapTime + 800)) {
        lastTapTime = currTapTime;
        return false;
    } else {
        return true;
    }
}

// CLICKZONE (MAYBE DEPRECATED/
var clickzone = {
    build: function() {
        var link = $(this).find('a').attr('href');
        $(this).click(function(e) {
            e.preventDefault();
            e.stopPropagation();
            document.location = link;
        });
    }
};
$('[data-mobile="click"]').each(clickzone.build);

// LAZY LOADING
var lazy = {
    set: function($imgs) {
        $imgs.lazy({
            // general
            bind: "load",
            threshold: 1000,
            fallbackHeight: 2000,
            visibleOnly: true,
            // delay
            delay: -1,
            combined: false,
            // attribute
            attribute: "data-original",
            removeAttribute: true,
            // effect
            effect: "fadeIn",
            effectTime: 250,
            // throttle
            enableThrottle: true,
            throttle: 250,
            // callback
            beforeLoad: function(element) {},
            onLoad: function(element) {},
            afterLoad: function(element) {
                element.css({
                    display: 'inline-block'
                });
                element.removeClass('lazy');
                $(element).trigger("afterLoadDone");
                var elLink = element.parents('a');
                if ($(elLink).hasClass('video')) {
                    $(elLink).addClass('picto');
                }
            },
            onFinishedAll: function() {},
            onError: function(element) {}
        });
    },
    change: function($imgs) {
        $imgs.lazy({
            // general
            bind: "event",
            // delay
            delay: 0,
            combined: false,
            fallbackHeight: 1000,
            threshold: 2000,
            // attribute
            attribute: "data-original",
            removeAttribute: true,
            // effect
            effect: "fadeIn",
            effectTime: 250,

            // throttle
            enableThrottle: true,
            throttle: 250,
            visibleOnly: false,
            // callback
            beforeLoad: function(element) {},
            onLoad: function(element) {},
            afterLoad: function(element) {
                element.css({
                    display: 'inline-block'
                });
                element.removeClass('lazy');
                $(element).trigger("afterLoadDone");
                var elLink = element.parents('a');
                if ($(elLink).hasClass('video')) {
                    $(elLink).addClass('picto');
                }
            },
            onError: function(element) {}
        });
    },
    slider: function($imgs) {
        $imgs.lazy({
            // general
            bind: "event",
            // delay
            delay: 0,
            combined: false,
            fallbackHeight: 10000,
            threshold: 10000,
            // attribute
            attribute: "data-original",
            removeAttribute: true,
            // effect
            effect: "fadeIn",
            effectTime: 250,
            // throttle
            enableThrottle: true,
            throttle: 250,
            visibleOnly: false,
            // callback
            beforeLoad: function(element) {},
            onLoad: function(element) {},
            afterLoad: function(element) {
                element.css({
                    display: 'inline-block'
                });
                element.removeClass('lazy');
                $(element).trigger("afterLoadDone");
                var elLink = element.parents('a');
                if ($(elLink).hasClass('video')) {
                    $(elLink).addClass('picto');
                }
            },
            onError: function(element) {}
        });
    }
};
/* Set all lazy images except slider / layer ones */
lazy.set($('img.lazy').not('.slider img.lazy'));

// LOADER
var Loader = function(target) {
    this.init(target);
};
Loader.prototype = {
    init: function(target) {;
		this.interval = 0;

        this.tpl = $('<div class="loading"><div class="circ"></div></div>');
		this.$target = $(target) || $('body');
        this.$circ = this.tpl.find('.circ');


        if (!this.$target.is('body')) {
            this.$target.css('position', 'relative');
            this.tpl.css({
                'width': this.$target.innerWidth(),
                'height': this.$target.innerHeight(),
                'min-height': 0,
                'position': 'absolute'
            });
        }
        return this;
    },
    show: function(text, background) {
        var oThis = this;
        this.tpl.appendTo(this.$target);

        if (typeof text !== 'undefined' && text.length) {
            this.tpl.append('<div class="loading-text">' + text + '</div>');
            this.tpl.find('div').wrapAll("<div class='loading_content wtxt'/>");
            background = true;
        } else {
            if ($(oThis.$target).attr('data-loadtext')) {
                text = $(oThis.$target).attr('data-loadtext');
                this.tpl.append('<div class="loading-text">' + text + '</div>');
                this.tpl.find('div').wrapAll("<div class='loading_content wtxt'/>");
                background = true;
            } else {
                this.tpl.find('div').wrapAll("<div class='loading_content'/>");
            }
        }

        this.tpl.find('.loading_content').css('margin-top', (this.tpl.height() / 2) - 37);

        if (background == false) {
            this.tpl.css('background', 'none');
        }
    },
    hide: function() {
        clearInterval(this.interval);
        this.tpl.find('.loading_content div').unwrap();
        this.tpl.find('.loading_content').removeClass('wtxt');
        this.tpl.remove();
        this.tpl.find('.loading-text').remove();
        this.tpl.css('background', '');
    }
};


// HEIGHT SETTER -> HOME PAGE OFFERS BUTTONS
function homepushCta(setH) {
    if ($('.txt2').length > 0) {
        var maxH = setH;
        for (i = 0; i < $('.txt2 .btn').length; i++) {
            if ($('.txt2 .btn').eq(i).innerHeight() > maxH) {
                maxH = $('.txt2 .btn').eq(i).innerHeight();
            }
        }

        $('.txt2 .btn').each(function(index, el) {
            $(el).css({
                height: maxH
            });
        });
    }
}

// HEIGHT SETTER -> ALL CTA BUTTONS
var magicCta = function(root, col, target) {
    this.init(root, col, target);
};
magicCta.prototype = {
    init: function(root, col, target) {
        var oThis = this;
        oThis.root = root;
        oThis.col = $(oThis.root).find(col);
        oThis.target = (target != undefined) ? target : oThis.col;
        oThis.maxH = $(oThis.root).height();


        oThis.col.each(function(index) {
            if ((!oThis.root.hasClass('blocks')) && (!oThis.root.hasClass('noadjust'))) {
					if (oThis.target != oThis.col) {
						if ($(this).innerHeight() == oThis.maxH) oThis.setTarget($(this).find(oThis.target).height());
					} else {
						if ($(this).innerHeight() < oThis.maxH) {
							$(this).css({
								height: oThis.maxH
							});
						}
					}

            }
        });

    },
    setTarget: function(targetH) {
        var oThis = this;
        oThis.targetH = targetH;
        $(oThis.root).find(oThis.target).each(function() {
			if ((!oThis.root.hasClass('blocks')) && (!oThis.root.hasClass('noadjust'))) {
					$(this).css({
						height: oThis.targetH
					});

            }
        });
    }
}

$('.cta').not('.blocks').each(function() {
		$('.cta').not('.noadjust').each(function() { new magicCta($(this), 'li'); })
});


$('.media').each(function() {
    new magicCta($(this), 'a', 'span');
});



// BUTTON UP SCROLL
if($('.showroom-mobile').length>0){
    $('.btn-up').addClass('showroom-mobile');
}
$(window).scroll(function() {
    if (scrollSwitch == 0) {
        if ($(this).scrollTop() > 30) {
            $('.btn-up').fadeIn();
        } else {
            $('.btn-up').fadeOut();
        }
    }
});


// FOOTER PIPES HANDLER
var FooterPipes = function(root, elements) {
    this.init(root, elements);
}
FooterPipes.prototype = {
    init: function(root, elements) {
        var oThis = this;
        oThis.root = root;
        oThis.elements = elements;
        oThis.h = $(oThis.root).height();
        oThis.ref = elements.eq(0);
        oThis.refOT = oThis.ref.offsetTop;

        oThis.elements.each(function() {
            if (oThis.refOT == this.offsetTop) {
                if (oThis.ref != this) {
                    $(this).css({
                        borderLeft: '1px solid #fff'
                    });
                }
            } else {
                oThis.ref = this;
                oThis.refOT = oThis.ref.offsetTop;
            }
        });
    }
}
$('.footer').each(function() {
    new FooterPipes($(this), $(this).find('li'));
});

/* ***************************************************************************************************
 ** ***************************************************************************************************
 ** 3. - SPECIFIC FUNCTIONS */

// MY PROJECT -> VEHICLES SELECTION -> FEEDER
var SelectFeeder = function(root) {
    this.init(root);
}
SelectFeeder.prototype = {
    init: function(root) {
        var oThis = this;

        oThis.root = root;
        oThis.json = $(oThis.root).attr('data-feeder');
        oThis.formID = $(oThis.root).attr('ID');
        oThis.receipt = $(oThis.root).find('fieldset');
        oThis.visualCtnt = $(oThis.root).find('figure');
        oThis.visual = $(oThis.root).find('img');
        oThis.visual.on("afterLoadDone", function() {
            oThis.visualW = oThis.visual.width();
            oThis.visualH = oThis.visual.height();
        });
        oThis.lazySrc = $(oThis.visual).attr('src');
        oThis.dfltSrc = $(oThis.visual).attr('data-original');
        oThis.cta = $(oThis.root).find('.cta');
        oThis.dfltVehicule = $(oThis.root).attr('data-defaut-vehicule');
        oThis.dfltFinition = $(oThis.root).attr('data-defaut-finition');
        oThis.dfltVersion = $(oThis.root).attr('data-defaut-version');

        oThis.ctaH = oThis.cta.outerHeight() + parseInt(oThis.cta.css('marginTop'));

        oThis.build();
        oThis.ctaSwitch(0);

    },
    ctaSwitch: function(switcher) {
        var oThis = this;
        if (0 == switcher) {
            oThis.cta.hide();
            $(oThis.root).css({
                paddingBottom: oThis.ctaH
            });
        } else {
            $(oThis.root).css({
                paddingBottom: 0
            });
            oThis.cta.show();
        }
        $(window).trigger('resize');
    },
    imgRndr: function(dataSrc) {
        var oThis = this;
        $(oThis.visualCtnt).empty();
        $('<img />').appendTo($(oThis.visualCtnt));
        var visual = $(oThis.root).find('img');
        $(visual).attr('src', oThis.lazySrc);
        $(visual).attr('data-original', dataSrc);
        $(visual).css({
            height: oThis.visualH,
            width: oThis.visualW,
            minWidth: oThis.visualW,
            maxWidth: oThis.visualW
        });
        $(visual).addClass('lazy');

        lazy.change($(visual));
        $(visual).on("afterLoadDone", function() {
            setTimeout(function() {
                oThis.resizeW();
            }, 1);
        });

    },
    resizeW: function() {
        var oThis = this;
        $(window).trigger('resize');
    },
    build: function() {
        var oThis = this;
        $.ajax({
            url: oThis.json,
            type: "GET",
            dataType: "json",
            success: function(response) {
                if (response.vehicules.length > 0) {
                    // APPENDING SELECT ELEMENTS INTO OBJECT BINDED FORM
                    $('<select class="select1" name="mp-v-select1"><option value="0">' + response.label1 + '</option></select>').appendTo(oThis.receipt);
                    $('<select class="select2" name="mp-v-select2" disabled="true"></select>').appendTo(oThis.receipt);
                    $('<select class="select3" name="mp-v-select3" disabled="true"></select>').appendTo(oThis.receipt);
                    // GETTING THESE ELEMENTS INTO JQUERY OBJECT
                    oThis.selectLvl1 = $(oThis.root).find('.select1');
                    oThis.selectLvl2 = $(oThis.root).find('.select2');
                    oThis.selectLvl3 = $(oThis.root).find('.select3');
                    // SETTING SUB-LEVELS SELECTS LABELS
                    oThis.selectLvl2Label = '<option value="0">' + response.label2 + '</option>';
                    oThis.selectLvl3Label = '<option value="0">' + response.label3 + '</option>';
                    // DISABLING SUB-LEVELS SELECTS
                    oThis.selectLvl2.addClass('disabled');
                    oThis.selectLvl3.addClass('disabled');


                    oThis.lvl1slctd = "";
                    oThis.lvl1hsChldrn = "";

                    $(oThis.selectLvl2Label).appendTo(oThis.selectLvl2);
                    $(oThis.selectLvl3Label).appendTo(oThis.selectLvl3);
                    $(window).trigger('resize');

                    oThis.selectLvl1.on('change', function() {
                        // CHECKING SELECTED OPTION INDEX
                        oThis.selectLvl1.selected = oThis.selectLvl1.prop("selectedIndex");

                        // IF LABEL IS SELECTED
                        if (0 == oThis.selectLvl1.selected) {
                            oThis.selectLvl2.html('');
                            oThis.selectLvl3.html('');
                            $(oThis.selectLvl2Label).appendTo(oThis.selectLvl2);
                            $(oThis.selectLvl3Label).appendTo(oThis.selectLvl3);
                            oThis.selectLvl2.attr('disabled', true);
                            oThis.selectLvl3.attr('disabled', true);
                            oThis.selectLvl2.unbind('change');
                            oThis.selectLvl3.unbind('change');
                            oThis.selectLvl2.addClass('disabled');
                            oThis.selectLvl3.addClass('disabled');
                            oThis.ctaSwitch(0);

                            // IMAGE RENDERING
                            oThis.imgRndr(oThis.dfltSrc);
                        } else {

                            // GETTING AND DISPLAYING VEHICULE VISUAL

                            // IMAGE RENDERING
                            oThis.imgRndr(response.vehicules[oThis.selectLvl1.selected - 1].visuel);

                            // CHECKING IF THIS VALUE HAS CHILDREN
                            (response.vehicules[oThis.selectLvl1.selected - 1].finitions.length > 0) ? oThis.lvl1hsChldrn = true: oThis.lvl1hsChldrn = false;
                            // IF IT HAS NO CHILDREN
                            if (false == oThis.lvl1hsChldrn) {
                                // RESETING SUB-LEVELS SELECTS

                                // EMPTYING THE SELECTS
                                oThis.selectLvl2.html('');
                                oThis.selectLvl3.html('');
                                // SETTING THE LABELS
                                $(oThis.selectLvl2Label).appendTo(oThis.selectLvl2);
                                $(oThis.selectLvl3Label).appendTo(oThis.selectLvl3);
                                oThis.selectLvl2.attr('disabled', true);
                                oThis.selectLvl3.attr('disabled', true);
                                oThis.selectLvl2.unbind('change');
                                oThis.selectLvl3.unbind('change');
                                oThis.selectLvl2.addClass('disabled');
                                oThis.selectLvl3.addClass('disabled');
                                oThis.ctaSwitch(1);
                            }
                            // IF IT HAS CHILDREN
                            else {
                                /* ORIGINAL SCENARIO USED TO LOCK VALIDATION UNTIL EVERY PATH IS COMPLETE
										oThis.ctaSwitch(0);
									*/
                                oThis.ctaSwitch(1);
                                oThis.selectLvl1Value = this.value;

                                // RESETING SUB-LEVELS SELECTS

                                // EMPTYING THE SELECTS
                                oThis.selectLvl2.html('');
                                oThis.selectLvl3.html('');
                                // SETTING THE LABELS
                                $(oThis.selectLvl2Label).appendTo(oThis.selectLvl2);
                                $(oThis.selectLvl3Label).appendTo(oThis.selectLvl3);
                                //

                                oThis.lvl2slctd = "";
                                oThis.lvl2hsChldrn = "";

                                // MAKING SELECT SUB-LEVEL 2 USABLE
                                oThis.selectLvl2.attr('disabled', false);
                                oThis.selectLvl2.removeClass('disabled');
                                // LOCKING SELECT SUB-LEVEL 3
                                oThis.selectLvl3.attr('disabled', true);
                                oThis.selectLvl3.unbind('change');
                                oThis.selectLvl3.addClass('disabled');

                                oThis.selectLvl2.on('change', function() {
                                    // CHECKING SELECTED OPTION INDEX
                                    oThis.selectLvl2.selected = oThis.selectLvl2.prop("selectedIndex");
                                    // IF LABEL IS SELECTED
                                    if (0 == oThis.selectLvl2.selected) {
                                        oThis.selectLvl3.html('');
                                        $(oThis.selectLvl3Label).appendTo(oThis.selectLvl3);
                                        oThis.selectLvl3.attr('disabled', true);
                                        oThis.selectLvl3.unbind('change');
                                        oThis.selectLvl3.addClass('disabled');
                                        /* ORIGINAL SCENARIO USED TO LOCK VALIDATION UNTIL EVERY PATH IS COMPLETE
												oThis.ctaSwitch(0);
											*/

                                        // GETTING AND DISPLAYING VEHICULE VISUAL

                                        // IMAGE RENDERING
                                        oThis.imgRndr(response.vehicules[oThis.selectLvl1.selected - 1].visuel);
                                    } else {

                                        // GETTING AND DISPLAYING VEHICULE VISUAL

                                        // IMAGE RENDERING
                                        oThis.imgRndr(response.baseVisuel + response.vehicules[oThis.selectLvl1.selected - 1].finitions[oThis.selectLvl2.selected - 1].codeVisuel);

                                        // CHECKING IF THIS VALUE HAS CHILDREN
                                        (response.vehicules[oThis.selectLvl1.selected - 1].finitions[oThis.selectLvl2.selected - 1].versions.length > 0) ? oThis.lvl2hsChldrn = true: oThis.lvl2hsChldrn = false;

                                        // IF IT HAS NO CHILDREN
                                        if (false == oThis.lvl2hsChldrn) {
                                            // RESETING SUB-LEVELS SELECTS

                                            // EMPTYING THE SELECTS
                                            oThis.selectLvl3.html('');
                                            // SETTING THE LABELS
                                            $(oThis.selectLvl3Label).appendTo(oThis.selectLvl3);
                                            oThis.selectLvl3.attr('disabled', true);
                                            oThis.selectLvl3.unbind('change');
                                            oThis.selectLvl3.addClass('disabled');
                                            oThis.ctaSwitch(1);
                                        }
                                        // IF IT HAS CHILDREN
                                        else {
                                            /* ORIGINAL SCENARIO USED TO LOCK VALIDATION UNTIL EVERY PATH IS COMPLETE
													oThis.ctaSwitch(0);
												*/
                                            oThis.selectLvl2Value = this.value;

                                            // RESETING SUB-LEVELS SELECTS

                                            // EMPTYING THE SELECTS
                                            oThis.selectLvl3.html('');
                                            // SETTING THE LABELS
                                            $(oThis.selectLvl3Label).appendTo(oThis.selectLvl3);
                                            //

                                            oThis.lvl3slctd = "";
                                            oThis.lvl3hsChldrn = "";

                                            // MAKING SELECT SUB-LEVEL 2 USABLE
                                            oThis.selectLvl3.attr('disabled', false);
                                            oThis.selectLvl3.removeClass('disabled');
                                            oThis.selectLvl3.on('change', function() {
                                                // CHECKING SELECTED OPTION INDEX
                                                oThis.selectLvl3.selected = oThis.selectLvl3.prop("selectedIndex");

                                                // CHECKING IF THIS VALUE IS NOT THE LABEL (FIRST OPTION)
                                                if (0 != oThis.selectLvl3.selected) {
                                                    // GETTING AND DISPLAYING VEHICULE VISUAL

                                                    // IMAGE RENDERING
                                                    oThis.imgRndr(response.baseVisuel + response.vehicules[oThis.selectLvl1.selected - 1].finitions[oThis.selectLvl2.selected - 1].versions[oThis.selectLvl3.selected - 1].codeVisuel);

                                                    // END OF THE ROAD, DISPLAYING BUTTONS
                                                    oThis.ctaSwitch(1);
                                                }
                                                // IF LABEL IS SELECTED
                                                else {
                                                    // GETTING AND DISPLAYING VEHICULE VISUAL

                                                    // IMAGE RENDERING

                                                    oThis.imgRndr(response.baseVisuel + response.vehicules[oThis.selectLvl1.selected - 1].finitions[oThis.selectLvl2.selected - 1].codeVisuel);

                                                    /* ORIGINAL SCENARIO USED TO LOCK VALIDATION UNTIL EVERY PATH IS COMPLETE
															oThis.ctaSwitch(0);
														*/
                                                }
                                                oThis.dfltVersion = "";
                                            });

                                            // FEEDING SELECT SUB-LEVEL3
                                            for (var i in response.vehicules[oThis.selectLvl1.selected - 1].finitions[oThis.selectLvl2.selected - 1].versions) {
                                                (oThis.dfltVersion == response.vehicules[oThis.selectLvl1.selected - 1].finitions[oThis.selectLvl2.selected - 1].versions[i].id) ? oThis.lvl3slctd = "selected": oThis.lvl3slctd = "";
                                                $('<option value="' + response.vehicules[oThis.selectLvl1.selected - 1].finitions[oThis.selectLvl2.selected - 1].versions[i].id + '" ' + oThis.lvl3slctd + '>' + response.vehicules[oThis.selectLvl1.selected - 1].finitions[oThis.selectLvl2.selected - 1].versions[i].name + '</option>').appendTo(oThis.selectLvl3);

                                                if ("selected" == oThis.lvl3slctd) {
                                                    oThis.selectLvl3.change();
                                                }
                                            }
                                        }

                                    }
                                    oThis.dfltFinition = "";

                                });
                                // FEEDING SELECT SUB-LEVEL2
                                for (var i in response.vehicules[oThis.selectLvl1.selected - 1].finitions) {
                                    (oThis.dfltFinition == response.vehicules[oThis.selectLvl1.selected - 1].finitions[i].id) ? oThis.lvl2slctd = "selected": oThis.lvl2slctd = "";
                                    $('<option value="' + response.vehicules[oThis.selectLvl1.selected - 1].finitions[i].id + '" ' + oThis.lvl2slctd + '>' + response.vehicules[oThis.selectLvl1.selected - 1].finitions[i].name + '</option>').appendTo(oThis.selectLvl2);

                                    if ("selected" == oThis.lvl2slctd) {
                                        oThis.selectLvl2.change();
                                    }
                                }

                            }

                        }
                        oThis.dfltVehicule = "";
                    });


                    // LOOPING JSON's ANSWER AND BUILDING FIRST SELECT's OPTIONS
                    for (var i in response.vehicules) {
                        (oThis.dfltVehicule == response.vehicules[i].id) ? oThis.lvl1slctd = "selected": oThis.lvl1slctd = "";
                        $('<option value="' + response.vehicules[i].id + '" ' + oThis.lvl1slctd + '>' + response.vehicules[i].name + '</option>').appendTo(oThis.selectLvl1);
                        // IF THERE'S A DEFAULT VEHICULE SET
                        if ("selected" == oThis.lvl1slctd) {
                            oThis.selectLvl1.change();
                        }
                    }
                }
            }
        });

    }
}
$('.selectfeeder').each(function() {
    new SelectFeeder($(this));
});

// SLIDER FOR DETAILS PAGES V1.0
function swipeContent() {
    if ($('.pagenav.remote').length > 0) {
        var prevBtn = $('.pagenav.remote').find('.prev a');
        var nextBtn = $('.pagenav.remote').find('.next a');
        var prevLink = prevBtn.attr('href');
        var nextLink = nextBtn.attr('href');

        $('.swipezone').swipe({
            swipeLeft: function(event, direction, distance, duration, fingerCount) {
                nextBtn.trigger('click');
                document.location.replace(nextLink);
            },
            swipeRight: function(event, direction, distance, duration, fingerCount) {
                prevBtn.trigger('click');
                document.location.replace(prevLink);
            },
            threshold: 100
        });
    }
}

// SLIDER FOR DETAILS PAGES V2.0
var SlidePages = function(root) {
    this.build(root);
}
SlidePages.prototype = {
    build: function(root) {
        var oThis = this;

        oThis.root = root;
        oThis.pages = $(oThis.root).find('.page')
        oThis.startSlide = 1; // Set to 0 (previous) 1 (actual) 2 (next)
        oThis.currentSlide = oThis.startSlide;
        oThis.oStartSlide = $(oThis.pages).eq(oThis.startSlide);
        oThis.pageW = $(oThis.root).outerWidth();
        oThis.collection = $(oThis.root).attr('data-collection');
        oThis.generator = $(oThis.root).attr('data-generator');
        oThis.actualId = oThis.oStartSlide.attr('data-id');
        oThis.lazyPath = $(oThis.root).attr('data-lazy');
        oThis.shareText = $(oThis.root).attr('data-sharetext');

        oThis.pages.wrapAll("<div class='pages'></div>");

        oThis.init(oThis.root);
    },
    init: function() {
        var oThis = this;

        $(oThis.root).css({
            position: 'relative'
        });
        $(oThis.root).find('.pages').css({
            position: 'absolute',
            top: 0,
            width: (oThis.pageW * oThis.pages.length)
        });
        oThis.pages.css({
            display: 'inline',
            float: 'left',
            width: oThis.pageW
        });

        var slideLeft = $(oThis.pages).eq(oThis.currentSlide).position().left;
        $(oThis.root).find('.pages').css({
            left: -slideLeft
        });
        oThis.setHeight($(oThis.pages).eq(oThis.currentSlide));

        // Getting the ID
        $.ajax({
            url: oThis.collection,
            type: "GET",
            dataType: "json",
            success: function(reponse) {
                oThis.collectionLength = $(reponse.collection).length;
            }
        });
        oThis.initSlide();
    },
    setHeight: function(slide) {
        var oThis = this;
        oThis.pagesH = slide.outerHeight();
        oThis.lazy = slide.find('.lazy');
        if (oThis.lazy.length > 0) {
            oThis.lazy.each(function(index) {
                $(this).on("afterLoadDone", function() {
                    oThis.pagesH = slide.outerHeight();
                    $(oThis.root).animate({
                        height: oThis.pagesH
                    });
                });
            });
        } else {
            $(oThis.root).css({
                height: oThis.pagesH
            });
        }
        $(oThis.root).css({
            overflow: 'hidden'
        });

    },
    initSlide: function(root) {
        var oThis = this;
        $(oThis.root).swipe({
            swipeLeft: function(event, direction, distance, duration, fingerCount) {
                oThis.slideNext();
            },
            swipeRight: function(event, direction, distance, duration, fingerCount) {
                oThis.slidePrev();
            },
            threshold: 30
        });
    },
    slidePrev: function(root) {
        var oThis = this,
            arrival = oThis.currentSlide - 1,
            slideLeft = oThis.pages.eq(arrival).position().left;
        if (oThis.actualId > 1) {
            $(oThis.root.find('.pages')).animate({
                left: -slideLeft
            }, function() {
                oThis.currentSlide = arrival;
                oThis.arrangeSlide(-1);
            });
        }
    },
    slideNext: function(root) {
        var oThis = this,
            arrival = oThis.currentSlide + 1,
            slideLeft = oThis.pages.eq(arrival).position().left;
        if (oThis.actualId < oThis.collectionLength) {
            $(oThis.root.find('.pages')).animate({
                left: -slideLeft
            }, function() {
                oThis.currentSlide = arrival;
                oThis.arrangeSlide(1);
            });
        }
    },
    arrangeSlide: function(direction) {
        // Setting Variables
        var direction = direction,
            oThis = this,
            target = null,
            requestId = null,
            actualId = $(oThis.pages).eq(oThis.currentSlide).attr('data-id'), // /!\ pas sur
            requestIndex = null;
        oThis.actualId = actualId;

        // Setting DIVs in the right order
        if (0 == oThis.currentSlide) {
            $(oThis.pages).eq(oThis.pages.length - 1).prependTo($(oThis.root.find('.pages')));
        } else if (oThis.pages.length - 1 == oThis.currentSlide) {
            $(oThis.pages).eq(0).appendTo($(oThis.root.find('.pages')));
        }

        oThis.currentSlide = 1;
        oThis.pages = $(oThis.root).find('.page');
        var slideLeft = $(oThis.pages).eq(oThis.currentSlide).position().left;
        $(oThis.root).find('.pages').css({
            left: -slideLeft
        });
        oThis.pagesH = oThis.pages.eq(oThis.currentSlide).outerHeight();
        $(oThis.root).animate({
            height: oThis.pagesH
        });
        oThis.setHeight($(oThis.pages).eq(oThis.currentSlide));

        // Setting the receiver
        if (-1 == direction) {
            target = oThis.pages.eq(oThis.currentSlide - 1);
        } else if (1 == direction) {
            target = oThis.pages.eq(oThis.currentSlide + 1);
        }


        shareThisButton(addThisBtns);

        // Getting the ID if there's something more to load
        if (actualId > 1 && actualId < oThis.collectionLength) {
            $.ajax({
                url: oThis.collection,
                type: "GET",
                dataType: "json",
                success: function(reponse) {
                    $(reponse.collection).each(function(index) {
                        if (actualId == this.id) {
                            requestIndex = index + direction;
                            requestId = reponse.collection[requestIndex].id;
                            oThis.callContent(requestId, target);
                        }
                    });
                }
            });
        }
    },
    callContent: function(requestId, target) {
        var oThis = this;

        /* PHP VERSION */
        $.ajax({
            url: oThis.generator + "?dataId=" + requestId,
            type: "GET",
            dataType: "html",
            success: function(response) {
                $(target).html(response);
                $(target).attr('data-id', requestId);
                lazy.change($('.lazy', target));
            }
        });

        /* VERSION TEST JSON //
			$.ajax({
				url: oThis.collection,
				type: "GET",
				dataType: "json",
				success : function(response){
					$(response.collection).each(function(index){
						if(requestId==this.id){

							var contentImg = this.image,
								contentTitle = this.title,
								contentDate = this.date,
								contentHat = this.hat,
								contentText = this.text;

							var template =
								'<div class="head"> \
								<header class="news"> \
								<figure><img class="lazy" src="'+oThis.lazyPath+'" data-original="'+contentImg+'" alt="'+contentTitle+'" /></figure> \
								<div class="txt"> \
								<span class="shareButton st_sharethis_custom" data-text="'+oThis.shareText+'">'+oThis.shareText+'</span> \
								<em class="date">'+contentDate+'</em> \
								<h1 class="title-lvl0"><span>'+contentTitle+'</span></h1> \
								</div> \
								</header> \
								</div>  \
								<section> \
								<div class="addthis_toolbox addthis_default_style social-buttons"> \
								<a class="addthis_button_tweet" tw:count="vertical"></a> \
								<a class="addthis_button_facebook_like" fb:like:layout="box_count" fb:like:height="63"></a> \
								<a class="addthis_button_google_plusone" g:plusone:size="Tall"  ></a> \
								</div> \
								<div class="hat"> \
								'+contentHat+' \
								</div>\
								<div class="text"> \
								'+contentText+' \
								</div>\
								</section>';

							$(target).html(template);
							$(target).attr('data-id',requestId);
							lazy.change($('.lazy',target));
						}
					});
				}
			});
			*/
    }
}
$('.slidepages').each(function() {
    new SlidePages($(this));
});

// SCROLLABLE TABLES AND ASSOCIATED NOTES DISPLAYING
var tabScroll = new Array();
var iniTables = function(root) {
    this.init(root);
}
iniTables.prototype = {
    init: function(root) {
        var oThis = this;
        oThis.root = root;
        oThis.note = $(oThis.root).find('.table-scroll-note');
        oThis.tables = $(oThis.root).find('table');

        if (oThis.note.length > 0) oThis.noteInit();
        if (oThis.tables.length > 0) oThis.build();

    },
    build: function(root) {
        var oThis = this;
        oThis.tables.each(function(index, el) {
            if ($(el).parents('.accordion.onglets')) {
                $(this).on('tablescroll', function(){
                    oThis.iscroll(el, index);
                });
            } else {
                oThis.iscroll(el, index);
            }
        });
    },
    iscroll: function(el, index) {
        var oThis = this;
        var context = $(el).parents('.text, .notice-text');
        $(el).wrap('<div class="table-scroll" id="tabscroll' + index + '"></div>');
        $('#tabscroll' + index).css({
            height: $(el).outerHeight()
        });
        tabScroll[index] = new iScroll('tabscroll' + index, {
            vScroll: false,
            hScrollbar: false,
            vScrollbar: false,
            bounce: true,
            useTransform: true,
            onBeforeScrollStart: null,
            onScrollMove: function() {
                oThis.note.fadeOut(1000);
            }
        });
        tabScroll[index].refresh();

    },
    noteInit: function() {
        var oThis = this;

        if (oThis.note.hasClass('simple') && sessionStorage.clickcount != 1) {
            //oThis.note.css({display:'block'});
        }
        oThis.note.click(function() {
            oThis.note.css({
                display: 'none'
            });
            var tog = 1;

            if (oThis.note.hasClass('simple')) {
                sessionStorage.clickcount = 1;
            }
        });
        var tog = 0;
    }
}
$('.swipezone').each(function() {
    new iniTables($(this));
});

// SOCIAL WALL
try {
    var $container = $('.masonry');
    if (0 < $container.length) {
        //$('.zoner').each(zoner.build);
        var loader = new Loader();
        var url = $container.attr('data-ws'),
            start = 0,
            end = 5,
            package = 5,
            nextstart = 0,
            addItems = function(start) {
                loader.show();
                $.ajax({
                    url: url,
                    data: {
                        start: start || 0
                    },
                    success: function(response) {
                        package = response.length;
                        end = start + eval(package - 1);
                        var tpl = $('#masonryTpl').html();
                        var datas = [];
                        $(response.data).each(function(i) {
                            datas.push(response.data[i]);
                        });
                        var newJson = {
                            "length": response.length,
                            "data": datas,
                            "nextstart": nextstart
                        };
                        var compiledTemplate = _.template(tpl, {
                            obj: newJson
                        });
                        $container.append(compiledTemplate);
                        $container.find('.added').each(function(index) {
                            $(this).removeClass('added').find('img').load(function() {
                                $container.masonry();
                            });
                            $container.masonry('appended', this);
                        });
                        loader.hide();
                        $container.masonry();
                        if (response.nextstart) {
                            $('.masonry + .addmore a').show().unbind('click').click(function() {
                                addItems(response.nextstart);
                            });
                        } else {
                            $('.masonry + .addmore a').remove();
                        };
                    }
                });
            };
        if (url) {
            /* Initialize masonry */
            $container.masonry({
                singleMode: true,
                resizeable: true,
                columnWidth: '.item',
                itemSelector: '.item',
                isAnimated: false
            });
            /* Initialize updater */
            //$('.masonry + .addmore a').hide();
            /* Get first set */
            addItems(start);
        };
    };
} catch (e) {};

// MENU
var menu = {
    $dom: $('.nav'),
    $search: $('.content').find('header').find('.search'),
    $btn: $('.content').find('header').find('.menu'),
    build: function() {

        $('.nav nav li.upper').each(function(index, el) {
            /* GESTION DES CLASSES SUR LES NIVEAU 1 */
            if (0 == index) {
                $(el).addClass('first-of-type');
            }
            if ($('.nav nav li.upper').length - 1 == index) {
                $(el).addClass('last-of-type');
            }

            /* GESTION DES CLASSES SUR LES NIVEAU 2 */
            if ($('li', el).length > 0) {
                $('li', el).each(function(index, el2) {

                    if (0 == index) {
                        $(el2).addClass('first-of-type');
                    }
                    if ($('li', el).length - 1 == index) {
                        $(el2).addClass('last-of-type');
                    }
                });
            }
            /* SI PAS DE NIVEAU 2, CHANGEMENT DE STYLE DU N1 */
            else {
                $(el).addClass('no-child');
            }
        });
        $('.nav nav li.brd').each(function(index, el) {
            if (0 == index) {
                $(el).addClass('first-of-type');
            }
            if ($('.nav nav li.brd').length - 1 == index) {
                $(el).addClass('last-of-type');
            }
        });
        $(this).click(menu.toggle);
        if (navigator.userAgent.toLowerCase().indexOf('android') > -1) {
            $('.nav nav a').bind('touchstart', function() {
                $(this).addClass('fake-active');
            }).bind('touchend', function() {
                $(this).removeClass('fake-active');
            }).bind("touchcancel", function() {
                var $this = $(this);
                $this.removeClass('fake-active');
            });
        }

        myScroll = new iScroll('wrapper', {
            hScrollbar: false,
            vScrollbar: false,
            bounce: false,
            onBeforeScrollStart: function(e) {
                var target = e.target;
                while (target.nodeType != 1) target = target.parentNode;

                if (target.tagName != 'SELECT' && target.tagName != 'INPUT' && target.tagName != 'TEXTAREA')
                    e.preventDefault();
            },
            onTouchEnd: function(){

            }
        });
        myScroll.refresh();
    },
    toggle: function() {
        var open = menu.$dom.hasClass('open');
        if (open) menu.close();
        else menu.open();
    },
    open: function() {
        menu.$btn.addClass('on');
        menu.$dom.addClass('open');
        $('nav').bind('click', function(e) {
            $('#search').blur();
        });
        $('#search').bind('focus, click, tapone', function(e) {
            e.preventDefault();
            e.stopPropagation();
        });
        $('.content').bind('click', function() {
            $('.content').bind('click', menu.close);
            $('#search').blur();
        });

        var testMsGesture = window.navigator.msPointerEnabled;
        if (testMsGesture) {
            $('body').addClass('scroll-no-touch-action');
        }

        setTimeout(function() {
            $('body').css({
                overflow: 'hidden'
            });
            $('.content').css({
                overflow: 'hidden'
            });
            $('.content').css({
                height: '100%'
            });
            $('.container').css({
                height: '100%'
            });
            myScroll.refresh();
        }, 0);
    },
    close: function(e) {
        if (e) {
            e.preventDefault();
            e.stopPropagation();
        };
        menu.$dom.removeClass('open');
        menu.$btn.removeClass('on');
        $('nav').unbind('click');
        $('#search').unbind('focus, click, tapone');
        $('.content').unbind('click');
        $('.nav nav a').unbind('touchstart, touchend, touchcancel');

        var testMsGesture = window.navigator.msPointerEnabled;
        if (testMsGesture) {
            $('body').removeClass('scroll-no-touch-action');
        }
        $('body').css({
            overflow: 'auto'
        });
        $('.content').css({
            overflow: 'auto'
        });
        $('.container').css({
            height: 'auto'
        });
    }
};
$('header .search').click(function(e) {
    if (!$('.nav').hasClass('open')) $('header .menu').trigger('click');
    $('#search').focus();
    e.stopPropagation();
    e.preventDefault();
});
$('header .menu').each(menu.build);

// MENU -> DROPDOWN FOR LVL1 NAVIGATION
var dropDownMenu = function() {
    var $li = $('.nav').find('.upper'),
        $li_a = $li.find(" > a");
    var opened = 'null';

    $li_a.each(function(i, el) {

        $(el).bind('click', function(e) {

            var _this = $(el).parent();
            //prevent same event from firing twice
            if (isJQMGhostClick(e)) {
                return;
            }
            if (!_this.hasClass('no-child')) {
                e.preventDefault();
                e.stopPropagation();

                if (!_this.hasClass('open') && _this.hasClass('upper')) {
                    if (opened != 'null') {
                        $li.eq(opened).find('ul').slideUp(150, function() {
                            $li.eq(opened).removeClass('open')
                        });

                    }
                    _this.addClass('open').find('ul').slideDown(200, function() {
                        opened = i;
                        setTimeout(function() {
                            myScroll.refresh();
                        }, 0);
                    });
                } else {
                    _this.find('ul').slideUp(150, function() {
                        _this.removeClass('open');
                        opened = 'null';
                        setTimeout(function() {
                            myScroll.refresh();
                        }, 0);
                    });
                }
            }
        });
    });
}
var setAccordions = function() {

    $('.showroom-mobile .accordion').each(function() {
        var dftStyles = $(this).attr('data-off');
        var onStyles = $(this).attr('data-on');
        if($(this).attr('data-firstoff') && $(this).attr('data-firsthover')){
            var dftFirstStyles = $(this).attr('data-firstoff');
            var hvrFirstStyles = $(this).attr('data-firsthover');
        }

        $(this).find('a').each(function(index) {
            if(dftFirstStyles != undefined && index==0){
                $(this).attr('style', dftFirstStyles);

                $(this).on('mouseenter', function(e) {
                    e.stopPropagation();
                    $(this).attr('style', hvrFirstStyles);

                }).on('mouseleave', function(e) {
                    e.stopPropagation();
                    $(this).attr('style', dftFirstStyles);
                });

            } else {
                $(this).attr('style', dftStyles);

                $(this).on('mouseenter', function(e) {
                    e.stopPropagation();
                    $(this).attr('style', hvrStyles);
                    //console.log('hover / '+hvrStyles)

                }).on('mouseleave', function(e) {
                    e.stopPropagation();
                    $(this).attr('style', dftStyles);
                });
            }

        });
    });
}
setAccordions();

// ACCORDION - TOGGLE
var Accordion = function(root) {
    this.init(root);
}
Accordion.prototype = {
    init: function(root) {
        var oThis = this;
        oThis.root = root;
        oThis.items = $(oThis.root).find('.item');
        oThis.section = $(oThis.root).parent('section');
        oThis.itemDflt = eval($(oThis.root).attr('data-init'));
        ($(oThis.root).attr('data-tie') != "all") ? oThis.tie = false: oThis.tie = true;
        if($(oThis.root).parents().hasClass('showroom-mobile')){
            oThis.dftStyles = $(oThis.root).attr('data-off');
            oThis.onStyles = $(oThis.root).attr('data-on');
        }
        oThis.items.each(function(i) {
            var oItem = this;
            var head = $(oItem).prev();
            $(head).attr('style',oThis.dftStyles);
            if (oThis.itemDflt == i){
                $(head).addClass('open');
                $(head).attr('style',oThis.onStyles);
            }
            if (!$(head).hasClass('open')) {
                $(head).addClass('close');
                $(oItem).addClass('close');
                $(head).attr('style',oThis.dftStyles);
                $(oItem).hide();
            } else {
                $(oItem).addClass('open');
                $(head).attr('style',oThis.onStyles);
            }
            $(head).each(function(index, el) {
                $(this).on('click', function() {
                    ($(el).hasClass('open')) ? oThis.close(oItem): oThis.open(oItem);
                });

            });
        });

    },
    open: function(element) {
        var oThis = this;
        $(element).show();
        if (oThis.tie == true) {
            $(oThis.items).each(function() {
                if (this != element) {
                    oThis.close(this)
                }
            });
        }
        if($(element).find('table').length>0){
            $('body').trigger('tablescroll');
        }
        var head = $(element).prev();
        $(head).removeClass('close');
        $(head).addClass('open');
        $(head).attr('style',oThis.onStyles);
        $(element).removeClass('close');
        $(element).addClass('open');
        var imgs = $(this).find('img.lazy');
        lazy.change(imgs);
        //globalRefresh();
        $(window).trigger('resize');

    },
    close: function(element) {
        var oThis = this;
        $(element).hide();
        var head = $(element).prev();
        $(head).removeClass('open');
        $(head).addClass('close');
        $(head).attr('style',oThis.dftStyles);
        $(element).removeClass('open');
        $(element).addClass('close');
    }

}
$('.accordion').each(function() {
    new Accordion($(this));
});

// 360deg view



var create360view = function(root) {
    this.init(root);
}
create360view.prototype = {
    init: function(root) {
        var oThis = this;
        oThis.root = root;
        oThis.view = $(oThis.root).find('.view-360');
        oThis.statusView = 0;
        oThis.tabImages = window.tabImages;

        $(window).on('resize orientation', function(e){
            oThis.setDim();
        });


        if(oThis.tabImages!="undefined" && oThis.tabImages.length>0){
            //$(oThis.view).css({'top':-($(window).height())});
            /* SWITCH VIEWS */
            if($(oThis.root).find('.view-selector').length>0){
                var canvas = document.createElement('canvas'), context;

				//iOS browser dectector :
				var isiOS = {

					browser: (/iPad|iPhone|iPod/.test(navigator.platform)),

					detectedVersion: function () {
													if (!!window.indexedDB) { return 8; } 					// iOS 8
													if (!!window.SpeechSynthesisUtterance) { return 7; } 	// iOS 7
													if (!!window.webkitAudioContext) { return 6; }  		// iOS 6
													if (!!window.matchMedia) { return 5; } 					// iOS 5
													if (!!window.history && 'pushState' in window.history) { return 4; }  // iOS 4
													return 100; // Si non trouvé, on considère que la version est la plus récente.
					 }
				};

                if ( canvas.getContext && !(isiOS.browser && isiOS.detectedVersion() <= 7)   ) {
                    $(oThis.root).find('.view-selector').css({'display':'inline-block'});
                }

                $(oThis.root).find('.view-selector').find('> div').each(function(){
                    if($(this).hasClass('inside')){
                        $(this).find('a').on('click', function(e){
                            e.preventDefault();
                            oThis.build(oThis.root);
                            $(this).addClass('active');
                            oThis.statusView = 1;
                        });
                    }
                });
                $(oThis.view).find('.closer').on('click', function(){
                    $(oThis.view).css({'top':-3000});
                    $(oThis.root).find('.active').removeClass('active');
                });

            }
        }
    },
    setDim : function(){
        var oThis = this;
        $(oThis.view).find('canvas').css({
            'width':$(oThis.view).width(),
            'height':$(oThis.view).height()
        })
    },
    build: function(root){
        var oThis = this;
		oThis.root = root;

        if(oThis.statusView != 1){
            var canvas = document.createElement('canvas'), context;
            if (canvas.getContext) {

                oThis.loader = new Loader(oThis.root);
                oThis.loader.show();
                oThis.scriptsLoaded=0;
                oThis.loadScript(oThis.root);
            }

            oThis.setDim();

        } else {
            oThis.loader.hide();
            $(oThis.view).css({'top':0});
        }

    },
    loadScript: function(root){
        var oThis = this;
        oThis.root = root

            var t = $.getScript( tabScripts[oThis.scriptsLoaded] )
            .done(function( script, textStatus ) {
                if(oThis.scriptsLoaded<tabScripts.length-1){
                    oThis.scriptsLoaded = oThis.scriptsLoaded +1;
                    oThis.loadScript(oThis.root);

                } else {
                    $(oThis.view).css({'top':0});
                    oThis.loader.hide();
                    oThis.callBack360()
                }

            })
            .fail(function( jqxhr, settings, exception ) {

                $(oThis.root).find('.view-selector').find('> div').each(function(){
                    if($(this).hasClass('inside')){
                        $(this).find('a').hide();
                    }
                });


                //console.log( tabScripts[me.scriptsLoaded] );
                //console.log( exception );
                //console.log('error loading JS')
            });

    },
       callBack360: function(inc){
        var oThis = this;
        //console.log('All scripts loaded');
        (function($, Inside, PointOfInterest) {
            "use strict";
            oThis.inside = new Inside(document.getElementById('canvas'), $(oThis.view));
            console.log(oThis.inside);
            oThis.inside.cubeSize = 100;
            oThis.inside.init(oThis.tabImages);
            oThis.inside.start();
        }(window.jQuery, NameSpace('inside.Inside'), NameSpace('inside.object3D.PointOfInterest')));

            gtmCit.initVue360($('.view-360'));
            $('.view-360').on('mousedown touchstart', function(){

                if($(this).hasClass('init')){
                    $(this).removeClass('init');
                }
            })
    }
}
$('.color-selector').each(function() {
    if($(this).find('.view-360').length>0){
        this.myView = new create360view($(this));
    }
});

// SLIDER - USING BXSLIDER

var createSlider = function(root) {
    this.init(root);
}
createSlider.prototype = {
    init: function(root) {
        var oThis = this;
        oThis.root = root;
        // LOADING
        $(oThis.root).addClass('set');
        oThis.loader = new Loader(root);
        oThis.loader.show();

        oThis.row = $(oThis.root).find('> div.row');
        oThis.items = $(oThis.row).find('> article');
        oThis.imgs = $(oThis.row).find('img.lazy');
        oThis.class = $(oThis.row).attr('class');
        oThis.colnum = parseInt(oThis.class.substr(oThis.class.indexOf('of') + 2, 1));
        oThis.margin = (1 == oThis.colnum || $(oThis.row).hasClass('collapse')) ? 0 : 10;
        oThis.infloop = ($(oThis.root).hasClass('evf') == true) ? false : true;
        oThis.checkLoad = 0;

        if( $(oThis.root).parents().hasClass('showroom-mobile')){
            oThis.pagerOff = $(oThis.root).attr('data-pageroff');
            oThis.brdr = $(oThis.root).attr('data-border');

            oThis.pagerOn = $(oThis.root).attr('data-pageron');
            //console.log(oThis.pagerOff)
        }



        oThis.isCss;

        if ((navigator.userAgent.match(/iPhone/i)) || (navigator.userAgent.match(/iPod/i))) {
            var ver = iOSversion();
            if (undefined != ver) {
                if (ver[0] > 6) {
                    oThis.isCss = false;
                } else {
                    oThis.isCss = true;
                }
            } else {
                oThis.isCss = true;
            }
        } else {
            oThis.isCss = true;
        }

        lazy.slider(oThis.imgs);
        if (oThis.imgs.length > 0) {
            oThis.imgs.on("afterLoadDone", function() {
                oThis.checkLoad = oThis.checkLoad + 1;
                if (oThis.checkLoad == oThis.imgs.length) {
                    $(oThis.root).removeClass('set');
                    if (oThis.items.length > oThis.colnum) {
                        oThis.build(root);
                    } else {
                        oThis.loader.hide();
                    }
                }
                //setTimeout(function(){ $(window).trigger('resize'); }, 1);
            });
        } else {
            $(oThis.root).removeClass('set');
            if (oThis.items.length > oThis.colnum) {
                oThis.build(root);
            } else {
                oThis.loader.hide();
            }
        }

    },
    build: function(root) {
        var oThis = this;

        oThis.obj = {
            infiniteLoop: oThis.infloop,
            speed: 250,
            slideWidth: 5000,
            minSlides: oThis.colnum,
            maxSlides: oThis.colnum,
            moveSlides: oThis.colnum,
            adaptiveHeight: true,
            adaptiveHeightSpeed: 100,
            useCSS: oThis.isCss,
            slideMargin: oThis.margin,
            swipeThreshold: 50,
            oneToOneTouch: true,
            onSliderLoad: function() {
                var slideQty = oThis.row.getSlideCount();
                if (slideQty <= oThis.colnum) {
                    $(".bx-default-pager", $(oThis.root)).hide()
                }
                //console.log(oThis.brdr);
                var concStyle =  $(oThis.root).find('.bx-wrapper').attr('style') + oThis.brdr;
                $(oThis.root).find('.bx-wrapper').attr('style', concStyle);
                oThis.loader.hide();

                //CPW-4297
                $(oThis.root).find('.bx-clone').each(function(ind, el) {
                    $(el).attr('id', $(el).attr('id') + '_transition');
                });

            },
            onSlideBefore: function(dom, oldi, newi) {
                // Has GTM
                if($(oThis.root).attr('data-gtm-init')){

                }

                // Has associated content 'dynscript'
                var dyn = dom.find('.btn.dynscript').get(0);
                if (dyn) {
                    var $placeholders = $('#car-details .hubs');

                    if ($placeholders.length > 0) {

                        if (dyn._opened) {
                            $placeholders.hide();
                            $('.car-details' + newi).show();
                        } else {
                            $placeholders.hide();
                        };

                    };

                };

            },
            onSlideAfter: function(dom, oldi, newi) {
            }
        };
        $(oThis.root).attr('data-gtm-init', '0')
        oThis.row.bxSlider(oThis.obj);
        gtmCit.initSlider($(oThis.root));

    }

}
$(document).ready(function(){
    $('.slider').each(function() {
        this.mySlider = new createSlider($(this));
    });
});

var changeSlide = false;

// FORMS -> CHECKBOXES
var checkboxSkin = function() {
    var $form_lvl = $('.form-lvl');

    $form_lvl.find('label').bind("click", function() {}); // iphone click

    $form_lvl.find('input[type=checkbox]').bind("change", function() {

        var _this = $(this),
            nxt = _this.prev();

        if (!_this.is(':checked')) {
            nxt.removeClass('active');
            _this.attr('checked', '');
        } else {
            nxt.addClass('active');
            _this.attr('checked', 'checked');
        }
    });

    // reset form filters
    /*
	$('.reset').find('button').click( function(){
		$slide.slider("option", "value", $('#price').val());
    	$slide.slider("value", $slide.slider("value"));
    	$( "#amount" ).text(  $( "#slider" ).slider( "value" ) + " �" );
    	$( "#price" ).val(  $( "#slider" ).slider( "value" ) );
		$('#amount').css({ left : $('#slider .ui-slider-handle').position().left });

    	$form_lvl.find('.active').removeClass('active');
    	$form_lvl.find('input').attr('checked',false);
	});
	*/
}


// CAR SELECTOR -> SLIDER FILTER
if ($("#slider").length) {
    var devisebefore = $("#devisebefore").val();
    var deviseafter = $("#deviseafter").val();
    var amountprice = function(event, ui) {
        $("#amount").text("< " + devisebefore + " " + $('#slider').val() + " " + deviseafter);
        $("#price").val($('#slider').val());
        if ($('.noUi-handle').css('left') == '0px') {
            $('.noUi-handle').css('left', '-1px');
        }
    };
    /*
    	var $slide =$("#slider").slider({
    		value: eval($('#price').val()),
    		min: eval($('#price').attr('data-from')),
    		max: eval($('#price').attr('data-to')),
    		step: eval($('#price').attr('data-step')),
    		slide: amountprice,
    		change: function( event, ui ){
    			//call function to filterCarSelector
    			if( !changeSlide ){
    				//filterCarSelector();
    			}
    		},
    		stop: amountprice,
    		animate: "fast"

    	});
    	*/
    var sliderRange = $("#slider");
    sliderRange.noUiSlider({
        start: eval($('#price').val()), //
        range: {
            'min': eval($('#price').attr('data-from')), //
            'max': eval($('#price').attr('data-to')) //
        },
        step: eval($('#price').attr('data-step')) //
    });
    var dragHalf = $('#slider .noUi-handle').width() / 2;

    sliderRange.on({
        slide: function() {
            amountprice();
            //$( "#amount" ).css({ left: $('#slider .noUi-handle').position().left - dragHalf });
        }
    });

    $("#amount").appendTo($('#slider .noUi-handle'));
    $("#amount").text("< " + devisebefore + " " + $("#slider").val() + " " + deviseafter);
    $("#price").val($("#slider").val())
}

// CAR SELECTOR AND FILTERS
var CarSelector = function(root) {
    this.init(root);
}
CarSelector.prototype = {
    init: function(root) {
        this.root = $(root);
        this.inputs = this.root.find('input');
        this.mastercarsGroup = $('.mastercars-group');
        this.resetButton = this.root.find('.reset button');
        this.loader = new Loader();

        this.setHandlers();
    },
    setHandlers: function() {
        var oThis = this;
        this.inputs.click(function() {
            oThis.ajaxPost();
        });


        var sliderRange = $("#slider");
        sliderRange.on({
            set: function() {
                oThis.ajaxPost();
                //$( "#amount" ).css({ left: $('#slider .noUi-handle').position().left - dragHalf });
            }
        });

        this.resetButton.click(function(e) {
            e.preventDefault();
            oThis.resetForm();
        });
    },
    ajaxPost: function() {
        var oThis = this;

        var request = $.ajax({
            url: oThis.root.attr('action'),
            type: "POST",
            data: oThis.root.serialize(),
            dataType: "html",
            beforeSend: function(jqXHR, textStatus) {
                scrollTopV = $(window).scrollTop();

                $('.content').css({
                    top: -scrollTopV,
                    left: 0
                });

                $('.container').addClass('popopen');
                $('.content').addClass('popopen');

                $('.container').css({
                    height: $(window).height()
                });

                oThis.loader.show();
                $('.loading').css({
                    width: $(window).width(),
                    height: $(window).height()
                });
            }
        });

        request.done(function(response) {

            $('.container').removeClass('popopen');
            $('.content').removeClass('popopen');
            $('.content').css({
                top: 0,
                left: 0
            });
            $('.container').css({
                height: 'auto'
            });
            $(window).scrollTo(scrollTopV, 0);

            oThis.loader.hide();
            //$(window).scrollTo( oThis.mastercarsGroup, 250);
            oThis.mastercarsGroup.html(response);
            lazy.change(oThis.mastercarsGroup.find('img.lazy'));
        });

        request.fail(function(jqXHR, textStatus) {});
        /*
			request.beforeSend(function( jqXHR, textStatus ) {
					oThis.loader.show();
			});*/
    },
    resetForm: function() {
        this.root[0].reset();
        this.root.find('input[type="checkbox"], input[type="radio"]').removeAttr('checked');
        $("#slider").val(eval($('#price').attr('data-to')));
        $("#amount").text("< " + devisebefore + " " + $("#slider").val() + " " + deviseafter);
        $("#price").val($("#slider").val());

        this.root.find('.active').removeClass('active');
        this.root.find('input').attr('checked', false);

        this.root.find('.range').each(function() {
            var input = this.getElementsByTagName('input')[0],
                defaultValue = input.getAttribute('data-to');

            input.value = defaultValue;
            if (input.name === 'passengers') {
                input.value = input.getAttribute('data-from');
            }

            $(input).trigger('change');
        });

        this.root.find('input[value*=TOUT]').each(function() {
            this.checked = true;
        });
        this.ajaxPost();
    }
}

// CAR SELECTOR -> SELECT SKIN
var selectSkin = function() {
    var $select = $('.select'),
        $box_lvl2 = $('.box-lvl2'),
        $url = $select.parents('form').attr('action');

    if ($select.length) {
        //$select.select2();
        $select.change(function() {
            var _this = $(this),
                _val = _this.val();

            if (_val) {

                // CAS "MY PROJECT" RECUPERATION DE VERSION DE VEHICULE
                if ($select.parents('form').hasClass('evv')) {
                    var opt = $('option:selected', $select).index() - 1;
                    $select.parents('form').find('.row').css({
                        display: 'none'
                    });
                    if (opt >= 0) {
                        $select.parents('form').find('.row').eq(opt).css({
                            display: 'block'
                        });
                    }
                } /* FIN */

                $.ajax({
                    type: 'GET',
                    url: $url,
                    data: $select.parents('form').serialize(),
                    dataType: 'html',
                    success: function(data) {
                        $box_lvl2.find('.accordion').html(data).show();
                        $('.accordion', $box_lvl2).each(accordion.build);

                    },
                    error: function(x, y, z) {
                        alert('error - loading content on ' + $url);
                    }
                });
            }
        });
    }
}


// CAR DETAILS -> FINISH CHECKBOXES
if ($('#edit-vehicle-finish').length > 0) {
    $('#edit-vehicle-finish').each(function(index, el) {
        var sdefvalue = $('#s-finish', el).val();

        $('.check-label', el).each(function(index, elm) {
            $(elm).bind('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                if ($(elm).attr('data-value') != $('#s-finish', el).val()) {
                    $('#s-finish', el).val($(elm).attr('data-value'));
                    $('.check-label', el).removeClass('checked');
                    $(elm).addClass('checked');
                } else {
                    $('#s-finish', el).val('');
                    $('.check-label', el).removeClass('checked');
                }
                // DISPLAYING THE SET FINISH VALUE
                alert('Recording' + $('#s-finish', el).val() + 'in the DB');
            });
        });

    });
}

// CAR DETAILS -> DISPLAY EQUIPMENTS & TECHNICAL NOTICE
if ($('.btn.dynscript').length > 0) {
    var tpl = $('#itemTpl').html(),
        $placeholder = $('#car-details'),
        toggle = [];
    $('.btn.dynscript').each(function(index, el) {
        toggle[index] = 0;
        $(el).bind('click', function(e) {

            e.preventDefault();
            e.stopPropagation();

            var tmpfinish = $(el).parents('article.col').find($('.check-label'));
            var tmpfinishval = tmpfinish.attr('data-value');

            if (toggle[index] == 0) {
                $placeholder.append($('<div class="hubs car-details' + index + '"></div>'));
                //$placeholder.find('.car-details'+index).html(tpl).find('.accordion').each(accordion.build);

                $('#car-details .accordion').each(function() {
                    new Accordion($(this));
                });
                toggle[index] = 1;
            } else {

            }

            el._opened = true;
            $placeholder.show();
            selectSkin();

            // CAR DETAILS -> FINISH VERSION
            if ($('#edit-vehicle-version').length > 0) {
                $('#edit-vehicle-version').each(function(index, el) {
                    var vdefvalue = $('#s-version').val();

                    $('.check-label', el).each(function(index, elm) {
                        $(elm).bind('click', function(e) {

                            if ($('#s-finish').val() == '' || $('#s-finish').val() != tmpfinishval) {
                                tmpfinish.trigger('click');
                            }
                            if ($('#s-version').val() != $(elm).attr('data-value')) {
                                $('#s-version').val($(elm).attr('data-value'));
                                $('.check-label', el).removeClass('checked');
                                $(elm).addClass('checked');
                            } else {
                                $('#s-version').val('');
                                $('.check-label', el).removeClass('checked');
                            }
                            // DISPLAYING THE SET FINISH VALUE
                            alert('Recording' + $('#s-version').val() + 'in the DB');
                        });
                    });

                });
            };
        });
    });
}

// VEHICLE'S COLOR SELECTION
var colors = {
    build: function() {

        /* Vars */
        var me = this,
            $me = $(me),
            $bg = $me.find('figure .lazy'),
            $colors = $me.find('ul a');

        me.current = null;

        /* Delay init */
        $bg.load(function() {
            if ($(this).hasClass('lazy')) return false;
            colors.change.call(me, null, $colors.get(0));
        });

        /* Events */
        $colors.click(function(e) {
            colors.change.call(me, e, this)
        });

        console.log('colors built');

    },
    change: function(e, link) {

        /* Vars */
        var me = this;

        if (e) e.preventDefault();

        /* First click create image */
        if (!link.img) {

            var src = link.getAttribute('href');
            var srcLazy = link.getAttribute('data-lazy');

            link.img = document.createElement('img');
            link.img.className = 'lazy';
            link.img.setAttribute("data-original", src);
            link.img.src = srcLazy;
            $(link.img).load(function() {
                colors.show.call(me, link);
            });
            //me.appendChild(link.img);

        } else {

            colors.show.call(me, link);

        };

    },
    show: function(link) {

        /* Vars */
        var me = this;

        if (me.current && link != me.current) {
            colors.hide.call(me, me.current);
        };

        $(me).find('li').removeClass('on');
        $(link).parent().addClass('on');
        $(me).prev('figure').find('img').remove(); // delete old img
        $(link.img).appendTo($(me).prev('figure')).fadeIn();

        // call lazy
        lazy.change($(me).prev('figure').find('.lazy'));

        me.current = link;
    },
    hide: function(link) {
        /* Vars */
        var me = this;

        $(me).find('li').removeClass('on');
        $(link.img).delay(50).fadeOut();
        me.current = null;
    }
};

// COLOR VEHICLE SELECTOR SLIDER
var addSliderThumb = function() {
    if ($('.thumb-slider').length > 0) {
        var $bxpager = $('.bx-pager');
        var $maxSlides = 9;
        if($('.thumb-slider').parents().hasClass('showroom-mobile')){
            var $slideWidth = 39;
        } else {
            var $slideWidth = 27;
        }

        if ($bxpager.find('li').length > 1) {
            if ($bxpager.find('li').length > $maxSlides) {
                $bxpager.find('ul').bxSlider({
                    controls: false,
                    infiniteLoop: false,
                    pager: false,
                    maxSlides: $maxSlides,
                    slideWidth: $slideWidth,
                    slideMargin: 3,
                    moveSlides: 5,
                    onSliderLoad: function() {
                        $('.colors').each(colors.build);
                    }
                });
            } else {
                $('.colors').each(colors.build);
            }
        } else {
            $('.bx-pager').hide();
            $('.colors').each(colors.build);
        }
    }
}

// LINK MY CAR BY Y.M.R
var linkMyCar = function() {
    var $tactileName = $('input[name="tactileName"]'),
        $edgeModal = $('.edge-modal'),
        $secondStep = $("#eligibilite-form"),
        $notlinkedCar = $("#notlinkedCar"),
        $isOklinkedCar = $("#isOklinkedCar"),
        $verifyNumber = $("#verify-number"),
        $edgeNotice = $(".edge-notice, .edge-overlay"),
        $continued = $(".continued"),
        $inputVerifyNumber = $("input[name='verify-number']"),
        $modalContent = $('#edge-modal'),
        $closeModal = $modalContent.find(".close");

    if ($tactileName.length) {
        $tactileName.on("change", function() {
            var _this = $(this),
                _val = _this.val();

            if (_val) {
                if (_val == 1) {
                    $secondStep.fadeIn("slow");
                    $notlinkedCar.fadeOut("slow");
                };
                if (_val == 0) {
                    $secondStep.fadeOut("slow");
                    $isOklinkedCar.fadeOut("slow");
                    $notlinkedCar.fadeIn("slow");
                };
            };
        });
    };
    if ($edgeModal.length) {
        var i = 0;
        $edgeModal.on("click", function(e) {
            var _this = $(this),
                $offset = _this.offset();
            e.preventDefault();
            $modalContent.css({
                "position": "absolute",
                "top": 45,
                "right": 5
            });
            $modalContent.fadeIn(300, function() {
                $(this).focus();
            });
        });
        $inputVerifyNumber.on("blur focus", function(e) {
            $modalContent.fadeOut("slow");
        });
        $inputVerifyNumber.on("keypress", function(e) {
            $modalContent.fadeOut("slow");
            if (i > 17)
                $edgeNotice.fadeIn("slow");
            i++
        });
    };
    if ($verifyNumber.length) {
        $verifyNumber.on("click", function(e) {
            var _this = $(this),
                $offset = _this.offset();
            e.preventDefault();
            $isOklinkedCar.fadeIn("slow");
            $edgeNotice.fadeIn("slow");
        });
        $inputVerifyNumber.on("blur", function(e) {
            $modalContent.fadeOut("slow");
        });
    };
    if ($continued.length) {
        $continued.on("click", function(e) {
            var _this = $(this);
            $secondStep.find("input[type='text']").focus();
            $edgeNotice.fadeOut("slow");
        });
    };
    if ($closeModal.length) {
        $closeModal.on("click", function(e) {
            var _this = $(this);
            _this.closest("#edge-modal").fadeOut("slow");
        });
    };
}

// LOADMORE
var loadmore = {
    build: function() {

        var me = this;

        me.$items = $(me).find('.item');
        me.$way = 1;
        me.$btn = $(me).find('.loadMore');
        me.$toggle = me.$btn.attr('data-toggle');

        me.$btn.click(function() {

            // reset
            if (me.$way == 0) {
                $(me.$items).hide();
                $(me.$items).removeClass('shown');
                $(me.$items).addClass('hidden');
                $(me.$items).eq(0).show();
                $(me.$items).eq(0).removeClass('hidden');
                $(me.$items).eq(0).addClass('shown');
                $('a', me.$btn).html(me.$toggle);
                me.$way = 1;
                return;
            }

            if (me.$way == 1) {
                $(me.$items).removeClass('hidden');
                $(me.$items).addClass('shown');
                $(me.$items).find('img.lazy').lazy();
                $(me.$items).show();
                me.$way = 0;
                me.$toggle = $('a', me.$btn).html();
                $('a', me.$btn).html(me.$btn.attr('data-toggle'));
                return;
            }
        });

    }
}
$('.dyncont').each(loadmore.build);

// INTERACTIVE MOSAIC
var intMosaic = function(root) {
    this.init(root);
}
intMosaic.prototype = {
    init: function(root) {
        oThis = this;
        oThis.root = root;
        oThis.items = $(oThis.root).find('figure');
        oThis.items.each(function() {
            $(this).on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                oThis.display($(this).next());
            });
        });
    },
    display: function(content) {
        $('.int-mosaic .item .ctnt').css({
            display: 'none'
        });
        oThis = this;
        $(content).css({
            display: 'block'
        });
        $('<div class="close"></div>').appendTo($(content));
        $('.close', content).bind('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            $('.close', content).remove();
            $(content).css({
                display: 'none'
            });
        });
        return false;
    }
}
$('.int-mosaic .item').each(function() {
    new intMosaic($(this));
});

// NOTES CLOSE BUTTON
if ($('.hb').length > 0) {
    $('.hb').each(function(index, el) {
        var par = $(el).parents('.popin-like');
        $(el).bind('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            par.hide();

        });
    });
}


// MEDIA POPIN

var mediaSlider = null;
var mediaPop = {
    init: function(col) {

        var me = this,
            $me = $(me);

        col.each(function(i, el) {
            var master = $(el).attr('data-bundle');
            if ("" == master || undefined == master) {
                master = "self" + i;
                $(el).attr('data-bundle', master);
            }
            $(el).bind('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                me.built(master, this);
                //lazy.change($('.mediaPop .slideThis img.lazy'));
            });
        });


    },
    built: function(master, elm) {
        var me = this,
            $me = $(me),
            mediaOk = 0,
            pointer = null,
            checkLoad = 0;

        $('body').append($('<div class="mediaMask"></div><div class="mediaPop"><span class="close"></span><div class="loading"><div class="circ"></div></div><div class="slideThis"></div></div>'));

        scrollTopV = $(window).scrollTop();
        $('.content').css({
            top: -scrollTopV,
            left: 0
        });
        $('.container').addClass('popopen');
        $('.content').addClass('popopen');
        $('.container').css({
            height: $(window).height()
        });


        $('.mediaMask, .mediaPop .close').bind('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            me.destroy();
        });

        $('[data-bundle="' + master + '"]').each(function(i, el) {
            if (el == elm) pointer = i;
            var legend = $(el).attr('title');
            var medium = $(el).attr('href');
            var altern = $(el).attr('data-other');

            // TESTS

            var isImg = (/\.jpeg|jpg|gif|png$/).test(medium),
                isVideo = (/\.mp4|webm$/).test(medium),
                isFlash = (/\.flv|swf$/).test(medium);

            var isYoutube = (/youtube.com/).test(medium),
                youtubeUrl = '';

            if (isYoutube) {
                if ((/\/embed/).test(medium)) {
                    youtubeUrl = medium;
                } else {
                    var ytRegExp = new RegExp('(//www\\.youtube\\.com/watch\\?v=)|(//youtu\\.be/)(\\w*)')
                    youtubeUrl = (ytRegExp.test(medium)) ? medium.replace(ytRegExp, '//www.youtube.com/embed/$3') : false;
                }
                youtubeUrl = youtubeUrl.split('&')[0];
            }

            if (isVideo) {
                var videos = medium.split('|'),
                    testExt = /^.+\.([^.]+)$/;
            }


            var builtMedium = '';
            if (isYoutube) {
                builtMedium = document.createElement('iframe');
                builtMedium.src = youtubeUrl;
                builtMedium.setAttribute('width', '100%');
                builtMedium.setAttribute('height', ($(window).width() * 56.25) / 100);
                builtMedium.setAttribute('frameborder', 0);
                builtMedium.setAttribute('allowfullscreen', true);
                mediaOk = 1;
            }
            if (isVideo) {
                if (videos.length < 2) {
                    alert("Can't find every sources");
                    me.destroy();
                    mediaOk = 0;
                } else {
                    builtMedium = document.createElement('video');
                    builtMedium.setAttribute('width', '100%');
                    builtMedium.setAttribute('height', ($(window).width() * 56.25) / 100);
                    builtMedium.setAttribute('controls', true);
                    var source1 = document.createElement('source');
                    var source1Type = videos[0].slice(videos[0].lastIndexOf('.') + 1, videos[0].length);
                    source1.setAttribute('src', videos[0]);
                    source1.setAttribute('type', 'video/' + source1Type);
                    builtMedium.appendChild(source1);
                    var source2 = document.createElement('source');
                    var source2Type = videos[1].slice(videos[1].lastIndexOf('.') + 1, videos[1].length);
                    source2.setAttribute('src', videos[1]);
                    source2.setAttribute('type', 'video/' + source2Type);
                    builtMedium.appendChild(source2);
                    mediaOk = 1;
                }
            }
            if (isImg) {
                builtMedium = document.createElement('img');
                //builtMedium.src = medium;

                builtMedium.className = 'lazy';
                builtMedium.setAttribute("data-original", medium);
                builtMedium.src = altern;
                mediaOk = 1;
            }
            if (isFlash) {
                builtMedium = "SWF not supported";
                mediaOk = 0;
            }

            var mediaCxt = $('<figure><figcaption>' + legend + '</figcaption></figure>');
            mediaCxt.prepend(builtMedium);
            $('.mediaPop .slideThis').append(mediaCxt);
        });


        var isCss = false;

        if ((navigator.userAgent.match(/iPhone/i)) || (navigator.userAgent.match(/iPod/i))) {

            var ver = iOSversion();

            if (undefined != ver) {
                if (ver[0] > 6) {
                    isCss = false;
                } else {
                    isCss = true;
                }
            } else {
                isCss = true;
            }
        } else {
            isCss = false;
        }
        if (1 == mediaOk) {
            if ($('.mediaPop .slideThis img.lazy').length > 0) {
                lazy.change($('.mediaPop .slideThis img.lazy'));
                $('.mediaPop .slideThis img').each(function(index) {
                    $(this).on('afterLoadDone', function() {
                        checkLoad = checkLoad + 1;

                        if (checkLoad == $('.mediaPop .slideThis img').length) {
                            me.slider(pointer, isCss);
                        }
                    });
                });
            } else {
                me.slider(pointer, isCss);
            }
        }
    },
    slider: function(pointer, isCss) {
        var me = this,
            $me = $(me);

        var items = $('.mediaPop').find('figure');
        var gtmCloser = function(){
            /* GTM closers sur Galerie Média*/
            var close_logo = $('.mediaPop').find('.close');
            close_logo.attr('data-gtm','eventGTM|Showroom::'+page_vehicule_label+'::MediaGallery|Close::Logo|'+close_logo.text()+'||');
        }
        var gtmArrow = function(currentIndex){
            /* GTM arrows sur Galerie Média*/
            var left = $('.mediaPop').find('a.bx-prev');
            var right = $('.mediaPop').find('a.bx-next');
            var left_file = $(items[(currentIndex-1+items.length)%items.length]).find('img').attr('src').split('/').pop();
            var right_file = $(items[(currentIndex+1)%items.length]).find('img').attr('src').split('/').pop();
            left.attr('data-gtm','eventGTM|Showroom::'+page_vehicule_label+'::MediaGallery|Navigation::Arrow::left|'+left_file+'||');
            right.attr('data-gtm','eventGTM|Showroom::'+page_vehicule_label+'::MediaGallery|Navigation::Arrow::right|'+right_file+'||');
        }
        var gtmPager = function(){
            /* GTM pagers sur Galerie Média*/
            $('.mediaPop').find('.bx-pager .bx-pager-item').each(function(){
                var pager_link = $(this).find('a');
                var slide_index = pager_link.attr('data-slide-index');
                var current_file = $(items[slide_index]).find('img').attr('src').split('/').pop();
                pager_link.attr('data-gtm','eventGTM|Showroom::'+page_vehicule_label+'::MediaGallery|Navigation::Pagers::'+slide_index+'|'+current_file+'||');
            });
        }

        mediaSlider = $('.mediaPop .slideThis').bxSlider({
            infiniteLoop: false,
            speed: 250,
            // slideWidth: $(window).width(),
            startSlide: pointer,
            minSlides: 1,
            maxSlides: 1,
            moveSlides: 1,
            useCSS: isCss,
            easing: 'linear',
            slideMargin: 0,
            swipeThreshold: 50,
            hideControlOnEnd: true,
            oneToOneTouch: true,
            slideSelector: 'figure',
            onSlideAfter: function(dom, oldi, newi) {
                if (dom.find('video').length > 0) {
                    var repaint = dom.find('video').clone();
                    dom.empty();
                    setTimeout(function() {
                        repaint.appendTo(dom);
                    }, 500);
                }

                gtmArrow(newi);
            },
            onSliderLoad: function(currentIndex) {
                $("body").css({
                    'position': 'fixed',
                    'width': '100%'
                });
                $('.mediaPop .bx-wrapper').eq(0).bind('click touchstart', function(e) {
                    if ($(e.target).is("figure")) {
                        if (isJQMGhostClick(e)) {
                            return;
                        }
                        me.destroy();
                    }
                });
                $(".mediaPop .bx-viewport").css("overflow", "inherit"); // #4502


                var wH = $(window).height(),
                    wW = $(window).width();

                $('.mediaPop .slideThis figure').each(function(index, el) {
                    // $(el).attr('style', styles)

                    $(el).css({
                        position: 'relative',
                        overflow: 'hidden',
                        height: wH,
                        width: wW
                    });

                    $(el).find('img, iframe, video').each(function() {
                        var oThis = this,
                            iH = $(oThis).height(),
                            iW = $(oThis).width();

                        //console.log("iH : " + iH + " / iW : " + iW)
                        if (wH > wW) {
                            //console.log("device : portrait");
                            var hValue = Math.ceil((iH * wW) / iW),
                                wValue = wW,
                                mLvalue = -(wValue / 2),
                                mTvalue = -(hValue / 2);

                                // IF IMAGE's 'HEIGHT BIGGER THAN WINDOW'S HEIGHT
                                if(hValue > wH){
                                    var hValue = wH,
                                        wValue = Math.ceil((iW * wH) / iH),
                                        mLvalue = -(wValue / 2),
                                        mTvalue = -(hValue / 2);
                                }

                        } else {
                            //console.log("device : landscape");
                            var hValue = wH,
                                wValue = Math.ceil((iW * wH) / iH),
                                mLvalue = -(wValue / 2),
                                mTvalue = -(hValue / 2);

                                // IF IMAGE's 'WIDTH LARGER THAN WINDOW'S WIDTH
                                if(wValue > wW){
                                    var wValue = wW,
                                        hValue = Math.ceil((iH * wW) / iW),
                                        mLvalue = -(wValue / 2),
                                        mTvalue = -(hValue / 2);
                                }
                        }
                        $(oThis).css({
                            position: 'absolute',
                            top: '50%',
                            left: '50%',
                            minHeight: hValue,
                            height: hValue,
                            maxHeight: hValue,
                            minWidth: wValue,
                            width: wValue,
                            maxWidth: wValue

                        });
                        setTimeout(function() {
                            $(oThis).css({
                                marginTop: mTvalue,
                                marginLeft: mLvalue
                            });
                        }, 500);

                    });

                });

                $('.mediaPop .loading').remove();
                if ($('.mediaPop .slideThis figure').eq(currentIndex).find('video').length > 0) {
                    var repaint = $('.mediaPop .slideThis figure').eq(currentIndex).find('video').clone();
                    $('.mediaPop .slideThis figure').eq(currentIndex).empty();
                    setTimeout(function() {
                        repaint.appendTo($('.mediaPop .slideThis figure').eq(currentIndex));
                        $(window).trigger('resize');
                    }, 500);
                }

                gtmCloser();
                gtmArrow(currentIndex);
                gtmPager();
            }

        });
        /*
                if (mediaSlider) {
                    $('.mediaPop .slideThis').css({
                        width: mediaSlider.getSlideCount() * $(window).width(),
                        height: $(window).height()
                    });
                }
        */
    },
    destroy: function() {
        $('.container').removeClass('popopen');
        $('.content').removeClass('popopen');
        $('.content').css({
            top: 0,
            left: 0
        });
        $('.container').css({
            height: 'auto'
        });
        $(window).scrollTo(scrollTopV, 0);
        if (mediaSlider) {
            mediaSlider.destroySlider();
        }
        $('.mediaPop').remove();
        $('.mediaMask').remove();
        $("body").css("position", "relative");
    }
}
if ($('.galleryPop').length > 0) mediaPop.init($('.galleryPop'));

// LEGAL NOTICES POPIN
var noticePop = function(root, index) {
    this.init(root, index);
}
noticePop.prototype = {
    init: function(root, index) {
        var oThis = this;
        oThis.root = root;
        oThis.loader = new Loader();
        oThis.checkLoad = 0;

        oThis.noticeScroll = new Array();

        oThis.mypos = $(oThis.root).position();
        oThis.mytext = $(oThis.root).next();
        oThis.dataClose = oThis.mytext.attr('data-close');
        if (oThis.mytext.hasClass('notice-text')) {
            $('a', oThis.root).removeAttr('href');
            $(oThis.root).bind('click', function(e) {
                oThis.scrollTopV = $(window).scrollTop();
                oThis.loader.show();
                //prevent same event from firing twice
                if (isJQMGhostClick(e)) {
                    return;
                }
                e.preventDefault();
                e.stopPropagation();

                $('.content').css({
                    top: -oThis.scrollTopV,
                    left: 0
                });

                $('.container').addClass('popopen');
                $('.content').addClass('popopen');

                // AJAX CONTENT DETECTION
                if (oThis.mytext.attr('data-src')) {
                    $.ajax({
                        url: oThis.mytext.attr('data-src'),
                        type: "GET",
                        dataType: "html",
                        success: function(response) {
                            $('<div class="ajax-filled"></div>').appendTo(oThis.mytext);
                            oThis.mytext.find('.ajax-filled').append($(response).find('.swipezone').html());
                            $(oThis.mytext).removeAttr('data-src');
                            mediaPop.init($('.galleryPop', oThis.mytext));
                            oThis.build(index);
                        }
                    });

                } else {
                    oThis.build(index);
                }
            });
        }
    },
    resizeMask: function(){
        $('.mask').css({
            width: $(window).width(),
            height: $(window).height()
        });
    },
    build: function(index) {
        var oThis = this;

        $('.container').css({
            height: $(window).height()
        });

        $('<div class="mask mask-n" style="z-index:199"></div>').prependTo($('body'));
        oThis.resizeMask();
        oThis.mytext.prependTo($('body'));
        oThis.lazyImgs = oThis.mytext.find('.lazy');
        if (oThis.lazyImgs.length > 0) {
            lazy.change(oThis.lazyImgs);

            oThis.lazyImgs.each(function(index) {
                $(this).on('afterLoadDone', function() {

                    oThis.checkLoad = oThis.checkLoad + 1;

                    if (oThis.checkLoad == oThis.lazyImgs.length) {
                        oThis.slider(index);
                    }
                });
            });

        } else {
            oThis.slider(index);
        }
        var testMsGesture = window.navigator.msPointerEnabled;
        if (testMsGesture) {
            $('body').addClass('scroll-no-touch-action');
        }
    },
    slider: function(index) {
        var oThis = this;
        if (Math.floor(($(window).height() * 90) / 100) <= oThis.mytext.height()) {

            oThis.mytext.css({
                height: Math.floor(($(window).height() * 90) / 100)
            });

            oThis.noticeScrollID = 'wrapper' + index;

            oThis.mytext.wrapInner('<div id="' + oThis.noticeScrollID + '" class="notice-wrapper"><div class="scrollnotice"></div></div>');

            oThis.noticeScroll[index] = new iScroll(oThis.noticeScrollID, {
                hScrollbar: false,
                vScrollbar: false,
                bounce: false
            });
            oThis.noticeScroll[index].refresh();

            setTimeout(function() {
                oThis.noticeScroll[index].refresh();
            }, 0);
            oThis.mytop = Math.floor((($(window).height() - oThis.mytext.outerHeight()) / 2));
        } else {
            oThis.mytop = Math.floor((($(window).height() - oThis.mytext.outerHeight()) / 2));
        }
        new iniTables(oThis.mytext);
        oThis.mytext.css({
            display: 'block',
            position: 'absolute',
            zIndex: 200,
            left: '5%',
            top: oThis.mytop,
            width: '90%'
        });


        $(window).on('resize', function(){
            oThis.resizeMask();
        })

        $('<div class="fermer">' + oThis.dataClose + '</div><div class="close"></div>').appendTo(oThis.mytext);

        $('.notice-text .fermer, .notice-text .close, .mask').bind('click', function(e) {
            //prevent same event from firing twice
            if (isJQMGhostClick(e)) {
                return;
            }

            e.preventDefault();
            e.stopPropagation();
            oThis.destroy(index);
        });
        oThis.loader.hide();
        return false;
    },
    destroy: function(index) {
        var oThis = this;
        var testMsGesture = window.navigator.msPointerEnabled;
        if (testMsGesture) {
            $('body').removeClass('scroll-no-touch-action');
        }


        $('.notice-text .close').remove();
        $('.notice-text .fermer').remove();
        $('.mask-n').remove();
        oThis.mytext.css({
            display: 'none'
        });
        oThis.mytext.insertAfter($(oThis.root));
        $('.container').removeClass('popopen');
        $('.content').removeClass('popopen');
        $('.content').css({
            top: 0,
            left: 0
        });
        $('.container').css({
            height: 'auto'
        });
        $(window).scrollTo(oThis.scrollTopV, 0);
    }
}
$('.notice, .tooltip').each(function(index) {
    new noticePop($(this), index);
});


// LOGIN POPIN
if ($('.connection-block-popin').length > 0) {
    $('a.login-btn').bind('click, tapone', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $('<div class="mask"></div>').prependTo($('body'));
        $('.connection-block-popin').prependTo($('body'));
        $('.connection-block-popin').css({
            display: 'block',
            position: 'absolute',
            zIndex: 10000,
            left: '5%',
            top: parseInt(($(window).height() - $('.connection-block-popin').height()) / 2) + 'px',
            width: '90%'
        });

        scrollTopV = $(window).scrollTop();
        $('.content').css({
            top: -scrollTopV,
            left: 0
        });
        $('.container').addClass('popopen');
        $('.content').addClass('popopen');
        $('.container').css({
            height: $(window).height()
        });

        //$('body').css({ overflow:'hidden' });
        $('.mask').css({
            width: $(window).width(),
            height: $(window).height()
        });

        $('<div class="close"></div>').appendTo($('.connection-block-popin'));

        $('.connection-block-popin .close, .mask').bind('click, tapone', function(e) {
            //$('body').css({ overflow:'auto' });

            $('.container').removeClass('popopen');
            $('.content').removeClass('popopen');
            $('.content').css({
                top: 0,
                left: 0
            });
            $('.container').css({
                height: 'auto'
            });
            $(window).scrollTo(scrollTopV, 0);
            $('.connection-block-popin').css({
                display: 'none'
            });
            $('.connection-block-popin .close').remove();
            $('.mask').remove();
        });
        return false;
    });
}

// CITROEN ID POPIN
if ($('.citroenid-block-popin').length > 0) {
    $('a.connection-btn').bind('click, tapone', function(e) {
        e.preventDefault();
        e.stopPropagation();
        if ($('.connection-block-popin').length > 0) {
            $('.connection-block-popin').hide();
        }
        if ($('.connection-block-popin').length == 0) {
            $('<div class="mask"></div>').prependTo($('body'));
        }
        $('.citroenid-block-popin').prependTo($('body'));
        $('.citroenid-block-popin').css({
            display: 'block',
            position: 'absolute',
            zIndex: 10000,
            left: '5%',
            top: parseInt(($(window).height() - $('.citroenid-block-popin').height()) / 2) + 'px',
            width: '90%'
        });

        scrollTopV = $(window).scrollTop();
        $('.content').css({
            top: -scrollTopV,
            left: 0
        });
        $('.container').addClass('popopen');
        $('.content').addClass('popopen');
        $('.container').css({
            height: $(window).height()
        });


        //$('body').css({ overflow:'hidden' });

        $('.mask').css({
            width: $(window).width(),
            height: $(window).height()
        });

        $('<div class="close"></div>').appendTo($('.citroenid-block-popin'));

        $('.citroenid-block-popin .close, .mask').bind('click, tapone', function(e) {
            //$('body').css({ overflow:'auto' });
            $('.container').removeClass('popopen');
            $('.content').removeClass('popopen');
            $('.content').css({
                top: 0,
                left: 0
            });
            $('.container').css({
                height: 'auto'
            });
            $(window).scrollTo(scrollTopV, 0);
            $('.citroenid-block-popin').css({
                display: 'none'
            });
            $('.citroenid-block-popin .close').remove();
            $('.mask').remove();
        });
        return false;
    });
}

// SUBSCRIBE POPIN
if ($('.subscribe-block-popin').length > 0) {
    $('a.sb').bind('click, tapone', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $('<div class="mask"></div>').prependTo($('body'));
        $('.subscribe-block-popin').prependTo($('body'));
        $('.subscribe-block-popin').css({
            display: 'block',
            position: 'absolute',
            zIndex: 10000,
            left: '5%',
            top: parseInt(($(window).height() - $('.subscribe-block-popin').height()) / 2) + 'px',
            width: '90%'
        });

        scrollTopV = $(window).scrollTop();
        $('.content').css({
            top: -scrollTopV,
            left: 0
        });
        $('.container').addClass('popopen');
        $('.content').addClass('popopen');
        $('.container').css({
            height: $(window).height()
        });

        //$('body').css({ overflow:'hidden' });

        $('.mask').css({
            width: $(window).width(),
            height: $(window).height()
        });
        $('<div class="close"></div>').appendTo($('.subscribe-block-popin'));

        $('.subscribe-block-popin .close, .mask').bind('click, tapone', function(e) {
            //$('body').css({ overflow:'auto' });
            $('.container').removeClass('popopen');
            $('.content').removeClass('popopen');
            $('.content').css({
                top: 0,
                left: 0
            });
            $('.container').css({
                height: 'auto'
            });
            $(window).scrollTo(scrollTopV, 0);
            $('.subscribe-block-popin').css({
                display: 'none'
            });
            $('.subscribe-block-popin .close').remove();
            $('.mask').remove();
        });
        return false;
    });
}

// COOKIES READING
function readCookie(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }
    // COOKIE INFORMATIONS
$('.closecookies').bind('click', function() {
    $('#block-cookies').hide();
});

function initLocatorPDV() {
    $('.locator').gLocator({
        onLoad: function() {}, // Initialisation
        onList: function() {
            $('#map-canvas').removeAttr('style');
        }, // When results displayed / refreshed
        onItemClick: function(storeId) { // When item (marker or result) is cliked, receives storeId as parameter
            try {
                getCarStock(storeId);
            } catch (e) {}
        },
        onDetails: function() {
            return false;
        }, // When details displayed if return false, default action is prevent
        onGeoloc: function() {},
        onGeolocError: function() {
            /* Display prompt, should use an HTML template */
            var mytext = $('.error-geoloc');
            var mytextClose = mytext.attr('data-close');
            $('.content').css({
                top: -scrollTopV,
                left: 0
            });

            $('.container').addClass('popopen');
            $('.content').addClass('popopen');

            $('.container').css({
                height: $(window).height()
            });
            $('<div class="mask mask-n" style="z-index:199"></div>').prependTo($('.container'));
            $('.mask').css({
                width: $(window).width(),
                height: $(window).height()
            });
            mytext.prependTo($('.container'));
            mytext.css({
                display: 'block',
                position: 'absolute',
                zIndex: 200,
                left: '5%',
                top: Math.floor((($(window).height() - mytext.outerHeight()) / 2)),
                width: '90%'
            });

            $('body').css({
                overflow: 'hidden'
            });

            $('<div class="fermer">' + mytextClose + '</div><div class="close"></div>').appendTo(mytext);

            $('.error-geoloc .fermer, .error-geoloc .close, .mask').bind('click', function(e) {
                $('.error-geoloc .close').remove();
                $('.error-geoloc .fermer').remove();
                $('.mask-n').remove();
                mytext.css({
                    display: 'none'
                });
                $('.container').removeClass('popopen');
                $('.content').removeClass('popopen');
                $('.content').css({
                    top: 0,
                    left: 0
                });
                $('.container').css({
                    height: 'auto'
                });
                $(window).scrollTo(scrollTopV, 0);
            });
            return false;
        }
    });
}

// STORE LOCATOR
if ($.fn.gLocator) {
    if ($('.locator') !== undefined) {
        if ($('.locator').length > 0) {
            // Lancement de l'API
            try {
                if (google) {
                    initLocatorPDV();
                }
            } catch (e) {
                // injecte le script que si celui-ci n'est d�j� pas charg�.
                var script = document.createElement('script');
                script.type = 'text/javascript';
                script.src = googlemapAPI + '&callback=initLocatorPDV';
                document.body.appendChild(script);
            }
        }
    }
};

(function($, win, doc, gtmCit) {
    /* GTM Functions */
    var $doc = $(doc),
        gtmModule = {};

    $doc.on('gtm', function(e) {
        // l'écoute des gtm se fait via la console Google Analytics.
/*
        var splitter = (e.dataGtm).split('|');
        var eventCategory = splitter[1];
        var eventAction = splitter[2];
        var eventLabel = '';

        for (var i = 3; i < splitter.length; i++){
            eventLabel += splitter[i] +' ';
        }
        try{
            console.log('ga(\'citroenTracker.send\', \'event\', ' + eventCategory + ', ' + eventAction + ', ' + eventLabel + ');');
        }
        catch (err) {}

        ga('citroenTracker.send', 'event', eventCategory, eventAction, eventLabel);
*/
    });

    var setTrigger = function(value) {
       // console.log("setTrigger : " + value);
        $doc.trigger({
            type: 'gtm',
            dataGtm: value
        });
    };

    gtmModule.tabs = function(data) {
        this.init(data)
    };
    gtmModule.tabs.prototype = {
        init: function(data) {
            var oThis = this;
            this.data = data[0];

            if (this.data == undefined) {
                return false;
            } else {
                this.sliced = this.data.split('|');
                this.$root = data.root;

                this.$root.find('li').each(function() {

                    $(this).on('click', function() {
                        var status = 'close';
                        if (!$(this).parent().hasClass('on')) {
                            status = 'open';
                        }
                        if (status == 'close') return;

                        oThis.sliced[3] = $(this).text();
                        oThis.data = oThis.sliced[0].concat('|', oThis.sliced[1], '|', oThis.sliced[2], '|', oThis.sliced[3], '|', oThis.sliced[4], '|', oThis.sliced[5]);
                        setTrigger(oThis.data);
                    });
                });
            }

        }
    }

    gtmModule.expandBar = function(data) {
        this.init(data)
    };
    gtmModule.expandBar.prototype = {
        init: function(data) {
            var oThis = this;
            this.data = data[0];

            this.sliced = data[0].split('|');
            //this.sliced[1] = "toggle";
            this.$root = data.root;

            this.$root.on('click', function() {
                var status = 'close';


                var eventLabel = oThis.sliced[3];
                if ($(this).hasClass('open')) {
                    status = 'open';
                    eventLabel = oThis.sliced[2];
                }
                if (oThis.sliced[3] == '' && status == 'close') return;


                oThis.data = oThis.sliced[0].concat('|', oThis.sliced[1], '|', eventLabel, '|', oThis.sliced[4], '|', oThis.sliced[5], '|', oThis.sliced[6]);
                setTrigger(oThis.data);
            });
        }
    }

    gtmModule.searchText = function(data) {
        this.init(data)
    };
    gtmModule.searchText.prototype = {
        init: function(data) {
            var oThis = this;

            this.data = data[0];

            this.sliced = data[0].split('|');
            //this.sliced[1] = "toggle";
            this.$root = data.root;
            var oText = this.$root.find('#searchText');

            this.$root.find('#searchSubmit').on('click', function() {
                var searchText =oText.val();
                oThis.data = oThis.sliced[0].concat('|', oThis.sliced[1], '|',  oThis.sliced[2], '|', searchText, '|', oThis.sliced[4], '|', oThis.sliced[5]);
                setTrigger(oThis.data);

            });
        }
    }

    gtmModule.toggle = function(data) {
        this.init(data)
    };
    gtmModule.toggle.prototype = {
        init: function(data) {
            var oThis = this;
            this.data = data[0];

            this.sliced = data[0].split('|');
            //this.sliced[1] = "toggle";
            this.$root = data.root;

            this.$root.on('click', function() {
                var status = 'close';


                var eventLabel = oThis.sliced[3];
                if (!$(this).parent().hasClass('open') && !$(this).hasClass('on')) {
                    status = 'open';
                    eventLabel = oThis.sliced[2];
                }
                if (oThis.sliced[3] == '' && status == 'close') return;


                oThis.data = oThis.sliced[0].concat('|', oThis.sliced[1], '|', eventLabel, '|', oThis.sliced[4], '|', oThis.sliced[5], '|', oThis.sliced[6]);
                setTrigger(oThis.data);
            });
        }
    }


    gtmModule.searchText = function(data) {
        this.init(data)
    };
    gtmModule.searchText.prototype = {
        init: function(data) {
            var oThis = this;

            this.data = data[0];
            this.sliced = data[0].split('|');
            this.$root = data.root;
            var keywordInput = this.$root.find('.tt-query');
            this.$root.on('submit', function() {
                oThis.data = oThis.sliced[0].concat('|', oThis.sliced[1], '|', oThis.sliced[2], '|', oThis.sliced[3], '|', oThis.sliced[4], '|', $(keywordInput).val());
                setTrigger(oThis.data);

            });

        }
    }


    gtmModule.clickable = function(data) {
        this.init(data)
    };
    gtmModule.clickable.prototype = {
        init: function(data) {
            var oThis = this;

            this.data = data[0];
            this.$root = data.root;
            this.$root.on('click', function() {
                setTrigger(oThis.data);

            });

        }
    }

    gtmModule.jColors = function(data) {
        this.init(data)
    };
    gtmModule.jColors.prototype = {
        init: function(data) {
            var oThis = this;
            this.data = data[0];
            if (this.data == undefined) {
                return false;
            } else {
                this.sliced = data[0].split('|');
                this.$root = data.root;
                this.$next = this.$root.find('.bx-next');
                this.$prev = this.$root.find('.bx-prev');
                this.$pager = this.$root.find('.bx-pager');
                this.$bxSlider = data.bxSlider;
                this.currentSlide = 0;
                //this.sliced[1] = "slideShow";
                oThis.setHandlers();
            }
        },
        setHandlers: function() {
            var oThis = this;
            this.$next.on('click', function() {
                if(!$(this).hasClass('.disabled')){
                    oThis.gtmNextPrev('Next');
                }
            });

            this.$prev.on('click', function() {
                if(!$(this).hasClass('.disabled')){
                    oThis.gtmNextPrev('Previous');
                }
            });

            this.$pager.on('click', function() {
                if(!$(this).hasClass('.active')){
                    oThis.gtmPager();
                }
            });

        },
        getMainSlide: function() {
            this.currentSlide = this.$bxSlider.getCurrentSlide();
        },
        gtmNextPrev: function(dir) {
            var oThis = this;
           this.getMainSlide();

            //console.log(this.currentSlide);
            oThis.sliced[3] = dir;
            oThis.sliced[5] = this.currentSlide+1;
            oThis.data = oThis.sliced[0].concat('|', oThis.sliced[1], '|', oThis.sliced[2], '|', oThis.sliced[3], '|', oThis.sliced[4], '|', oThis.sliced[5]);
            setTrigger(oThis.data);

        },
        gtmPager: function() {
            var oThis = this;
            this.getMainSlide();
             oThis.sliced[3] = 'pager';
            oThis.sliced[5] = this.currentSlide+1;
            oThis.data = oThis.sliced[0].concat('|', oThis.sliced[1], '|', oThis.sliced[2], '|', oThis.sliced[3], '|', oThis.sliced[4], '|', oThis.sliced[5]);
            setTrigger(oThis.data);
        },

    }

    gtmModule.slider = function(data) {
        this.init(data)
    };
    gtmModule.slider.prototype = {
        init: function(data) {
            var oThis = this;
            this.data = data[0];
            if (this.data == undefined) {
                return false;
            } else {
                this.sliced = data[0].split('|');
                this.$root = data.root;
                this.$next = this.$root.find('.bx-next');
                this.$prev = this.$root.find('.bx-prev');
                this.$pager = this.$root.find('.bx-pager');
                this.$bxSlider = data.bxSlider;

                this.currentSlide = 0;
                //this.sliced[1] = "slideShow";
                oThis.setHandlers();
            }
        },
        setHandlers: function() {
            var oThis = this;
            this.$next.on('click', function() {
                if(!$(this).hasClass('.disabled')){
                    oThis.gtmNextPrev('Next');
                }
            });

            this.$prev.on('click', function() {
                if(!$(this).hasClass('.disabled')){
                    oThis.gtmNextPrev('Previous');
                }
            });

            this.$pager.on('click', function() {
                if(!$(this).hasClass('.active')){
                    oThis.gtmPager();
                }
            });

            this.$root.on('onTouchMove', function() {
                oThis.gtmTouch();
            });
        },
        getMainSlide: function() {
            //this.currentSlide = $(this).$bxSlider.getCurrentSlide();
            if (this.$root.find('.col').eq(this.currentSlide + 1).find('.title').attr('data-text') !== undefined) {
                this.contentText = this.$root.find('.col').eq(this.currentSlide + 1).find('.title').attr('data-text');
            } else {
                this.contentText = "";
            }
        },
        gtmNextPrev: function(dir) {
            var oThis = this;
            this.getMainSlide();
            //console.log(this.currentSlide);
            this.data.dir = dir;
            oThis.sliced[2] = dir;
            oThis.sliced[3] = this.contentText;
            oThis.sliced[5] = this.currentSlide+1;
            oThis.data = oThis.sliced[0].concat('|', oThis.sliced[1], '|', oThis.sliced[2], '|', oThis.sliced[3], '|', oThis.sliced[4], '|', oThis.sliced[5]);
            setTrigger(oThis.data);

        },
        gtmPager: function() {
            var oThis = this;
            this.getMainSlide();
            oThis.sliced[2] = "pager";
            oThis.sliced[3] = this.contentText;
            oThis.sliced[5] = this.currentSlide+1;
            oThis.data = oThis.sliced[0].concat('|', oThis.sliced[1], '|', oThis.sliced[2], '|', oThis.sliced[3], '|', oThis.sliced[4], '|', oThis.sliced[5]);
            setTrigger(oThis.data);
        },
        gtmTouch: function() {
            var oThis = this;
            this.getMainSlide();
            oThis.sliced[2] = "touch";
            oThis.sliced[3] = this.contentText;
            oThis.sliced[5] = this.currentSlide+1;
            oThis.data = oThis.sliced[0].concat('|', oThis.sliced[1], '|', oThis.sliced[2], '|', oThis.sliced[3], '|', oThis.sliced[4], '|', oThis.sliced[5]);
            setTrigger(oThis.data);
        }
    }

	gtmModule.video = function(data) {
        console.log(data);
        this.init(data)
    };

	gtmModule.video.prototype = {
        init: function(data) {
            var oThis = this;
            this.data = data[0];
            if (this.data == undefined) {
                return false;
            } else {
                this.sliced = data[0].split('|');
                this.$root = data.root;
                this.idVideo = this.$root.attr('id');
                this.$bigplay = this.$root.parents('.video-js').find('.vjs-big-play-button');
                this.$switchbtn = this.$root.parents('.video-js').find('.vjs-play-control');

                //this.sliced[1] = "slideShow";
                oThis.setHandlers();
            }
        },
        setHandlers: function() {
            var oThis = this;
            this.$bigplay.on('click', function() {
                oThis.gtmPlayStop('Play');
            });

            this.$switchbtn.on('click', function() {
                if(!$(this).hasClass('vjs-paused')){
                    oThis.gtmPlayStop('Pause');
                } else {
                    oThis.gtmPlayStop('Play');
                }
            });
        },
        gtmPlayStop: function(dir) {
            var oThis = this;
            this.data.dir = dir;
            oThis.sliced[2] = 'Video::'+dir;
			var videoName = oThis.sliced[3];
			var view = Math.round((oThis.$root.get(0).currentTime*100)/oThis.$root.get(0).duration);

            if(dir=="Pause"){
                oThis.sliced[3] = videoName + ':' + view.toString() + '%';
            } else {
                oThis.sliced[3] = videoName;
            }
			oThis.sliced[4] = '';
            oThis.sliced[5] = '';
            oThis.data = oThis.sliced[0].concat('|', oThis.sliced[1], '|', oThis.sliced[2], '|', oThis.sliced[3], '|', oThis.sliced[4], '|', oThis.sliced[5]);

            setTrigger(oThis.data);

        }
    }


    gtmModule.vue360 = function(data) {
        console.log(data);
        this.init(data)
    };

    gtmModule.vue360.prototype = {
        init: function(data) {
            var oThis = this;
            this.data = data[0];
            if (this.data == undefined) {
                return false;
            } else {
                this.sliced = data[0].split('|');
                this.$root = data.root;

                //this.sliced[1] = "slideShow";
                oThis.setHandlers();
            }
        },
        setHandlers: function() {
            var oThis = this;
            this.$root.on('click', function() {
                oThis.data = oThis.sliced[0].concat('|', oThis.sliced[1], '|', oThis.sliced[2], '|', oThis.sliced[3], '|', oThis.sliced[4], '|', oThis.sliced[5]);

                setTrigger(oThis.data);
            });

        }
    }

    /*gtmModule.dragnchange = function(data) {
        this.init(data)
    };
    gtmModule.dragnchange.prototype = {
        init: function(data) {
            var oThis = this;
            oThis.data = data[0];

            if (this.data == undefined) {
                return false;
            } else {
                oThis.sliced = data[0].split('|');

                oThis.$root = data.root;


                oThis.$root.find('.drag').on('mouseup', function() {
                    var leftPercent = parseInt(this.style.left.substr(0, (this.style.left.length-1) ) );

                    if (leftPercent < 50) {
                        oThis.eventName = oThis.sliced[2];
                        oThis.data = oThis.sliced[0].concat('|', oThis.sliced[1], '|', oThis.sliced[2], '|', oThis.sliced[4], '|', oThis.sliced[5], '|', oThis.sliced[6]);
                        setTrigger(oThis.data);

                    } else if ( leftPercent > 50 ) {
                        oThis.eventName = oThis.sliced[3]
                        oThis.data = oThis.sliced[0].concat('|', oThis.sliced[1], '|',oThis.sliced[2], '|', oThis.sliced[3], '|', oThis.sliced[5], '|', oThis.sliced[6]);
                        setTrigger(oThis.data);

                    }
                });
            }
        }
    }*/



    $(document).on('newgtmattr', '[data-gtm], [data-gtm-js]', function(e) {
        var $this = $(this);
    //    console.log("newgtmattr")
    if($this.attr('data-gtm-init')!='1'){
           if ($this.attr('data-gtm') != undefined  ) {
                 try {
                    var data = $this.attr('data-gtm').replace(/\'/g, '"');
                    $this.attr('data-gtm-init','1');
                    var oData ={
                       0: data,
                       'root' : $this
                   }

                        new gtmModule.clickable(oData);

                } catch (err) {}

               // console.log('data-gtm')


            }

            if ($this.attr('data-gtm-js') != undefined) {
              //  console.log('data-gtm-js')
                try {
                    var data = $this.attr('data-gtm-js');

                      oData = $.parseJSON(data);
                    oData.root = $this;



                    if (oData.type !== 'slider' && oData.type !== 'jColors' && gtmModule[oData.type] !== undefined) {


                        $this.attr('data-gtm-init','1');

                        new gtmModule[oData.type](oData);
                    }
                } catch (err) {}
            }
        }
    });

    gtmCit.initNewGTM = function(){
        $('[data-gtm], [data-gtm-js]').each(function() {
            $(this).trigger('newgtmattr');
        });
    };

    gtmCit.initjColors = function(obj) {
        var $root = $(obj.context),
            data = $root.attr('data-gtm-js');

        if (data !== undefined && $root.attr('data-gtm-init')!='1') {
            data = data.replace(/\'/g, '"'),
                oData = $.parseJSON(data);
            oData.root = $root;
            oData.bxSlider = obj;

            $root.attr('data-gtm-init','1');
            new gtmModule[oData.type](oData);
        }
    }


    gtmCit.initSlider = function(obj) {
        var $root = $(obj.context),
            data = $root.attr('data-gtm-js');

        if (data !== undefined && $root.attr('data-gtm-init')!='1') {
            data = data.replace(/\'/g, '"'),
                oData = $.parseJSON(data);
            oData.root = $root;
            oData.bxSlider = obj;

            $root.attr('data-gtm-init','1');
            new gtmModule[oData.type](oData);
        }
    }

    gtmCit.initVue360 = function(obj) {

        var $root = $(obj.context),
            data = $root.attr('data-gtm-js');

        if (data !== undefined && $root.attr('data-gtm-init')!='1') {
            data = data.replace(/\'/g, '"');
            console.log(data),
            oData = $.parseJSON(data);
            console.log(oData);
            oData.root = $root;

            $root.attr('data-gtm-init','1');
            new gtmModule[oData.type](oData);
        }

    }

    window.addEventListener('load', function() {

        new gtm_listener.viewed(document.querySelector('#gtm-visibility-test'), function() {}, 100);

        new gtm_listener.dragged(document.querySelector('.dragnchange .drag'), function() {}, 100);

    }, false);


})(jQuery, window, document, window.gtmCit = window.gtmCit || {});


    /* iPad dummy events to activate :hover on all elements */
    // $('body').bind(STARTEVENT,function(){  });

    /* Google Tag Manager Functions  */
    var gtm_listener = {

        start: ('ontouchstart' in window) ? 'touchstart' : 'mousedown',
        move: ('ontouchstart' in window) ? 'touchmove' : 'mousemove',
        end: ('ontouchstart' in window) ? 'touchend' : 'mouseup',


        viewed: function(element, callback, degree) {

            if (!element) return;

            if ($) $(element).unbind('click').parents('[data-gtm]').unbind('click');

            /* Vars */
            var that = this,
                check = function(e) {

                    /* Get viewport and element offsets */
                    var st = window.pageYOffset || document.body.scrollTop,
                        vh = window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight,
                        et = Math.round(gtm_listener.top(that._el)) - Math.round(vh * (100 - that._degree) / 100);

                    /* Callback if eligible and remove event listener */
                    if (st > et) {
                        that._callback.call(element);
                        window.removeEventListener('scroll', check, false)
                    };

                };

            /* Properties */
            that._el = element;
            that._degree = degree || 0;
            that._callback = callback || function() {};

            /* Events */
            window.addEventListener('scroll', check, false)

        },
        top: function(obj) {
            var top = 0;
            if (obj.offsetParent) {
                while (obj.offsetParent) {
                    top += obj.offsetTop;
                    obj = obj.offsetParent;
                };
            } else if (obj.y) {
                top += obj.y;
            };
            return top;
        },




        dragged: function(element, callback, x, y) {

            if (!element) return;

            if ($) $(element).unbind('click').parents('[data-gtm]').unbind('click');

            /* Vars */
            var that = this,
                set = function(e) {

                    /* Events */
                    document.addEventListener(gtm_listener.move, check, false);
                    document.addEventListener(gtm_listener.end, stop, false);

                },
                check = function(e) {

                    /* Current */
                    var dx = Math.abs(that._x - that._el.offsetLeft),
                        dy = Math.abs(that._y - that._el.offsetTop);

                    if ((dx > that._dx && 0 < that._dx) || (dy > that._dy && 0 < that._dy)) stop(null, true);

                },
                stop = function(e, kill) {

                    document.removeEventListener(gtm_listener.move, check, false);
                    document.removeEventListener(gtm_listener.end, stop, false);

                    if (!kill) return;

                    that._el.removeEventListener(gtm_listener.start, set, false);
                    that._callback.call(element);

                };

            /* Properties */
            that._el = element;
            that._dx = x || 0;
            that._dy = y || 0;
            that._callback = callback || function() {};

            /* Origin */
            that._x = that._el.offsetLeft;
            that._y = that._el.offsetTop;

            /* Events */
            that._el.addEventListener(gtm_listener.start, set, false)

        }

    };

// PAGE READY
(function($) {

    // Car selector init
    $('form[id*=carselector]').each(function() {
        new CarSelector(this)
    });

    //Setting Home Push Btns
    homepushCta(0);

    // add slider thumb ( page showroom )
    addSliderThumb();

    // V2.4 - CPW-3498 // Eligibilité Link My Car
    linkMyCar();

    // skin select page showroom
    selectSkin();

    // dropDown Menu
    dropDownMenu();

    // checkbox skin page showroom
    checkboxSkin();

    addThisScript();

}(jQuery))


//  Dynamisation CTA
var setCtaMobile = function() {
    $('.showroom-mobile .cta').each(function() {
        var dftStyles = $(this).attr('data-off');
        //console.log(dftStyles)
        var hvrStyles = $(this).attr('data-hover');

        $(this).find('a').each(function() {
            $(this).attr('style', dftStyles);

            $(this).on('mouseenter', function() {
                $(this).attr('style', hvrStyles);

            }).on('mouseleave', function() {
                $(this).attr('style', dftStyles);
            });

        });
    });
}
setCtaMobile();

var setTools = function() {

    $('.showroom-mobile .tools').each(function() {
        var dftStyles = $(this).attr('data-off');
        var hvrStyles = $(this).attr('data-hover');
        if($(this).attr('data-firstoff') && $(this).attr('data-firsthover')){
            var dftFirstStyles = $(this).attr('data-firstoff');
            var hvrFirstStyles = $(this).attr('data-firsthover');
        }

        $(this).find('a').each(function(index) {
            if(dftFirstStyles != undefined && index==0){
                $(this).attr('style', dftFirstStyles);

                $(this).on('mouseenter, touchstart', function(e) {
                    e.stopPropagation();
                    $(this).attr('style', hvrFirstStyles);

                }).on('mouseleave, touchend', function(e) {
                    e.stopPropagation();
                    $(this).attr('style', dftFirstStyles);
                });

            } else {
                $(this).attr('style', dftStyles);

                $(this).on('mouseenter, touchstart', function(e) {
                    e.stopPropagation();
                    $(this).attr('style', hvrStyles);
                    //console.log('hover / '+hvrStyles)

                }).on('mouseleave, touchend', function(e) {
                    e.stopPropagation();
                    $(this).attr('style', dftStyles);
                });
            }

        });
    });
}
setTools();

var setLinks = function() {
    $('.showroom-mobile .links').each(function() {
        var dftStyles = $(this).attr('data-off');
        var hvrStyles = $(this).attr('data-hover');

        $(this).find('a').each(function(index) {
            $(this).attr('style', dftStyles);

            $(this).on('touchstart', function(e) {
                e.stopPropagation();
                $(this).attr('style', hvrStyles);
                //console.log('hover / '+hvrStyles);

            }).on('touchend', function(e) {
                e.stopPropagation();
                $(this).attr('style', dftStyles);
            });
        });
    });
}
setLinks();


//  Dynamisation PagerShowroom
var setPagerShowroom = function() {

    $('.showroom-mobile.pagenav').each(function() {
        var dftStyles = $(this).attr('data-on');

        $(this).find('li a').each(function() {
            $(this).attr('style', dftStyles);

        });
    });
}
setPagerShowroom();


// GLOBAL REFRESH
var globalRefresh = function() {
    if ($('.cta').length > 0) {
        $('.cta').each(function() {
            new magicCta($(this), 'li');
        })
    }
    if ($('.media').length > 0) {
        $('.media').each(function() {
            new magicCta($(this), 'a', 'span');
        })
    }
    if(myScroll) {
        myScroll.refresh();
    }

    // Reload bxslider by MISSIRIA / #4502
    if (mediaSlider) {
        /* VERSION DESTROY
		mediaSlider.destroySlider();
		mediaSlider.reloadSlider();
		*/
        var wH = $(window).height(),
            wW = $(window).width();


        // VERSION UPDATE
        $('.mediaPop .slideThis figure').each(function(index, el) {
            // $(el).attr('style', styles)

            $(el).css({
                position: 'relative',
                overflow: 'hidden',
                height: wH,
                width: wW
            });




            $(el).find('img, iframe, video').each(function() {
                var oThis = this,
                    iH = $(oThis).height(),
                    iW = $(oThis).width();

                //console.log("iH : " + iH + " / iW : " + iW)
                if (wH > wW) {
                    console.log("device : portrait");
                    var hValue = Math.ceil((iH * wW) / iW),
                        wValue = wW,
                        mLvalue = -(wValue / 2),
                        mTvalue = -(hValue / 2);

                        if(hValue>wH){
                            var hValue = wH,
                                wValue = Math.ceil((iW * wH) / iH),
                                mLvalue = -(wValue / 2),
                                mTvalue = -(hValue / 2);
                        }

                } else {
                    //console.log("device : landscape");
                    var hValue = wH,
                        wValue = Math.ceil((iW * wH) / iH),
                        mLvalue = -(wValue / 2),
                        mTvalue = -(hValue / 2);

                        if(wValue>wW){
                            var wValue = wW,
                                hValue = Math.ceil((iH * wW) / iW),
                                mLvalue = -(wValue / 2),
                                mTvalue = -(hValue / 2);
                        }
                }
                $(oThis).css({
                    position: 'absolute',
                    top: '50%',
                    left: '50%',
                    minHeight: hValue,
                    height: hValue,
                    maxHeight: hValue,
                    minWidth: wValue,
                    width: wValue,
                    maxWidth: wValue

                });
                setTimeout(function() {
                    $(oThis).css({
                        marginTop: mTvalue,
                        marginLeft: mLvalue
                    });
                }, 500);


            });

        });
        /*
                $('.mediaPop .slideThis').css({
                    width: mediaSlider.getSlideCount() * $(window).width(),
                    height: $(window).height()
                });
        */
    }

    //if( $( "#slider" ).length ){$('#amount').css({ left : $('#slider .ui-slider-handle').position().left + dragHalf });}
};
$(window).bind('resize orientation', globalRefresh);
// $('.content').click(menu.close);


$(document).on('ready', function(){

    /* AJOUT CPW-4097 */
    //  Dynamisation Comparateur
    var setPointsFortsLight = function(root,index) {
        var root = root;

        var $sliders = $(root).find('.slide').not('.bx-clone');
        var gtmArrow = function(currentIndex){
            var $left = $(root).find('a.bx-prev');
            var $right = $(root).find('a.bx-next');
            var step_number = parseInt(currentIndex)+1;
            var left_slide = $sliders.get((currentIndex-1+$sliders.length)%$sliders.length);
            var right_slide = $($sliders.get((currentIndex+1)%$sliders.length));
            $left.attr('data-gtm','eventGTM|Showroom::'+page_vehicule_label+'::Strengths::'+step_number+'|Navigation::Arrow::left|'+$(left_slide).attr('data-label')+'||');
            $right.attr('data-gtm','eventGTM|Showroom::'+page_vehicule_label+'::Strengths::'+step_number+'|Navigation::Arrow::right|'+$(right_slide).attr('data-label')+'||');
        };
        var gtmPager = function(){
            $(root).find('.bx-pager-link').each(function(){
                var $link = $(this);
                var index = $link.attr('data-slide-index');
                var step_number = parseInt(index)+1;
                var slide = $sliders.get(index);
                $link.attr('data-gtm','eventGTM|Showroom::'+page_vehicule_label+'::Strengths::'+step_number+'|Navigation::Click::'+step_number+'|'+$(slide).attr('data-label')+'||');
            });
        };
        $(root).find('.slides').bxSlider({
            infiniteLoop:true,
            auto: true,
            slideSelector:'div.slide',
            onSlideAfter: function($slider, oldIndex, newIndex) {
                gtmArrow(newIndex);

                var value = 'eventGTM|Showroom::'+page_vehicule_label+'::Strengths|Display::'+(newIndex+1)+'|'+$slider.attr('data-label')+'||';
                console.log("setTrigger : " + value);
                $(document).trigger({
                    type: 'gtm',
                    dataGtm: value
                });
            },
            onSliderLoad: function(currentIndex) {

            }
        });
        gtmArrow(0);
        gtmPager();
    }
    $('.clspointsfortslight').each(function(index,el){
        new setPointsFortsLight(el,index);
    });
    gtmCit.initNewGTM();
});
