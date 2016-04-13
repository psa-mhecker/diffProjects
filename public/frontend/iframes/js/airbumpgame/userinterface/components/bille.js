;
(function(createjs, components, Loader, MathUtils) {
    "use strict";
    //
    // CONSTRUCTEUR
    //
    var Bille = function(x, y) {
        this.initialize();
        this._bille = new createjs.Bitmap(Loader.GetResult('img/bille.png'));
        this.regX = 29;
        this.regY = 23;
        // var s = new createjs.Shape();
        // s.graphics.beginFill('#ff00ff').drawCircle(0, 0, 20).endFill();
        // this.addChild(s);
        this.addChild(this._bille);
        this.x = x;
        this.y = y;
        this._angle = 90;
        this.calculEquationDroite();
    };

    var p = Bille.prototype = new createjs.Container();
    //
    //  VARIABLES PRIVEE
    //
    p._bille;
    //
    //  VARIABLES PUBLIC
    //
    p.equationDroite;
    p.angle;
    //
    //  FUNCTIONS
    //

    p.calculEquationDroite = function() {
        var x0 = this.x;
        var y0 = this.y;
        var a = this.angle * Math.PI / 180;
        var d = 500;
        var x1 = x0 + d * Math.cos(a);
        var y1 = y0 + d * Math.sin(a);
        this.equationDroite = MathUtils.EquationDroite(x0, y0, x1, y1);
    };

    //
    //  NAMESPACE
    //    
    components.Bille = Bille;
}(createjs, NameSpace('airbumpgame.userinterface.components'), NameSpace('airbumpgame.utils.Loader'), NameSpace('airbumpgame.utils.MathUtils')));