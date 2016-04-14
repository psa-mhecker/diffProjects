// Constants
var Tabs = {
  ADD_FIELD_TAB: 0,
  FIELD_SETTINGS_TAB: 1
};

var TabsNiv2 = {
		ORDER_TAB: 0,
		PARAM_TAB: 1,
		FIELDS_TAB:2
};

var TabsNiv1 = {
	STEPS_TAB: 0,
	VERSIONS_TAB: 1
};

var FIELD_TYPES = [
  'textbox','checkbox','radio','dropdownlist','dropdown','password','textarea','file','richtexteditor','captcha','datepicker','slider','colorpicker'
];

//form validation

function checkTime_hour_min_sec(field, rules, i, options){
	if (field.val() != "") {
        // this allows the use of i18 for the error msgs
       var patt = new RegExp("^([01][0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$");
       return patt.test(val);
	   
    }
  }

//declare an extender
ko.extenders.validateTime = function(target, option) {
		  // add some sub-observables to our observable
		 target.hasError = ko.observable();
		 target.validationMessage = ko.observable();
		
		 target.subscribe(function(newValue) {
			 validate(newValue);
		 });
		 
		 //define a function to do validation
		 function validate(newValue) {
		    var patt = new RegExp("^([01][0-9]|2[0-3]):[0-5][0-9]:[0-5][0-9]$");
		    if (patt.test(newValue)) {
		    	target.hasError(false);
		    } else {
		    	target.hasError(true);
		    	target.validationMessage("Format invalide");
		    }
		 }
		
		 //initial validation
		 validate(target());
		
		 //return the original observable
		 return target;
};

// Root view model
var EditorViewModel = function(data) {
	var self = this;
	// values for type parameter in the settings panel (used for civility, phone and DPR)
	self.tblSettingType = ko.observableArray([{ name: lang['BOFORMS_LABEL_TYPE_RADIO'], id: 'radio'}, 
									          { name: lang['BOFORMS_LABEL_TYPE_DROPDOWN'], id: 'dropdown'}]);

	self.tblVisibleType = ko.observableArray([{ name: lang['BOFORMS_LABEL_TRUE'], id: 'visible'}, 
									          { name: lang['BOFORMS_LABEL_FALSE'], id: 'notvisible'}]);

	
	self.formCommentary = ko.observable(form_commentary.replace(/<!--backspace-->/g, ""));
	self.formCommentaryVisible = ko.observable(form_commentary_visible);
	
	self.genericSteps = ko.observableArray(GENERIC_STEPS);
	//this.form = new FormViewModel(data);
	
	this.currentTabNiv1 = ko.observable(TabsNiv1.STEPS_TAB);
	this.currentTabNiv2 = ko.observable(TabsNiv2.ORDER_TAB);
	
	this.currentTab = ko.observable(Tabs.ADD_FIELD_TAB);
	  
	this.displayFieldsTab = ko.observable(false);		// Onglets permettant l'ajout ou modif d'un champ d'une étape
	this.displayStepsTab = ko.observable(true);			// Onglets permettant la modif de l'ordre, des param et des champs d'une etape
	this.displayStepParam = ko.observable(false);		// Panneau d'affichage du formulaire de paramétrage de l'étape
	this.displayStepsGeneric = ko.observable(true);		// Panneau d'affichage des étapes génériques
	this.displayStepsForm = ko.observable(true);		// Panneau d'affichage des etapes a  droite
	this.displayFields = ko.observable(false);			// Panneau d'affichage des champs de l'étape
	this.displayVersionsTab = ko.observable(false);		// Panneau d'affichage des versions

	this.displayTagsUnderQuestion = ko.observable(false);
	
	/*************************************************************************************************************************************/


	/*************************************************************************************************************************************/
	

	
	this.stepTitle = ko.observable();
	this.stepID = ko.observable(0);
	this.selectedStep = ko.observable(null);
	
	
	var aSteps = [];
	for(var i=0;i<FORM_STEPS.length;i++)
	{
		aSteps.push(new Step(FORM_STEPS[i], false));
	}
	this.stepsList = ko.observableArray(aSteps);

	
	var aStepsGeneric = [];

	for(i=0;i<GENERIC_STEPS.length;i++)
	{
		aStepsGeneric.push(new Step(GENERIC_STEPS[i], true));
	}
	this.stepsListGeneric = ko.observableArray(aStepsGeneric);

	
	this.jsonRes = ko.computed(function(){
	    return ko.toJSON(self.stepsList);
	  }, this);


	this.stepConfiguration = ko.observableArray([]);
	this.stepConfigurationGeneric = ko.observableArray([]);
		
	this.fieldsets = ko.observableArray([]);//aFieldsets;
	this.fieldsetsGeneric = ko.observableArray([]);
	
	this.tagsUnderQuestion = ko.observableArray([]);
			
	this.displayFieldset = function(data, event){
		var obj = $(event.target);
		var parents = $(obj).parents();
		if ($(obj).is('img')) {
			var fieldset = jQuery(parents[4]).find('.fieldset');
		}  else {
			var fieldset = jQuery(parents[3]).find('.fieldset');
		}
				
		if(jQuery(fieldset).css('display') == 'block')
		{
			jQuery(fieldset).hide();
			data.fieldsetIsCollapsed(false);
			if ($(obj).is('img')) {
				jQuery(obj).attr('src', '/modules/boforms/images/plus.png');
			} else {
				jQuery(obj).find('img').attr('src', '/modules/boforms/images/plus.png');	
			}
		} else {
			jQuery(fieldset).show();
			data.fieldsetIsCollapsed(true);
			if ($(obj).is('img')) {
				jQuery(obj).attr('src', '/modules/boforms/images/moins.png');
			} else {
				jQuery(obj).find('img').attr('src', '/modules/boforms/images/moins.png');	
			}
		}
		
	};
	
	// cherche si un champ existe dans un générique d'étape
	// et si ce champ existe dans le générique de l'étape courante
	this.searchFieldInGeneric = function(data) {
		var bFoundInGeneric = false;
		var bIsCurrentStep = false;
		
		var aStepListGeneric = this.stepsListGeneric();
		var aFieldsetsGeneric = [];
		
		var aLineFound = null;
		var aFieldFound = null;
		var selectedName = self.selectedField().name();
		
		// boucle sur les etapes generique
		for(var i=0;i<aStepListGeneric.length;i++)
		{	
			  if(aStepListGeneric[i]['id'] == data.id)
			  {
				  bIsCurrentStep = true;  
			  } else {
				  bIsCurrentStep = false;
			  }
			  
			  aFieldsetsGeneric = aStepListGeneric[i]['fieldsetsGeneric'];
			  
			  	// recherche si le champ est présent
				for(var ib=0;ib<aFieldsetsGeneric.length;ib++)
				{
					var aQuestions = aFieldsetsGeneric[ib]['questionsGeneric']();
					for(var j=0;j<aQuestions.length;j++)
					{
						var aLines = aQuestions[j]['linesGeneric'];
						for(var k=0;k<aLines.length;k++)
						{
							var aFields = aLines[k]['fieldsGeneric'];
							
							for(var l=0;l<aFields.length;l++)
							{
								if(selectedName == aFields[l]['name']())
								{
									if (bIsCurrentStep == true) {
										bFoundInGeneric = true;
									}
									
									aFieldFound = aFields[l];
									aLineFound = aLines[k];  
								}
							}				
						}
					}
				}
		 
			}
		
		var resultat = new Object();
		resultat.bFoundInGeneric = bFoundInGeneric;
		resultat.lineFound = aLineFound;
		resultat.fieldFound = aFieldFound;
		return resultat;
	};
	
	// recherche les fieldsets courant
	this.getCurrentFieldSets = function (data) {
		var aStepList = this.stepsList();
		var aFieldsets = aStepList[data.id]['fieldsets']();
		for(var i=0;i<aStepList.length;i++)
		{
		  if(aStepList[i]['id'] == data.id)
		  {
			  aFieldsets = aStepList[i]['fieldsets']();
		  }
		}
		return aFieldsets;
	};
	
	// cherche une ligne dans l'etape courante à partir de la ligne dans le generic
	this.searchLineInStandardFromGeneric = function(aFieldsets, lineFoundInGeneric) {
		for(var i=0;i<aFieldsets.length;i++)
		{
			var aQuestions = aFieldsets[i]['questions']();
			for(var j=0;j<aQuestions.length;j++)
			{
				var aLines = aQuestions[j]['lines']();
				for(var k=0;k<aLines.length;k++)
				{
					if(lineFoundInGeneric['name'] == aLines[k]['name'])
					{
						return aLines[k];
					}
				}
			}
		}
	};
	
	this.getNewFieldsetName = function(aFieldsets) {
		var last_fieldset_name = aFieldsets[aFieldsets.length - 1].name;
		var tbl_split = last_fieldset_name.split('_');
		var tbl_split2 = tbl_split[1].split('-');
		
		var num_page = tbl_split2[0];
		var num_fieldset = 1 + parseInt(tbl_split2[1]);
		return "fieldSet_" + num_page + '-' + num_fieldset;
	};

	this.getClassIdFromName = function(fieldsetname) {
		return fieldsetname + '_fieldset';
	};
	
	// supprimer les fieldsets crees pour deplacer un champ d'une etape a une autre
	this.removeOldFieldset = function() {
		var aFieldsets = self.fieldsets();
		var aNewFieldsets = [];
		for (i = 0; i < aFieldsets.length; i++) {
			var fieldset = aFieldsets[i];
			if (fieldset.move_fieldset == true) {
				var questions = fieldset.questions();
				var nb_fields = 0;
				for (zz = 0; zz < questions.length; zz++) {
					var lines = questions[zz].lines();
					for (zzz = 0; zzz < lines.length; zzz++) {
						var fields = lines[zzz].fieldsStandard();
						if (fields.length != 0) {
							nb_fields++;
						}
					}
				}
				// keep move_fieldset only if they contains at least one field
				if (nb_fields > 0) {
					aNewFieldsets.push(fieldset);
				}
			} else {
				// keep all default fieldsets
				aNewFieldsets.push(fieldset);
			}
		}
		self.fieldsets(aNewFieldsets);
		self.selectedStep().fieldsets(aNewFieldsets);
	};
	
	
	// Click pour changer le champ d'etape : le champ sera déplacé dans le dernier fieldset, de la derniere question, de la derniÃƒÂ¨re ligne
	this.setStepField = function(parent, data, event) {
		if(confirm("Voulez vous changer ce champ d'étape ?"))
		{
			// recherche du champ dans le generique
			var result = this.searchFieldInGeneric(data);
			var lineFoundInGeneric = result.lineFound;
			var fieldFoundInGeneric = result.fieldFound;	
			
			// recherche des fieldsets de l'etape cible
			var aFieldsets = this.getCurrentFieldSets(data);
			
			// si champ trouvé dans le générique de l'étape courante
			if(result.bFoundInGeneric)
			{
				// on cherche la ligne correspondante dans le standard
				aLine = this.searchLineInStandardFromGeneric(aFieldsets, lineFoundInGeneric);
				
				// move the field
				
				var aFieldsStandard = aLine.addField(self.selectedField());
				aLine.fieldsStandard(aFieldsStandard);
				
				this.removeField(self.selectedParentLine(), self.selectedField(), event, false);
				
				// ne pas autoriser le click sur le bouton du formulaire generique
				fieldFoundInGeneric.isEnabled = false;
				
				this.removeOldFieldset();
			} else {
				var fieldset_name = this.getNewFieldsetName(aFieldsets);
				var fieldset_classid = this.getClassIdFromName(fieldset_name);
				
				// create line and adds the field
				var oLine = new Object();
				oLine.name = 'tmp_line_' + self.selectedField().name();
				oLine.hasMoved = false;
				oLine.fieldsStandard = [self.selectedField()];
				var aLine = new Line(oLine ,fieldset_classid, false);
				
				// create question
				var oQuestion = new Object();
				oQuestion.name = 'tmp_question_' + self.selectedField().name();
				oQuestion.hasMoved = false;
				oQuestion.line = aLine;
				
				var aQuestion = new Question(oQuestion, fieldset_classid, false);
				
				// create fieldset
				var oFieldset = new Object();
				oFieldset.name = 'tmp_' + fieldset_name;
				oFieldset.classid = fieldset_classid;
				oFieldset.hasMoved = false;
				oFieldset.question = [aQuestion];
				var aFieldset = new Fieldset(oFieldset, false);
				aFieldset.move_fieldset = true; // mark this fieldset as a new one
								
				this.removeField(self.selectedParentLine(), self.selectedField(), event, false);
				
				aFieldsets.push(aFieldset); 
				
				// ne pas autoriser le click sur le bouton du formulaire generique
				fieldFoundInGeneric.isEnabled = false;
				
				$(".genericFields button").each(function(){
				  if(fieldFoundInGeneric.name() == $(this).attr("data-name"))
				  {
					  $(this).attr('disabled','disabled');   
					  $(this).removeClass('primary');
					  $(this).addClass('info');
				  }
				});
				
				this.removeOldFieldset();
				
			}	
			saveForm('draft',true,false,true);
			self.currentTab(Tabs.ADD_FIELD_TAB);
			return true;
		} else {
			return false;
		}
	};
	
	// click pour modifier le libellé de l'étape a la volée
	this.setStepFieldTitle = function(data, event) {
		//if($(event.target).attr("value") == "")
		//{
		//	alert("Ce champs est vide");
		//	$(event.target).focus();
		//} else {
			self.selectedStep().title($(event.target).attr("value"));
		//}
	};
	
	
	// Focus pour modifier le title dans les 2 langues
	this.setTitleFieldValues = function(field, data, event) {
		//console.log(field.titre);
		
		if(field.titre != null)
		{
			Titletemp = html_entity_decode(strip_tags(field.titre()));
			
			if(Titletemp.length>200)
			{
				Titletemp=Titletemp.substring(0,200)+'...'
			}
			
			field.TitleStripped(Titletemp);
		}
		/*
		var aTitle = [];
		
		//field.parentLine(field.title());
		var aTitleField = field.title();
		if(typeof(aTitleField) != "object")
		{
			aTitleField = aTitleField();
		} 
		
		
		for(var i=0;i<aTitleField.length;i++)
		{
			var name = ""
			if(aTitleField[i]['id'] == data.id)name = data.title;
			else name = aTitleField[i]['title'];
			
			aTitle.push({id: aTitleField[i]['id'],isDefaultLanguage : aTitleField[i]['isDefaultLanguage'],title : name, titleStripTagged:strip_tags(name)});
		}
		
		field.title(aTitle);*/
	

	};
	
	// Focus out pour "enregistrer" dans le tableau de l'étape le changement effectué sur le champ
	this.setStepFieldsChoicesValues = function(field, data, event) {
		var aChoicesField = field.choices();
		if(typeof(aChoicesField) != "object")
		{
			aChoicesField = aChoicesField();
		} 
		
		var aChoices = [];
		for(var i=0;i<aChoicesField.length;i++)
		{
			
			aChoices.push(aChoicesField[i]);
		}

		field.choices(aChoices);

		
	};
	
	// Focus out pour "enregistrer" dans le tableau de l'étape le changement effectué sur le champ
	this.setStepFieldsValues = function(element) {
		
		
		//TODO if necessary
		//console.log(element);
		/*
		if(element == "")
		{
			alert("Ce champs est vide");
			$(event.target).focus();
		}
		*/
	};
	
	// Click sur l'onglet de paramétrage de l'étape
	this.clickStepParam = function(data, event) {
		self.displayStepsForm(true);
		
		self.stepTitle(data.title());
		self.stepID(data.id);
		
		self.selectedStep(data);
		getStepSelected(self.selectedStep());
		
		self.currentTabNiv1(TabsNiv1.STEPS_TAB);
		self.currentTabNiv2(TabsNiv2.PARAM_TAB);
		
		
		self.displayStepsGeneric(false);
		
		self.displayStepParam(true);
		self.displayFields(false);
		self.displayFieldsTab(false);

		
	};
		
	// Click sur l'onglet de paramétrage des champs de l'étape
	this.stepTitle = ko.observable("");
	
	this.setStepFields = function(data) {
	  self.currentTabNiv1(TabsNiv1.FORM_TAB);
	  self.currentTabNiv2(TabsNiv2.FIELDS_TAB);
	  self.currentTab(Tabs.ADD_FIELD_TAB);
	  
	  self.displayFields(true);
	  self.displayFieldsTab(true);
	  self.displayStepsGeneric(false);
	  self.displayStepsForm(false);
	  self.displayStepParam(false);

	  var iStep = data.id;

	  self.selectedStep(data);
	  getStepSelected(self.selectedStep());
	  this.stepTitle(self.selectedStep().title());
	  
	  var aStepList = self.stepsList();
	  var aStepListGeneric = self.stepsListGeneric();
	  
	  var aFieldsets = [];
	  var aStepConfiguration = [];
	  var aTagsUnderQuestion = [];
	  for(var i=0;i<aStepList.length;i++)
	  {
		  if(aStepList[i]['id'] == iStep)
		  {
			  aFieldsets = aStepList[i]['fieldsets'];
			  aStepConfiguration = aStepList[i]['stepConfiguration'];
			  aTagsUnderQuestion = aStepList[i]['tagsUnderQuestion']();
		  }
	  }
	  
	  var aFieldsetsGeneric = [];
	  var aStepConfigurationGeneric = [];
	  for(i=0;i<aStepListGeneric.length;i++)
	  {
		  if(aStepListGeneric[i]['itkg_code'] == data.itkg_code)
		  {
			  aFieldsetsGeneric = aStepListGeneric[i]['fieldsetsGeneric'];
			  aStepConfigurationGeneric = aStepListGeneric[i]['stepConfigurationGeneric'];

		  }
	  }
	  
	  this.stepConfigurationGeneric(aStepConfigurationGeneric);
	  this.stepConfiguration(aStepConfiguration);
	  
	  this.fieldsetsGeneric(aFieldsetsGeneric);
	  this.fieldsets(aFieldsets());
	  
	  this.tagsUnderQuestion(aTagsUnderQuestion);
	  this.displayTagsUnderQuestion(aTagsUnderQuestion.length > 0);
	};
	
	// Click sur l'onglet de paramétrage de l'ordre des étapes
	this.clickStepOrder = function() {
		self.currentTabNiv1(TabsNiv1.STEPS_TAB);
		self.currentTabNiv2(TabsNiv2.ORDER_TAB);
		
		self.displayStepsGeneric(true);
		self.displayFields(false);
		self.displayFieldsTab(false);
		self.displayStepsForm(true);
		self.displayStepsTab(true);
		self.displayStepParam(false);

	};
	
	this.clickForm = function() {

		self.currentTabNiv1(TabsNiv1.STEPS_TAB);
		self.currentTabNiv2(TabsNiv2.ORDER_TAB);
		
		self.displayStepsForm(true);
		$(".tabsNiv2").show();
		$("#doPublishBtn").show();
		$("#doSaveBtn").show();
		$("#doResetBtn").show();
		
		self.displayStepsGeneric(true);
		self.displayFields(false);
		self.displayFieldsTab(false);
		self.displayStepsForm(true);
		self.displayStepsTab(true);
		self.displayStepParam(false);
		
	};

	this.clickVersions = function() {
		
		
		$(".tabsNiv2").hide();
		$("#doPublishBtn").hide();
		$("#doSaveBtn").hide();
		$("#doResetBtn").hide();
		
		self.displayStepsGeneric(false);
		self.displayFields(false);
		self.displayFieldsTab(false);
		
		//self.displayStepsTab(false);
		self.displayStepParam(false);
		self.displayStepsForm(false);
	};
  

  this.selectedField = ko.observable(null);
  this.selectedFieldAlternativ = ko.observableArray(null);
  this.selectedParentLine = ko.observable(null);
  
  this.versions = ko.observable('true');
  this.steps = ko.observable('true');


  
  this.displayCommonForm = ko.observable(true);
  this.selectField = function(parent, field) {
	
	  // click sur un autre champ alors qu'on vient de modifier le libellÃ© d'un autre
	  if(self.selectedField() != null)
	  {
		  //var aTitleField = self.selectedField().titre();
		  var  title;
		  $('.titleField').each(function(){
			 
			  
			  /*for(var i=0;i<aTitleField.length;i++)
			  {
				  
				  if($(this).attr("id") == aTitleField[i]['id'])
				  {

					  if($(this).val() != aTitleField[i]['title'])
					  {						  
						  title = {id: aTitleField[i]['id'],isDefaultLanguage : aTitleField[i]['isDefaultLanguage'],title : $(this).val(), titleStripTagged:strip_tags($(this).val())};  
					  }
				  }
			  }*/
			  
			  title =$(this).val();
			 
		  });
		  if(typeof(title) != "undefined")
		  {
			  this.setTitleFieldValues(self.selectedField(), title);
		  }
		  
	  }
	  
	  //
	  
	  // si le champ peut changer d'etape, Dans la liste on prelectionne le radio associe a l'etape
	  var aStepListGeneric = this.stepsList();
	  for(var i=0;i<aStepListGeneric.length;i++)
	  {
		  
		  if(aStepListGeneric[i]['id'] == self.selectedStep().id)
		  {
			  aStepListGeneric[i]['isSelected'] = true;
		  } else {
			  aStepListGeneric[i]['isSelected'] = false;
		  }
	  }
	  self.stepsList(aStepListGeneric);
  
	  switch(field.type())
	  {
	  	case 'html':
	  		if(field.bHtmlLock)
	  			{
	  			return false;
	  			}
		  case 'toggle':
			  if(field.bHtmlLock)
			  {
				  return false;
			  }
	  	case 'button':
	  		self.displayCommonForm(false);
	  		break;
	  	case 'hidden':
	  		// ParamÃ©trage des field hidden impossible
	  		return false;
		default:
			self.displayCommonForm(true);
	  }

	  self.currentTabNiv1(TabsNiv1.FORM_TAB);
	  self.currentTabNiv2(TabsNiv2.FIELDS_TAB);
	  self.currentTab(Tabs.FIELD_SETTINGS_TAB);
	  
	  // search for alternative fields to display (phone number)
	  // (here, parent is the question object)
	  var aFieldsAlternativ = [];
	  if ('lines' in parent) {
		  for (var iii = 0; iii < parent.lines().length; iii++) {
			  var line = parent.lines()[iii];
			  
			  for(var zz=0;zz < line.fieldsStandard().length;zz++)
			  {  
				  if( line.fieldsStandard()[zz]['isAlternativ'])
				  {
					  line.fieldsStandard()[zz]['namehref'] = "#" +  line.fieldsStandard()[zz]['name']();
					  aFieldsAlternativ.push( line.fieldsStandard()[zz]);
				  }
			  }		  
		  }
	  }
	  self.selectedField(field);
	  if(aFieldsAlternativ.length > 0)
	  {
		  self.selectedFieldAlternativ(aFieldsAlternativ);
	  }
	  
	  
	  //on stocke le parent pour pouvoir changer le field de step par la suite
	  self.selectedParentLine(parent);

	  $("html, body").animate({ scrollTop: 0 });
	 
  };

  this.selectedCompoAv = ko.observable(null);
  this.selectCompoAv = function(parent, data, event)
  {
	  return; 
	  /*
	  self.selectedCompoAv(data);
	  
	  self.currentTabNiv1(TabsNiv1.FORM_TAB);
	  self.currentTabNiv2(TabsNiv2.FIELDS_TAB);
	  self.currentTab(Tabs.FIELD_SETTINGS_TAB);
	  
	  $("html, body").animate({ scrollTop: 0 });
	  */
  };
  
  // Ajoute un flag "hasMoved" sur la fieldset/ligne/question/field pour logger les changements effectués
  this.afterMovedFieldset = function(data, event, parent)
  {  	  
	  data.item.hasMoved = true;
	  data.item.fieldsetIsCollapsed(true);
	  
	  var aStepsList = this.stepsList();
	  var selectedStep = this.selectedStep();
	  
	  for(var i=0;i<aStepsList.length;i++)
		{
		  if(aStepsList[i]['id'] == selectedStep.id)
		  {
			  aStepsList[i]['fieldsets'](data.targetParent());
		  }
		}
	  
	  
  };
  
  this.afterMovedQuestion = function(data, event, parent)
  {
	  data.item.hasMoved = true;
	  parent.questions(data.targetParent());
  };
  
  this.beforeMoveLine = function(arg) {
	  if (arg.item.name == 'line_USR_PHONE_TYPE') {
		  arg.cancelDrop = true;	  
	  }
  };
  
  this.afterMovedLine = function(data, event, parent)
  {
	 	 	 
	  data.item.hasMoved = true;
	  parent.lines(data.targetParent());
	  
	  
	  /*Alert listener*/
	  var sCode = data.item.name;
	  sCode = sCode.substr(5);
	  
	  $.each( ALISTENED, function( key, value ) {
	 	if(key==sCode)
	 	{
	 			 		
	 		$("#dialog-confirm").html(lang['BOFORMS_LISTENER_LISTENED']);
		    
		    $("#dialog-confirm").dialog({
		        resizable: false,
		        modal: true,
		        title: "Information",
		        height: 150,
		        width: 400,
		        buttons: {
		            "Ok": function () {
		                $(this).dialog('close');
		                
		            }
		        }
		    });
	 			 		
	 		return false;
	 	}	
	  });
	
	  $.each( ALISTENING, function( key, value ) {
		 	if(key==sCode)
		 	{
		 		
		 		$("#dialog-confirm").html(lang['BOFORMS_LISTENER_LISTENING']);
			    
			    $("#dialog-confirm").dialog({
			        resizable: false,
			        modal: true,
			        title: "Information",
			        height: 250,
			        width: 400,
			        buttons: {
			            "Ok": function () {
			                $(this).dialog('close');
			                
			            }
			        }
			    });
		 		return false;
		 	}	
		  });
	  /***/
  };
  
  this.afterMoved = function(data, event, parent) {
  	data.item.hasMoved = true;
  	parent.fieldsStandard(data.targetParent());
  };
 
  this.addFieldAlternativ  = function(parent, data, event) {
	 var aFieldsets = this.fieldsets();
	 var aFields = [];
	 
	 var aFieldsGeneric = parent['fieldsGeneric'];
	 for(var i=0;i<aFieldsGeneric.length;i++)
	 {
		 if(aFieldsGeneric[i]['isAlternativ'])
		 {
			 aFieldsGeneric[i]['isEnabled'] = true;
			 aFields.push(aFieldsGeneric[i]);
		 }
	 }
	 
	 
	 
	 for(var i=0;i<aFieldsets.length;i++)
	 {
		 var aQ = aFieldsets[i]['questions']();
		 for(var j=0;j<aQ.length;j++)
		 {
			 var aL = aQ[j]['lines']();
			 for(var k=0;k<aL.length;k++)
			 {
				 if(aL[k]['name'] == parent['name'])
				 {
					 
					 for(var m=0;m<aFields.length;m++)
					 {
						var aFieldsStandard = aL[k].addField(aFields[m]);
					 	aL[k]['fieldsStandard'](aFieldsStandard);
					 }
				 }
			 }
		 }
	 }
	 $(event.target).attr('disabled','disabled');  
	 $(event.target).removeClass('primary');
	 $(event.target).addClass('info');
  };
  
  this.addField = function(parent, data, event) {
	  
	 var aFieldsets = this.fieldsets();
	 for(var i=0;i<aFieldsets.length;i++)
	 {
		 var aQ = aFieldsets[i]['questions']();
		 for(var j=0;j<aQ.length;j++)
		 {
			 var aL = aQ[j]['lines']();
			 for(var k=0;k<aL.length;k++)
			 {
				
				 if(aL[k]['name'] == parent['name'])
				 {
					 
					 var aFieldsStandard = aL[k].addField(data);
					 aL[k]['fieldsStandard'](aFieldsStandard);
				 }
			 }
		 }
	 }
	 
	 
	 var aFieldsetsGeneric = this.fieldsetsGeneric();
	 for(i=0;i<aFieldsetsGeneric.length;i++)
	 {
		 var aQ = aFieldsetsGeneric[i]['questionsGeneric']();
		 for(var j=0;j<aQ.length;j++)
		 {
			 var aL = aQ[j]['linesGeneric'];
			 for(var k=0;k<aL.length;k++)
			 {
				 var aF = aL[k]['fieldsGeneric'];
				
				 for(var m=0;m<aF.length;m++)
				 {
					 
					 if($(event.target).attr('data-name') == aF[m]['name']())
					 {
						 aF[m]['isEnabled'] = false;
					 }
				 }
			 }
		 }
	 }
	 this.fieldsetsGeneric(aFieldsetsGeneric);
	 
	 
	 if (data.name() == 'connector_brandid' || data.name() == 'connector_facebook' || data.name() == 'html_HTML_loginPopup' || data.name() == 'GET_MYCITROEN') {
		 if (is_dde == '1') {
		 	this.allowRemovalForFields(true);
		 } else {
			this.allowRemovalForFields(false);
		 }
	 }	
	 
    $(event.target).attr('disabled','disabled');   
    $(event.target).removeClass('primary');
    $(event.target).addClass('info');

  };

  this.removeFieldAlternativ = function(parent, data, event, disabled) {
	  var aFieldsStandard = parent.fieldsStandard();
	  
	  var aFieldsAlternativ = [];
	  for(var i=0;i<aFieldsStandard.length;i++)
	  {
		  if(aFieldsStandard[i]['isAlternativ'])
		  {
			  aFieldsAlternativ.push(aFieldsStandard[i]);
		  }
		  
	  }
	  var aFields = [];
	  for(var i=0;i<aFieldsAlternativ.length;i++)
	  {
		  aFields = parent.removeField(aFieldsAlternativ[i]);
	  }
	  
	  parent.fieldsStandard(aFields);

	  self.currentTabNiv1(TabsNiv1.FORM_TAB);
	  self.currentTabNiv2(TabsNiv2.FIELDS_TAB);
	  self.currentTab(Tabs.ADD_FIELD_TAB);  
		
	  
	  
	  $(".genericFields button").each(function(){
	    	
		  
		  if(data.name() == $(this).attr("data-name"))
		  {
				$(this).removeAttr('disabled');
				$(this).removeClass('info');
				$(this).addClass('primary');
		  }
	  });
  };
  
  
  // Removes the given field. 
  this.removeField = function(parent, data, event, disabled) {
	  var fieldsStandard = parent.removeField(data);
	  parent.fieldsStandard(fieldsStandard);
	  
	  self.currentTabNiv1(TabsNiv1.FORM_TAB);
	  self.currentTabNiv2(TabsNiv2.FIELDS_TAB);
	  self.currentTab(Tabs.ADD_FIELD_TAB);  
	 
	  var aStepListGeneric = this.stepsListGeneric();
	  // boucle sur les etapes generique
	  for(var iii=0;iii<aStepListGeneric.length;iii++) {
		  aFieldsetsGeneric = aStepListGeneric[iii].fieldsetsGeneric;
		  for(var i=0;i<aFieldsetsGeneric.length;i++)
		  {
			 var aQ = aFieldsetsGeneric[i]['questionsGeneric']();
			 for(var j=0;j<aQ.length;j++)
			 {
				 var aL = aQ[j]['linesGeneric'];
				 for(var k=0;k<aL.length;k++)
				 {
					 var aF = aL[k]['fieldsGeneric'];
					
					 for(var m=0;m<aF.length;m++)
					 {
						 
						 if(data.name() == aF[m]['name']())
						 {
							 aF[m].isEnabled = true;
							 
						 }
					 }
				 }
			 }
		  }
		  
	  } 
	  this.stepsListGeneric(aStepListGeneric);
	  
	  $(".genericFields button").each(function(){
		  if(data.name() == $(this).attr("data-name"))
		  {
				$(this).removeAttr('disabled');
				$(this).removeClass('info');
				$(this).addClass('primary');
				
		  }
	  });
	  this.removeOldFieldset(); // supprime les fieldset crees quand on deplace un champ dans une autre etape (si ils sont vides)
}



  // allow the removal of a few fields ( GET_MYCITROEN etc.)
  this.allowRemovalForFields = function(activate) {
	  var aFieldsets = this.fieldsets();
		 for(var i=0;i<aFieldsets.length;i++)
		 {
			 var aQ = aFieldsets[i]['questions']();
			 for(var j=0;j<aQ.length;j++)
			 {
				 var aL = aQ[j]['lines']();
				 for(var k=0;k<aL.length;k++)
				 {
					var thefields = aL[k]['fieldsStandard']();
					for (zz = 0; zz < thefields.length; zz++) {
						if (thefields[zz].name() == 'GET_MYCITROEN' || thefields[zz].name() == 'connector_brandid' || 
								thefields[zz].name() == 'connector_facebook' || thefields[zz].name() == 'html_HTML_loginPopup') {
							if (activate == true) {							
								thefields[zz].field_is_removable(true);
							} else {
								thefields[zz].field_is_removable(false);
							}
						}
					}
					 
				 }
			 }
		 }
  }; 
  
var getStepSelected = function(aSelectedField)
{
	for(var i=0;i<FORM_STEPS.length;i++)
	{
		if(FORM_STEPS[i]['id'] == aSelectedField['id'])
		{
			FORM_STEPS[i]['isSelected'] =   ko.observable(aSelectedField['id']);
			
		} else {
			FORM_STEPS[i]['isSelected'] =   ko.observable(false);
		}
		FORM_STEPS[i]['id'] =   FORM_STEPS[i]['id'];
		if(typeof(FORM_STEPS[i]['title']) == 'string')
		{
			FORM_STEPS[i]['title'] =   ko.observable(FORM_STEPS[i]['title']);
		} else {
			FORM_STEPS[i]['title'] =   FORM_STEPS[i]['title'];
		}
		
		
	}
}


var getDefaultDataForType = function(name) {

	for(var i=0; i<GENERIC_TYPES.length;i++)
	{
		if(name == GENERIC_TYPES[i]['name'])
		{
			return GENERIC_TYPES[i];
		}
	}
}



};


/***************************************************************************************************************************************/



var Question = function(question, fieldsetClassID, isGeneric) {
	var self = this;
	
	self.name = question['name'];
   	self.containmentSelector = "#"+fieldsetClassID;
    self.hasMoved = question['hasMoved'];
    self.isNewQuestion = false; // added for the addQuestion functionality
    
    if (question['isNewQuestion']) {
    	self.isNewQuestion = true;
    }    
    
    if(question['template'])
    {

    	if(DEVICE_ID == '1')
    	{
    		self.template = '/modules/boforms/images/'+ BRAND_ID +'/mobile/'+question['template']+'.png';
    	}else{
    		self.template = '/modules/boforms/images/'+ BRAND_ID +'/' + question['template']+'.png';
    	}
    	
    } else {
    	self.template = false;
    }
    
    var aLines = [];
    var aLinesGeneric = [];
    
    if(typeof(question['lines']) != 'undefined' && typeof(question['lines']) != 'function')
    {
    
	    for(var i=0;i<question['lines'].length;i++)
		{
	    	aLines.push(new Line(question['lines'][i], fieldsetClassID, false));
	    	aLinesGeneric.push(new Line(question['lines'][i], fieldsetClassID, true));
		}
	    
	    if(!isGeneric)
    	{
	    	self.lines = ko.observableArray(aLines);
    	} else {
    		 self.linesGeneric = aLinesGeneric;
    	}
    } else if(!isGeneric) {
    	self.lines = ko.observableArray([question.line]);
    } else {
   		self.linesGeneric = question.linesGeneric;
   	}
   
    if (!isGeneric) {
    	self.displayQuestion = ko.computed(function() {
    		if (self.isNewQuestion) {
    			return true;
    		}
    		
 	    	var nb_line_hidden = 0;
 	    	for (a = 0; a < self.lines().length; a++) {
 	    		if (self.lines()[a].displayLine() == false) {
 	    			nb_line_hidden++;
 	    		}
 	    	}
 	    	return (self.template || (self.lines().length > 0 &&  nb_line_hidden != self.lines().length));
 	    });
    	
    	self.hasNoLines = ko.computed(function() {
    		return self.lines().length == 0;
    	});
    } else {
    	self.displayQuestion = ko.computed(function() {
 	    	var nb_line_hidden = 0;
 	    	for (a = 0; a < self.linesGeneric.length; a++) {
 	    		if (self.linesGeneric[a].displayLine() == false) {
 	    			nb_line_hidden++;
 	    		}
 	    	}
 	    	return (self.template || (self.linesGeneric.length > 0 &&  nb_line_hidden != self.linesGeneric.length));
 	    });
    }
    
};

var Line = function(line, fieldsetClassID, isGeneric) {
	var self = this;
	
	self.name = line.name;
    //this.prevent = !!line.prevent;
    self.hasMoved = line.hasMoved;
    
    var aFieldsGeneric = [];
    var aFieldsStandard = [];
 
    if(typeof(line['aFieldsGeneric']) != 'undefined')
    {
	    for(var i=0;i<line['aFieldsGeneric'].length;i++)
		{
	  		aFieldsGeneric.push(new Field(line['aFieldsGeneric'][i], true));
		}
	    this.fieldsGeneric = aFieldsGeneric;
    } else {

    	this.fieldsGeneric = line.fieldsGeneric;
    }
    
    if(!isGeneric)
	{
	    if(typeof(line['aFieldsStandard']) != 'undefined')
	    {
		    for(var i=0;i<line['aFieldsStandard'].length;i++)
			{
		    	aFieldsStandard.push(new Field(line['aFieldsStandard'][i], false));
			}
		    self.fieldsStandard = ko.observableArray(aFieldsStandard);
	    } else {
	    	self.fieldsStandard = ko.observableArray(line.fieldsStandard);
	    }
	    
	    // we dont want to display empty lines or hidden fields
	    self.displayLine = ko.computed(function() {
	    	var nb_fields_hidden = 0;
	    	var nb_html_locked = 0;
	    	var nb_field_alternative_hidden = 0;
	    	for (a = 0; a < self.fieldsStandard().length; a++) {
	    		if (self.fieldsStandard()[a].isAlternativ && self.fieldsStandard()[a].showAlternative() == 0) {
	    			// on affiche le premier champ téléphone mais pas les autres pour lesquels showAlternative vaut 0
	    			nb_field_alternative_hidden++;
	    		} else if (self.fieldsStandard()[a].type() == 'hidden') {
	    			nb_fields_hidden++;
	    		} else if (self.fieldsStandard()[a].type() == 'connector' && self.fieldsStandard()[a].display() == 0) {
	    			nb_fields_hidden++;	
	    		} else if (self.fieldsStandard()[a].type() == 'html' && self.fieldsStandard()[a].bHtmlLock == true) {
	    			nb_html_locked++;
	    		}
	    	}
	    	return (self.fieldsStandard().length > 0 && ((nb_fields_hidden + nb_html_locked + nb_field_alternative_hidden) != self.fieldsStandard().length));
	    });
	}  else {
		 // we dont want to display empty lines or hidden fields
	    self.displayLine = ko.computed(function() {
	    	var nb_fields_hidden = 0;
	    	var nb_html_locked = 0;
	    	var nb_field_alternative_hidden = 0;
	    	for (a = 0; a < self.fieldsGeneric.length; a++) {
	    		if (self.fieldsGeneric[a].isAlternativ && self.fieldsGeneric[a].showAlternative() == 0) {
	    			// on affiche le premier champ téléphone mais pas les autres pour lesquels showAlternative vaut 0
	    			nb_field_alternative_hidden++;
	    		} else if (self.fieldsGeneric[a].type() == 'hidden') {
	    			nb_fields_hidden++;
	    		} else if (self.fieldsGeneric[a].type() == 'connector' && self.fieldsGeneric[a].display() == 0) {
	    			nb_fields_hidden++;	
	    		} else if (self.fieldsGeneric[a].type() == 'html' && self.fieldsGeneric[a].bHtmlLock == true) {
	    			nb_html_locked++;
	    		}
	    	}
	    	return (self.fieldsGeneric.length > 0 && ((nb_fields_hidden + nb_html_locked + nb_field_alternative_hidden) != self.fieldsGeneric.length));
	    });
	}
    
    this.containmentSelector = "#"+fieldsetClassID;
    

    
    this.addField = function(data) {
    	
        if (data.type === undefined) {
          return false;
        }
       
        aFieldsStandard.push(data);
        return aFieldsStandard;
    }
    
    this.removeField = function(data) {
    	
    	var iIndex = 0;
    	var aFieldsStandard = this.fieldsStandard();

    	for(var i=0;i<aFieldsStandard.length;i++)
		{
    		if(data.name() == aFieldsStandard[i]['name']())
			{
    			iIndex = i;
			}
		}
    	
    	aFieldsStandard.splice(iIndex, 1);

        return aFieldsStandard;
      
    }
    
    
};


var DatePicker = function(datas) {
    this.dateStart = ko.observable(datas['dateStart']);
    this.dateEnd = ko.observable(datas['dateEnd']);
    
    this.openingStart = ko.observable(datas['openingStart']).extend({validateTime: ""});
    this.openingEnd = ko.observable(datas['openingEnd']).extend({validateTime: ""});
    
    this.format = ko.observable(datas['format']);
    this.hourlabel = ko.observable(datas['hourlabel']);
    this.libeletEnumeration = ko.observable(new TextEnumeration(datas['libeletEnumeration']));
    this.dayEnumeration = ko.observable(new TextEnumeration(datas['dayEnumeration']));
    this.monthEnumeration = ko.observable(new TextEnumeration(datas['monthEnumeration']));
    this.forbiddenDays = ko.observable(new FordiddenEnumeration(datas['forbiddenDays']));
};

var FordiddenEnumeration = function(datas) {
	this.day = ko.observable(new ForbiddenElements(datas['day'], 'day'));
	this.month = ko.observable(new ForbiddenElements(datas['month'], 'month'));
	this.year = ko.observable(new ForbiddenElements(datas['year'], 'year'));
	
	this.date = ko.observable(new ForbiddenElements(datas['date'], 'date'));
	this.weekday = ko.observable(new ForbiddenElements(datas['weekday'], 'weekday'));
	this.period = ko.observable(new ForbiddenElements(datas['period'], 'period'));
	this.recursiveDay = ko.observable(new ForbiddenElements(datas['recursiveDay'], 'recursiveDay'));
};

var ForbiddenElements = function(datas, type) {
	var items = [];
	if (typeof(datas) !== 'undefined') {
		for (i = 0; i < datas.length; i++) {
			if (type == 'weekday') {
				items.push(new ForbiddenWeekdayElement(datas[i]));
			} else {
				items.push(new ForbiddenElement(datas[i]));	
			}
		}
	}

	this.addForbidden = function(parent, type) {
		if (type == 'weekday') {
			parent.items.push(new ForbiddenWeekdayElement("mon"));
		}	else {
			parent.items.push(new ForbiddenElement(""));
		}
	};

	this.removeForbidden = function(parent, data) {
		if(typeof(parent.items()) == "object")
		{
			parent.items.remove(data);
		} else {
			var items = parent.items();
			items.remove(data);
		}
 	};

	this.items = ko.observableArray(items);
};

var ForbiddenElement = function(datum) {
	this.value = ko.observable(datum);
};

var ForbiddenWeekdayElement = function(datum) {
	var datum_tbl = [];
	datum_tbl.push('mon');
	datum_tbl.push('tue');
	datum_tbl.push('wed');
	datum_tbl.push('thu');
	datum_tbl.push('fri');
	datum_tbl.push('sat');
	datum_tbl.push('sun');
	
	this.value = ko.observable(datum);
	
	this.weekdayoptions = ko.observableArray(datum_tbl);
};

var TextEnumeration = function(datas) {
	this.id   = ko.observable(datas['id']);
	
	var items = [];
	for (i = 0; i < datas['items'].length; i++) {
		items.push(new TextEnumerationItem(datas['items'][i]));
	}
	this.items = ko.observableArray(items);
	
};

var TextEnumerationItem = function(datas) {
	this.value = ko.observable(datas['value']);
	this.id = ko.observable(datas['id']);
};

var ChoiceItem = function(choice) {
	var self = this;
	this.id = ko.observable(choice.id);
	this.choice = ko.observable(choice.choice);
	this.choiceLabel = ko.observable(choice.choiceLabel);	
}

// PATCH TEMPORAIRE JIRA 710 
var ChoiceItemRadio = function(theparent, choice) {
	var self = this;
	var theparent = theparent;
	
	this.id = ko.observable(choice.id);
	this.choice = ko.observable(choice.choice);
	this.choiceLabel = ko.observable(choice.choiceLabel);
	
	if (choice.actual == 'true') {
		this.selectedValue = ko.observable(choice.choiceLabel);
	} else {
		this.selectedValue = ko.observable('');
	}
	
	this.selectedValue.subscribe(function (selectedRadioText) {
		if (selectedRadioText != '') {
			theparent.updateValues(selectedRadioText, self);
		}
	});
	
	this.selected = ko.computed(function() {
		return (this.selectedValue() == this.choiceLabel()); 
    }, this); 	
		
}

var ListChoiceItemRadio = function(field) {
	var self = this;
	
	this.choicesRadios = ko.observableArray([]);
	
	if (field.choices_radios) {
    	for (cptCR = 0; cptCR < field.choices_radios.length; cptCR++) {
    		tblForRadios = ko.observableArray([]);
    		
    		// creation d'une liste de boutons radios
    		var tbl_id_seen = [];
    		for (cptCR_radio = 0; cptCR_radio < field.choices_radios[cptCR].length; cptCR_radio++) {
    			// interdire deux id identiques selectionnes
    			for (z = 0; z < tbl_id_seen.length; z++) {
					if (tbl_id_seen[z] == field.choices_radios[cptCR][cptCR_radio].id) {
						field.choices_radios[cptCR][cptCR_radio].selected = 'false';
					} 					
				}
    			// stockage id selectionnes
    			if (field.choices_radios[cptCR][cptCR_radio].selected == 'true') {
					tbl_id_seen.push(field.choices_radios[cptCR][cptCR_radio].id);
				}
    			    			
    			tblForRadios.push(new ChoiceItemRadio(this, field.choices_radios[cptCR][cptCR_radio]));
    		}
    		
    		this.choicesRadios.push(tblForRadios);
    	}
    }
	
	this.updateValues = function(selectedRadioText, the_choice) {
		for (i = 0; i < self.choicesRadios().length;i++) {
			var listChoices =  self.choicesRadios()[i];
			for (j = 0; j < listChoices().length; j++) {
				if (listChoices()[j].id() == the_choice.id()) {
					if (listChoices()[j].choiceLabel() != selectedRadioText) {
						listChoices()[j].selectedValue('');
					}
				}  
			}
		}
	}

}

// FIN PATCH JIRA 710

var Field =  function(field, isGeneric) {
	var self = this;
	this.preventSort = !!field.preventSort;
	this.listener = field.listener;
	this.bHtmlLock = field.bHtmlLock;
	this.bListened = field.bListened;
	
	if(typeof(field.align) == 'string') {
		this.align = ko.observable(field.align);
	} else {
		this.align = field.align;
	}
	
	if(typeof(field.name) == 'string') this.name = ko.observable(field.name);
	else this.name = field.name;
	
	this.instructions = ko.observable(field.instructions);
	this.content = ko.observable(field.content);

	
	if (is_dde == '0' && isGeneric == true && (field.name == 'GET_MYCITROEN' || field.name == 'html_HTML_loginPopup')) {
		this.isEnabled = ko.observable(false);
 	} else {
		if(typeof(field.isEnabled) == 'string') this.isEnabled = ko.observable(field.isEnabled);
		else this.isEnabled = ko.observable(field.isEnabled);
 	}
	
	if(typeof(field.is_required) == 'string') this.is_required = ko.observable(field.is_required);
	else this.is_required = ko.observable(field.is_required);
	
	if(typeof(field.regexp) == 'string') this.regexp = ko.observable(field.regexp);
	else this.regexp = field.regexp;
		
	if(typeof(field.regexp_msg) == 'string') this.regexp_msg = ko.observable(field.regexp_msg);
	else this.regexp_msg = field.regexp_msg;
	
	if(typeof(field.required_msg) == 'string') this.required_msg = ko.observable(field.required_msg);
	else this.required_msg = ko.observable(field.required_msg);
	
	if(typeof(field.type) == 'string') this.type = ko.observable(field.type);
	else this.type = field.type;
	
	if(typeof(field.hasMoved) == 'string') this.type = ko.observable(field.hasMoved);
	else this.hasMoved = field.hasMoved;
	
	if(typeof(field.required_central) == 'string') this.required_central = ko.observable(field.required_central);
	else this.required_central = field.required_central;
	
	if(typeof(field.change_etape) == 'string') this.change_etape = ko.observable(field.change_etape);
	else this.change_etape = field.change_etape;
	
	if(typeof(field.isDisplayed) == 'string') this.isDisplayed = ko.observable(field.isDisplayed);
	else this.isDisplayed = ko.observable(field.isDisplayed);
	
	if(typeof(field.isAlternativ) == 'string') this.isAlternativ = ko.observable(field.isAlternativ);
	else this.isAlternativ = field.isAlternativ;
	
	if ('showAlternative' in field) {
		this.showAlternative = ko.observable(field.showAlternative);
	} else {
		this.showAlternative = ko.observable(0);
	}
	
	// default_value_new = valeur du champ texte
	// default_value = valeur du champ hidden et du place_hoder
	this.default_value = ko.observable(field.default_value);
		
	this.titre = ko.observable(field.titre);
	
	if(typeof(field.TitleStripped) == 'string') this.TitleStripped = ko.observable(field.TitleStripped);
	else this.TitleStripped = field.TitleStripped;
	
	// quelques cas particulier pour le titre du champ à droite
	this.displayTitleStripped = true;
	
	
	
	if(this.titre()==null)
	{
		if ((field.type == 'connector' || field.type == 'radio' || field.type == 'dropdown') && (field.name != 'MULTIFORMS_CHOICE')) {
			this.displayTitleStripped = true;
			this.TitleStripped = lang['BOFORMS_LABEL_' + field.name];
		} else if (field.type == 'checkbox' || (field.name == 'MULTIFORMS_CHOICE' || field.name == 'SBS_USER_OFFER' || field.name == 'REQUEST_CALLBACK' || field.name == 'GET_MYCITROEN' || field.name == 'SBS_COM_OFFER' || field.name == 'SBS_USR_OFFER' || field.name == 'REQUEST_INTEREST_FINANCING' || 
					field.name == 'REQUEST_INTEREST_INSURANCE' || field.name == 'REQUEST_INTEREST_SERVICE' || field.name == 'LEGAL_MENTION_ANSWER' || field.name == 'LEGAL_MENTION_CPP_ANSWER' )) {
			this.displayTitleStripped = false;
		}
	} else {
		
		this.displayTitleStripped = true;
	}
	
	// traduction des boutons ajouter un champ a gauche
    for (cc = 0; cc < field.title.length; cc++) {
    	if (field.title[cc].titleStripTagged == 'SBS_USR_OFFER_2' && is_lp == 1) {
    		field.title[cc].titleStripTagged = lang['BOFORMS_LABEL_' + field.title[cc].titleStripTagged + '_LP'];
    	} else if (field.title[cc].titleStripTagged == 'SBS_NWL_NEWS' ||  field.title[cc].titleStripTagged == 'connector_brandid' || field.title[cc].titleStripTagged == 'connector_facebook' || field.title[cc].titleStripTagged == 'SBS_USR_OFFER_2' || field.title[cc].titleStripTagged == 'MULTIFORMS_CHOICE' || field.title[cc].titleStripTagged == 'SBS_USER_OFFER' || field.title[cc].titleStripTagged == 'REQUEST_CALLBACK' || field.title[cc].titleStripTagged == 'SBS_COM_OFFER' || field.title[cc].titleStripTagged == 'SBS_USR_OFFER' || field.title[cc].titleStripTagged == 'REQUEST_INTEREST_FINANCING' ||
    		field.title[cc].titleStripTagged == 'SBS_COM_OFFER_2' || field.title[cc].titleStripTagged == 'SBS_USR_OFFER_2' || 
    		field.title[cc].titleStripTagged == 'LEGAL_MENTION_CPP_ANSWER' || field.title[cc].titleStripTagged == 'GET_MYCITROEN' || field.title[cc].titleStripTagged == 'REQUEST_INTEREST_INSURANCE' || field.title[cc].titleStripTagged == 'REQUEST_INTEREST_SERVICE' || field.title[cc].titleStripTagged == 'UNS_NWS_CPP_MOTIF' || field.title[cc].titleStripTagged == 'LEGAL_MENTION_ANSWER') { 
    		field.title[cc].titleStripTagged = lang['BOFORMS_LABEL_' + field.title[cc].titleStripTagged];
    	}
    }
    
    
	this.title = ko.observableArray(field.title);
    
	
    ///////// choices //////////
    
	
	
    the_selected_choices = [];
    for (zzz = 0; zzz < field.choices.length; zzz++) {
    	// replace br using javascript for choice text values
    	field.choices[zzz].choice = field.choices[zzz].choice.replace(/<br[ ]*>/g, ' ').replace(/<br[ ]*\/\>/g, ' ').replace(/<BR[ ]*\/\>/g, ' ').replace(/<\/br[ ]*\/\>/g, ' ');
    	
    	if (field.choices[zzz].selected == 'true') {
    		the_selected_choices.push(field.choices[zzz].id);
    	}  
    }
    
    // PATCH TMP jira 710
    if (field.type == 'dropdown' || field.type == 'radio') {
    	this.choicesRadios = new ListChoiceItemRadio(field);
    }
    // FIN PATCH jira 710
	    
    this.choices = ko.observableArray([]);
    for (cpt_ch = 0; cpt_ch < field.choices.length; cpt_ch++) {
    	this.choices.push(new ChoiceItem(field.choices[cpt_ch]));	
    }
   
    this.selected_choice = ko.observableArray(the_selected_choices); 
    
    /////////////////////////////
    
    if (field.name == 'TECHNICAL_SEND_REQUEST') {
    	this.pageErrorLabel = ko.observable(field.pageErrorLabel);
    }
    
    if (this.type() == 'connector') {
    	this.buttonName = ko.observable(field.button_name);
    	this.labelTagGtm = ko.observable(field.label_tag_gtm);
    	this.display = ko.observable(field.display && is_dde);
    }	  
    
    
    
    if (field.name == 'USR_EMAIL') {
    	this.emailParamMessageValue = ko.observable(field.email_param_message_value);
    }
        
    if (this.type() == 'datepicker') {
    	this.datePickerVisible = true;
    	this.datePicker = ko.observable(new DatePicker(field.datePicker));
    } else {
    	this.datePickerVisible = false;
    }
    
    if (this.type() == 'textbox') {
    	this.inputmask = ko.observable(field.inputmask);
    }
    
    this.updateSelectedChoices = function(data, event) {
    	//console.log(data);
    }
    
    this.clearRadios = function() {
    	self.selected_choice([]);
    }
    
    this.displayHtmlIcon = false;
   
    if(this.type() == 'html')
	{
    	this.displayHtmlIcon = true;
	}
    
    this.isNotHidden = true;
    if(this.type() == 'hidden')
	{
    	this.isNotHidden = false;
	}
       
	this.previewTemplateName = ko.computed(function(){
	    return "tmp-field-preview-" + this.type();
	  }, this);
	
	this.settingsTemplateName = ko.computed(function(){
	    return "tmp-field-settings-" + this.type();
	  }, this);
	  
	
	this.hasChoices = ko.computed(function() {
		return this.choices && this.choices().length !== 0;
	}, this);

	this.addChoice = function(parent) {
		parent.choices.push(ko.mapping.fromJS({"choice": ""}));
	};
	
	this.removeChoice = function(parent, data) {
		
		
		if(typeof(parent.choices()) == "object")
		{
			parent.choices.remove(data);
		} else {
			var aChoices = parent.choices();
			aChoices.remove(data);
		}
		
 	};
 	
 	this.setTitleField = function(parent, data) {
    	var title = parent.title();
 		for(var i=0;i<title.length;i++)
		{
    		if(data.id == title[i]['id'])
			{
    			title[i] = data;
			}
		}
 		this.title(title);
      
    }
 	
 	
 	// admin can remove these fields
 	if (is_dde == '1' && isGeneric == false && (field.name == 'GET_MYCITROEN' || field.name == 'connector_brandid' || field.name == 'connector_facebook' || field.name == 'html_HTML_loginPopup')) {
 		this.field_is_removable = ko.observable(true);
 	} else {
 		if (field.name == 'html_HTML_OBLIGATORY') {
 			this.field_is_removable = ko.observable(true);
 		} else {
 			this.field_is_removable = ko.observable( ! ( ((this.type() == 'toggle' || this.type() == 'html') && this.bHtmlLock == false) || (this.type() == 'button') || (this.listener == 'listener') ) );
 		}
 	}
};

var Fieldset = function(fieldset, isGeneric) {
	var self = this;
    this.name = fieldset['name'];
    this.classid = fieldset['classid'];
    this.hasMoved = fieldset['hasMoved'];
    this.move_fieldset = false; // true if the fieldset was created in order to receive a moved field
    this.fieldsetIsCollapsed = ko.observable(true); // if the fieldset is collapsed
    
    var aQuestions = [];
    var aQuestionsGeneric = [];

    if(typeof(fieldset['questions']) != 'undefined' && typeof(fieldset['questions']) != 'function')
    {
	    for(var i=0;i<fieldset['questions'].length;i++)
		{
	    	if (isGeneric == true) {
	    		aQuestionsGeneric.push(new Question(fieldset['questions'][i], fieldset['classid'], true));
	    	} else {
	    		aQuestions.push(new Question(fieldset['questions'][i], fieldset['classid'], false));
	    	}
		}
	    
	    if(!isGeneric)
    	{
	    	this.questions = ko.observableArray(aQuestions);
    	} else {
    		this.questionsGeneric = ko.observableArray(aQuestionsGeneric);
    	}
	    
    } else if (!isGeneric) {
    	this.questions = ko.observableArray(fieldset.question);
    } else { 
    	this.questionsGeneric = ko.observableArray(fieldset.questionsGeneric); 
    }   
    
    if (!isGeneric) {
    	self.displayFieldset = ko.computed(function() {
 	    	var nb_question_hidden = 0;
 	    	for (a = 0; a < self.questions().length; a++) {
 	    		if (self.questions()[a].displayQuestion() == false) {
 	    			nb_question_hidden++
 	    		}
 	    	}
 	    	return (self.questions().length > 0 &&  nb_question_hidden != self.questions().length);
 	    });
    } else {
    	self.displayFieldset = ko.computed(function() {
 	    	var nb_question_hidden = 0;
 	    	for (a = 0; a < self.questionsGeneric().length; a++) {
 	    		if (self.questionsGeneric()[a].displayQuestion() == false) {
 	    			nb_question_hidden++
 	    		}
 	    	}
 	    	return (self.questionsGeneric().length > 0 &&  nb_question_hidden != self.questionsGeneric().length);
 	    });
    }
    
    this.addQuestionAtTop = function(data, e) {
    	var lastquestion_index = self.questions().length + 1;
    	
    	var questionData = [];
    	questionData['name'] = self.name.replace('fieldSet', 'question') + '-' + lastquestion_index; 
    	questionData['hasMoved'] = false;
    	questionData['template'] = false;
    	questionData['isNewQuestion'] = true; // always displays this block even if empty
    	questionData['lines'] = [];
    	
    	var newQuestion = new Question(questionData, self.classid, false);
    	self.questions.splice(0, 0, newQuestion);
    }
    
    // adds a new empty question to the current fieldset
    this.addQuestionHere = function(e, data) {
    	var lastquestion_index = self.questions().length + 1;

    	for (ii = 0; ii < self.questions().length; ii++) {
    		if (self.questions()[ii].name == data.name) {
    			var questionData = [];
    	    	questionData['name'] = self.name.replace('fieldSet', 'question') + '-' + lastquestion_index; 
    	    	questionData['hasMoved'] = false;
    	    	questionData['template'] = false;
    	    	questionData['isNewQuestion'] = true; // always displays this block even if empty
    	    	questionData['lines'] = [];
    	    	
    	    	var newQuestion = new Question(questionData, self.classid, false);
    	    	self.questions.splice(ii + 1, 0, newQuestion);
    	    	break;
    		}
    	}
	};
	
	this.deleteThisQuestion = function(e, data) {
		for (ii = 0; ii < self.questions().length; ii++) {
    		if (self.questions()[ii].name == data.name) {
    			self.questions.splice(ii, 1);
    			break;
    		}
		}
	};
	
}

var StepConfiguration = function(step) {
	if ('configuration' in step) {
		this.next_label_display = false;
		this.previous_label_display = false;
				
		if ('next_label' in step['configuration']) {
			this.next_label = ko.observable(step['configuration']['next_label']);
			this.next_label_display = true;
		}
		if (BRAND_ID != 'AP' && 'previous_label' in step['configuration']) {
			this.previous_label = ko.observable(step['configuration']['previous_label']);
			this.previous_label_display = true;
		}
		
		if (this.next_label_display == false && this.previous_label_display == false) {
			this.configuration_ok = false;	
		} else {
			this.configuration_ok = true;
		}
		
	} else {
		this.configuration_ok = false;
	}	
}


var TagElement = function(stepTag) {
	this.name = stepTag.name;
	this.action = stepTag.action;
	this.category = stepTag.category;
	this.label = ko.observable(stepTag.label);
}

var Step = function(step, isGeneric) {
	this.id = step['id'];
	this.title = ko.observable(step['title']);
	this.itkg_code = step['itkg_code'];
	
	//this.connector_brandid_seen = ko.observable(step.connector_brandid_seen);
	
	
	var aFieldsets = [];
	var aFieldsetsGeneric = [];
	
	if(typeof(step['fieldsets']) != 'undefined')
    {
		
	    for(var i=0;i<step['fieldsets'].length;i++)
		{	    	
	    	if(isGeneric)
	    	{
	    		aFieldsetsGeneric.push(new Fieldset(step['fieldsets'][i], true));
	    	} else {
	    		aFieldsets.push(new Fieldset(step['fieldsets'][i], false));	
	    	}
		}
    }
	
	if(isGeneric)
	{
		this.fieldsetsGeneric = aFieldsetsGeneric;
		this.stepConfigurationGeneric = new StepConfiguration(step);
	} else {
		this.fieldsets = ko.observableArray(aFieldsets);
		this.stepConfiguration = new StepConfiguration(step);
	}
	

	var aTagsUnderQuestion = [];
	if (step['gtm_tags_under_question']) {
		for (j = 0; j < step['gtm_tags_under_question'].length; j++) {
			aTagsUnderQuestion.push(new TagElement(step['gtm_tags_under_question'][j]));
		}
	}
	this.tagsUnderQuestion = ko.observableArray(aTagsUnderQuestion);
}



/***************************************************************************************************************************************/


// Custom ko bindings
ko.bindingHandlers.tab = {
  init: function(element, valueAccessor) {
    var currentTab = valueAccessor();
    $(element).find('a').click(function() {
      currentTab($(this).parent().index());
    });
  },

  update: function(element, valueAccessor, allBindingsAccessor, viewModel) {
	
    var currentTab = valueAccessor()();
    $(element).find('li:nth(' + currentTab + ') a:first').trigger('click');
    // Dirty hack
    if (currentTab !== Tabs.FIELD_SETTINGS_TAB) {
      viewModel.selectedField && viewModel.selectedField(null);
    }
  }
};


function strip_tags( str ) {
    str=str.toString();
    return str.replace(/<\/?[^>]+>/gi, '');
}


// désactive la touche "Entrée"
document.onkeypress = processKey;
function processKey(e)
{
    if (null == e)
        e = window.event ;
    if (e.keyCode == 13)  {
        return false;
    }
}

//Décode une chaîne
function html_entity_decode(texte) {
	texte = texte.replace(/&quot;/g,'"'); // 34 22
	texte = texte.replace(/&amp;/g,'&'); // 38 26	
	texte = texte.replace(/&#39;/g,"'"); // 39 27
	texte = texte.replace(/&lt;/g,'<'); // 60 3C
	texte = texte.replace(/&gt;/g,'>'); // 62 3E
	texte = texte.replace(/&circ;/g,'^'); // 94 5E
	texte = texte.replace(/&lsquo;/g,'‘'); // 145 91
	texte = texte.replace(/&rsquo;/g,'’'); // 146 92
	texte = texte.replace(/&ldquo;/g,'“'); // 147 93
	texte = texte.replace(/&rdquo;/g,'”'); // 148 94
	texte = texte.replace(/&bull;/g,'•'); // 149 95
	texte = texte.replace(/&ndash;/g,'–'); // 150 96
	texte = texte.replace(/&mdash;/g,'—'); // 151 97
	texte = texte.replace(/&tilde;/g,'˜'); // 152 98
	texte = texte.replace(/&trade;/g,'™'); // 153 99
	texte = texte.replace(/&scaron;/g,'š'); // 154 9A
	texte = texte.replace(/&rsaquo;/g,'›'); // 155 9B
	texte = texte.replace(/&oelig;/g,'œ'); // 156 9C
	texte = texte.replace(/&#357;/g,''); // 157 9D
	texte = texte.replace(/&#382;/g,'ž'); // 158 9E
	texte = texte.replace(/&Yuml;/g,'Ÿ'); // 159 9F
	texte = texte.replace(/&nbsp;/g,' '); // 160 A0
	texte = texte.replace(/&iexcl;/g,'¡'); // 161 A1
	texte = texte.replace(/&cent;/g,'¢'); // 162 A2
	texte = texte.replace(/&pound;/g,'£'); // 163 A3
	texte = texte.replace(/&curren;/g,' '); // 164 A4
	texte = texte.replace(/&yen;/g,'¥'); // 165 A5
	texte = texte.replace(/&brvbar;/g,'¦'); // 166 A6
	texte = texte.replace(/&sect;/g,'§'); // 167 A7
	texte = texte.replace(/&uml;/g,'¨'); // 168 A8
	texte = texte.replace(/&copy;/g,'©'); // 169 A9
	texte = texte.replace(/&ordf;/g,'ª'); // 170 AA
	texte = texte.replace(/&laquo;/g,'«'); // 171 AB
	texte = texte.replace(/&not;/g,'¬'); // 172 AC
	texte = texte.replace(/&shy;/g,'­'); // 173 AD
	texte = texte.replace(/&reg;/g,'®'); // 174 AE
	texte = texte.replace(/&macr;/g,'¯'); // 175 AF
	texte = texte.replace(/&deg;/g,'°'); // 176 B0
	texte = texte.replace(/&plusmn;/g,'±'); // 177 B1
	texte = texte.replace(/&sup2;/g,'²'); // 178 B2
	texte = texte.replace(/&sup3;/g,'³'); // 179 B3
	texte = texte.replace(/&acute;/g,'´'); // 180 B4
	texte = texte.replace(/&micro;/g,'µ'); // 181 B5
	texte = texte.replace(/&para/g,'¶'); // 182 B6
	texte = texte.replace(/&middot;/g,'·'); // 183 B7
	texte = texte.replace(/&cedil;/g,'¸'); // 184 B8
	texte = texte.replace(/&sup1;/g,'¹'); // 185 B9
	texte = texte.replace(/&ordm;/g,'º'); // 186 BA
	texte = texte.replace(/&raquo;/g,'»'); // 187 BB
	texte = texte.replace(/&frac14;/g,'¼'); // 188 BC
	texte = texte.replace(/&frac12;/g,'½'); // 189 BD
	texte = texte.replace(/&frac34;/g,'¾'); // 190 BE
	texte = texte.replace(/&iquest;/g,'¿'); // 191 BF
	texte = texte.replace(/&Agrave;/g,'À'); // 192 C0
	texte = texte.replace(/&Aacute;/g,'Á'); // 193 C1
	texte = texte.replace(/&Acirc;/g,'Â'); // 194 C2
	texte = texte.replace(/&Atilde;/g,'Ã'); // 195 C3
	texte = texte.replace(/&Auml;/g,'Ä'); // 196 C4
	texte = texte.replace(/&Aring;/g,'Å'); // 197 C5
	texte = texte.replace(/&AElig;/g,'Æ'); // 198 C6
	texte = texte.replace(/&Ccedil;/g,'Ç'); // 199 C7
	texte = texte.replace(/&Egrave;/g,'È'); // 200 C8
	texte = texte.replace(/&Eacute;/g,'É'); // 201 C9
	texte = texte.replace(/&Ecirc;/g,'Ê'); // 202 CA
	texte = texte.replace(/&Euml;/g,'Ë'); // 203 CB
	texte = texte.replace(/&Igrave;/g,'Ì'); // 204 CC
	texte = texte.replace(/&Iacute;/g,'Í'); // 205 CD
	texte = texte.replace(/&Icirc;/g,'Î'); // 206 CE
	texte = texte.replace(/&Iuml;/g,'Ï'); // 207 CF
	texte = texte.replace(/&ETH;/g,'Ð'); // 208 D0
	texte = texte.replace(/&Ntilde;/g,'Ñ'); // 209 D1
	texte = texte.replace(/&Ograve;/g,'Ò'); // 210 D2
	texte = texte.replace(/&Oacute;/g,'Ó'); // 211 D3
	texte = texte.replace(/&Ocirc;/g,'Ô'); // 212 D4
	texte = texte.replace(/&Otilde;/g,'Õ'); // 213 D5
	texte = texte.replace(/&Ouml;/g,'Ö'); // 214 D6
	texte = texte.replace(/&times;/g,'×'); // 215 D7
	texte = texte.replace(/&Oslash;/g,'Ø'); // 216 D8
	texte = texte.replace(/&Ugrave;/g,'Ù'); // 217 D9
	texte = texte.replace(/&Uacute;/g,'Ú'); // 218 DA
	texte = texte.replace(/&Ucirc;/g,'Û'); // 219 DB
	texte = texte.replace(/&Uuml;/g,'Ü'); // 220 DC
	texte = texte.replace(/&Yacute;/g,'Ý'); // 221 DD
	texte = texte.replace(/&THORN;/g,'Þ'); // 222 DE
	texte = texte.replace(/&szlig;/g,'ß'); // 223 DF
	texte = texte.replace(/&agrave;/g,'à'); // 224 E0
	texte = texte.replace(/&aacute;/g,'á'); // 225 E1
	texte = texte.replace(/&acirc;/g,'â'); // 226 E2
	texte = texte.replace(/&atilde;/g,'ã'); // 227 E3
	texte = texte.replace(/&auml;/g,'ä'); // 228 E4
	texte = texte.replace(/&aring;/g,'å'); // 229 E5
	texte = texte.replace(/&aelig;/g,'æ'); // 230 E6
	texte = texte.replace(/&ccedil;/g,'ç'); // 231 E7
	texte = texte.replace(/&egrave;/g,'è'); // 232 E8
	texte = texte.replace(/&eacute;/g,'é'); // 233 E9
	texte = texte.replace(/&ecirc;/g,'ê'); // 234 EA
	texte = texte.replace(/&euml;/g,'ë'); // 235 EB
	texte = texte.replace(/&igrave;/g,'ì'); // 236 EC
	texte = texte.replace(/&iacute;/g,'í'); // 237 ED
	texte = texte.replace(/&icirc;/g,'î'); // 238 EE
	texte = texte.replace(/&iuml;/g,'ï'); // 239 EF
	texte = texte.replace(/&eth;/g,'ð'); // 240 F0
	texte = texte.replace(/&ntilde;/g,'ñ'); // 241 F1
	texte = texte.replace(/&ograve;/g,'ò'); // 242 F2
	texte = texte.replace(/&oacute;/g,'ó'); // 243 F3
	texte = texte.replace(/&ocirc;/g,'ô'); // 244 F4
	texte = texte.replace(/&otilde;/g,'õ'); // 245 F5
	texte = texte.replace(/&ouml;/g,'ö'); // 246 F6
	texte = texte.replace(/&divide;/g,'÷'); // 247 F7
	texte = texte.replace(/&oslash;/g,'ø'); // 248 F8
	texte = texte.replace(/&ugrave;/g,'ù'); // 249 F9
	texte = texte.replace(/&uacute;/g,'ú'); // 250 FA
	texte = texte.replace(/&ucirc;/g,'û'); // 251 FB
	texte = texte.replace(/&uuml;/g,'ü'); // 252 FC
	texte = texte.replace(/&yacute;/g,'ý'); // 253 FD
	texte = texte.replace(/&thorn;/g,'þ'); // 254 FE
	texte = texte.replace(/&yuml;/g,'ÿ'); // 255 FF
	return texte;
}


