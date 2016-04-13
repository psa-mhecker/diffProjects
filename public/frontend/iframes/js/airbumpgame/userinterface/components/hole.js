;
(function(createjs, components, ElementBase) {
    "use strict";
    //
    // CONSTRUCTEUR
    //
    var Hole = function(x, y) {
        this.initialize();
        var s = new createjs.Shape();
        s.graphics.beginFill('#505d66').drawCircle(0, 0, 40).endFill();
        this.addChild(s);
        this.x = x;
        this.y = y;
    };

    var p = Hole.prototype = new createjs.Container();
    //
    //  VARIABLES PRIVEE
    //

    //
    //  VARIABLES PUBLIC
    //

    //
    //  FUNCTIONS
    //

    //
    //  NAMESPACE
    //    
    components.Hole = Hole;
}(createjs, NameSpace('airbumpgame.userinterface.components')));