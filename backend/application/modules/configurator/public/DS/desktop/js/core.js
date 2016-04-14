(function($, win, doc, ISO) {

    var testMsGesture = window.navigator.pointerEnabled;
  if (navigator.vendor){
  	var isSafari = navigator.vendor.indexOf("Apple")==0 && /\sSafari\//.test(navigator.userAgent);
  	if(isSafari){
  		$('html').addClass('safari');
  	}
  }

    var objs = {}, storeList = [];

    ISO.$htmlTag = $('html').addClass('js').removeClass('js-notready');
    ISO.$doc = $(doc);
    ISO.$win = $(win);
    ISO.$body = $('body');

    if (ISO.$htmlTag.hasClass('csstransitions')){
        jQuery.fx.off = true;
    }

    ISO.moduleCreate = function(name, obj) {
        objs[name] = obj;
    };

    ISO.getmoduleCreate = function(name){
      return objs[name];
    };

    ISO.storeObj =  function(obj, id, callback){
      if(obj.id === undefined && id !== undefined){
        obj.id = id;
      }
      storeList.push(obj);
      if(typeof callback === 'function' && callback){callback();}
      return obj;
    };

    ISO.getStoreObj =  function(id){
      for(var i = 0, lng = storeList.length; i < lng; i++){
        if(storeList[i].id === id){
          return storeList[i];
        }
      }
    };

    ISO.destroyObj = function(id){
      var arr = [];
      for(var i = 0, lng = storeList.length; i < lng; i++){
        if(storeList[i].id === id && storeList[i].destroy){
          storeList[i].destroy();
        } else {
          arr.push(storeList[i]);
        }
      }
      storeList = arr;
    };

    ISO.control = function() {
        var listObjs = [];

        var init = function (content) {
          var $dataObjs;

          if (content) {
            $dataObjs = $(content).find('[data-jsobj]')
          }
          else {
            $dataObjs = $('[data-jsobj]')
          }

          $dataObjs.each(function (ind, el) {
              setObj($(el));
          });
        };

         var setObj = function($el, objName) {
            var data = $el.attr('data-jsobj');
            var objNames = getObjParam(data);

            for ( var key in objNames ) {
              var scriptName =  objNames[key];
              if (objs[scriptName] !== undefined) {
                    objs[scriptName]($el, objNames.option);
                }
            }

        };
        
        var setModule = function($el, objName) {
            if (objName !== undefined) {
                objs[objName]($el);
            }
        };

        var getObjParam = function(data) {
            data = data.substring(1,data.length -1).replace(/\'/g, '"');
            try{
                return jQuery.parseJSON(data);
            }catch(err){
                console.log(err);
            }
        };

        return {
            init: init,
            listObjs: listObjs,
            setModule: setModule
        };
    }();

    $(function() {
        ISO.control.init();
        $.subscribe('configurator.stepsLoaded', function () {
          ISO.control.init('#dynamic-content');
        })
    });


})(jQuery, window, document, window.ISO = window.ISO || {});
