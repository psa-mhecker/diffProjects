// Constants
var Tabs = {
  ADD_FIELD_TAB: 0,
  FIELD_SETTINGS_TAB: 1,
  FORM_SETTINGS_TAB: 2
};

var FIELD_TYPES = [
  "text", "number", "textarea", "checkbox", "radio", "select", "section",
  "page", "date", "email", "phone",
  "url", "civility", "iban", "captcha", "p", "submit"
];

// Root view model
var EditorViewModel = function(data) {
  var self = this;

  // Data
  this.form = new FormViewModel(data);
  this.currentTab = ko.observable(Tabs.ADD_FIELD_TAB);
  this.selectedField = ko.observable(null);

  // Helper data
  this.formSettingsSelected = ko.computed(function(){
    return this.currentTab() === Tabs.FORM_SETTINGS_TAB
  }, this);

  this.selectedFieldIndex = ko.computed(function() {
    return self.form.fields.indexOf(self.selectedField());
  }, this);

  this.formJSON = ko.computed(function(){
    return ko.toJSON(self.form);
  }, this);

  // Behaviour
  this.showFormSettings = function() {
    self.currentTab(Tabs.FORM_SETTINGS_TAB);
  };

  this.selectField = function(field) {
    self.selectedField(field);
    self.currentTab(Tabs.FIELD_SETTINGS_TAB);
  };

  this.selectFieldAtIndex = function(index) {
    if (index >= 0 && index < self.form.fields().length) {
      self.selectField(self.form.fields()[index]);
    }
  };

  // Duplicates the given field by delegating to FormViewModel
  this.duplicateField = function(field) {
    var field = self.form.duplicateField(field);
    if (field) {
      self.selectedField(field);
    }
  };

  // Adds a new field from given data. event is used to get the type of the
  // new field. Actually delegates to the FormViewModel
  this.addField = function(data, event) {
    var type = $(event.target).data("type");
    data.type = type;
    var field = self.form.addField(data);

    // Scroll to the field
    // TODO This should not be here
    if (field) {
      $("html, body").animate({
        scrollTop: $(".field-wrapper:last").offset().top
      }, 1000);
    }
  };

  // Removes the given field. Actually delegates to the FormViewModel
  this.removeField = function(field) {
    if (field === self.selectedField()) {
      self.selectedField(null);
    }
    self.form.removeField(field);
    if (!self.form.hasFields() && self.currentTab() === Tabs.FIELD_SETTINGS_TAB) {
      self.currentTab(Tabs.ADD_FIELD_TAB);
    }
  };

  this.createFirstField = function() {
    var field = self.form.addField({type: 'textarea'});
    field.title(lang['FORMBUILDER_TITLE_FIRSTFIELD']);
    self.selectField(field);
  };

  this.removeselectedField = function() {
    var selectedField = self.selectedField();
    if (selectedField !== null) {
      self.removeField(selectedField);
    }
  };

  this.selectNextField = function(){
    self.selectFieldAtIndex(self.selectedFieldIndex() + 1);
  };

  this.selectPrevField = function(){
    self.selectFieldAtIndex(self.selectedFieldIndex() - 1);
  };

  // Setup keyboard shortcuts
  $(document)
    .bind('keydown', 'backspace', this.removeselectedField)
    .bind('keydown', 'del', this.removeselectedField)
    .bind('keydown', 'j', this.selectNextField)
    .bind('keydown', 'k', this.selectPrevField);
};

var getDefaultDataForType = function(type, number) {
  switch (type) {
  case 'text': 
	return {
      title: lang['FORMBUILDER_TITLE_TEXT'],
      name: "FORMFIELD_" + type.toUpperCase() + '_' + number
    };
  case 'number':
    return {
      title: lang['FORMBUILDER_TITLE_NUMBER'],
      name: "FORMFIELD_" + type.toUpperCase() + '_' + number
    };
  case 'textarea':
	return {
      title: lang['FORMBUILDER_TITLE_TEXTAREA'],
      name: "FORMFIELD_" + type.toUpperCase() + '_' + number
    };
  case 'checkbox':
    return {
      title: lang['FORMBUILDER_TITLE_CHECK'],
      choices: [
        {choice: lang['FORMBUILDER_TITLE_FIRSTCHOICE']},
        {choice: lang['FORMBUILDER_TITLE_SECONDCHOICE']},
        {choice: lang['FORMBUILDER_TITLE_THIRDCHOICE']}
      ],
      name: "FORMFIELD_" + type.toUpperCase() + '_' + number
    };
  case 'radio':
    return {
      title: lang['FORMBUILDER_TITLE_SELECTACHOICE'],
      choices: [
        {choice: lang['FORMBUILDER_TITLE_FIRSTCHOICE']},
        {choice: lang['FORMBUILDER_TITLE_SECONDCHOICE']},
        {choice: lang['FORMBUILDER_TITLE_THIRDCHOICE']}
      ],
      name: "FORMFIELD_" + type.toUpperCase() + '_' + number
    };
  case 'select':
    return {
      title: lang['FORMBUILDER_TITLE_SELECTACHOICE'],
      choices: [
        {choice: lang['FORMBUILDER_TITLE_FIRSTCHOICE']},
        {choice: lang['FORMBUILDER_TITLE_SECONDCHOICE']},
        {choice: lang['FORMBUILDER_TITLE_THIRDCHOICE']}
      ],
      name: "FORMFIELD_" + type.toUpperCase() + '_' + number
    };
  case 'section':
    return {
      title: lang['FORMBUILDER_TITLE_SECTIONBREAK'],
      instructions: lang['FORMBUILDER_TITLE_SECTIONDESC'],
      name: "FORMFIELD_" + type.toUpperCase() + '_' + number
    };
  case "submit":
	    return {
	      title: lang['FORMBUILDER_TITLE_SUBMIT'],
	      name: "FORMFIELD_" + type.toUpperCase() + '_' + number
	    };
  case 'shortname':
    return {
      title: lang['FORMBUILDER_TITLE_NAME'],
      name: "FORMFIELD_" + type.toUpperCase() + '_' + number
    };
  case "phone":
    return {
      title: lang['FORMBUILDER_TITLE_PHONE'],
      name: "FORMFIELD_" + type.toUpperCase() + '_' + number
    };
  case "civility":
	    return {
	      title: lang['FORMBUILDER_TITLE_CIVILITY'],
	      choices: [
	        {choice: lang['FORMBUILDER_LABEL_MME']},
	        {choice: lang['FORMBUILDER_LABEL_MLLE']},
	        {choice: lang['FORMBUILDER_LABEL_MR']}
	      ],name: "FORMFIELD_" + type.toUpperCase() + '_' + number
	    };  
  case "iban":
	    return {
	      title: lang['FORMBUILDER_TITLE_IBAN'],
	      name: "FORMFIELD_" + type.toUpperCase() + '_' + number
	    };  
  default: 
	return {
      name: "FORMFIELD_" + type.toUpperCase() + '_' + number}
  }
};

var FormViewModel = function(data) {
  var self = this;
  var mapping = {
    'fields': {
      create: function(options) {
        return new FieldViewModel(options.data || []);
      }
    }
  };
  this.name = ko.observable(lang['FORMBUILDER_TITLE_UNTITLED']);
  this.description = ko.observable("");
  this.success = ko.observable("");
  this.fields = ko.observableArray([]);
  ko.mapping.fromJS(data, mapping, this);

  this.hasFields = ko.computed(function(){
    return this.fields().length !== 0;
  }, this);

  // Behaviour
  this.duplicateField = function(field) {
    var newFieldData = ko.toJS(field),
        index = ko.utils.arrayIndexOf(self.fields(), field),
        newField = new FieldViewModel(newFieldData);
    self.fields.splice(index, 0, newField);
    return newField;
  };

  this.addField = function(data) {
    if (data.type === undefined) {
      return false;
    }
    var newField = new FieldViewModel({type: data.type});
    ko.mapping.fromJS(getDefaultDataForType(data.type, this.fields().length), {}, newField);
    self.fields.push(newField);
    return newField;
  };

  this.removeField = function(field) {
    self.fields.remove(field);
  };
};

// Helper
var traverse = function(o, func) {
  for (i in o) {
    func.apply(this, [o, i, o[i]]);
    if (typeof(o[i])=="object") {
      traverse(o[i],func);
    }
  }
};

FormViewModel.prototype.toJSON = function() {
  var obj = {
    name: ko.utils.unwrapObservable(this.name),
    description: ko.utils.unwrapObservable(this.description),
    success: ko.utils.unwrapObservable(this.success),
    fields: ko.utils.unwrapObservable(this.fields)
  };
  // Remove ko mappings from the object recursively
  traverse(obj, function(object, key, value) {
    if (key === '__ko_mapping__') {
      delete object[key];
    };
  });

  return obj;
};

var FieldViewModel = function(data) {
  var self = this;
  this.type = ko.observable();
  this.title = ko.observable(lang['FORMBUILDER_TITLE_UNTITLED']);
  this.name = ko.observable("Test");
  this.is_required = ko.observable(false);
  this.placeholder = ko.observable("");
  this.regexp = ko.observable("");
  this.regexp_msg = ko.observable("");
  this.choices = ko.observableArray([]);
  this.sizes = ko.observableArray([]);
  this.is_randomized = ko.observableArray(null);
  ko.mapping.fromJS(data, {}, this);

  // Data
  this.previewTemplateName = ko.computed(function(){
    return "tmp-field-preview-" + this.type();
  }, this);

  this.settingsTemplateName = ko.computed(function(){
    return "tmp-field-settings-" + this.type();
  }, this);

  this.hasChoices = ko.computed(function() {
    return this.choices && this.choices().length !== 0;
  }, this);

  this.hasSizes = ko.computed(function() {
	    return this.sizes && this.sizes().length !== 0;
	  }, this);

  // Behaviour
  this.addChoice = function() {
    self.choices.push(ko.mapping.fromJS({"choice": ""}));
  };

  this.removeChoice = function(choice) {
    self.choices.remove(choice);
  };
};

FieldViewModel.prototype.toJSON = function() {
  return {
    type: ko.utils.unwrapObservable(this.type),
    title: ko.utils.unwrapObservable(this.title),
    name: ko.utils.unwrapObservable(this.name),
    is_required: ko.utils.unwrapObservable(this.is_required),
    placeholder: ko.utils.unwrapObservable(this.placeholder),
    regexp: ko.utils.unwrapObservable(this.regexp),
    regexp_msg: ko.utils.unwrapObservable(this.regexp_msg),
    choices: ko.utils.unwrapObservable(this.choices),
    sizes: ko.utils.unwrapObservable(this.sizes),
    is_randomized: ko.utils.unwrapObservable(this.is_randomized)
  };
};

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

(function() {
  var __hasProp = Object.prototype.hasOwnProperty;
  var data_key = "sortable_data";

  ko.bindingHandlers['sortableList'] = {
    init: function(element, valueAccessor) {
      $(element).mousedown(function() {
        // Keep track of the order of all child nodes (including text/comments)
        $(this).data("preSortChildren", ko.utils.makeArray(this.childNodes));
      });

      return $(element).sortable({
        update: function(event, ui) {
          // Figure out what data item was moved, from where, and to where
          var movedDataItem = $(ui.item).data(data_key);
          var possiblyObservableArray = valueAccessor();
          var array = ko.utils.unwrapObservable(possiblyObservableArray);
          var previousIndex = ko.utils.arrayIndexOf(array, movedDataItem);
          var newIndex = $(element).children().index(ui.item);

          // Restore the order of child nodes (including text/comments)
          this.innerHTML = "";
          $(this).append($(this).data("preSortChildren"));

          // Update the underlying collection to reflect the data item movement
          array.splice(previousIndex, 1);
          array.splice(newIndex, 0, movedDataItem);
          if (typeof possiblyObservableArray.valueHasMutated === 'function')
            possiblyObservableArray.valueHasMutated();
        }
      });
    }
  };

  ko.bindingHandlers['sortableItem'] = {
    init: function(element, valueAccessor, allBindingsAccessor, viewModel) {
      return $(element).data(data_key, ko.utils.unwrapObservable(valueAccessor()));
    }
  };
}).call(this);