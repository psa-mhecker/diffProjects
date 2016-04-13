var touchDetect;
var bind = function (fn, me) {
    return function () {
        return fn.apply(me, arguments);
    };
};
var indexOf = [].indexOf || function (item) {
    for (var i = 0, l = this.length; i < l; i += 1) {
        if (i in this && this[i] === item) {
            return i;
        }
    }
    return -1;
};
touchDetect = function () {
    function touchDetect() {
        this.checkMe = bind(this.checkMe, this);
        this.touchState = indexOf.call(document.documentElement, 'ontouchstart') >= 0 || this.is_windowstouch_device() ? true : false;
    }
    touchDetect.prototype.is_windowstouch_device = function () {
        return this.temp = 'ontouchstart' in window || navigator.msMaxTouchPoints ? true : false;
    };
    touchDetect.prototype.checkMe = function () {
        return this.touchState;
    };
    return touchDetect;
}();
window.touchDetect = touchDetect;