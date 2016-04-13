;
(function(utils) {
    "use strict";
    //
    // CONSTRUCTEUR
    //
    var MathUtils = {};

    //
    //  VARIABLES PRIVEE
    //

    //
    //  VARIABLES PUBLIC
    //

    //
    //  FUNCTIONS
    //
    MathUtils.EquationDroite = function(x0, y0, x1, y1) {
        var a = (y1 - y0) / (x1 - x0);
        var b = y1 - (a * x1);
        return {
            a: a,
            b: b,
            x0: x0,
            y0: y0,
            x1: x1,
            y1: y1
        }
    };

    MathUtils.CalculIntersectionDroite = function(equationDroite1, equationDroite2) {
        var a0 = equationDroite1.a;
        var b0 = equationDroite1.b;
        var a1 = equationDroite2.a;
        var b1 = equationDroite2.b;
        var x = (b1 - b0) / (a0 - a1);
        var y = a0 * x + b0;
        return {
            x: x,
            y: y
        };
    };

    MathUtils.DistanceBetweenPoints = function(point1, point2) {
        var p0 = point1;
        var p1 = point2;
        var dx = p1.x - p0.x;
        var dy = p1.y - p0.y;
        var d = Math.sqrt(dx * dx + dy * dy);
        return d;
    };

    MathUtils.DistanceVector = function(vector) {
        return Math.sqrt(vector.x * vector.x + vector.y * vector.y);
    };

    //
    //  NAMESPACE
    //    
    utils.MathUtils = MathUtils;
}(NameSpace('airbumpgame.utils')));