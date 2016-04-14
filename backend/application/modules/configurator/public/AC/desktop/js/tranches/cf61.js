ISO.moduleCreate('sliceCF61', function($el, param) {
    var cf61 = (function() {
        var $lameHeightDivElem,
            $slideListElem,
            $selectElem,
            $extendListElem,
            $loadMoreResultsElem,
            $extendAllElem,
            $lameContainerElem,
            moreResultsBasePath,
            moreResultsNextPage;

        function init() {
            // init your module
            // attach events
            $lameHeightDivElem = $(".js-lame-extend");
            $selectElem = $('select');
            $slideListElem = $('.slide-list');
            $extendListElem = $('.js-extend');
            $extendAllElem = $('.js-extend-all');
            $loadMoreResultsElem = $('.js-load-more');
            $lameContainerElem = $('.js-lame-container');
            $selectElem.selectBox();

            moreResultsBasePath = "tr-default-ajaxresults{{PAGE}}.html";
            moreResultsNextPage = 1;

            events();
        };

        function events() {
            $slideListElem.on("click", slideListElemClick);
            $extendListElem.on("click", extendList);
            $extendAllElem.on("click", extendAll);
            $loadMoreResultsElem.on("click", loadMoreResultsClick);
            $lameHeightDivElem.each(lameHeightDivElemEach);
        };


        function slideListElemClick(e) {
            var options = {
                duration: "normal",
                progress: lameSideListSlideToggleCompleted
            };

            $(this).find("ul").slideToggle(options);
            $(this).toggleClass("open");
        };

        function lameHeightDivElemEach() {
            recalculateLameHeight($(this));
        };

        function lameSideListSlideToggleCompleted() {
            recalculateLameHeight($(this).parents('.js-lame-extend'));
        };

        function recalculateLameHeight(lane) {
            var asideHeight = lane.find("aside").height();
            var boxHeight = lane.find(".js-lane-select").height();
            var iconsHeight = lane.find(".js-conf-icons").height();
            var lameHeight = asideHeight + 53;
            var confHeight = lameHeight - iconsHeight;
            lane.css("height", lameHeight);
            lane.find('.configuration-list').css("height", confHeight);
        };

        function extendList() {
            $(this).parent().toggleClass('active');
            $(this).next().slideToggle();
        };

        function extendAll() {
            $(this).parents('.row').next().find('.js-extend').each(extendList);
        };

        function loadMoreResultsClick() {
            var url = moreResultsBasePath.replace("{{PAGE}}", moreResultsNextPage)
            $.ajax({
                url: url,
                dataType: 'html'
            }).done(processMoreResults);
        };

        function processMoreResults(response) {
            var searchResults = JSON.parse(extractResponseJson(response));

            for (var i = 0; i < searchResults.resultCount; i++) {
                $lameContainerElem.append(searchResults.resultList[i].html);
            }

            moreResultsNextPage++;
            if (searchResults.resultCount < 7) {
                $loadMoreResultsElem.hide();
            }
        };

        /*

        This function extracts Json object from HTML response.

        */

        function extractResponseJson(html) {
            return $(html).find('#Results').html();
        }

        return {
            init: init
        };
    })();

    cf61.init();
});
