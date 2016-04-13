;
(function(components) {
	"use strict";
	//
	// CONSTRUCTEUR
	//
	var Forground = function() {
		this.initialize();
		this._leftBump = new createjs.Shape();
		this.addChild(this._leftBump)
		this._rightBump = new createjs.Shape();
		this.addChild(this._rightBump);
		this._leftBump.visible = false;
		this._rightBump.visible = false;
	};

	var p = Forground.prototype = new createjs.Container();
	//
	//  VARIABLES PRIVEE
	//
	p._leftBump;
	p._rightBump;
	p._startTime;
	p._isBump = true;

	//
	//  VARIABLES PUBLIC
	//

	//
	//  FUNCTIONS
	//
	p.leftBump = function() {
		this._startTime = new Date().getTime();
		this._isBump = true;
		this._rightBump.visible = false;
		this._leftBump.visible = true;
	};

	p.rightBump = function() {
		this._startTime = new Date().getTime();
		this._isBump = true;
		this._rightBump.visible = true;
		this._leftBump.visible = false;
	};

	p.render = function(time) {
		if (this._isBump) {
			if (new Date().getTime() - this._startTime > 150) {
				this._isBump = true;
				this._leftBump.visible = false;
				this._rightBump.visible = false;
			}
		}
	};


	p.resize = function(width, height) {
		var w = width * 0.015;
		if (w < 10) w = 10;
		this._leftBump.graphics.clear();
		this._leftBump.graphics.beginFill('#ffffff').drawEllipse(-w, 0, w * 2, height).endFill();
		this._rightBump.graphics.clear();
		this._rightBump.graphics.beginFill('#ffffff').drawEllipse(width - w, 0, w * 2, height).endFill();
	};

	//
	//  NAMESPACE
	//    
	components.Forground = Forground;
}(NameSpace('airbumpgame.userinterface.components')));