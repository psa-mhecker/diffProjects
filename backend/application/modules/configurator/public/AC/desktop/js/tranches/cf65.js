ISO.moduleCreate('sliceCF65', function($el, param) {
    var cf65 = (function() {
        var regexEmail,
            useKeypress,
            $emailElem,
            $confirmationElem,
            $nameElement,
            $nextButtonElement;

        function init() {
            // init your module
            // attach events
            useKeypress = false;
            regexEmail = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
            $emailElem = $('#email');
            $confirmationElem = $('#filtres2');
            $nameElement = $('#conf');
            $nextButtonElement = $('.js-cf65-btn-next');


            events();
            checkName();
        };

        function events() {
            $emailElem.on("keyup", checkMailElemKeyup);

            $nameElement.on("keyup", checkName);

            $emailElem.on("focusout", checkMailElemFocusout);

            $nextButtonElement.on("click", nextButtonElementClick);

            $confirmationElem.on("click", checkAgreeClick);
        };

        function checkMailElemKeyup(e) {
            if (useKeypress) {
                checkMail();
            }
        };

        function checkMailElemFocusout(e) {
            checkMail();
            useKeypress = true;
        };

        function checkAgreeClick(e) {
            checkAgree();
        };

        function nextButtonElementClick(e) {
            var chkMail = checkMail();
            var chkName = checkName();
            var chkAgree = checkAgree();
            if (!(chkMail && chkName && chkAgree)) {
                e.stopImmediatePropagation();
            }
        };

        //Email validation  
        function checkMail() {
            var $retVal = false;
            if (regexEmail.test($emailElem.val())) {
                $emailElem.removeClass('error');
                $emailElem.siblings('.check').show();
                $emailElem.siblings('.cross').hide();
                $emailElem.siblings('.error-msg').hide();
                $retVal = true;
            } else {
                $emailElem.siblings('.check').hide();
                $emailElem.siblings('.cross').show();
                $emailElem.siblings('.error-msg').show();
                $emailElem.addClass('error');
            }
            return $retVal;
        };

        //Configuration name validation
        function checkName() {
            var $retVal = false;
            if ($nameElement.val() !== "") {
                $nameElement.removeClass('error');
                $nameElement.siblings('.check').show();
                $nameElement.siblings('.cross').hide();
                $nameElement.siblings('.error-msg').hide();
                $retVal = true;
            } else {
                $nameElement.siblings('.check').hide();
                $nameElement.siblings('.cross').show();
                $nameElement.siblings('.error-msg').show();
                $nameElement.addClass('error');
            }
            return $retVal;
        };

        //Legal agreement validation
        function checkAgree() {
            var $retVal = false;
            if ($confirmationElem[0].checked) {
                $retVal = true;
                $confirmationElem.parent().find('.cross').hide();
                $confirmationElem.parent().siblings('.error-msg').hide();
            } else {
                $confirmationElem.parent().find('.cross').show();
                $confirmationElem.parent().siblings('.error-msg').show();
            }
            return $retVal;
        };

        return {
            init: init
        };
    })();

    cf65.init();
});
