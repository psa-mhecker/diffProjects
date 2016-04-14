'use strict';

$(function(){

    var $nav;
    var MARGE = 80;

    init();

    function init() {
        $nav = $('.ancre').find('ul');

        if ($nav.length === 0) {
            return; // exit because the ancre isn't in this page
        }
        updateAnchor();
        events();

    }

    function events() {
        $nav.on('moveLeft', moveLeft)
            .on('moveRight', moveRight)
            .on('click', move);
        $(window).on('resize', updateAnchor);
    }

    function updateAnchor () {
      if ($nav[0].scrollWidth > $nav.width()) {
          $nav.addClass('more right');

      } else {
          $nav.removeClass('more right left');
          $nav.stop(true, true).animate({'margin-left': '0px'});
      }
    }

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
});
