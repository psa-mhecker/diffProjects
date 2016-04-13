;
(function(createjs, components, MathUtils) {
    "use strict";
    //
    // CONSTRUCTEUR
    //

    var Physics = function() {}
    for (var m in createjs.EventDispatcher.prototype) {
        Physics[m] = createjs.EventDispatcher.prototype[m];
    }
    var p = Physics.prototype;

    //
    //  VARIABLES PRIVEE
    //
    p._vitesse;
    p._vitesseX;
    p._vitesseY;
    p._scenario;
    p._isStart;
    p._lastBumper;
    p._startXToHole;
    p._angle;
    p._isFlipLeftFlip;
    p._isFlipRightFlip;
    p._intervalIdEnd;
    p._side;
    p._compteurFlip;


    //
    //  VARIABLES PUBLIC
    //

    p.bille;
    p.leftFlip;
    p.rightFlip;
    p.centerBumper;
    p.leftBumper;
    p.rightBumper;
    p.airBump;
    p.target;
    p.scale;
    p.canvas;
    p.hole;
    p.startButton;
    p.centerToHole;
    //
    //  FUNCTIONS
    //

    p.init = function() {
        var _this = this;
        this.leftFlip.addEventListener('onFlip', function() {
            if (_this._scenario == 'hole') {
                _this._isFlipLeftFlip = true;
            }
        })
        this.rightFlip.addEventListener('onFlip', function() {
            if (_this._scenario == 'hole') {
                _this._isFlipRightFlip = true;
            }
        })

    };

    p.start = function() {
        this._compteurFlip = 0;
        clearTimeout(this._intervalIdEnd);
        this._isStart = true;
        this._side = 'left';
        // this._side = 'right';
        this.bille.visible = true;
        this._restart();
        this.airBump.hide();
        this.leftBumper.show();
        this.centerBumper.show();
        this.rightBumper.show();
    };

    p._restart = function() {
        this.bille.scaleX = this.bille.scaleY = 1;
        this.centerToHole = null;
        this.bille.angle = 90;
        this._vitesse = 35;
        if (this._side == 'left') {
            this.bille.x = -110;
            this._scenario = 0;
        } else {
            this.bille.x = 110;
            this._scenario = 5;
        }
        this.bille.y = -this.canvas.height * 0.5 / this.scale - 1450;
        this.target = {
            x: this.bille.x,
            y: this.bille.y + 200
        };
        this._isFlipLeftFlip = false;
        this._isFlipRightFlip = false;
    };

    p.render = function(time) {
        if (!this._isStart) return;
        time = time / 40;
        if (this._scenario == 0) {
            this.bille.calculEquationDroite();
            var eq1 = this.leftFlip.equationDroite;
            var eq2 = this.bille.equationDroite;
            this.target = MathUtils.CalculIntersectionDroite(eq1, eq2);
        } else if (this._scenario == 5) {
            this.bille.calculEquationDroite();
            var eq1 = this.rightFlip.equationDroite;
            var eq2 = this.bille.equationDroite;
            this.target = MathUtils.CalculIntersectionDroite(eq1, eq2);
        }
        if (this._scenario == 'endHole') {
            this.bille.scaleX = this.bille.scaleY = this.bille.scaleX - time * 0.2;
            if (this.bille.scaleX <= 0) {
                this.bille.scaleX = this.bille.scaleY = 0;
                this._restart();
            }
        } else if (this._scenario == 'hole') {
            var rx = Math.abs(this._startXToHole - this.target.x) * 0.5;
            var a = this._angle * Math.PI / 180;
            var x = this.centerToHole.x + rx * Math.cos(a);
            var eq = this.bille.equationDroite;

            var y = eq.a * x + eq.b;
            y = y + rx * 3 * Math.sin(a);
            this.bille.x = x;
            this.bille.y = y;
            if (this._side == 'left') this._angle += this._vitesse * 0.4 * time;
            else this._angle -= this._vitesse * 0.4 * time;
            if (this._side == 'left' && this.bille.y < 200 && this._isFlipLeftFlip) {
                this.airBump.bump();
                this._isFlipLeftFlip = false;
                this._lastBumper = this.rightBumper;
                this.target = {
                    x: this.rightBumper.x,
                    y: this.rightBumper.y + 75
                }
                this.deplaceBille(2);
                this._scenario = 1;
            }
            if (this._side == 'right' && this.bille.y < 200 && this._isFlipRightFlip) {
                this.airBump.bump();
                this._isFlipRightFlip = false;
                this._lastBumper = this.leftBumper;
                this.target = {
                    x: this.leftBumper.x,
                    y: this.leftBumper.y + 75
                }
                this.deplaceBille(2);
                this._scenario = 6;
            }
        } else {
            this.deplaceBille(time);
        }
        var p0 = {
            x: this.bille.x,
            y: this.bille.y
        };
        var p1 = {
            x: this.target.x,
            y: this.target.y
        }
        var d0 = MathUtils.DistanceVector({
            x: this._vitesseX,
            y: this._vitesseY
        });
        var d1 = MathUtils.DistanceBetweenPoints(p0, p1);
        if (d1 < d0) {
            this.bille.x = this.target.x;
            this.bille.y = this.target.y;
            if (this._scenario == 0) {
                var a = this.leftFlip.startRotation + (this.leftFlip.endRotation - this.leftFlip.startRotation) * 0.5;
                if (this.leftFlip.angle == this.leftFlip.startRotation) {
                    this.target = {
                        x: this.hole.x,
                        y: this.hole.y
                    }
                    this._startXToHole = this.bille.x;
                    this.centerToHole = {
                        x: (this.target.x + this._startXToHole) * 0.5,
                        y: this.bille.y
                    };
                    this._angle = 180;
                    var dx = this.target.x - this.bille.x;
                    var dy = this.target.y - this.bille.y;
                    var a = Math.atan2(dy, dx);
                    this.bille.angle = a * 180 / Math.PI;
                    this.bille.calculEquationDroite();
                    this._scenario = 'hole';
                } else if (this.leftFlip.angle < a) {
                    this._lastBumper = this.centerBumper;
                    this.airBump.bump();
                    this.target = {
                        x: this.centerBumper.x - 70,
                        y: this.centerBumper.y + 10
                    }
                    this.deplaceBille(2);
                    this._scenario = 4;
                } else {
                    this._lastBumper = this.rightBumper;
                    this.airBump.bump();
                    this.target = {
                        x: this.rightBumper.x,
                        y: this.rightBumper.y + 75
                    }
                    this._scenario = 1;
                }
                this._vitesse *= 1.1;
            } else if (this._scenario == 1) {
                this._lastBumper.bump();
                this.airBump.bump();
                this.target = {
                    x: this.canvas.width * 0.5 / this.scale - 25,
                    y: this.rightBumper.y + 200
                }
                this._vitesse *= 1.1;
                this._scenario = 2;
            } else if (this._scenario == 2) {
                this.airBump.bump();
                this._lastBumper = this.centerBumper;
                this.target = {
                    x: this.centerBumper.x + 50,
                    y: this.centerBumper.y - 50
                }
                this._vitesse *= 1.1;
                this._scenario = 3;
            } else if (this._scenario == 3) {
                this._lastBumper.bump();
                this.airBump.bump();
                this.target = {
                    x: 0,
                    y: -this.canvas.height * 0.5 / this.scale - 150
                }
                this._scenario = 'finish';
            } else if (this._scenario == 4) {
                this._lastBumper.bump();
                this.airBump.bump();
                this._lastBumper = this.leftBumper;
                this.target = {
                    x: this.leftBumper.x + 75,
                    y: this.leftBumper.y
                }
                this._scenario = 1;
            } else if (this._scenario == 5) {
                this._compteurFlip++;
                var a = this.rightFlip.startRotation + (this.rightFlip.endRotation - this.rightFlip.startRotation) * 0.5;
                if (this.rightFlip.angle == this.rightFlip.startRotation) {
                    this.target = {
                        x: this.hole.x,
                        y: this.hole.y
                    }
                    this._startXToHole = this.bille.x;
                    this.centerToHole = {
                        x: (this.target.x + this._startXToHole) * 0.5,
                        y: this.bille.y
                    };
                    this._angle = 0;
                    var dx = this.target.x - this.bille.x;
                    var dy = this.target.y - this.bille.y;
                    var a = Math.atan2(dy, dx);
                    this.bille.angle = a * 180 / Math.PI;
                    this.bille.calculEquationDroite();
                    this._scenario = 'hole';
                } else if (this.rightFlip.angle > a) {
                    this._lastBumper = this.centerBumper;
                    this.airBump.bump();
                    this.target = {
                        x: this.centerBumper.x + 70,
                        y: this.centerBumper.y + 10
                    }
                    this.deplaceBille(2);
                    this._scenario = 9;
                } else {
                    this._lastBumper = this.leftBumper;
                    this.airBump.bump();
                    this.target = {
                        x: this.leftBumper.x,
                        y: this.leftBumper.y + 75
                    }
                    this.deplaceBille(2);
                    this._scenario = 6;
                }

            } else if (this._scenario == 6) {
                // document.onkeydown = null;
                this._lastBumper.bump();
                this.airBump.bump();
                this.target = {
                    x: -this.canvas.width * 0.5 / this.scale + 25,
                    y: this.leftBumper.y + 200
                }
                this._vitesse *= 1.1;
                this._scenario = 7;
            } else if (this._scenario == 7) {
                this.airBump.bump();
                this._lastBumper = this.centerBumper;
                this.target = {
                    x: this.centerBumper.x - 50,
                    y: this.centerBumper.y - 50
                }
                this._vitesse *= 1.1;
                this._scenario = 8;
            } else if (this._scenario == 8) {
                this._lastBumper.bump();
                this.airBump.bump();
                this.target = {
                    x: 0,
                    y: -this.canvas.height * 0.5 / this.scale - 150
                }
                this._scenario = 'finish';
            } else if (this._scenario == 9) {
                this._lastBumper.bump();
                this.airBump.bump();
                this._lastBumper = this.rightBumper;
                this.target = {
                    x: this.rightBumper.x - 75,
                    y: this.rightBumper.y
                }
                this._scenario = 6;
            } else if (this._scenario == 'hole') {
                this._scenario = 'endHole';
            } else if (this._scenario == 'finish') {
                if (this._side == 'left') {
                    this._side = 'right';
                    this._restart();
                } else {
                    // this._side = 'left';
                    // this._restart();
                    this.bille.visible = false;
                    this._isStart = false;
                    this.airBump.show();
                    this.leftBumper.hide();
                    this.rightBumper.hide();
                    this.centerBumper.hide();
                    clearTimeout(this._intervalIdEnd);
                    var _this = this;
                    this._intervalIdEnd = setTimeout(function() {
                        _this._end()
                    }, 2000);
                }
            }
        }
    };


    p._end = function() {
        this.airBump.hide();
        this.leftBumper.show();
        this.rightBumper.show();
        this.centerBumper.show();
        this.startButton.show();
    };

    p.deplaceBille = function(time) {
        this.bille.calculEquationDroite();
        var dx = this.target.x - this.bille.x;
        var dy = this.target.y - this.bille.y;
        var a = Math.atan2(dy, dx);
        this.bille.angle = a * 180 / Math.PI;
        this._vitesseX = this._vitesse * Math.cos(a);
        this._vitesseY = this._vitesse * Math.sin(a);
        this.bille.x += this._vitesseX * time;
        this.bille.y += this._vitesseY * time;
    };
    //
    //  NAMESPACE
    //    
    components.Physics = Physics;
}(createjs, NameSpace('airbumpgame.userinterface.components'), NameSpace('airbumpgame.utils.MathUtils')));
