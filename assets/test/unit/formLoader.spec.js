import FormLoader from './modules/formLoader.js';

describe("Test Form initialization", function () {
	var formItem = document.createElement('div'),
		formLoader;
	formItem.id = 'wf_form_content';
	formItem.className = 'wf_form_content';
	document.body.appendChild(formItem);

	beforeEach(function (done) {
		var initialInit = FormLoader.prototype.init;
		spyOn(FormLoader.prototype, 'init').and.callFake(function () {
			initialInit.apply(formLoader, arguments);
		});
		var initialSpin = Spinner.prototype.spin;
		spyOn(Spinner.prototype, 'spin').and.callFake(function () {
			return initialSpin.apply(new Spinner(), arguments);
		});
		var initialLoaded = FormLoader.prototype.formLoaded;
		spyOn(FormLoader.prototype, 'formLoaded').and.callFake(function () {
			initialLoaded.apply(formLoader, arguments);
			done();
		});
		window.dataLayer = window.dataLayer || [];
		formLoader = new FormLoader(formItem, {
			"formParams": {
				"instance": "APFR190100000002",
				"lang": "fr",
				"country": "FR",
				"culture": "fr-FR",
				"brandIdConnector": "pc",
				"context": "desktop",
				"environment": "PROD"
			},
			"contextualization": {
				"preselectedVehicleLcdv": ""
			},
			"position": "1",
			"eventCategory": "NDP_PF17_FORM::position-1"
		});
	});
	it("has been init", function () {
		expect(FormLoader.prototype.init).toHaveBeenCalled();
	});
	it("has shown a spinner", function () {
		expect(Spinner.prototype.spin).toHaveBeenCalled();
	});
	it("has been loaded", function () {
		expect(FormLoader.prototype.formLoaded).toHaveBeenCalled();
	});
});
