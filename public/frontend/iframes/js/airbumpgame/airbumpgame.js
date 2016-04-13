(function(createjs, airbumpgame, Loader, Flip, Bumper, Hole, AirBump, Bille, MathUtils, Physics, StartButton, Curseur) {
    "use strict";

    var AirBumpGame = function() {}

    var p = AirBumpGame.prototype

    p._physics;

    p.canvas;

    p.init = function(canvas) {
        this.canvas = canvas;
        var _this = this;
        Loader.addEventListener('complete', function() {
            _this._init();
        });
        Loader.Load('img/voiture.png', 'img/voitureombre.png', 'img/bumper1.png', 'img/bumper2.png', 'img/airbump1.png', 'img/airbump2.png', 'img/bille.png', 'img/start.png', 'img/flecheG.jpg', 'img/curseur.png', 'img/rond.png');
    };

    p._init = function() {
        var _this = this;

        var canvas = this.canvas;

        var context = canvas.getContext("2d");
        context.webkitImageSmoothingEnabled = context.mozImageSmoothingEnabled = true;
        var stage = new createjs.Stage(canvas);
        createjs.Touch.enable(stage);

        stage.enableMouseOver(100);


        var refWidth = 690;
        var refHeight = 630;

        var container = new createjs.Container();
        stage.addChild(container);
        var airBump = new AirBump(0, -165);
        var leftFlip = new Flip(-240, 76, 45, 0, 1);
        var rightFlip = new Flip(240, 76, 135, 180, -1);
        var centerBumper = new Bumper(0, -96);
        centerBumper.addEventListener('finishGame', function() {
            // leftBumper.hide();
            // rightBumper.hide();
            // centerBumper.hide();
            // airBump.show();
            // startButton.show();
        });
        var leftBumper = new Bumper(-257, -247)
        var rightBumper = new Bumper(267, -247)
        var hole = new Hole(0, refHeight * 0.5);
        var bille = new Bille(-90, 0);
        bille.rightFlip = rightFlip;

        var mathShape = new createjs.Shape();
        var startButton = new StartButton();
        startButton.y = 18;
        startButton.addEventListener('startGame', function() {
            if (parent.dataLayer) {
                parent.dataLayer.push({
                    "event": "uaevent",
                    "eventAction": "click",
                    "eventCategory": "C4 Cactus",
                    "eventLabel": "Start"
                });
            }
            start();

        })

        startButton.addEventListener('onFinish', function() {
            stop();
        })

        // var fleche = new createjs.Bitmap(Loader.GetResult('img/fleche.jpg'));
        var flecheG = new createjs.Container();
        flecheG.addChild(new createjs.Bitmap(Loader.GetResult('img/flecheG.jpg')));
        flecheG.cursor = 'pointer';
        var flecheD = new createjs.Container();
        flecheD.addChild(new createjs.Bitmap(Loader.GetResult('img/flecheG.jpg')));
        flecheD.cursor = 'pointer';
        flecheD.scaleX = -1;
        var curseur = new Curseur();
        curseur.flip = rightFlip;
        curseur.x = 150;
        curseur.y = 130;

        container.addChild(airBump);
        container.addChild(leftFlip);
        container.addChild(rightFlip);
        container.addChild(centerBumper);
        container.addChild(leftBumper);
        container.addChild(rightBumper);
        container.addChild(hole);
        container.addChild(bille);
        container.addChild(startButton);
        var isDesktop;
        if (navigator.userAgent.match(/Android/i) || navigator.userAgent.match(/webOS/i) || navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPad/i) || navigator.userAgent.match(/iPod/i) || navigator.userAgent.match(/BlackBerry/i) || navigator.userAgent.match(/Windows Phone/i)) {
            isDesktop = false;
            container.addChild(curseur);
        } else {
            isDesktop = true;
            container.addChild(flecheG);
            container.addChild(flecheD);
        }

        this._physics = new Physics();
        this._physics.leftFlip = leftFlip;
        this._physics.rightFlip = rightFlip;
        this._physics.centerBumper = centerBumper;
        this._physics.leftBumper = leftBumper;
        this._physics.rightBumper = rightBumper;
        this._physics.airBump = airBump;
        this._physics.bille = bille;
        this._physics.canvas = canvas;
        this._physics.hole = hole;
        this._physics.startButton = startButton;
        this._physics.init();

        var lastTime = new Date().getTime();
        var isStart;

        var intervalIDRender;

        function start() {
            if (!isDesktop) curseur.stop();
            lastTime = new Date().getTime();
            document.onkeydown = function(e) {
                if (e.keyCode == 37) {
                    leftFlip.clickHandler();
                }
                if (e.keyCode == 39) {
                    rightFlip.clickHandler();
                }
            }

            flecheG.removeAllEventListeners();
            flecheG.addEventListener('click', function(event) {
                leftFlip.clickHandler();
            });

            flecheD.removeAllEventListeners();
            flecheD.addEventListener('click', function(event) {
                rightFlip.clickHandler();
            });

            createjs.Ticker.addEventListener('tick', stage);
            createjs.Ticker.setFPS(60);
            clearInterval(intervalIDRender);
            render();
            intervalIDRender = setInterval(function() {
                render();
            }, 40);
            isStart = true;
            _this._physics.start();
        };

        // createjs.Ticker.addEventListener('tick', stage);
        // createjs.Ticker.setFPS(60);

        if (!isDesktop) curseur.start();

        function stop() {
            if (!isDesktop) curseur.start();
            document.onkeydown = null;
            clearInterval(intervalIDRender);
            createjs.Ticker.removeEventListener('tick', stage);
        };

        var gr = mathShape.graphics;
        var stats = new Stats();
        // document.body.appendChild(stats.domElement);

        function render() {
            stats.begin();
            var d = new Date().getTime();
            var time = d - lastTime;
            lastTime = d;
            leftFlip.render(time);
            rightFlip.render(time);
            leftBumper.render(time);
            rightBumper.render(time);
            centerBumper.render(time);
            airBump.render(time);
            _this._physics.render(time);
            stats.end();
        };


        var scale;

        function resizeCanvas() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
            var s = canvas.width / refWidth;
            if (refHeight * s > canvas.height) {
                s = canvas.height / refHeight;
            }
            scale = s;
            bille.scale = scale;
            _this._physics.scale = scale;
            container.scaleX = container.scaleY = s;
            container.x = canvas.width * 0.5;
            container.y = canvas.height * 0.5;
            if (!isStart) {
                bille.y = -canvas.height * 0.5 / s - bille.getBounds().height;
            }
            flecheG.x = canvas.width / scale * 0.5 - 150;
            flecheG.y = canvas.height / scale * 0.5 - 60;
            flecheD.x = flecheG.x + 90;
            flecheD.y = flecheG.y;
            stage.update();
        };
        var intervalID;
        window.onresize = function() {
            clearTimeout(intervalID);
            intervalID = setTimeout(function() {
                resizeCanvas();
            }, 10);
        }
        window.onresize();
        stage.update();
    };
    airbumpgame.AirBumpGame = AirBumpGame;
}(createjs, NameSpace('airbumpgame'), NameSpace('airbumpgame.utils.Loader'), NameSpace('airbumpgame.userinterface.components.Flip'), NameSpace('airbumpgame.userinterface.components.Bumper'), NameSpace('airbumpgame.userinterface.components.Hole'), NameSpace('airbumpgame.userinterface.components.AirBump'), NameSpace('airbumpgame.userinterface.components.Bille'), NameSpace('airbumpgame.utils.MathUtils'), NameSpace('airbumpgame.userinterface.components.Physics'), NameSpace('airbumpgame.userinterface.components.StartButton'), NameSpace('airbumpgame.userinterface.components.Curseur')));
