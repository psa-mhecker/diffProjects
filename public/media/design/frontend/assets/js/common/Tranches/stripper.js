(function ($) {
    $(document).ready(function () {
        $(document).on('click', '.strip .closer', function (e) {
            var $this;
            e.preventDefault();
            $this = $(this);
            $('#strip1').toggleClass('js-showed');
            $('#strip2').toggleClass('js-showed');
        });
        $(document).on('change', '.strip input', function (e) {
            var $this;
            e.preventDefault();
            $this = $(this);
            $('#strip1').toggleClass('js-showed');
            $('#strip2').toggleClass('js-showed');
        });
    });
    return $(window).load(function () {
        $('#strip1').toggleClass('js-showed');
    });
}(jQuery));