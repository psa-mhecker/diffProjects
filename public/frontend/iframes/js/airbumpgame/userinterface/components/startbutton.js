;
(function(createjs, components, Loader) {
    "use strict";
    //
    // CONSTRUCTEUR
    //
    var StartButton = function() {
        this.initialize();
        var s = new createjs.Shape();

        this._image = new createjs.Bitmap(Loader.GetResult('img/start.png'));
        var bounds = this._image.getBounds();
        this.regX = bounds.width * 0.5;
        this.regY = bounds.height * 0.5;
        s.graphics.beginFill('#addff9').drawRoundRect(0, 0, bounds.width, bounds.height, 10).endFill();
        this.addChild(s);
        this.addChild(this._image);
        this.cursor = 'pointer';
        var _this = this;
        this.addEventListener('pressup', function(e) {
            _this.hide();
        })
    };

    var p = StartButton.prototype = new createjs.Container();
    //
    //  VARIABLES PRIVEE
    //
    p._image;

    //
    //  VARIABLES PUBLIC
    //

    //
    //  FUNCTIONS
    //
    p.show = function() {
        var _this = this;
        new createjs.Tween.get(this).wait(600).to({
            alpha: 1
        }, 500).call(function() {
            _this.dispatchEvent('onFinish');

        });
    };
    p.hide = function() {
        this.dispatchEvent('startGame');
        var _this = this;
        new createjs.Tween.get(this).to({
            alpha: 0
        }, 500);
    };

    //
    //  NAMESPACE
    //    
    components.StartButton = StartButton;
}(createjs, NameSpace('airbumpgame.userinterface.components'), NameSpace('airbumpgame.utils.Loader')));