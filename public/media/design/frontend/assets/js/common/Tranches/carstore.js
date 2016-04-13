(function ($) {
    $(document).ready(function () {
        var $tabs;
        var changeTab;
        $tabs = $('.CarTabContainer li');
        changeTab = function (index) {
            $tabs.removeClass('wf_active');
            return $($tabs[index]).addClass('wf_active');
        };
        return $tabs.find('a').on('click', function (e) {
            e.preventDefault();
            return changeTab($tabs.index($(this).parent()));
        });
    });
}(jQuery));