var Type_Demande = {
  VALIDATION_CENTRAL: 0,
  EVOLUTION_FORMULAIRE: 1,
  NOTIFICATION_ANOMALIE: 2
};

var Type_Priorite = {
  BLOQUANTE: 1,
  MAJEURE: 3,
  MINEURE: 4
};

var FieldDatasModel = function(data) {
	var self = this;
	
	self.identifier = data.id;
	
	// si site LP
	if (data.label == 'SBS_USR_OFFER_2' && sr_form_site_id == sr_from_site_id_landing) {
		self.label = lang['BOFORMS_LABEL_' + data.label + '_LP'];
	} else if (data.label == 'LEGAL_MENTION_CPP_ANSWER' || data.label == 'SBS_NWL_NEWS' || data.label == 'UNS_NWS_CPP_MOTIF' || 
			data.label == 'MULTIFORMS_CHOICE' || data.label == 'SBS_USER_OFFER' || data.label == 'REQUEST_CALLBACK' || 
			data.label == 'GET_MYCITROEN' || data.label == 'SBS_USR_OFFER' || data.label == 'SBS_COM_OFFER' ||
			data.label == 'SBS_COM_OFFER_2' || data.label == 'SBS_USR_OFFER_2' || 
			data.label == 'REQUEST_INTEREST_FINANCING' || data.label == 'REQUEST_INTEREST_INSURANCE' || data.label == 'REQUEST_INTEREST_SERVICE') {
		self.label = lang['BOFORMS_LABEL_' + data.label];
	} else {
		self.label = data.label;
	}
	
	self.description = "";
	self.ischecked = ko.observable(false);
	//self.is_empty = ko.observable(false); // field used for validation
	self.toggleAssociation = function(data) {
		self.ischecked(! self.ischecked());
		return true;
	};

};

var FieldComponentModel = function(data) {
	var self = this;
	
	self.description = "";
	self.label = data.label;
	self.ischecked = ko.observable(false);
	
	self.toggleAssociation = function(data) {
		self.ischecked(! self.ischecked());
		return true;
	};
}

// Root view model
var RootViewModel = function(data) {
	var self = this;
	self.request_datas = ko.observable( new SupportViewModel() );
	
	self.jsonRes = ko.computed(function(){
	    return ko.toJSON(self.request_datas);
    }, this);
	
	// do validate
	self.validateDataModel = function() {
		return self.request_datas().validateDataModel();
	}
}

var SupportViewModel = function(data) {
	var self = this;
	
	self.tblTypeDemande = ko.observableArray([{ name: lang['BOFORMS_REQUEST_TYPE_CENTRAL_VALIDATION'], id: 0}, 
									          { name: lang['BOFORMS_REQUEST_TYPE_FORM_EVOLUTION'], id: 1}, 
									          { name: lang['BOFORMS_REQUEST_TYPE_NOTIFY_ANOMALY'], id: 2}]);
	
	self.tblListePriorite = ko.observableArray([{ name: lang['BOFORMS_REQUEST_BLOCKING'], id: Type_Priorite.BLOQUANTE}, 
	                                            { name: lang['BOFORMS_REQUEST_MAJOR'], id: Type_Priorite.MAJEURE}, 
	                                            { name: lang['BOFORMS_REQUEST_MINOR'], id: Type_Priorite.MINEURE}]);
	
	self.tblTypeNotification = ko.observableArray([	
	        { name: lang['BOFORMS_NOTIFICATION_NEW_FIELDS'], id: 0, is_displayed: false},
			{ name: lang['BOFORMS_NOTIFICATION_DEL_MANDATORY_FIELD'], id: 1, is_displayed: false},
			{ name: lang['BOFORMS_NOTIFICATION_MODIFY_IMPRINT'], id: 2, is_displayed: false}, 
			{ name: lang['BOFORMS_NOTIFICATION_UPD_BUSINESS_COMPONENT'], id: 3, is_displayed: false},
			{ name: lang['BOFORMS_NOTIFICATION_UPD_USER_INTERFACE'], id: 4, is_displayed: false},
			{ name: lang['BOFORMS_NOTIFICATION_MODIFY_OPT_IN'], id: 5, is_displayed: false},
			{ name: lang['BOFORMS_NOTIFICATION_MODIFY_STEP_ORDER'], id: 6, is_displayed: false},
			{ name: lang['BOFORMS_NOTIFICATION_OTHER_REQUEST'], id: 7, is_displayed: false}]);
	
	self.notificationDisplayAddButton = function() {
		var obj = ko.utils.arrayFirst(self.tblTypeNotification(), function (item) {
            return self.type_notification() == item.id;
        });
		return (obj.is_displayed == false);
	};
	
	// required fields 
	var tbl_tmp = [];
	for (jjj = 0; jjj < tbl_required_fields.length; jjj++) {
		tbl_tmp.push(new FieldDatasModel(tbl_required_fields[jjj]));
	}
	this.tbl_required_fields  = ko.observableArray(tbl_tmp);
	tbl_tmp = [];
		 
	// components
	for (jjj = 0; jjj < tbl_result_compos.length; jjj++) { 
		tbl_tmp.push(new FieldComponentModel(tbl_result_compos[jjj]));	
	}
	this.tbl_result_compos  = ko.observableArray(tbl_tmp);
	tbl_tmp = [];
	
	// all fields
	for (jjj = 0; jjj < tbl_all_fields.length; jjj++) {
		tbl_tmp.push(new FieldDatasModel(tbl_all_fields[jjj]));
	}
	this.tbl_all_fields  = ko.observableArray(tbl_tmp);
	tbl_tmp = [];
	
	self.type_demande = ko.observable(null); 
	self.priorite     = ko.observable(null);
	
	
	self.environnement =  ko.observable(sr_environnement);
	self.countrycode =  ko.observable(sr_country_code); 
	self.device =  ko.observable(sr_device);
	self.site =  ko.observable(sr_site);
	self.formcontext = ko.observable(sr_form_context);
	self.formcustomertype= ko.observable(sr_form_customer_type);
	self.culture_str = ko.observable(sr_culture_str);
	
	self.webmaster_name =  ko.observable(sr_webmaster_name);
	self.rpi =  ko.observable(sr_rpi);
	self.form_type =  ko.observable(sr_form_type);
	self.scode = ko.observable(sr_scode);
	
	// Demande de type validation au central
	self.request_description     = ko.observable(''); // Descriptif de la demande (champs texte libre)
	self.xml_registered_version  = ko.observable(sr_xml_saved_version); // XML de la version enregistrée (non du fichier)
	
	// Demande de type évolution du formulaire
	self.request_title = ko.observable(''); // titre de la demande
	self.type_notification = ko.observable(null);
	
	self.request_more_description = ko.observable(''); // précision de la demande
	
	self.request_more_description_0 = ko.observable('');
	self.request_more_description_2 = ko.observable('');
	self.request_more_description_3 = ko.observable('');
	self.request_more_description_4 = ko.observable('');
	self.request_more_description_5 = ko.observable('');
	self.request_more_description_6 = ko.observable('');
	self.request_more_description_7 = ko.observable('');
	
	// Demande de type Notification d’anomalie
	self.anomalie_description = ko.observable('');
	
	self.all_errors = ko.observableArray([]); // array with all the errors
	
	
	self.type_demande_changed = function (obj, event) {
		// clean all errors
		self.all_errors.removeAll();
	};
	
	// form validation
	self.validateDataModel = function() {
		// clean all errors
		self.all_errors.removeAll();

		// validates
		if (typeof(self.type_demande()) === 'undefined') {
			return false;
		} else if (self.type_demande() == Type_Demande.VALIDATION_CENTRAL) {
			if (self.request_description() == '') {
				self.all_errors.push('request_description');
			}
		} else if (self.type_demande() == Type_Demande.EVOLUTION_FORMULAIRE) {
			if (self.request_title() == '') {
				self.all_errors.push('request_title');
			}
			
			var nb_request = 0;
			for (var j = 0; j < self.tblTypeNotification().length; j++) {
				var id = self.tblTypeNotification()[j].id;
				var is_displayed = self.tblTypeNotification()[j].is_displayed;
				if (is_displayed == true) {
					nb_request++;
					
					if (self.checkDatasForNotification(id) == false) {
						self.all_errors.push('error_notification_' + id);		
					}
				}	
			}
			
			// we should have at least one request
			if (nb_request == 0) {
				self.all_errors.push('no_request_added');
			}
		} else if (self.type_demande() == Type_Demande.NOTIFICATION_ANOMALIE) {
			var nb_checked = 0;
			var nb_not_filled = 0;
			var tbl = self.tbl_all_fields();
			for (j = 0; j < tbl.length; j++) {
				var obj = tbl[j];
				if (obj.ischecked()) {
					nb_checked++;
					if (obj.description == '') {
						nb_not_filled++;
					}
				}
			}		
			
			if (self.anomalie_description() == '') {
				if (nb_checked == 0) {
					self.all_errors.push('evo_form_empty');
				} else if (nb_not_filled > 0) {
					self.all_errors.push('evo_description_empty');
				}
			} else {
				if (nb_not_filled > 0) {
					self.all_errors.push('evo_description_empty');
				}
			} 
			
			if (self.request_title() == '') {
				self.all_errors.push('request_title');
			}
		}
		
		// we should have no errors
		return (self.all_errors().length == 0);
	};
	self.notificationDeleteRequest = function(i) {
		self.addRemoveRequest(i,false);
	};
	
	self.notificationAddRequest = function(data, event) {
		self.addRemoveRequest(self.type_notification(), true);	
	};
	
	// validates list of checkboxes
	self.checkListCheckBox = function(id, tbl) {
		var nb_checked = 0;
		var nb_not_filled = 0;
		for (j = 0; j < tbl.length; j++) {
			var obj = tbl[j];
			if (obj.ischecked()) {
				nb_checked++;
				if (obj.description == '') {
					//obj.is_empty(true);
					nb_not_filled++;
				}
			}
		}		
		return (nb_not_filled == 0 && nb_checked > 0);
	};
	
	// validates a notification_type bloc
	self.checkDatasForNotification = function(id) {
		if (id == 0) {
			return self.request_more_description_0() != '';
		} else if (id == 1) {
			return self.checkListCheckBox(id, self.tbl_required_fields());  
		} else if (id == 2) {
			return self.request_more_description_2() != '';
		} else if (id == 3) {
			return self.checkListCheckBox(id, self.tbl_result_compos()); // check selected components
		} else if (id == 4) {
			return self.request_more_description_4() != '';
		} else if (id == 5) {
			return self.request_more_description_5() != '';
		} else if (id == 6) {
			return self.request_more_description_6() != '';
		} else if (id == 7) {
			return self.request_more_description_7() != '';
		} else {
			return false;
		}
	};
	
	self.addRemoveRequest = function(the_id, is_add_request) {
		var obj = ko.utils.arrayFirst(self.tblTypeNotification(), function (item) {
            return the_id == item.id;
        });
		
		if (obj != null) {
			obj.is_displayed = is_add_request;
			
			// on positionne le bloc ajouté en dernier dans la div #notification_all_blocs 
			if (is_add_request == true) {
				var last_bloc_id = $('#notification_all_blocs').children().last().attr('id');
				if (last_bloc_id != 'notification_bloc_' + the_id) {
					$("#notification_bloc_" + the_id).insertAfter('#'  + last_bloc_id);
				}
			}
			
			// updates the data model
			self.tblTypeNotification.replace(self.tblTypeNotification()[the_id], obj);
		}
	}
	
	self.displayBlockNotification = function(i) {
		var obj = ko.utils.arrayFirst(self.tblTypeNotification(), function (item) {
         	return i == item.id;
        });
		
		if (obj == null) {
			return false;
		} else {
			return (obj.is_displayed);
		}
		return true;
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