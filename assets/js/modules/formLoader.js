// Create the defaults once
var pluginName = 'formLoader';
var defaults = {
	webformsScript: '/version/vc/script/webforms_loader.js',
	formParams: {
		brand: 'ap', // Marque [ap, ac, ds] en minuscule
		context: window.isMobile ? 'mobile' : 'desktop',
		brandidConnector: window.isMobile ? 'mobile' : 'pc',
		otherCss: [], // Liste de CSS spécifiques additionnels
		from: 'NDP'
	},
	target: 'wf_form_content',
	contextualization: {
		siteGeo: '',
		preselectedVehicleLcdv: ''
	}
};

// The actual plugin constructor
export default function FormLoader(element, options) {
	this.element = element;
	this.options = $.extend(true, {}, defaults, options);
	this._defaults = defaults;
	this._name = pluginName;
	this._create();
}

FormLoader.prototype = {
	_create: function() {
		this.spinner = new Spinner().spin(this.element);
		this.interval = window.setInterval(this._checkFormLoaded.bind(this), 1000);
		this.addScript('//maps.google.com/maps/api/js?libraries=geometry&sensor=true&region='+this.options.formParams.country);
		this.addScript(
			this.options.webformsScript,
			this.init.bind(this),
			this.formLoadingErrorCallback.bind(this)
		);
	},
	_checkFormLoaded: function() {
		if($(this.element).find('form, .wf_globalError').length) {
			this.formLoaded();
			window.clearInterval(this.interval);
		}
	},
	addScript: function(url, callback, error){
		var script = document.createElement('script');
		script.setAttribute('src', url);
		script.onload = callback || $.noop;
		script.onerror = error || $.noop;
		document.body.insertBefore(script, document.body.firstChild);
	},
	init: function () {
		window.loadFormsParameters = function() {

			$(document).on('animationstart webkitAnimationStart MSAnimationStart', function(e) {
				if(e.originalEvent.animationName === 'nodeInserted') {
					this.formLoaded();
					window.clearInterval(this.interval);
				}
			}.bind(this));

			new citroen.webforms.WebFormsFacade({
				source: '/forms/v2?instanceid='+this.options.formParams.instance+'&culture='+this.options.formParams.culture,
				returnURL: '',
				dealerLocatorFluxType: 'dealerdirectory2',
				target: this.options.target,
				siteGeo : this.options.contextualization.siteGeo,
				autoFill: {
					'GIT_TRACKING_ID': getGITID(),
					'TESTDRIVE_CAR_LCDV': this.options.contextualization.preselectedVehicleLcdv,
					'PAGE_TITLE': document.title,
					'DS_APPLICATION_CODE': 'NDP',
					'GTM_SITE_TYPE_LEVEL_2': sessionStorage.getItem('prevSiteTypeLevel2') || 'forms',
					'GTM_PAGEVARIANT ': this.options.contextualization.preselectedVehicleLcdv ? 'context-car' : 'context-none',
					'GTM_POSITION_TRANCHE': this.options.position
				},
				brochurePickerPreselectedVehiclesLcdv: [this.options.contextualization.preselectedVehicleLcdv],
				carPickerPreselectedVehicles: [this.options.contextualization.preselectedVehicleLcdv],
				// preselectedVehicleLcdv will be used on a next version of forms and carPickerPreselectedVehicles removed, so pass both to be sure
				preselectedVehicleLcdv: [this.options.contextualization.preselectedVehicleLcdv],
				onPostAjaxSuccess: this.successCallback.bind(this),
				onPostAjaxFailure: this.formErrorCallback.bind(this),
				onPostAjaxError: this.formErrorCallback.bind(this)
			});

			citroen.webforms.parameters.contextualize(this.options.formParams);

		}.bind(this);

		window.formParams = this.options.formParams; // webforms_loader needs this variable in global

		loadFormsResources(this.options.formParams.context);
	},
	formLoaded: function() {
		this.spinner.stop();
		if(_.where(window.dataLayer, {pageCategory: 'form page'}).length) { // if we have forms dataLayer, remove the parent page one
			window.dataLayer = _.filter(window.dataLayer, function(item) {
				return item.pageCategory === 'form page' || item.event;
			});
		}
	},
	_decodeURIParams: function(URIParams) {
		var params = {},
			vars = URIParams.split("&");
		for (var i = 0, len = vars.length; i < len; i++) {
			var pair = vars[i].split("=");
			params[pair[0]] = pair[1];
		}
		return params;
	},
	_replaceValues: function(templateValue, values) {
		var corres = {
			email: 'USR_EMAIL',
			firstname: 'USR_FIRST_NAME',
			lastname: 'USR_LAST_NAME'
		};
		values = values || {};

		for(var key in corres) {
			templateValue = templateValue.replace('##'+key+'##', values[corres[key]] || '');
		}

		return templateValue;
	},
	successCallback: function(data) {
		var $confirm = $(this.element).siblings('.confirm-forms'),
			values = this._decodeURIParams(data.message.substr(1)),
			name = (values.USR_CIVILITY ? values.USR_CIVILITY+' ' : '') +
					(values.USR_FIRST_NAME || '') + ' ' + (values.USR_LAST_NAME || '');

		$confirm.find('.name').html(name);

		$confirm.find('.replace-values').each(function(i, el) {
			var $el = $(el);
			$el.replaceWith(this._replaceValues($el.html(), values));
		}.bind(this));

		$(this.element).remove();
		$confirm.show();
		$(document).scrollTop(0);
		this._createVirtualPage(values);
	},
	_createVirtualPage: function(values) {
		// Get forms virtual page first
		var event = _.where(window.dataLayer, {event: 'updatevirtualpath'});
		if(!event.length) {
			event = [window.dataLayer[window.dataLayer.length -1]];
		}
		event = _.clone(event[event.length - 1]);

		event.virtualPageURL = event.virtualPageURL.replace(/step-.*$/, 'step-4');
		event.virtualPageURL = event.virtualPageURL.replace(/form$/, 'confirmation');

		if(['contact request', 'newsletter deregistration','cnil request'].indexOf(event.formsName) !== -1) {
			event.pageCategory = 'confirmation page';
		} else {
			event.pageCategory = 'lead page';
			event.formsLeadType = this._getLeadType(event.formsName, values);
		}

		event.formsLeadID = values.GIT_TRACKING_ID;

		if(['contact request', 'newsletter deregistration', 'newsletter registration', 'cnil request'].indexOf(event.formsName) === -1) {
			event.mainStepIndicator = '4';
			event.mainStepName = 'confirmation';
		}

		if(this.options.contextualization.preselectedVehicleLcdv) {
			event.customDimension2 = this.options.contextualization.preselectedVehicleLcdv.substr(0, 6);
		}

		event.formsPostalCode = values.USR_ADDR_ZIP_CODE;
		event.uiExpectedPurchase = values.USR_PLAN_RENEW_DATE;

		event.vehicleModelBodystyle = (values.LCDV_GTM_6||values.TESTDRIVE_CAR_LCDV||'').substr(0, 6);
		event.vehicleModelBodystyleLabel = (values.TESTDRIVE_CAR||'').toLowerCase();

		event.edealerName = (values.DEALER_NAME||'').toLowerCase();
		event.edealerSiteGeo = values.DEALER_GEOSITE_CODE;
		event.edealerID = values.DEALER_RRDI;
		event.edealerCity = (values.DEALER_CITY||'').toLowerCase();
		event.edealerAddress = (values.DEALER_ADDR_1||'').toLowerCase();
		event.edealerPostalCode = values.DEALER_POSTAL_CODE;
		event.edealerRegion = (values.DEALER_REGION||'').toLowerCase();
		event.edealerCountry = (this.options.formParams.country||'').toLowerCase();

		if(event.vehicleModelBodystyle) {
			var pageNameArray = event.pageName.split('/');
			if(pageNameArray.length > 1) {
				pageNameArray[pageNameArray.length - 2] = event.vehicleModelBodystyle;
			}
			event.pageName = pageNameArray.join('/');
		}


		for(var variable in event) {
			if(!event[variable]) {
				delete event[variable];
			}
		}

		window.dataLayer.push(event);
	},
	_getLeadType: function(formsName, values) {
		if(['test drive', 'offer request', 'estimate request', 'brochure request'].indexOf(formsName) !== -1) {
			return 'hot lead';
		}
		return 'cold lead';
	},
	formErrorCallback: function(data) {
		var clone = $('.slice-pf17 .error').clone();
		clone.find('.information').remove();
		var values = this._decodeURIParams(data.message.substr(1));
		NDP.TrackEventGTM.pushToDataLayer({
			event: 'uaevent',
			eventCategory: this.options.eventCategory,
			eventAction: 'Display::DisplayError::'+(values.GTM_FORMS_NAME||''),
			eventLabel: clone.text().trim()
		});
		this.formLoadingErrorCallback();
	},
	formLoadingErrorCallback: function() {
		var $error = $(this.element).parent().find('.error').show();
		_.defer(function() { // use defer so that element is shown for fade-in of icon
			$error.find('.information').addClass('fade');
		});
	}
};
