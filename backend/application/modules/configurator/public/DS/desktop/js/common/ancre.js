/* Toggle */
var ds = ds || {};

ds.ancre = (function ($) {
    'use strict';
    var module = {};

    var $nav;
    var MARGE = 80;

    
    module.init = function (selector) {
        var selector = selector || "ancre";
        $nav = $('.' + selector).find('ul');
        
        if ($nav.length === 0) {
            return; // exit because the ancre isn't in this page
        }

        if ($nav[0].scrollWidth > $nav.width()) {
            $nav.addClass('more right');     
            attachEvents();
        } else {
            // center categories if there are only few
            // 
            var width = 0;
            $nav.children().each(function (index, el) {
                width += $(el).outerWidth( true );
            });            
            var ratio = Math.floor(width *100 /  $nav.width());
            //  auto margins and a width that is smaller than its 
            // container in order to center categories
            $nav.css({'margin': 'auto', 'width': ratio + '%'});
        }
    };

    function attachEvents() {
        $nav.on('moveLeft', moveLeft)
            .on('moveRight', moveRight)
            .on('click', move);
    };

    function moveLeft() {
        var $this = $(this),
            left = 0,
            width = $this.parent().width(),
            categories = $this.children();
        
        categories.each(function (index, el) {
            var $el = $(el);

            left = $el.position().left + $el.width() - width + 10;
            if (left > 0) {
                return false;
            }
        });
        left += MARGE;
        $this.stop(true, true).animate({'margin-left': "-=" + left +'px'}, function () {
            var $last = categories.last(),
                $first = categories.first();

            if ($last.position().left + $last.width() >= width) {
                $this.addClass('right');
            } else {
                $this.removeClass('right');
                $this.stop(true, true).animate({'margin-left': '+=' + MARGE + 'px'});
            }
            if ( $first.position().left >= 0) {
                $this.removeClass('left');
            } else {
                $this.addClass('left');
            }
        }); 
    }

    function moveRight() {
        var $this = $(this),
            left = 0,
            width = $this.parent().width(),
            categories = $this.children();
        
        

        categories.each(function (index, el) {
            var $el = $(el);

            left = $el.position().left + $el.width();
            if (left > 0) {
                left = $el.width()
                return false;
            }
        });

        left = parseInt($this.css('margin-left')) + left;

        $this.stop(true, true).animate({'margin-left': left +'px'}, function () {
            var $last = categories.last(),
                $first = categories.first();
          
            if ($last.position().left + $last.width() >= width) {
                $this.addClass('right');
            } else {
                $this.removeClass('right');
            }

            if ( $first.position().left >= 0) {
                $this.removeClass('left');        
                $this.stop(true, true).animate({'margin-left': 0});
            } else {
                $this.addClass('left');
            }
        }); 
    }

    function move(e) {
        var $this = $(this),
            $parent = $this.parent(),
            $children =  $this.children(),
            $last = $children.last(),
            $first = $children.first(),
            width = $last.position().left + $last.width() - $first.position().left;

        if ($parent.width() >= width) {
          return;
        }

        if (e.pageX - $parent.offset().left > $parent.width() - MARGE && $this.hasClass('right')) {
            $this.trigger('moveLeft');
        } else if (e.pageX - $parent.offset().left < MARGE && $this.hasClass('left')) {
            $this.trigger('moveRight');
        };
    }

    return module;
})(jQuery);