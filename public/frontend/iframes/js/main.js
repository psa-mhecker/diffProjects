(function(AirBumpGame) {
    "use strict";

    window.addEventListener('load', function() {
        init();
    });

    function init() {
        var canvas = document.getElementById('canvas');
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
        var airBumpGame = new AirBumpGame();
        airBumpGame.init(canvas);
    };
}(NameSpace('airbumpgame.AirBumpGame')));
