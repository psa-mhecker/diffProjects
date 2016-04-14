/*
 This file contains validations that are too specific to be part of the core
 Please reference the file AFTER the translation file or the rules will be overwritten
 Use at your own risk. We can't provide support for most of the validations
*/
(function($){
    if($.validationEngineLanguage == undefined || $.validationEngineLanguage.allRules == undefined )
        alert("Please include other-validations.js AFTER the translation file");
    else {
        $.validationEngineLanguage.allRules["postcode"] = {
            // UK zip codes
            "regex": /^([A-PR-UWYZa-pr-uwyz]([0-9]{1,2}|([A-HK-Ya-hk-y][0-9]|[A-HK-Ya-hk-y][0-9]([0-9]|[ABEHMNPRV-Yabehmnprv-y]))|[0-9][A-HJKS-UWa-hjks-uw])\ {0,1}[0-9][ABD-HJLNP-UW-Zabd-hjlnp-uw-z]{2}|([Gg][Ii][Rr]\ 0[Aa][Aa])|([Ss][Aa][Nn]\ {0,1}[Tt][Aa]1)|([Bb][Ff][Pp][Oo]\ {0,1}([Cc]\/[Oo]\ )?[0-9]{1,4})|(([Aa][Ss][Cc][Nn]|[Bb][Bb][Nn][Dd]|[BFSbfs][Ii][Qq][Qq]|[Pp][Cc][Rr][Nn]|[Ss][Tt][Hh][Ll]|[Tt][Dd][Cc][Uu]|[Tt][Kk][Cc][Aa])\ {0,1}1[Zz][Zz]))$/,
            "alertText": "* Invalid postcode"
        };
		$.validationEngineLanguage.allRules["iban"] = {
				"func" : function(field, rules, i, options) {
					return isIbanValid(field.val());
				},
				"alertText" : "* invalid IBAN"
		};
		$.validationEngineLanguage.allRules["bic"] = {
				"regex" : /^[a-zA-Z]{4}[a-zA-Z]{2}[a-zA-Z0-9]{2}([a-zA-Z0-9]{3})?$/,
				"alertText" : "* invalid BIC"
		};
        //  # more validations may be added after this point
    }
})(jQuery);