ISO.moduleCreate('slice58', function($el, param) {

  $el.each(function() {
    $this = $(this);
    this.toggleCheck = new ModuleToggle($this);
    this.teintesCheck = new teintesHandler($this);


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

});