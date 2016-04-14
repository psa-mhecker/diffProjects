ISO.moduleCreate('slice88', function($el, param) {
 
  $el.each(function() {
    $this = $(this);
    this.toggleCheck = new ModuleToggle($this);    


    $this.find('.slickslideshow').each(function() {
      this.slickCheck = new slickslideshow($(this));
    });

    $this.find('a').each(function() {
      var $linkGlobal = $(this);
      if ($linkGlobal.is('[data-popinname]')) {
        var popTrigger = this;
        this.openpopingabCheck = new openpopingab($(popTrigger));
      }
    });
  });


var slice88 = {

    init: function(){
      var $zone = $el;
      var normalize = function (){
        if($zone.hasClass('slice-cc88')){
            $zone.find('.align-horizontal .option').each(function (){

              var $current   = $(this);
              var $eltImage  = $current.find('.medias').first();

                $eltImage.find('img').first().one("load", function() {

                    var eltpadding = parseInt($current.css('padding'), 10);
                    var imgHeight  = $eltImage.outerHeight()+eltpadding;


                    $eltImage.css({
                      position: 'absolute',
                      bottom: eltpadding,
                      left: eltpadding,
                      right: eltpadding,
                      width: 'auto'
                    });

                    $current.css({
                      paddingBottom: imgHeight+'px'
                    });



                }).each(function() {
                  if(this.complete) $(this).load();
                });


            });


        }
        if($zone.hasClass('slice-dc88')){


            $zone.find('.family.align-horizontal').each(function(index, el) {
              var $current = $(this);
              var $currentOptions = $current.find('.option');

              var maxHeight = 0;
              var maxTitleHeight = 0;

              $currentOptions.each(function(index, el) {
                var $this = $(this);
                var $eltImage  = $this.find('.medias').first();

                var $currentTitle = $this.find('.option-title').first();
                var titleHeight = 0;

                var thisHeight = 0;

                $this.css({
                  height: 'auto'
                });

                $currentTitle.css({
                  height: 'auto'
                });

                titleHeight = $currentTitle.outerHeight();

                if(titleHeight > maxTitleHeight){
                  maxTitleHeight = titleHeight;
                }

                thisHeight = $this.outerHeight();
                if(thisHeight > maxHeight){
                  maxHeight = thisHeight;
                }

              });

              $currentOptions.each(function(index, el) {
                var $this = $(this);
                var $currentTitle = $this.find('.option-title').first();

                $this.outerHeight(maxHeight);
                $currentTitle.outerHeight(maxTitleHeight);
              });

            });

        }


      };
      
      $('.open-category-content').slideToggle(0);
      window.addEventListener("resize", normalize);




      $zone.find('.plus-perso').click(function (){
        var $current = $(this);
        var $currentSpan = $current.find('span').first();

        var divIdToOpen = $current.data('open');
        var $divToOpen = $('#'+divIdToOpen);

        var isOpen = $divToOpen.hasClass('open');

        if(!isOpen){
          $currentSpan.text('Moins de personalisations');
          $divToOpen.addClass('open');
          $divToOpen.slideToggle(100);
          $current.addClass('opened');
          normalize();
        }
        else{
          $('html, body').stop().animate({
            scrollTop: $divToOpen.offset().top-$(window).height()+$current.outerHeight(true)
          }, 1000, 'swing', function (){
            if(this.tagName.toUpperCase() == "BODY"){
              $divToOpen.slideToggle(0);
              $currentSpan.text('Plus de personalisations');
              $divToOpen.removeClass('open');
              $current.removeClass('opened');
            }
          });
        }
      });

      $('label.checkbox').click(function (e){
        e.preventDefault();
        e.stopImmediatePropagation();
        var $current = $(this);

        labelCheck($current);

        if($current.hasClass('selected')){
          $current.removeClass('selected');
        }
        else{
          $current.addClass('selected');
        }
        $.publish('configurator.newdata');
      });


      $('label.radio').click(function(e) {
        e.preventDefault();
        var $current = $(this);
        var $parent = $current.closest('.family').find('.option');

        $parent.removeClass('active');

        labelCheck($current);

        if($parent.hasClass('active')) {
          $parent.find('label.radio').removeClass('selected');
          $current.addClass('selected');
        }
        $.publish('configurator.newdata');
      });

      var labelCheck = function(elt){
        if(elt.hasClass('selected') && elt.hasClass('checkbox')){
          elt.closest('.option').removeClass('active');
        }
        else{
          elt.closest('.option').addClass('active');
        }
      };

      setTimeout(function(){
        normalize()
      }, 500);
    }

  };
  slice88.init();

});
