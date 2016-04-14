/*
 This file contains validations that are too specific to be part of the core
 Please reference the file AFTER the translation file or the rules will be overwritten
 Use at your own risk. We can't provide support for most of the validations
 */
(function($) {
	if ($.validationEngineLanguage == undefined
			|| $.validationEngineLanguage.allRules == undefined)
		alert("Please include other-validations.js AFTER the translation file");
	else {
		$.validationEngineLanguage.allRules["postcode"] = {
			// Germany zip codes | Accepts 12345 format zipcodes
			"regex" : /^\d{5}?$/,
			"alertText" : "* Invalid zipcode"
		};
		$.validationEngineLanguage.allRules["iban"] = {
			"func" : function(field, rules, i, options) {
				return isIbanValid(field.val());
			},
			"alertText" : "* IBAN ungültig"
		};
		$.validationEngineLanguage.allRules["bic"] = {
				"regex" : /^[a-zA-Z]{4}[a-zA-Z]{2}[a-zA-Z0-9]{2}([a-zA-Z0-9]{3})?$/,
				"alertText" : "* BIC ungültig"
		};
		// # more validations may be added after this point
	}
})(jQuery);