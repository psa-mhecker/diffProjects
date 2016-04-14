ISO.moduleCreate('sliceDN14', function($el, param) {

    var Nav = (function() {
        var $nav,
            $panier,
            $bg,
            width,
            $window,
            $nextStep,
            navOffset,
            PANIER_WIDTH,
            firstPassFlag,
            $firstLevelMenu,
            $firstLevelAncre,
            $secondLevelMenu;

        var animation = {
            time: 0,
            diff: 14,
            minTime: 300,
            maxTime: 1000,
            easeOutQuad: function(t) {
                return -this.maxTime * (t /= this.diff) * (t - 2) + this.minTime;
            }
        };

        function init() {
            $nav = $('#nav', $el);
            $panier = $(".panier");
            $window = $(window);
            $nextStep = $nav.find('.next-step');
            navOffset = $nav.position().top;
            PANIER_WIDTH = 32.42;
            $firstLevelMenu = $nav.find('#main-menu > li');
            $firstLevelAncre = $firstLevelMenu.find(' > a');
            $secondLevelMenu = $firstLevelMenu.find('li');

            var isiPad = navigator.userAgent.match(/iPad/i) != null;

            if (isiPad) {
                $firstLevelMenu.css({
                    'display': 'inline-block'
                });
            };

            var width = (100 - 28) / ($firstLevelMenu.length - 2) + '%';
            $firstLevelMenu.each(function (index, val) {
                if (index === 0 || index === $firstLevelMenu.length - 1) {
                    return
                };
                $(val).css( {
                    'width': width
                });
            });

            events();

            if ($firstLevelMenu.first().hasClass('disabled')) {
                $firstLevelAncre.eq(1).trigger('click');
            } else {
                $firstLevelAncre.first().trigger('click');
            };

            setDimension();
        }

        // Attach all nav's events
        function events() {
            $window.on("scroll", updateStickyNav);
            $window.on("resize", setDimension);
            $window.on("orientationchange", setDimension);
            $nextStep.on("click", showToNextStep);
            $firstLevelAncre.on("click", selectfirstLevelStep);
            $secondLevelMenu.on("click", selectSubCategory);
        }

        // fired whenever we click on a first-level-menu
        function selectfirstLevelStep(e) {
            e.preventDefault();
            var $li = $(this).parent();
            var $prevSelectedStep = $li.siblings('.active');

            // Do nothing if an element is already clicked    
            if ($li.hasClass('active') || $li.hasClass('disabled')) {
                return;
            } else {
                // toggle highlight for the selected and de-selected 
                // item
                $li.addClass('active');
                $prevSelectedStep.removeClass('active');
            }

            if ($li.index() === $firstLevelMenu.length - 1 || $li.index() === 0) {
                $nextStep.hide();
            } else {
                $nextStep.show();
            }

            // hide substeps of the de-selected element
            // and show subSteps for the new selected element            
            hideSubSteps($prevSelectedStep, function() {
                showSubSteps($li, $li.index() === 1 && firstPassFlag);
            });

            if ($li.index() === 1 && !firstPassFlag) {
                firstPassFlag = true;
            }
        }

        // hide sub step of the last deselected 
        // first-level-nav. this function get called 
        // whenever we change curernt selected first-level-nav
        function hideSubSteps($el, callback) {
            var $submenu = $el.find('ul');

            $submenu.children().each(function(index, el) {
                setTimeout(function() {
                    $(el).stop().animate({
                        marginTop: -25,
                        opacity: 0
                    }, 300, function() {
                        $(el).css({
                            zIndex: -1,
                            marginTop: -62
                        });
                    });
                });
            });
            setTimeout(function () {
                    $submenu.removeClass('push-to-top');
            }, 300);

            if (callback) {
                callback();
            }
        }

        // show sub step of the current selected 
        // first-level-nav. this function get called 
        // whenever we click on a first-level-nav
        function showSubSteps($selectedFirstLevelNav, selectSecond) {
            var time;
            var $submenu = $selectedFirstLevelNav.find('ul');
            var counter = $submenu.children().length;
            $submenu.children().each(function(index, el) {
                if (index === 0) {
                    $(el).trigger('click');
                }
                time = animation.easeOutQuad(index + 1);
                (function(time) {
                    setTimeout(function() {
                        $(el).css({
                            zIndex: 2
                        }).stop().animate({
                            marginTop: 0,
                            opacity: 1,
                            zIndex: 2
                        }, time, function() {
                            --counter;
                            if (!counter) {
                                $submenu.addClass('push-to-top');
                            }
                        });

                    });
                })(time);
            });
        }

        // Stick the nav to the top if the nav reached 
        // the page top
        function updateStickyNav() {
            var $scroll = $(this).scrollTop();
            var $panierRealHeight = 0;
            var condPanier = true;
            if($panier.find(".header").height() > 0)
                panierRealHeight = $panier.height() + 121 - $panier.find(".header").height();
            else 
                panierRealHeight = $panier.height() + 121;
            condPanier = ((panierRealHeight) + 103 <= ($(window).height()));

            if (($scroll >= navOffset) && condPanier) {
                $panier.removeClass('relative-position')
                    .addClass('sticky');
                var rightposition = parseInt(($window.width()-$nav.parent().width())/2);
                if ($window.width() < 1024 ) {rightposition = rightposition * 2;}

                $panier.css({
                    'width': ((($nav.parent().width()) * PANIER_WIDTH)/100),
                    'right' : rightposition
                }); 
            } else {
                $panier.removeClass('sticky').addClass('relative-position');
                $panier.removeAttr("style");
            }

            if ($scroll >= navOffset) {
                $nav.removeClass('content')
                    .addClass('sticky');

                if ($('.nav-clone').length === 0) {
                    $('<div class="nav-clone"></div>').insertAfter($nav).height($nav.height());
                }

                $nav.css({
                    'width': $nav.parent().width()
                });

            } else {
                $nav.removeClass('sticky').addClass('content');
                $nav.css({
                    'width': '100%'
                });

                if ($('.nav-clone').length > 0) {
                    $('.nav-clone').remove();
                }
            }

            if ($window.width() < 1024 ) {
                if ($nav.hasClass('sticky')) {
                    $nav.css({
                        'left': - $window.scrollLeft()
                    });
                    $panier.css({
                        'right' : rightposition + $window.scrollLeft()
                    });

                } else {
                    $nav.css({
                        'left': 'inherit'
                    });
                    $panier.removeAttr("style");
                };
            } else {
                $nav.css({
                    'left': 'auto'
                });
            }
        }

        // Select next sub step if there is any, otherwise
        // select next first-level-step.
        function showToNextStep(e) {
            e.preventDefault();
            var $selectedFirstLevelNav = $("#main-menu > li.active");
            var $selectedSecondLevelNav = $("li.active li.active");

            // Check if this is the last substep
            if ($selectedSecondLevelNav.next().length == 0) {
                $selectedFirstLevelNav.next().children('a').trigger('click');
            } else {
                $selectedSecondLevelNav.next().trigger('click');
            }
        };

        function loadContent() {
            $('#dynamic-content').finish().fadeOut(300).fadeIn(300);
        }

        function selectSubCategory(e) {
            var $this = $(this);
            e.preventDefault();

            if ($this.hasClass('active')) {
                return;
            }

            var link = $this.find('a');
            var titles = '<h1>'+ link.data('title') + '</h1>' + 
                            '<p class="subtitle">'+ link.data('sub-title') + '</p>';

            $('.titles').finish().fadeOut(300, function () {
                $(this).html(titles).fadeIn(300)
            });

            // load content;
            $.publish("iso.loadSteps", $this);

            $secondLevelMenu.removeClass('active');
            $this.addClass("active");
        };

        function setDimension() {
            if ($nav.hasClass('sticky')) {
                $nav.width($nav.parent().width());
                var rightposition = parseInt(($window.width()-$nav.parent().width())/2);
                if ($window.width() < 1024 ) {rightposition = rightposition * 2;}
                $panier.css({
                    'width': ((($nav.parent().width()) * PANIER_WIDTH) /100),
                    'right' : rightposition
                });
            }

            if ($window.width() < 1024 ) {
                if ($nav.hasClass('sticky')) {
                    $nav.css({
                        'left': - $window.scrollLeft()
                    });
                    $panier.css({
                        'right' : rightposition + $window.scrollLeft()
                    });

                } else {
                    $nav.css({
                        'left': 'inherit'
                    });
                    $panier.removeAttr("style");
                };
            } else {
                $nav.css({
                    'left': 'auto'
                });
            }

            // recalculate the nav top position because it depends 
            // on page width;

            if ($nav.hasClass('sticky')) {
                // we use nav-clone which a hidden copy of the nav because
                // $nav is fixed and the top will always be equal to zero
                // whereas nav-clone is static
                navOffset = $('.nav-clone').position().top;
            } else {
                navOffset = $nav.position().top;
            }
        }

        return {
            init: init
        };
    })();

    Nav.init();
});
