(function ($) {
    $(document).ready(function () {
        window.initiateToggle = function (mySliceSelector) {
            $(document).on('click', mySliceSelector + ' ' + '.js-toggle-btn', function (e) {

                var $this;
                var $toggleContext;
                $this = $(this);
                if($this.parent().attr('href') == undefined || $this.parent().attr('href') == ""){
                    e.preventDefault();
                    $toggleContext = $this.closest('.js-toggle-context');
                    $toggleContext.toggleClass('js-toggle-opened');
                }
            });
            $(document).on('click', mySliceSelector + ' ' + '.js-toggle-all-open-btn', function (e) {
                var $this;
                var $toggleAllContext;
                e.preventDefault();
                $this = $(this);
                $toggleAllContext = $this.closest('.js-toggle-all-context');
                $toggleAllContext.find('.js-toggle-context').addClass('js-toggle-opened');
            });
            $(document).on('click', mySliceSelector + ' ' + '.js-toggle-all-close-btn', function (e) {
                var $this;
                var $toggleAllContext;
                e.preventDefault();
                $this = $(this);
                $toggleAllContext = $this.closest('.js-toggle-all-context');
                $toggleAllContext.find('.js-toggle-context').removeClass('js-toggle-opened');
            });
        };
    });
}(jQuery));