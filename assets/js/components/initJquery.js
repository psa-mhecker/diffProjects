import DealerLocator from './modules/dealerLocator.js';
import FormLoader from './modules/formLoader.js';
import MosaicUSP from './modules/mosaicUSP.js';

var modules = {
	'dealerLocator': DealerLocator,
	'formLoader': FormLoader,
	'mosaicUSP': MosaicUSP
};

Object.keys(modules).forEach(moduleName => {

	;(function ($, window, document, undefined) {

		// Create the defaults once
		var pluginName = moduleName;

		$.fn[pluginName] = function (options) {
			return this.each(function () {
				if (!$.data(this, "plugin_" + pluginName)) {
					$.data(this, "plugin_" + pluginName,
						new modules[moduleName](this, options));
				}
			});
		};

	})(jQuery, window, document);
});



