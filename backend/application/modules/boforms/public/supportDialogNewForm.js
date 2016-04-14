
var Type_Priorite = {
  BLOQUANTE: 1,
  MAJEURE: 3,
  MINEURE: 4
};

var Type_Cible = {
	PARTICULIER: 1,
	PROFESSIONEL: 2
};

var FieldDatasModel = function(data) {
	var self = this;
	self.identifier = data.label;
	
	if (data.label == 'LEGAL_MENTION_CPP_ANSWER' || data.label == 'SBS_NWL_NEWS' || data.label == 'UNS_NWS_CPP_MOTIF' || 
		data.label == 'MULTIFORMS_CHOICE' || data.label == 'SBS_USER_OFFER' || data.label == 'SBS_USR_OFFER' ||  data.label == 'REQUEST_CALLBACK' || 
		data.label == 'GET_MYCITROEN' || data.label == 'SBS_COM_OFFER' || data.label == 'REQUEST_INTEREST_FINANCING' || 
		data.label == 'SBS_COM_OFFER_2' || data.label == 'SBS_USR_OFFER_2' || 
		data.label == 'REQUEST_INTEREST_INSURANCE' || data.label == 'REQUEST_INTEREST_SERVICE') {
		self.label =  lang['BOFORMS_LABEL_' + data.label];
	} else {
		self.label = data.label;
	}
	
	self.chkname = data.chkname;
	
	self.description = "";
	self.isrequiredcentral = (data.required_central == '1');
	self.ischecked = ko.observable(data.required_central == '1');

};


var SupportViewModel = function(data) {
	var self = this;
	
	
	self.type_demande = 3;
	self.tblListePriorite = ko.observableArray([{ name: lang.BOFORMS_REQUEST_BLOCKING, id: 1}, 
	                                            { name: lang.BOFORMS_REQUEST_MAJOR, id: 3}, 
	                                            { name: lang.BOFORMS_REQUEST_MINOR, id: 4}]);
	
	self.tblListeOpportunities = ko.observableArray(listOpportunities);

	self.tblListeFields = ko.observableArray([]);
	
	self.formaddfields = ko.observable('');
	self.formexample = ko.observable('');
	
	self.priorite = ko.observable(-1);
	self.opportunity = ko.observable();
	
	self.form_type = ko.observable('');
	self.form_description = ko.observable('');
	
	self.form_target_selected_part = ko.observable(false);
	self.form_target_selected_pro = ko.observable(false); 
		
	self.workflow_standard = ko.observable(false);
	self.workflow_context_pos = ko.observable(false); 
	self.workflow_context_vehicle = ko.observable(false);
	
	self.device_web = ko.observable(false);
	self.device_mobile = ko.observable(false);
	
	self.all_errors = ko.observableArray([]); // array with all the errors
	self.error_no_data_found = ko.observable(false);
	
	self.rpi =  ko.observable(sr_rpi);
	self.countrycode =  ko.observable(sr_country_code);
	self.site =  ko.observable(sr_site);
	self.webmaster_name =  ko.observable(sr_webmaster_name);
	self.environnement =  ko.observable(sr_environnement);
	self.culture_str = ko.observable(sr_culture_str);
	self.groupe_id = ko.observable(groupe_id);
	
	self.request_title = ko.observable('');
	
	// validates list of checkboxes
	self.checkListCheckBox = function(tbl) {
		var nb_checked = 0;
		var nb_not_filled = 0;
		for (j = 0; j < tbl.length; j++) {
			var obj = tbl[j];
			if (obj.ischecked()) {
				nb_checked++;
			}
		}		
		return nb_checked > 0;
	};
	
	
	// form validation
	self.validateDataModel = function() {
		// clean all errors
		self.all_errors.removeAll();

		// validates
		
		if (typeof(self.priorite()) === 'undefined' || self.priorite <= 0) {
			self.all_errors.push('priorite');
		}
		
		if (self.form_type() == '') {
			self.all_errors.push('form_type');
		}

		if (self.form_description() == '') {
			self.all_errors.push('form_description');
		}
		
		if (self.form_target_selected_part() == false &&  self.form_target_selected_pro() == false) {
			self.all_errors.push('form_target_selected');
		}
		
		if (self.workflow_standard() == false && self.workflow_context_pos() == false && self.workflow_context_vehicle() == false) {
			self.all_errors.push('workflow');
		}
		
		if (self.device_web() == false && self.device_mobile() == false) {
			self.all_errors.push('device');
		}
		
		
		if (typeof(self.opportunity()) === 'undefined' || self.opportunity() == '') {
			self.all_errors.push('opportunity');
		}
		
		// validates checkboxes
		//if (! self.checkListCheckBox(self.tblListeFields()))  {
			//self.all_errors.push('checkboxes_fields');
		//}
		
		if (self.request_title() == '') {
			self.all_errors.push('request_title');
		}
		
		// we should have no errors
		return (self.all_errors().length == 0);
	};
	
	// checks changes on a few fields
	
	self.device_web.subscribe(function(newValue) {
		self.doUpdateFieldsZone();
	});
	self.device_mobile.subscribe(function(newValue) {
		self.doUpdateFieldsZone();
	});
	self.opportunity.subscribe(function(newValue) {
		self.doUpdateFieldsZone();
	});
	
	self.form_target_selected_part.subscribe(function(newValue) {
		self.doUpdateFieldsZone();
	});
	self.form_target_selected_pro.subscribe(function(newValue) {
		self.doUpdateFieldsZone();
	});	
	
	self.doUpdateFieldsZone = function() {
		self.tblListeFields.removeAll();
		
		if ( (self.device_mobile() == true || self.device_web() == true) && 
			 (self.form_target_selected_pro() == true || self.form_target_selected_part() == true) && 
			 typeof(self.opportunity()) !== 'undefined' && self.opportunity() != '' && self.opportunity() != '-1') {
			
			var device_mobile_prm = self.device_mobile() ? '1': '0';
			var device_web_prm    = self.device_web()    ? '1': '0';
			var part_prm = self.form_target_selected_part() ? '1': '0';
			var pro_prm    = self.form_target_selected_pro()    ? '1': '0';
			
			var url = "/_/module/boforms/BoForms_Administration_SupportRequest/getFormComponentsAgregate?cible_part=" + part_prm + "&cible_pro=" + pro_prm + 
					  "&device_mobile=" + device_mobile_prm + "&device_web=" + device_web_prm + 
					  "&formulaire=" + self.opportunity() + "&groupe_id=" + groupe_id + "&time=" + new Date().getTime();
		
			$.get(url, function( data ) {
				json = $.parseJSON(data);
				
				if (json.result && json.result.length > 0) {
					var object_json = json.result;
					self.tblListeFields.removeAll();
					
					for (var i = 0; i < object_json.length; i++) {
						var label_tmp = object_json[i].label;
						
						self.tblListeFields.push(new FieldDatasModel({chkname: 'chkfield' + i,label: label_tmp,  required_central: object_json[i].required_central}));
					}
					self.error_no_data_found(false);
				} else {
					self.tblListeFields.removeAll();
					if ($('#span_form_not_found').css('visibility') == 'visible') {
						$('#span_form_not_found').html($('#span_form_not_found').html() + '.');
					}					
					self.error_no_data_found(true);
				}
			});
		} else {
			self.tblListeFields.removeAll();
			self.error_no_data_found(false);
		}
	}
};

//Root view model
var RootViewModel = function(data) {
	var self = this;
	self.request_datas = ko.observable( new SupportViewModel() );
	
	self.jsonRes = ko.computed(function(){
	   return ko.toJSON(self.request_datas);
    }, this);
	
	// do validate
	self.validateDataModel = function() {
		return self.request_datas().validateDataModel();
	};
};

// knockout model

var builderSupport = new RootViewModel();

$( document ).ready(function() {
	// loads knockout
	ko.applyBindings(builderSupport);
	
	$('.btn_send_support_request').on('click', function(e) {
		e.defaultPrevented = true;
		
		// set the hidden field with the json value
		if (builderSupport.validateDataModel()) {
			$('#frm_to_post').submit();
			return false;
		}	
		return false;
	});
});