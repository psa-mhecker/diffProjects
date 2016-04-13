;
(function(createjs, components, Loader) {
    "use strict";
    //
    // CONSTRUCTEUR
    //
    var AirBump = function(x, y) {
        this.initialize();
        this._airBump1 = new createjs.Bitmap(Loader.GetResult('img/airbump1.png'));
        this._airBump1.regX = 688 * 0.5;
        this._airBump1.regY = 115 * 0.5;
        this._airBump2 = new createjs.Bitmap(Loader.GetResult('img/airbump2.png'));
        this._airBump2.regX = 699 * 0.5;
        this._airBump2.regY = 131 * 0.5;
        this.addChild(this._airBump1);
        this.addChild(this._airBump2);
        this._airBump2.visible = false;
        this.x = x;
        this.y = y;
    };

    var p = AirBump.prototype = new createjs.Container();
    //
    //  VARIABLES PRIVEE
    //
    p._airBump1;
    p._airBump2;
    p._startTime;
    p._isBump = true;

    //
    //  VARIABLES PUBLIC
    //

    //
    //  FUNCTIONS
    //
    p.bump = function() {
        this._startTime = new Date().getTime();
        this._isBump = true;
        this._airBump2.alpha = 1;
        this._airBump1.visible = false;
        this._airBump2.visible = true;
    };

    p.render = function(time) {
        if (this._isBump) {
            if (new Date().getTime() - this._startTime > 250) {
                this._isBump = true;
                this._airBump1.visible = true;
                this._airBump2.visible = false;
            }
        }
    };

    p.show = function() {
        this._isBump = false;
        this._airBump2.visible = true;
        this._airBump2.alpha = 0;
        new createjs.Tween.get(this._airBump2).to({
            alpha: 1
        }, 500);
    };

    p.hide = function() {
        var _this = this;
        this._isBump = false;
        new createjs.Tween.get(this._airBump2).to({
            alpha: 0
        }, 500);
    };

    //
    //  NAMESPACE
    //    
    components.AirBump = AirBump;
}(createjs, NameSpace('airbumpgame.userinterface.components'), NameSpace('airbumpgame.utils.Loader')));
