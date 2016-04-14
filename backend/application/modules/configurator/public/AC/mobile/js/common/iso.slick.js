  var slickslideshow = function($root) {
    this.init($root);
  };
  slickslideshow.prototype = {
    init: function(obj) {
      var oThis = this;
      oThis.root = $(obj);

      oThis.data = oThis.root.attr('data-jsobj');
      oThis.dataClean = oThis.data.substring(1, oThis.data.length - 1).replace(/\'/g, '"');
      try {
        oThis.dataCol = jQuery.parseJSON(oThis.dataClean);
      } catch (err) {
        console.log(err);
      }
      oThis.param = oThis.dataCol.option;


      oThis.popincontent = $('.popinContainer .iscrollScrollbar .iscrollscroller');

      oThis.root.slick({
        infinite: oThis.param.infinite,
        slidesToShow: oThis.param.slidetoshow,
        slidesToScroll: oThis.param.slidestoscroll,
        dots: oThis.param.dots,
        speed: oThis.param.speed
      });

      if (oThis.param.externalnav == true) {
        oThis.slickcontextexternalnav = oThis.root.parents('.slickcontext').find('.slickexternalnav');
        oThis.slickcontextnav();
      }

      if (oThis.param.zoomeffect == true) {
        // oThis.root.find('[data-popinName="popinzoom"]').on('click', function(e) {
        //   e.preventDefault();
        //   oThis.zoomeffectpopin($(this));
        // });

        if (oThis.root.next().hasClass('zoom')) {
          oThis.root.next().on('click', function(e) {
            e.preventDefault();
            oThis.zoompictoclick($(this));
          });
        }
      }

      $(document).on('closepopin', function() {
        oThis.setToggleH();
      });
    },

    slickcontextnav: function() {
      var oThis = this;

      oThis.root.on('beforeChange', function(event, slick, currentSlide, nextSlide) {
        oThis.slickcontextexternalnav.find('li img').removeClass('on');
        oThis.slickcontextexternalnav.find('li:eq(' + nextSlide + ') img').addClass('on');

        if (oThis.param.externalnav == true) {
          oThis.root.parents('.slickcontext').find('.airbump-name').html(oThis.slickcontextexternalnav.find('li:eq(' + nextSlide + ') img').attr('data-picturename'));
        }

        if (oThis.root.parents('.slickcontext').attr('data-pricetype') == 'monthly' && oThis.root.parents('.slickcontext').find('ul.palette').length != 0) {
          oThis.root.parents('.slickcontext').find('.price-monthly').html(parseInt(oThis.root.parents('.slickcontext').attr('data-pricemonthly')) + parseInt(oThis.slickcontextexternalnav.find('li:eq(' + nextSlide + ') img').attr('data-pricemonthlysecond')));
        } else {
          oThis.root.parents('.slickcontext').find('.price-outright').html(parseInt(oThis.root.parents('.slickcontext').attr('data-priceoutright')) + parseInt(oThis.slickcontextexternalnav.find('li:eq(' + nextSlide + ') img').attr('data-priceoutrightsecond')));
        }
      });

      oThis.slickcontextexternalnav.find('li img').on('click', function(e) {
        if ($(this).hasClass('on')) {
          e.preventDefault();
          e.stopImmediatePropagation();
        } else {
          $(this).parents('.slickexternalnav').find('li img').removeClass('on');
          $(this).addClass('on');
          $(this).parents('.slickcontext').find('.slickslideshow').slick('slickGoTo', $(this).parents('li').index());

          if (oThis.param.externalnav == true) {
            $(this).parents('.slickcontext').find('.airbump-name').html($(this).attr('data-picturename'));
          }

          $(this).parents('.slickcontext').find('.price-prefix').css('display', 'none');
          if ($(this).parents('.slickcontext').attr('data-pricetype') == 'monthly') {
            $(this).parents('.slickcontext').find('.price-monthly').html(parseInt($(this).parents('.slickcontext').attr('data-pricemonthly')) + parseInt($(this).attr('data-pricemonthlysecond')));
          } else {
            $(this).parents('.slickcontext').find('.price-outright').html(parseInt($(this).parents('.slickcontext').attr('data-priceoutright')) + parseInt($(this).attr('data-priceoutrightsecond')));
          }
        }
      });
    },

    setToggleH: function(obj) {
      var oThis = this;

      if (oThis.root.parents('.mod-toggle-open').length > 0) {
        oThis.root.parents('.mod-toggle-content').height(oThis.root.parents('div').outerHeight());
      }
    },

    zoompictoclick: function(obj) {
      obj.parents('.slickcontext').find('.slickslideshow .slick-active .linkvisu').click();
    }
  }


$(document).ready(function() {

  /*$('.slickslideshow').each(function() {
    this.slickCheck = new slickslideshow($(this));
  });*/

  /*$.subscribe('configurator.stepsLoaded', function () {
    $('.slickslideshow').each(function() {
      this.slickCheck = new slickslideshow($(this));
    });

  })*/
});
