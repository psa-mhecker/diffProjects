;
(function(createjs, utils) {
    "use strict";
    var Loader = {};
    for (var m in createjs.EventDispatcher.prototype) {
        Loader[m] = createjs.EventDispatcher.prototype[m];
    }
    Loader._preloader = new createjs.LoadQueue();


    Loader.Load = function() {
        Loader._preloader.on('fileload', Loader._FileloadHandler, Loader);
        Loader._preloader.on('complete', Loader._CompleteHandler, Loader);
        var manifest = [];
        if (Array.isArray(arguments[0])) {
            for (var i = 0; i < arguments[0].length; i++) {
                var p = arguments[0][i];
                manifest.push({
                    src: p,
                    id: p
                });
            }
        } else {
            for (var m in arguments) {
                var p = arguments[m];
                manifest.push({
                    src: p,
                    id: p
                });
            }
        }
        Loader._preloader.loadManifest(manifest);
    };

    Loader._FileloadHandler = function(e) {
        var item = e.item;
    };

    Loader._CompleteHandler = function() {
        Loader._preloader.removeAllEventListeners();
        Loader.dispatchEvent('complete');
    };

    Loader.GetResult = function(id) {
        var element = Loader._preloader.getResult(id);
        if (!element) {
            throw {
                toString: function() {
                    return 'le fichier ' + id + ' n\'existe pas';
                }
            };
        }
        return element;
    };
    utils.Loader = Loader;
}(createjs, NameSpace('airbumpgame.utils')));