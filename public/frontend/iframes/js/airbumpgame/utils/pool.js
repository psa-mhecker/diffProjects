;
(function() {
    "use strict";
    //
    // CONSTRUCTEUR
    //
    var Pool = function(constructor) {
        this._constructor = constructor;
        this._freeObjects = [];
        this._usedObjects = [];
    }
    var p = Pool.prototype;
    //
    //  VARIABLES PRIVEE
    //
    p._constructor;
    p._freeObjects;
    p._usedObjects;

    //
    //  VARIABLES PUBLIC
    //

    //
    //  FUNCTIONS
    //
    p.get = function() {
        var element;
        if (this._freeObjects.length) {
            element = this._freeObjects.pop();
        } else {
            element = new this._constructor();
        }
        this._usedObjects.push(element);
        // console.log('get');
        // console.log('free', this._freeObjects.length);
        // console.log('used', this._usedObjects.length);
        return element;
    };

    p.release = function(element) {
        this._freeObjects.push(element);
        this._usedObjects.splice(this._usedObjects.indexOf(element), 1);
        // console.log('release');
        // console.log('free', this._freeObjects.length);
        // console.log('used', this._usedObjects.length);
    };

    //
    //  NAMESPACE
    //    
    utils.Pool = Pool;
}(NameSpace('airbumpgame.utils')));