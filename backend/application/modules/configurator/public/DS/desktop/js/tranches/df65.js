ISO.moduleCreate('sliceDF65', function($el, param) {
    var df65 = (function() {
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
            $nextButtonElement = $('.js-df65-btn-next');

            checkName();
            events();
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
                $emailElem.siblings('.check').removeClass('hidden');
                $emailElem.siblings('.cross').addClass('hidden');
                $emailElem.siblings('.error-msg').addClass('hidden');
                $retVal = true;
            } else {
                $emailElem.siblings('.check').addClass('hidden');
                $emailElem.siblings('.cross').removeClass('hidden');
                $emailElem.siblings('.error-msg').removeClass('hidden');
                $emailElem.addClass('error');
            }
            return $retVal;
        };

        //Configuration name validation
        function checkName() {
            var $retVal = false;
            if ($nameElement.val() !== "") {
                $nameElement.removeClass('error');
                $nameElement.siblings('.check').removeClass('hidden');
                $nameElement.siblings('.cross').addClass('hidden');
                $nameElement.siblings('.error-msg').addClass('hidden');
                $retVal = true;
            } else {
                $nameElement.siblings('.check').addClass('hidden');
                $nameElement.siblings('.cross').removeClass('hidden');
                $nameElement.siblings('.error-msg').removeClass('hidden');
                $nameElement.addClass('error');
            }
            return $retVal;
        };

        //Legal agreement validation
        function checkAgree() {
            var $retVal = false;
            if ($confirmationElem[0].checked) {
                $retVal = true;
                $confirmationElem.parent().find('.cross').addClass('hidden');
                $confirmationElem.parent().siblings('.error-msg').addClass('hidden');
            } else {
                $confirmationElem.parent().find('.cross').removeClass('hidden');
                $confirmationElem.parent().siblings('.error-msg').removeClass('hidden');
            }
            return $retVal;
        };

        return {
            init: init
        };
    })();

    df65.init();
});
