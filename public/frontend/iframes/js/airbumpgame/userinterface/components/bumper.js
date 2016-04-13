;
(function(createjs, components, Loader) {
    "use strict";
    //
    // CONSTRUCTEUR
    //
    var Bumper = function(x, y) {
        this.initialize();
        this._bumper1 = new createjs.Bitmap(Loader.GetResult('img/bumper1.png'));
        this._bumper1.regX = 62;
        this._bumper1.regY = 53;
        this._bumper2 = new createjs.Bitmap(Loader.GetResult('img/bumper2.png'));
        this._bumper2.regX = 68;
        this._bumper2.regY = 67;
        this.addChild(this._bumper1);
        this.addChild(this._bumper2);
        this._bumper2.visible = false;
        this.x = x;
        this.y = y;
    };

    var p = Bumper.prototype = new createjs.Container();
    //
    //  VARIABLES PRIVEE
    //
    p._bumper1;
    p._bumper2;
    p._startTime;
    p._isBump = true;

    //
    //  VARIABLES PUBLIC
    //

    //
    //  FUNCTIONS
    //

    p.show = function() {
        var _this = this;
        new createjs.Tween.get(this).to({
            alpha: 1
        }, 500);
    };

    p.hide = function() {
        var _this = this;
        new createjs.Tween.get(this).to({
            alpha: 0
        }, 500).call(function() {
            _this.dispatchEvent('finishGame');
        });
    };

    p.bump = function() {
        this._startTime = new Date().getTime();
        this._isBump = true;
        this._bumper1.visible = false;
        this._bumper2.visible = true;
    };

    p.render = function(time) {
        if (this._isBump) {
            if (new Date().getTime() - this._startTime > 150) {
                this._isBump = true;
                this._bumper1.visible = true;
                this._bumper2.visible = false;
            }
        }
    };

    //
    //  NAMESPACE
    //    
    components.Bumper = Bumper;
}(createjs, NameSpace('airbumpgame.userinterface.components'), NameSpace('airbumpgame.utils.Loader')));