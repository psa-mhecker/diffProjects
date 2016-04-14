<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Form Builder</title>
{$header}
</head>
<body>

<div class="container">
  <div class="content">
    <div class="page-header row">
      <div class="pull-left"><h1>Form Builder</h1></div>
    </div>
    <div class="row">
      <div class="span8 sidebar">
        <ul class="tabs" data-tabs="tabs" data-bind="tab: $root.currentTab">
          <li class="active"><a href="#add-field-pane">{'FORMBUILDER_ADD_FIELD'|t}</a></li>
          <li><a href="#field-settings-pane">{'FORMBUILDER_ADD_FIELD_SETTING'|t}</a></li>
          <li><a href="#form-settings-pane">{'FORMBUILDER_ADD_FORM_SETTING'|t}</a></li>
        </ul>
 {literal}
        <div class="tab-content span6">
          <div class="tab-pane active" id="add-field-pane">
            <div data-bind="template: 'tmpl-add-field'"></div>
          </div>
          <div class="tab-pane" id="field-settings-pane">
            <div data-bind="template: {name: 'tmpl-field-settings', data: selectedField }"></div>
          </div>
          <div class="tab-pane" id="form-settings-pane">
            <div data-bind="template: {name: 'tmpl-form', data: form }"></div>
          </div>
        </div>
      </div>
      <div class="span8 main-content" data-bind="with: form">
        <div class="field-wrapper"
             data-bind="click: $root.showFormSettings, css: { selected: $root.formSettingsSelected }">
          <h2 data-bind="text: name"></h2>
          <p data-bind="text: description"></p>
        </div>
        <form data-bind="template: {name: 'tmpl-field-preview', foreach: fields}, sortableList: fields"
              class="form-stacked"></form>
        <div data-bind="ifnot: hasFields">
 {/literal}
           <div class="alert-message block-message warning">
            <p><strong>{'FORMBUILDER_TXT_NOFIELD'|t}</strong></p>
            <div class="alert-actions">
              <button class="btn small" data-bind="click: $root.createFirstField">{'FORMBUILDER_TXT_CREATEAFIELD'|t}</button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div style="display:none;">
    <pre id="result" data-bind="text: $root.formJSON"></pre></div>
  </div>

</div>

<footer class="footer">
  <div class="container">
  <center><button id="btnok" onclick="submitForm()" class="btn primary">{'FORMBUILDER_OK'|t}</button>&nbsp;<button onclick="if (confirm('{'FORMBUILDER_CLOSE_MSG'|t}')) window.close();" id="btncancel" class="btn cancel">{'FORMBUILDER_CANCEL'|t}</button></center>
    <!--<h6>Keyboard shortcuts</h6>
    <ul>
      <li><strong>j</strong> Select next field</li>
      <li><strong>k</strong> Select previous field</li>
      <li><strong>DEL or BACKSPACE</strong> Delete selected field</li>
    </ul>-->
  </div>
</footer>

<!-- Form settings -->
<script type="text/html" id="tmpl-form">
  <form class="form-stacked" data-bind="submit: $.noop">
    <p>
      <label>{'FORMBUILDER_LABEL_NAME'|t}</label>
      <input data-bind="value: name">
    </p>
    <p>
      <label>{'FORMBUILDER_LABEL_DESCRIPTION'|t}</label>
      <textarea data-bind="value: description"></textarea>
    </p>
    <p>
      <label>{'FORMBUILDER_LABEL_SUCCESS'|t}</label>
      <textarea data-bind="value: success"></textarea>
    </p>
  </form>
</script>

<!-- Add Field Pane -->
<script type="text/html" id="tmpl-add-field">
  <div class="add-field-pane" data-bind="click: $root.addField">
    <!-- TODO: Generate these buttons dynamically -->
<button class="btn info" data-type="text">{'FORMBUILDER_TYPE_SINGLE_LINE_TEXT'|t}</button>
<button class="btn info" data-type="number">{'FORMBUILDER_TYPE_NUMBER'|t}</button>
<button class="btn info" data-type="textarea">{'FORMBUILDER_TYPE_PARAGRAPH_TEXT'|t}</button>
<button class="btn info" data-type="checkbox">{'FORMBUILDER_TYPE_CHECKBOXES'|t}</button>
<button class="btn info" data-type="radio">{'FORMBUILDER_TYPE_MULTIPLE_CHOICE'|t}</button>
<button class="btn info" data-type="select">{'FORMBUILDER_TYPE_DROPDOWN'|t}</button>
<button class="btn info" data-type="section">{'FORMBUILDER_TYPE_SECTION_BREAK'|t}</button>
<button class="btn info" data-type="page">{'FORMBUILDER_TYPE_PAGE_BREAK'|t}</button>
<br><br>
<!--<button class="btn info" data-type="shortname">{'FORMBUILDER_TYPE_NAME'|t}</button>-->
<!--<button class="btn info" data-type="file">{'FORMBUILDER_TYPE_FILE_UPLOAD'|t}</button>-->
<!--<button class="btn info" data-type="address">{'FORMBUILDER_TYPE_ADDRESS'|t}</button>-->
<button class="btn info" data-type="date">{'FORMBUILDER_TYPE_DATE'|t}</button>
<button class="btn info" data-type="email">{'FORMBUILDER_TYPE_EMAIL'|t}</button>
<!--<button class="btn info" data-type="time">{'FORMBUILDER_TYPE_TIME'|t}</button>-->
<button class="btn info" data-type="phone">{'FORMBUILDER_TYPE_PHONE'|t}</button>
<button class="btn info" data-type="url">{'FORMBUILDER_TYPE_WEBSITE'|t}</button>
<button class="btn info" data-type="civility">{'FORMBUILDER_TYPE_CIVILITY'|t}</button>
<button class="btn info" data-type="iban">{'FORMBUILDER_TYPE_IBAN'|t}</button>
<button class="btn info" data-type="captcha">{'FORMBUILDER_TYPE_CAPTCHA'|t}</button>
<button class="btn info" data-type="submit">{'FORMBUILDER_TYPE_SUBMIT'|t}</button>
  </div>
</script>

<!-- Generic field preview template -->
{literal}
<script type="text/html" id="tmpl-field-preview">
  <div class="clearfix field-wrapper"
       data-bind="click: $root.selectField,
                  css: { selected: $data == $root.selectedField() },
                  sortableItem: $data">
    <label>
      <span data-bind="if: is_required" class="required">*</span>
      <span data-bind="text: title"></span>
    </label>
    <div data-bind="template: previewTemplateName"></div>
    <div class="pull-right">
      <button data-bind="click: $root.duplicateField" class="btn xsmall success">+</button>
      <button data-bind="click: $root.removeField" class="btn xsmall danger">-</button>
    </div>
  </div>
</script>
{/literal}
<!-- Generic field settings template -->
<script type="text/html" id="tmpl-field-settings">
  <div data-bind="if: $data">
    <form class="form-stacked">
      <div class="clearfix">
        <label>{'FORMBUILDER_LABEL_CODE'|t}</label>
        <input type="text" readonly="true" data-bind="value: name">
      </div>
      <div class="clearfix">
        <label>{'FORMBUILDER_LABEL_TITLE'|t}</label>
        <textarea data-bind="value: title" class="xlarge"></textarea>
      </div>
      <div class="clearfix">
        <label>{'FORMBUILDER_LABEL_TYPE'|t}</label>
        <select data-bind="options: FIELD_TYPES, value: type"></select>
      </div>
      <div class="clearfix">
        <label>{'FORMBUILDER_LABEL_INSTRUCTIONS'|t}</label>
        <textarea data-bind="value: placeholder"></textarea>
      </div>
      <div class="clearfix">
        <label>
          <input type="checkbox" data-bind="checked: is_required">
          <span>{'FORMBUILDER_LABEL_REQUIRED?'|t}</span>
        </label>
      </div>
      <div data-bind="template: settingsTemplateName"></div>
    </form>
  </div>
  <div data-bind="ifnot: $data">
  {'FORMBUILDER_TXT_SELECTAFIELD'|t}
  </div>
</script>

<!-- Radio field -->
{literal}
<script type="text/html" id="tmp-field-preview-radio">
  <ul data-bind="foreach: choices" class="unstyled">
    <label>
      <input type="radio" data-bind="attr: {name: $parent.FieldId}" disabled readonly>
      <span data-bind="text: choice"></span>
    </label>
  </ul>
</script>
{/literal}
<script type="text/html" id="tmp-field-settings-radio">
  <div data-bind="template: 'tmp-choices', data: $data"></div>
</script>

<!-- Number field -->
<script type="text/html" id="tmp-field-preview-number">
  <input type="number" class="large">
</script>
<script type="text/html" id="tmp-field-settings-number"></script>

<!-- Text field -->
<script type="text/html" id="tmp-field-preview-text">
  <input type="text" class="xlarge">
</script>
<script type="text/html" id="tmp-field-settings-text">
      <div class="clearfix">
        <label>{'FORMBUILDER_LABEL_REGEXP'|t}</label>
        {literal}
        <input type="text" class="regexp" data-bind="value: regexp" />
        {/literal}
        <div class="clearfix">
        	<br />
	        <label>{'FORMBUILDER_LABEL_REGEXP_SAMPLE'|t}</label>
        	<p style="color:red;">
        	/^[0-9\ ]+$/<br />
			/^[a-zA-Z\ \']+$/
			</p>
        </div>
      </div>
      <div class="clearfix">
        <label>{'FORMBUILDER_LABEL_REGEXP_MSG'|t}</label>
		<textarea data-bind="value: regexp_msg"></textarea>
      </div>
</script>

<!-- Checkboxes field -->
{literal}
<script type="text/html" id="tmp-field-preview-checkbox">
  <ul data-bind="foreach: choices" class="unstyled">
    <label>
      <input type="checkbox" data-bind="attr: {name: $parent.FieldId}" disabled readonly>
      <span data-bind="text: choice"></span>
    </label>
  </ul>
</script>
{/literal}
<script type="text/html" id="tmp-field-settings-checkbox">
  <div data-bind="template: 'tmp-choices', data: $data"></div>
</script>

<!-- Section -->
<script type="text/html" id="tmp-field-preview-section">
  <p data-bind="text: placeholder"></p>
</script>
<script type="text/html" id="tmp-field-settings-section"></script>

<!-- Page -->
<script type="text/html" id="tmp-field-preview-page"></script>
<script type="text/html" id="tmp-field-settings-page"></script>

<!-- Textarea field -->
<script type="text/html" id="tmp-field-preview-textarea">
  <textarea class="xlarge"></textarea>
</script>
<script type="text/html" id="tmp-field-settings-textarea"></script>

<!-- Shortname field -->
<script type="text/html" id="tmp-field-preview-shortname">
  <input >&nbsp;<input >
</script>
<script type="text/html" id="tmp-field-settings-shortname"></script>

<!-- File field -->
<script type="text/html" id="tmp-field-preview-file">
  <input type="file">
</script>
<script type="text/html" id="tmp-field-settings-file"></script>

<!-- Date field -->
<script type="text/html" id="tmp-field-preview-date">
  <input type="date">
</script>
<script type="text/html" id="tmp-field-settings-date"></script>

<!-- Time field -->
<script type="text/html" id="tmp-field-preview-time">
  <input type="time">
</script>
<script type="text/html" id="tmp-field-settings-time"></script>

<!-- Address field -->
<script type="text/html" id="tmp-field-preview-address">
  <input type="address" class="large">
</script>
<script type="text/html" id="tmp-field-settings-address"></script>

<!-- Money field -->
<script type="text/html" id="tmp-field-preview-p">
  <p>(affichage de la valeur saisie dans "titre")</p>
</script>
<script type="text/html" id="tmp-field-settings-p"></script>

<!-- Email field -->
<script type="text/html" id="tmp-field-preview-email">
  <input type="email" class="large">
</script>
<script type="text/html" id="tmp-field-settings-email"></script>

<!-- Phone field -->
<script type="text/html" id="tmp-field-preview-phone">
  <input type="phone" class="large">
</script>
<script type="text/html" id="tmp-field-settings-phone"></script>

<!-- Select field -->
<script type="text/html" id="tmp-field-preview-select">
  <select data-bind="options: choices, optionsText: 'choice'"></select>
</script>
<script type="text/html" id="tmp-field-settings-select">
  <div data-bind="template: 'tmp-choices', data: $data"></div>
</script>

<!-- Select field -->
<script type="text/html" id="tmp-field-preview-civility">
  <select data-bind="options: choices, optionsText: 'choice'"></select>
</script>
<script type="text/html" id="tmp-field-settings-civility">
  <div data-bind="template: 'tmp-choices', data: $data"></div>
</script>

<!-- URL field -->
<script type="text/html" id="tmp-field-preview-url">
  <input type="url" class="large">
</script>
<script type="text/html" id="tmp-field-settings-url"></script>

<!-- Likert field -->
<script type="text/html" id="tmp-field-preview-submit">
  <input type="submit" value="submit">
</script>
<script type="text/html" id="tmp-field-settings-submit"></script>

<!-- IBAN field -->
<script type="text/html" id="tmp-field-preview-iban">
  <input type="text" class="large">
</script>
<script type="text/html" id="tmp-field-settings-iban"></script>

<!-- CAPTCHA field -->
<script type="text/html" id="tmp-field-preview-captcha">
  <img src="{$imgpath}recaptcha.gif" width="250" />
</script>
<script type="text/html" id="tmp-field-settings-captcha"></script>


<!-- Helpers -->
<script type="text/html" id="tmp-choices">
  <div class="clearfix">
    <label>{'FORMBUILDER_LABEL_CHOICES'|t}</label>
    <ul data-bind="foreach: choices" class="unstyled">
      <li>
        <input data-bind="value: choice" class="">
        <button data-bind="click: $parent.addChoice" class="btn xsmall success">+</button>
        <button data-bind="click: $parent.removeChoice" class="xsmall btn danger">-</button>
      </li>
    </ul>
    <div data-bind="ifnot: hasChoices">
      <button data-bind="click: addChoice" class="btn xsmall success">+ {'FORMBUILDER_LABEL_ADDACHOICE'|t}</button>
    </div>
  </div>
  <!--<div class="clearfix">
    <label>
      <input data-bind="checked: is_randomized" type="checkbox">
      <span>{'FORMBUILDER_LABEL_RANDOMIZED?'|t}</span>
    </label>
  </div>-->
</script>

</body>
<script>
	var formData = {literal}{{/literal}name: "{'FORMBUILDER_TITLE'|t}", description: "{'FORMBUILDER_DESC'|t}"{literal}}{/literal};
{literal}
	if (window.opener) {
		var initval = window.opener.$("[id=FORMBUILDER_STRUCTURE]").val();
		if (initval) {
			formData = JSON.parse(initval);
		}
	}
var builder = new EditorViewModel(formData);
ko.applyBindings(builder);
</script>

<script>
	function submitForm() {
		window.opener.$("[id=FORMBUILDER_STRUCTURE]").val($('#result').html());	
		window.close();	
	}
</script>
{/literal}
</html>