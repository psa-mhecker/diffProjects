;
(function(createjs, components, Loader) {
    "use strict";
    //
    // CONSTRUCTEUR
    //
    var Curseur = function() {
        this.initialize();
        this._curseur = new createjs.Bitmap(Loader.GetResult('img/curseur.png'));
        this._curseur.regX = -5;
        this._curseur.regY = -15;
        var rond = new createjs.Bitmap(Loader.GetResult('img/rond.png'));
        this.addChild(rond);
        this.addChild(this._curseur);
        this.visible = false;
    };
    var p = Curseur.prototype = new createjs.Container();
    //
    //  VARIABLES PRIVEE
    //
    p._intervalIDRender;
    p._lastTime;
    p._step;
    p._time;
    p._curseur;

    //
    //  VARIABLES PUBLIC
    //
    p.flip;

    //
    //  FUNCTIONS
    //
    p._render = function() {
        var d = new Date().getTime();
        var time = d - this._lastTime;
        this._lastTime = d;
        time = time / 40;
        if (this._step == 0) {
            this._curseur.x = 100;
            this._curseur.y = 100;
            this._step = 1;
        } else if (this._step == 1) {
            this._curseur.x += (0 - this._curseur.x) / (10 * time);
            this._curseur.y += (0 - this._curseur.y) / (10 * time);
            var dx = this._curseur.x - 0;
            var dy = this._curseur.y - 0;
            var dd = Math.sqrt(dx * dx + dy * dy);
            if (dd < 1) {
                this._step = 2;
            }
        } else if (this._step == 2) {
            this.flip.clickHandler();
            this._curseur.scaleX = this._curseur.scaleY = 0.8;
            this._step = 3;
            this._time = d;
        } else if (this._step == 3) {
            if (d - this._time > 500) {
                this.scaleX = this.scaleY = 1;
                this._step = 4;
                this._time = d;
            }
        }
        if (this._step == 4) {
            this._curseur.x += (100 - this._curseur.x) / (10 * time);
            this._curseur.y += (100 - this._curseur.y) / (10 * time);
            var dx = this._curseur.x - 100;
            var dy = this._curseur.y - 100;
            var dd = Math.sqrt(dx * dx + dy * dy);
            if (dd < 1) {
                this._step = 0;
            }
        }

        this.flip.render(time * 20);
        this.getStage().update();
    }

    p.start = function() {
        this.visible = true;
        this._step = 0;
        this._lastTime = new Date().getTime();
        var _this = this;
        this._intervalIDRender = setInterval(function() {
            _this._render();
        }, 40);

    }

    p.stop = function() {
        clearInterval(this._intervalIDRender);
        this.visible = false;

    }

    //
    //  NAMESPACE
    //    
    components.Curseur = Curseur;
}(createjs, NameSpace("airbumpgame.userinterface.components"), NameSpace("airbumpgame.utils.Loader")));
