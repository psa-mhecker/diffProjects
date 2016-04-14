/**
 * Created on 01/12/15.
 * @example pour un simple evenement sur un bouton ou un lien
 *
 * <a class="btn" href="" data-gtm="{eventType: 'click', dataList: { event: 'uaevent',
 * eventCategory: 'pt2::position-10', eventAction: 'Redirection', eventLabel: 'Citadine et compacte'}}">Légal</a>
 *
 *
 */
window.NDP = window.NDP || {};
window.NDP.TrackEventGTM = {
	setGtmEvent: function(element){
		var dataGtm = element.data('gtm'),
		   _oTrackData = this._checkTrackData(dataGtm.dataList); // Recuperation du tagEvent dans un tableau

		if(_oTrackData){ // Do not track event without _oTrackData
			var _event = _oTrackData.eventType || 'click'; // Recuperation du type d'evenement

			element.on(_event, function (event) {
				this.pushToDataLayer(_oTrackData);
			}.bind(this));
		}
	},
	_checkTrackData: function(dataGtm){
		if(typeof dataGtm === 'object' && dataGtm.event){
			for (var variable in dataGtm){
				if(variable !== "event" && !dataGtm[variable]){
					delete dataGtm[variable];
				}
			}
			return dataGtm;
		}
	},
	pushToDataLayer: function(dataTrack){
		var dataGtm = this._checkTrackData(dataTrack);
		if(dataGtm){
			window.dataLayer.push(dataGtm);
		}
	}
};


;(function($, window, document, undefined) {

	// Create the defaults once
	var pluginName = "trackEventGTM",
		defaults = {
			dataContener: '[data-gtm]'
		};

	var TrackEventGTM = function (element, options) {

		this.element = element;

		this.options = $.extend({}, defaults, options);

		this._defaults = defaults;
		this._name = pluginName;

		NDP.TrackEventGTM.setGtmEvent($(this.element));
	};

	$.fn[pluginName] = function(options) {
		return this.each(function() {
			if (!$.data(this, "plugin_" + pluginName)) {
				$.data(this, "plugin_" + pluginName,
					new TrackEventGTM(this, options));
			}
		});
	};


})(jQuery, window, document);
