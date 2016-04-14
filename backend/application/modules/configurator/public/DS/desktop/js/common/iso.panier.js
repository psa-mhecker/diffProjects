var config = config || {};
config.Panier = function($root) {
  this.init($root);
};

config.Panier.prototype = {
  init: function(el) {
    this.root = $(el);
    this.datastep      =   this.root.attr('data-step');
    this.datapricetype =   this.root.attr('data-pricetype');
    this.dataswitch    =   this.root.attr('data-switch');
    this.datashow      =   this.root.attr('data-show');
    this.root.find('.multiple-items').slick({dots:true, arrows:false});
    this.eventAll();
    this.initPriceType();
  },
  eventAll: function() {
    //SWITCH OUTRIGHT MONTHLY  
    this.root.find(".pricetype-onglet").on('click', this.clickToSwitch.bind(this));
    //BACK TO TOP
    this.root.parent().find('h3 .back-to-top').on('click', this.backToTop);
    //TOGGLE PANEL WITH SLICK
    this.root.find(".detail-panier-toggle .detail-panier").on('click', this.togglePanel.bind(this));
  },
  initPriceType: function() {

    if("monthly->outright" === this.datapricetype) {
      this.root.find(".price.second-price").insertAfter(this.root.find(".infoPrice"));
    }

    if("outright->monthly" === this.datapricetype) {
      this.root.find(".infoPrice").insertAfter(this.root.find(".price.second-price"));
    }

    if("out" === this.datashow) {
      this.root.parent().find(".header figure.in").hide();
    }

    if("in" === this.datashow) {
      this.root.parent().find(".header figure.out").hide();
    }
  },
  clickToSwitch: function(e) {
    var $el = $(e.target);

    if($el.hasClass("outright-onglet")) {
      this.datapricetype = "outright";
      $el.parents('.content-panel').attr('data-pricetype', this.datapricetype);
    }
    if($el.hasClass("monthly-onglet")) {
      this.datapricetype = "monthly";
      $el.parents('.content-panel').attr('data-pricetype', this.datapricetype);
    }
  },
  backToTop: function() {
  $('html, body').animate({
      scrollTop: 0
    }, 200);
    return false;
  },
  togglePanel: function(e) {
    var $panel = $(e.target).parents(".content-panel");
    var $panierToggle = $panel.find(".detail-panier-toggle");
    $panel.find('.multiple-items').slick("unslick");
    $panierToggle.toggleClass('open');
    $panierToggle.find(".detail-panier-content").slideToggle( "fast");
    $panel.find('.multiple-items').slick({dots:true, arrows:false});
    if($panierToggle.hasClass('open'))
      $panel.find('.btn-panier-style-3').insertAfter($panierToggle.find(".separator"));
    else
      $panel.find('.btn-panier-style-3').insertAfter($panierToggle);
  }
}

$(function  () {
  new config.Panier($(".panier").find(".content-panel"));
})
