(function(createjs, components, Loader, MathUtils) {
    "use strict";
    //
    // CONSTRUCTEUR
    //
    var Flip = function(x, y, startRotation, endRotation, flipSide) {
        this.initialize();
        this._line = new createjs.Shape;
        this.addChild(this._line);
        var a = (startRotation + 180) * Math.PI / 180;
        var d = 1500;
        this._endX = d * Math.cos(a);
        this._endY = d * Math.sin(a);
        this._drawLine(3);
        this._car = new createjs.Bitmap(Loader.GetResult("img/voiture.png"));
        this._car.scaleX = this._car.scaleY = 0.5;
        this._shadow = new createjs.Bitmap(Loader.GetResult("img/voitureombre.png"));
        this.addChild(this._shadow);
        this.addChild(this._car);
        // var s = new createjs.Shape();
        // s.graphics.beginFill('#ff0000').drawCircle(0, 0, 20).endFill();
        // this.addChild(s);


        var bounds = this._car.getBounds();
        this._car.regY = bounds.height * .5;
        // this._car.regX = 45;
        // this._car.regY = 45;
        this._shadow.regY = bounds.height * .25;
        this._shadow.x = -10;
        this._shadow.y = 10;
        this._ratio = .245;
        this.cursor = "pointer";
        this.startRotation = startRotation;
        this.endRotation = endRotation;
        this.setRotation(startRotation);
        var _this = this;
        this.addEventListener("mousedown", function(e) {
            _this.clickHandler()
        });
        this._isEndFlip = true;
        this.x = x;
        this.y = y;
        this._compteur = 0;
        this._flipSide = flipSide;
        this.calculEquationDroite();
        this.onFlip = function() {}
    };
    var p = Flip.prototype = new createjs.Container;

    //
    //  VARIABLES PRIVEE
    //

    p._flipSide;
    p._line;
    p._car;
    p._shadow;
    p._endX;
    p._endY;
    p._compteur;
    p._animate;
    p._direction;
    p._height;
    p.angle;
    p.equationDroite;
    p.startRotation;
    p.endRotation;
    p.onFlip;

    //
    //  VARIABLES PUBLIC
    //


    //
    //  FUNCTIONS
    //
    p.calculEquationDroite = function() {
        var a = (this.angle - 90 * this._flipSide) * Math.PI / 180;
        var x0 = this.x;
        var y0 = this.y;
        var d = 72;
        var x0 = x0 + d * Math.cos(a);
        var y0 = y0 + d * Math.sin(a);
        var a = this.angle * Math.PI / 180;
        var d = 500;
        var x1 = x0 + d * Math.cos(a);
        var y1 = y0 + d * Math.sin(a);
        this.equationDroite = MathUtils.EquationDroite(x0, y0, x1, y1)
    };
    p.setRotation = function(angle) {
        this._car.rotation = angle;
        this._shadow.rotation = angle;
        this.angle = angle
    };
    p.clickHandler = function() {
        if (this._animate) return;
        //console.log(this._flipSide, parent.dataLayer);
        if (parent.dataLayer) {

            if (this._flipSide == 1) {
                parent.dataLayer.push({
                    "event": "uaevent",
                    "eventAction": "click",
                    "eventCategory": "C4 Cactus",
                    "eventLabel": "Left"
                });
            } else if (this._flipSide == -1) {
                parent.dataLayer.push({
                    "event": "uaevent",
                    "eventAction": "click",
                    "eventCategory": "C4 Cactus",
                    "eventLabel": "Right"
                });
            }
        }
        this._animate = true;
        this._direction = 1;
        this.dispatchEvent("onFlip")
    };
    p.render = function(time) {
        if (this._animate) {
            time = time / 40;
            this._compteur += .5 * time * this._direction;
            if (this._compteur > 1) this._compteur = 1;
            if (this._compteur < 0) this._compteur = 0;
            this.setRotation(this.startRotation + this._compteur * (this.endRotation - this.startRotation));
            if (this._direction == 1 && this._compteur >= 1) this._direction = -1;
            else if (this._direction == -1 && this._compteur <= 0) this._animate = false;
            this.calculEquationDroite();
        }
    };
    p._drawLine = function(size) {
        this._line.graphics.setStrokeStyle(size, "round").beginStroke("#bde5fa").moveTo(0, 0).lineTo(this._endX, this._endY).endStroke()
    };

    //
    //  NAMESPACE
    //    

    components.Flip = Flip
})(createjs, NameSpace("airbumpgame.userinterface.components"), NameSpace("airbumpgame.utils.Loader"), NameSpace("airbumpgame.utils.MathUtils"));
