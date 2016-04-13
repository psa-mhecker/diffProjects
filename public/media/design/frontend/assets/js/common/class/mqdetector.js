var mqDetector;
mqDetector = function () {
    function mqDetector(myBreackPoint) {
        this.breakPoint = myBreackPoint ? myBreackPoint : 1024;
        this.ieOld = $('.ie-old')[0];
    }
    mqDetector.prototype.checkMe = function () {
        var matchMediaAvailable;
        matchMediaAvailable = false;
        if (navigator.userAgent.indexOf('Chrome') !== -1 || navigator.userAgent.indexOf('Firefox') !== -1) {
            matchMediaAvailable = true;
        }
        if (matchMediaAvailable) {
            if (window.matchMedia('(max-width:' + this.breakPoint + 'px)').matches || $(window).width() <= this.breakPoint - 24) {
                return true;
            } else {
                return false;
            }
        } else {
            if ($(window).width() <= this.breakPoint && !this.ieOld) {
                return true;
            } else {
                return false;
            }
        }
    };
    return mqDetector;
}();
window.mqDetector = mqDetector;