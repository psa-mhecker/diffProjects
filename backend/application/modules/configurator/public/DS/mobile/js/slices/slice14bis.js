/* N14 - Navigation showroom */

'use strict';

var N14 = function($root) {
  this.init($root);
};

N14.prototype = {

  init: function($root) {
    var oThis = this;
    oThis.$root = $root;

    oThis.step = 0;
    oThis.stepLength = 0;
    oThis.state = false;
    oThis.myScroll = null;

    oThis.locStorRecap = 'recap';

    oThis.data = [];
    oThis.slickOption = {
      'infinite': true,
      'slidetoshow': 1,
      'slidetoscroll': 1,
      'zoomeffect': true,
      'dots': true,
      'speed': 300
    };
    // TO REMOVE AFTER DYNAMISATION
    oThis.test = 1;
    // TO REMOVE AFTER DYNAMISATION

    oThis.$body = $('.body');
    oThis.$recap = oThis.$body.find('.recap');
    oThis.$recapTable = oThis.$recap.find('.recap-table');


    oThis.url = oThis.$root[0].dataset.html;
    oThis.$priceContainer = oThis.$root.find('.price');
    oThis.$pcInfos = oThis.$priceContainer.find('a');
    oThis.$nav = $('#nav', oThis.$root);
    oThis.$visual = $('.visual', oThis.$root);
    oThis.stepLength = parseInt($('b i:eq(1)', oThis.$nav).text());
    oThis.step = parseInt($('.container section', '.mainContainer')[0].dataset.step);
    oThis.placeHolder = $('.container');

    oThis.displayBtn();
    oThis.events();
    oThis.initSlick();

    //if recap in storage
    if (localStorage.recap) {
      oThis.readStorage();
    }

    if(oThis.$body[0].dataset.pricetype=="outright"){
      $('.containsRadioButton', oThis.$recap).eq(1).click();
    } else if(oThis.$body[0].dataset.pricetype=="monthly"){
      $('.containsRadioButton', oThis.$recap).eq(0).click();
    }

    oThis.$body.find('a').each(function() {
      var linkGlobal = this;
      if ($(this).is('[data-popinname]')) {
        var popTrigger = this;
        this.openpopingabCheck = new openpopingab($(popTrigger));
      }
    });

    oThis.getSelected();
  },

  readJsobj: function(that) {
    // var jsobj = that.attr('data-jsobj');
    // console.log(jsobj);
    // jsobj = jsobj.substring(1, oThis.data.length - 1).replace(/\'/g, '"');
    // try {
    //   oThis.dataCol = jQuery.parseJSON(oThis.dataClean);
    // } catch (err) {
    //   console.log(err);
    // }
    // oThis.param = oThis.dataCol.option;
  },

  /**
   * events() Detect events and dispatch action
   */
  events: function() {
    var oThis = this;

    //Open summary
    $('.btn-recap', oThis.$root).on('click', function() {
      oThis.open();
    });
    //Close summary
    $(document).on('click', '.closeRecap', function() {
      oThis.close();
    });
    $('.closeRecap, .md-overlay, .md-modal').on('touchmove', function(e) {
      e.preventDefault();
      e.stopPropagation();
    });

    //Next Step
    $('.button.next', oThis.$root).on('click', function() {
      oThis.getSelected();
      oThis.nextStep(oThis.step + 1);
    });

    //Prev Step
    $('.recap-table .md-trigger', oThis.$recap).on('click', function(e) {
      e.preventDefault();
      oThis.prevStep($(this));
    });

    //Complete configuration
    $('.button.next.end', oThis.$root).on('click', function() {
      oThis.getSelected();
    });

    //Choice price type outright/monthly
    $('.containsRadioButton', oThis.$recap).on('click', function(e) {
      // e.preventDefault();
      oThis.priceType($(this));
    });

    //Send mail
    $('.send-email', oThis.$recap).on('click', function(e) {
      e.preventDefault();
      oThis.saveMail($(this));
    });


    $.subscribe('configurator.newdata', function(){ oThis.update()});


  },

  open: function() {
    var oThis = this;

    $('.body').addClass('recapopen');

    if (oThis.myScroll === null) {
      oThis.myScroll = new iscrollScrollbars(oThis.$recap, true);
    }else{
      oThis.myScroll.refreshpopin();
    }

    oThis.state = true;
  },

  close: function() {
    var oThis = this;

    $('.body').removeClass('recapopen');

    oThis.state = false;
  },

  /**
   * changePrice() Manage price monthly/spot
   */
  priceType: function($that) {
    var oThis = this;

    $('.radio.selected', oThis.$recap).removeClass('selected');
    $('.radio', $that).addClass('selected');

    var type = $('.radio-group .radio.selected input', oThis.$recap)[0].value;

    if (type === '0') {
      oThis.$body[0].dataset.pricetype = 'monthly';
      // SHOW INFOS FROM PRICE BLOCK
      oThis.$pcInfos.show();
    } else if (type === '1') {
      oThis.$body[0].dataset.pricetype = 'outright';
      // HIDE INFOS FROM PRICE BLOCK
      oThis.$pcInfos.hide();
    }
  },

  /**
   * updatePrice() Update price
   */
  updatePrice: function() {
    var oThis = this;
    var url = '../../../json/price.json';

    $.ajax({
      type: 'GET',
      url: url,
      data: oThis.data,
      success: function(resp) {
        // TO REMOVE AFTER DYNAMISATION
        var data = resp[Math.floor(Math.random() * resp.length)];
        // TO REMOVE AFTER DYNAMISATION
        $('.price-outright', oThis.$root).text(data.priceOutright);
        $('.price-monthly', oThis.$root).text(data.priceMonthly);
      },
      error: function(err) {
        // console.log(err);
      }
    });

  },

  update: function() {
    var oThis = this;



    oThis.getSelected();
    oThis.updatePrice();
    oThis.updateSlick();
  },

  initSlick: function(){
    var oThis = this;

    oThis.$visual.slick(oThis.slickOption);
  },

  updateSlick: function() {
    var oThis = this;
    var url = '../../../json/n14slide.json';
    var $items = "";
    var index;

    $.ajax({
      type: 'GET',
      url: url,
      data: oThis.data,
      success: function(resp) {
        // TO REMOVE AFTER DYNAMISATION
        if (oThis.test === 1) {
          oThis.test = 0;
        }else{
          oThis.test = 1;
        }
        // TO REMOVE AFTER DYNAMISATION

        for (var i = 0; i < resp[oThis.test].length; i++) {
          var dom = '<div class="slickslideshow-slide">' +
            '<a href="#" class="linkvisu" data-popinName="popinzoom" data-jsobj="[{"obj":"isoPopinOpenLink"}]">' +
              '<figure>' +
                '<img src=' + resp[oThis.test][i].url + ' alt="alt" data-picturename="alt" class="slickslideshow-slide-bigvisu" />' +
              '</figure>' +
            '</a>' +
          '</div>';
          $items += dom;
        }

        var currentSlide = oThis.$visual.slick('slickCurrentSlide');

        oThis.$visual.slick('unslick').empty().append($items);
        oThis.initSlick();
        oThis.$visual.slick('slickGoTo', currentSlide, true);

        oThis.$visual.find('a').each(function() {
          var linkGlobal = this;
          if ($(this).is('[data-popinname]')) {
            var popTrigger = this;
            this.openpopingabCheck = new openpopingab($(popTrigger));
          }
        });
      },
      error: function(err) {
        // console.log(err);
      }
    });
  },

  updateRecap: function() {
    var oThis = this;
    var steps = oThis.data;

    //cleanRecap
    var $list = oThis.$recapTable.find('li.active');
    $('strong', $list).remove();
    $list.removeClass('active');

    //updateRecap
    for (var i = 0; i < steps.length; i++) {
      var step = steps[i];
      if (typeof step !== 'undefined') {
        var $step = oThis.$recapTable.find('li').eq(i);

        $step.addClass('active');

        if (steps[i] !== null) {
          //FOR CONFIGS
          for (var a = 0; a < steps[i].length; a++) {
            var name = steps[i][a].name;
            var $that = $('.md-trigger', $step);

            $('<strong>' + name + '</strong>').insertBefore($that);
          }
        }
      }
    }
  },
  /**
   * getSelected() Get input checked and update oThis.data + localStorage
   */
  getSelected: function() {
    var oThis = this,
        step = oThis.step,
        dataTmp = [];


    // console.log($('input.radio:checked', 'section'));
    $('label.selected input.radio:checked, label.selected input.checkbox').each(function(i, el) {
      var $that = $(this).parents('.inputcontext'),
          $catCont = $(this).parents('.category'),
          dataAttr = $that[0].dataset,
          $context = $that.parents('section[class^="slice"]'),
           id = el.id,
           name = dataAttr.name,
           priceMonthly = dataAttr.pricemonthly,
           priceOutright = dataAttr.priceoutright;
      step = parseInt($context[0].dataset.step);

      var newConfig = {
        'id': id,
        'step': step,
        'type': 'xx',
        'name': name,
        'priceMonthly': priceMonthly,
        'priceOutright': priceOutright
      };


      dataTmp.push(newConfig);

    });

    oThis.data[step] = dataTmp;

    oThis.updateRecap();
    // oThis.save();
  },

  /**
   * updateStep() Update nav bar
   *  @param {Integer} step : Step number
   */
  updateStep: function(step) {
    var oThis = this;
    var stepName = oThis.$recapTable.find('li').eq(step).find('span:eq(0)').text();
    oThis.step = step;


    // oThis.$nav[0].dataset.step = step; //update step bar
    $('.body')[0].dataset.step = step;

    $('b i:eq(0)', oThis.$nav).text(step + 1); //update 1/4
    $('.title', oThis.$nav).text(stepName); //update step name
    
  },

  prevStep: function(that) {
    var oThis = this;
    var toUrl = that[0].dataset.tourl;
    var toStep = that[0].dataset.tostep;
    var $modal = $('.md-modal.md-effect-5');
    var $nextBtn = $('.md-change-step', $modal);

    toStep = parseInt(toStep);
    if (toStep !== oThis.step) {

      $nextBtn.on('click', function() {
        oThis.ajax(toUrl, function(resp) {
          $('.container', '.mainContainer').empty().append(resp);

          var title = '';
          // TO REMOVE AFTER DYNAMISATION
          if (toStep === 0) {
            title = 'Choisissez une finiton';
          } else if (toStep === 1) {
            title = 'Choisissez une motorisation';
          } else if (toStep === 2) {
            title = 'Choisissez une teinte et des Airbumps';
          } else if (toStep === 3) {
            title = 'Choisissez un garnissage';
          } else if (toStep === 4) {
            title = 'Choisissez les équipements';
          }
          // TO REMOVE AFTER DYNAMISATION

          $('.choice-title h3', oThis.$root).text(title);
          var $nextStep = $('section', '.mainContainer .container');

          $('.md-modal .md-close, .closeRecap').click();

          oThis.updateStep(toStep);
          oThis.data.splice(toStep + 1, oThis.data.length + 1);

          $.publish('configurator.stepsLoaded');
          if($('.body')[0].dataset.step<="4"){
            $('.button.next', oThis.$root).find('span').eq(0).show();
            $('.button.next', oThis.$root).find('span').eq(1).hide();
          }
          oThis.updateRecap();  

          ISO.control.init(oThis.placeHolder);
          
        });
      });


    }
  },

  nextStep: function(toGo) {
    var oThis = this;

    $('html, body').animate({
      scrollTop: 0
    });

    oThis.ajax(oThis.url, function(data) {
// TO REMOVE AFTER DYNAMISATION
      // console.log(data.step[toGo]);
      $.ajax({
        type: 'GET',
        dataType: 'html',
        url: data.step[toGo].urlHtml,
        success: function(resp) {
//KEEP THIS AFTER DYNAMISATION
          oThis.updateStep(data.step[toGo].step);
          $('.choice-title h3', oThis.$root).text(data.step[toGo].title);
          $('.container', '.mainContainer').empty().append(resp);
          var $nextStep = $('section', '.mainContainer .container');

          /**
           * @todo Reinitializer tous les data-jsobj de la nouvelle step
           */
          if($('.body')[0].dataset.step=="4"){
            $('.button.next', oThis.$root).find('span').eq(0).hide();
            $('.button.next', oThis.$root).find('span').eq(1).show();
          }
          ISO.control.init(oThis.placeHolder);
          oThis.update();


//KEEP THIS AFTER DYNAMISATION
        },
        error: function(err) {
          // console.log(err);
        }
      });
// TO REMOVE AFTER DYNAMISATION
    });
  },

  ajax: function(url, callback) {

    $.ajax({
      type: 'GET',
      url: url,
      success: function(resp) {
        callback(resp);
      },
      error: function(err) {
        // console.log(err);
      }
    });

  },
  /**
   * save() Save Recap in local storage
   */
  save: function() {
    var oThis = this;
    var data = JSON.stringify(oThis.data);

    window.localStorage.setItem(oThis.locStorRecap, data);
  },

  /**
   * readStorage() Read localStorage and maj oThis.data
   */
  readStorage: function() {
    var oThis = this;
    var data = JSON.parse(window.localStorage.getItem(oThis.locStorRecap));
    oThis.data = data;
  },

  /**
   * readStorage() Clean localStorage
   */
  clearStorage: function() {
    var oThis = this;
    window.localStorage.clear(oThis.locStorRecap);
  },

  saveMail: function(btn) {
    var oThis = this;
    var url = btn.attr('href');

    $.ajax({
      type: 'POST',
      url: url,
      data: oThis.data,
      success: function(resp) {
        // console.log(resp);
      },
      error: function(err) {
        // console.log(err);
      }
    });

  },

  /**
   * displayBtn() Manage btn nextStep
   */
  displayBtn: function() {
    var i = 0;
    var onceflag = false;
    var oThis = this;

    //If last step
    if (oThis.step === oThis.stepLength) {
      $('.button', oThis.$root).addClass('end');
    }

    if ($('.body').outerHeight() <= screen.height) {
      $('.button', oThis.$root).addClass('visible');
    }

    $(document).scroll(function() {
      if (i >= 10) {
        onceflag = true;
        $('.button', oThis.$root).addClass('visible');
      } else {
        i++;
      }
    });

  }
};

$(document).ready(function() {
  $('.slice-n14bis').each(function(){
    this.N14 = new N14($(this));
  });
});