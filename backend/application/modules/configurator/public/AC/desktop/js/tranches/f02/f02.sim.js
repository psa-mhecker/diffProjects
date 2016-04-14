(function Inside(THREE, $, inside, Cube, PointOfInterest) {
    "use strict";
    //
    // CONSTRUCTEUR
    //




    function isWebGL() {
        var canvas = document.createElement('canvas');
        var gl = null;
        try {
            // Essaye de récupérer le contexte standard. En cas d'échec, il teste l'appel experimental
            gl = canvas.getContext("webgl") || canvas.getContext("experimental-webgl");
        } catch (e) {}
        if (!gl) {
            gl = null;
        }
        return (gl ? true : false);
    }
    var Inside = function(canvas, zoneMouse) {
        this._canvas = canvas;
        var _this = this;

        this._initMouseDownX = 0;
        this._initMouseDownY = 0;
        this._lastRotationX = 0;
        this._lastRotationY = 0;
        this._isMouseDown = false;
        this._canvasWidth = 0;
        this._canvasHeight = 0;
        this._isOver = false;


        if (isWebGL()) {
            this._renderer = new THREE.WebGLRenderer({
                canvas: this._canvas
            });

        } else {
            this._renderer = new THREE.CanvasRenderer({
                canvas: this._canvas
            });
        }


        this._scene = new THREE.Scene();
        this._camera = new THREE.PerspectiveCamera(50, window.innerWidth / window.innerHeight, 1, 1000);

        $(window).resize(function(e) {
            _this._resize();
        })
        this.tabPointOfInterest = [];


        var wireMaterial = new THREE.MeshBasicMaterial({
            color: 0x00ff00,
            // visible: false,
            wireframe: true,
        });


        this._group = new THREE.Object3D();
        this._pointOfInterestGroup = new THREE.Object3D();

        // this._group.add(this._cube);
        this._scene.add(this._group);
        this._group.add(this._pointOfInterestGroup);

        this._raycaster = new THREE.Raycaster();
        this._mouse = new THREE.Vector2();


        this._resize();


        this._zoneMouse = zoneMouse;
//        var ttt = this._canvas;
        this._render();
    };
    var p = Inside.prototype;


    //
    //  VARIABLES PRIVEE
    //

    Inside._ISTOUCH = 'ontouchstart' in window;
    Inside._STARTEVENT = (Inside._ISTOUCH) ? 'touchstart' : 'mousedown';
    Inside._MOVEEVENT = (Inside._ISTOUCH) ? 'touchmove' : 'mousemove';
    Inside._ENDEVENT = (Inside._ISTOUCH) ? 'touchend' : 'mouseup';

    p._zoneMouse = null;
    p._canvas = null;
    p._renderer = null;
    p._scene = null;
    p._camera = null;

    p._initMouseDownX = 0;
    p._initMouseDownY = 0;
    p._lastRotationX = 0;
    p._lastRotationY = 0;
    p._isMouseDown = false;
    p._canvasWidth = 0;
    p._canvasHeight = 0;


    p._cube = null;
    p._group = null;
    p._pointOfInterestGroup = null;


    p._raycaster = null;
    p._mouse = null;

    p._intersects = null;
    p._isOver = false;

    p._isStartMove = false;

    p._d = 1 * Math.PI;
    p._constant = Math.PI / 2.2;


    //
    //  VARIABLES PUBLIC
    //
    //
    p.cubeSize = 100;
    p.tabPointOfInterest = [];
    p.onUpdatePointOfInterest = function() {};

    //
    //  FUNCTIONS
    //

    p.init = function(tabImages) {
        this._initMouseDownX = 0;
        this._initMouseDownY = 0;
        this._lastRotationX = 0;
        this._lastRotationY = 0;
        this._isMouseDown = false;
        this._isOver = false;

        var poi;

        for (var i = 0; i < this.tabPointOfInterest.length; i++) {
            poi = this.tabPointOfInterest[i];
            poi.data = null;
            poi.worldPosition = null;
            this._pointOfInterestGroup.remove(poi);
        }

        this.tabPointOfInterest = [];
        this._group.remove(this._cube);
        var _this = this;

        var cubeMaterial = [];
        THREE.ImageUtils.crossOrigin = '';
        for (var i = 0; i < tabImages.length; i++) {
            var textureLoader = new THREE.TextureLoader();
            var map; 
            if (i == tabImages.length - 1) {
                map = textureLoader.load(tabImages[i],  function() {
                    _this._render();
                });
            } else {
                map = textureLoader.load(tabImages[i]);
            }
            var meshBasicMaterial = new THREE.MeshBasicMaterial({
                map: map,
                overdraw: 0.6
            });
            cubeMaterial.push(meshBasicMaterial);
        }
        this._cube = new Cube(this.cubeSize, 15, cubeMaterial);
        this._group.add(this._cube);
        this._group.rotation.x = 0;
        this._group.rotation.y = 0;


    }

    p.start = function() {
        var _this = this;
        $(this._zoneMouse).on(Inside._STARTEVENT, function(e) {
            _this._onMouseDown(e);
        });

    };

    p.stop = function() {
        $(this._zoneMouse).unbind(Inside._MOVEEVENT);
        $(this._zoneMouse).unbind(Inside._STARTEVENT);
        $(this._zoneMouse).unbind(Inside._ENDEVENT);
    };

    p.addPointOfInterest = function(pointOfInterest) {
        this.tabPointOfInterest.push(pointOfInterest);
        this._pointOfInterestGroup.add(pointOfInterest);
        this.updatePointOfInterest();
    };

    p.removePointOfInterest = function(pointOfInterest) {
        this.tabPointOfInterest.splice(this.this.tabPointOfInterest.indexOf(pointOfInterest), 1);
        this._pointOfInterestGroup.remove(pointOfInterest);
        this.updatePointOfInterest();
    };

    p._onMouseDown = function(e) {
        this._isMouseDown = true;
        this._initMouseDownX = e.pageX;
        this._initMouseDownY = e.pageY;

        if (Inside._ISTOUCH) {
            this._initMouseDownX = e.originalEvent.changedTouches[0].pageX;
            this._initMouseDownY = e.originalEvent.changedTouches[0].pageY;
        }
        
        var _this = this;
        $('canvas').on(Inside._ENDEVENT, function(e) {
            _this._onMouseUp(e);
        })

        $('canvas').on(Inside._MOVEEVENT, function(e) {
            _this._onMouseMove(e);
        })
    };

    p._onMouseUp = function(e) {
        this._isMouseDown = false;
        this._lastRotationX = this._group.rotation.x;
        this._lastRotationY = this._group.rotation.y;
        $(this._zoneMouse).unbind('mousemove');
        $(this._zoneMouse).unbind('mouseup');
    };

    p._onMouseMove = function(e) {
        if (Inside._ISTOUCH && !this._isMouseDown) {
            this._isMouseDown = true;
            this._initMouseDownX = e.originalEvent.changedTouches[0].pageX;
            this._initMouseDownY = e.originalEvent.changedTouches[0].pageY;
        }

        if (this._isMouseDown) {
            e.preventDefault();
            var x = e.pageX;
            var y = e.pageY;
            if (Inside._ISTOUCH) {
                x = e.originalEvent.changedTouches[0].pageX;
                y = e.originalEvent.changedTouches[0].pageY;
            }
            
            var dx = (this._initMouseDownX - x) / this._canvasWidth;
            var dy = (this._initMouseDownY - y) / this._canvasHeight;
            
            //var d = this._d;
            this._group.rotation.x = dy * this._d + this._lastRotationX;
            this._group.rotation.y = dx * this._d + this._lastRotationY;
            if (this._group.rotation.x > this._constant) {
                this._group.rotation.x = this._constant
            }
            if (this._group.rotation.x < -this._constant) {
                this._group.rotation.x = -this._constant
            }
            
            
            
            this.updatePointOfInterest();
            this._render();
        }
    };

    p.updatePointOfInterest = function() {
        var poi;
        var pos;
        for (var i = 0; i < this.tabPointOfInterest.length; i++) {
            poi = this.tabPointOfInterest[i];
            pos = poi.position.clone();
            // console.log(pos);
            this._pointOfInterestGroup.localToWorld(pos);
            poi.worldPosition = pos.clone();
            pos.y *= -1;
            pos.project(this._camera);
            poi.canvasX = (pos.x + 1) * 0.5 * this._canvasWidth;
            poi.canvasY = (pos.y + 1) * 0.5 * this._canvasHeight;
        }
        this.onUpdatePointOfInterest();
        // $('.point').css('top', y + 'px');
        // $('.point').css('left', x + 'px');

    };

    // if (this._getIntersectObjects(e).length) {
    //     if (!this._isOver) {
    //         this._isOver = true;
    //         $(this._canvas).css('cursor', 'pointer');
    //         var o = this._intersects[0].object;
    //         var pos = o.position.clone();
    //         o.localToWorld(pos);
    //         pos.project(this._camera);
    //         var x = (pos.x + 1) * 0.5 * this._canvasWidth;
    //         var y = (pos.y + 1) * 0.5 * this._canvasHeight;
    //         this.onmouseover(e, x, y);
    //     }
    // } else {
    //     if (this._isOver) {
    //         this._isOver = false;
    //         $(this._canvas).css('cursor', 'default');
    //         this.onmouseout(e, this._intersects);
    //     }
    // }
    // };

    p._getIntersectObjects = function(e) {
        var x = e.pageX - $(e.target).offset().left;
        var y = e.pageY - $(e.target).offset().top;
        this._mouse.x = (x / this._canvasWidth) * 2 - 1;
        this._mouse.y = -((y / this._canvasHeight) * 2 - 1);
        this._raycaster.setFromCamera(this._mouse, this._camera);
        this._intersects = this._raycaster.intersectObjects(this._pointOfInterestGroup.children, true);
        return this._intersects;
    };

    p.resize = function() {
        var _this = this;
        setTimeout(function() {
                _this._resize();
            }, 500);

    };

    p._resize = function() {
/*        $(this._canvas).css('width', '');
        $(this._canvas).css('height', '');
        $(this._canvas).removeAttr('style');*/
        this._canvasWidth = $(this._canvas).width();
        this._canvasHeight = $(this._canvas).height();
        this._camera.aspect = this._canvasWidth / this._canvasHeight;
        this._camera.updateProjectionMatrix();
        this._renderer.setSize(this._canvasWidth, this._canvasHeight);
        this.updatePointOfInterest();
        this._render();
    };

    p._render = function() {
        this._renderer.render(this._scene, this._camera);
    };

    //
    //  NAMESPACE
    //    
    inside.Inside = Inside;
}(window.THREE, window.jQuery, NameSpace('inside'), NameSpace('inside.object3D.Cube'), NameSpace('inside.object3D.PointOfInterest')));
