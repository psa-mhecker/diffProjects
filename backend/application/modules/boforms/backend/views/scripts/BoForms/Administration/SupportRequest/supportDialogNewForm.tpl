{$header}

{literal}
<style type="text/css">
  	   .error_span { color: red;font-weight:bold; }
</style>
{/literal}

<div>
    <!-- result form -->
    <form id="frm_to_post" class="form-stacked" method="post" enctype="multipart/form-data" action="postSupportRequest" style="display: none;">
		<input type="hidden" id="support_request_data" name="datas" data-bind="value:jsonRes" />
	</form>

	<!-- ko with: request_datas -->		
		<form class="form-stacked" method="post" action="#">
			<div class="clearfix">
				<span>* </span><select name="listePriorite" data-bind="options:tblListePriorite, optionsText:'name', optionsValue:'id', optionsCaption: lang['BOFORMS_SUPPORT_CHOOSE_PRIORITY'], value:priorite"></select>
				<div class="clearfix" style="color: red;font-weight:bold;" data-bind="visible: all_errors.indexOf('priorite') >= 0, text: '{t('BOFORMS_VALIDATION_REQUIRED_FIELD')}'"> </div>
			</div>
			
			<div class="clearfix">
				<label><span>* </span>{t('BOFORMS_SUPPORT_REQUEST_TITLE')}</label>
				<input name="request_title" type="text" data-bind="value: request_title" />
				<div class="clearfix" style="color: red;font-weight:bold;" data-bind="visible: all_errors.indexOf('request_title') >= 0, text: '{t('BOFORMS_VALIDATION_REQUIRED_FIELD')}'"> </div>
				{*<p style="margin-right: 10px; font-style: italic; text-align: justify;">{t('BOFORMS_VALIDATION_HELP_TITLE_NEW_FORM')}</p>*}
			</div>	
			
			<div class="clearfix">
				<label><span>* </span>{t('BOFORMS_TYPE_FORMULAIRE')}</label>
				<input name="form_type" type="text" style="width:80%;" data-bind="value: form_type" />
				<div class="clearfix" style="color: red;font-weight:bold;" data-bind="visible: all_errors.indexOf('form_type') >= 0, text:  '{t('BOFORMS_VALIDATION_REQUIRED_FIELD')}'"> </div>
			</div>
			<div class="clearfix">
				<label><span>* </span>{t('BOFORMS_TYPE_FORM_OBJECTIVE')}</label>
				<textarea name="form_description" style="width:80%;" data-bind="value: form_description"></textarea>
				<div class="clearfix" style="color: red;font-weight:bold;" data-bind="visible: all_errors.indexOf('form_description') >= 0, text: '{t('BOFORMS_VALIDATION_REQUIRED_FIELD')}'"> </div>
			</div>
			
			<div class="clearfix">
				<label><span>* </span>{t('BOFORMS_POPUP_CREATE_NEW_FORM_TARGET')}</label>
				<input name="radio_part" type="checkbox" data-bind="name: 'radio_part',value: '1', checked: form_target_selected_part" />
				<span>{t('BOFORMS_REFERENTIAL_CUSTOMER_TYPE_PARTICULAR')}</span>
				<input name="radio_pro" type="checkbox" data-bind="name: 'radio_pro',value: '2', checked: form_target_selected_pro" />
				<span>{t('BOFORMS_REFERENTIAL_CUSTOMER_TYPE_PROFESSIONAL')}</span>
				<div class="clearfix" style="color: red;font-weight:bold;" data-bind="visible: all_errors.indexOf('form_target_selected') >= 0, text:  '{t('BOFORMS_VALIDATION_REQUIRED_FIELD')}'"> </div>
			</div>
			
			<div class="clearfix">
				<label><span>* </span>{t('BOFORMS_POPUP_CREATE_NEW_FORM_WORKFLOW')}</label>
				<div>
					<input name="chkstandard" type="checkbox" data-bind="name:'chkstandard',checked: workflow_standard" />
					<span>{t('BOFORMS_REFERENTIAL_FORM_CONTEXT_STANDARD')}</span>
					<input name="chkpos" type="checkbox" data-bind="name:'chkpos',checked: workflow_context_pos" />
					<span>{t('BOFORMS_REFERENTIAL_FORM_CONTEXT_CONTEXTUALIZED_SALE')}</span>
					<input name="chkvehicle" type="checkbox" data-bind="name:'chkvehicle',checked: workflow_context_vehicle" />
					<span>{t('BOFORMS_REFERENTIAL_FORM_CONTEXT_CONTEXTUALIZED_VEHICLE')}</span>
				</div>
				<div class="clearfix" style="color: red;font-weight:bold;" data-bind="visible: all_errors.indexOf('workflow') >= 0, text: '{t('BOFORMS_VALIDATION_REQUIRED_FIELD')}'"> </div>
			</div>
			
			<div class="clearfix">
				<label><span>* </span>{t('BOFORMS_POPUP_CREATE_NEW_FORM_DEVICE')}</label>
				<div>
					<input name="radio_web" type="checkbox" data-bind="name: 'radio_web', value: 'web', checked: device_web" />
					<span>{t('BOFORMS_REFERENTIAL_DEVICE_WEB')}</span>
					<input name="radio_mobile" type="checkbox" data-bind="name: 'radio_mobile', value: 'mobile', checked: device_mobile" />
					<span>{t('BOFORMS_REFERENTIAL_DEVICE_MOBILE')}</span>
				</div>
				<div class="clearfix" style="color: red;font-weight:bold;" data-bind="visible: all_errors.indexOf('device') >= 0, text:  '{t('BOFORMS_VALIDATION_REQUIRED_FIELD')}'"> </div>
			</div>
			
			<div class="clearfix">
				<span>* </span>
				<select name="choose_form_type" style="width:428px;" name="listeOpportunities" data-bind="value:opportunity">
					<option value="">{t('BOFORMS_POPUP_CREATE_NEW_FORM_CHOOSE_FORM_TYPE')}</option>
					{foreach $theOpportunities as $id_opp => $label_opp}
						<option value="{$id_opp}">{$label_opp}</option>
					{/foreach}
					<option value="-1">{t('BOFORMS_REFERENTIAL_FORM_TYPE_OTHER')}</option>
				 </select>
				 
				<span id="span_form_not_found" class="error_span" data-bind="visible: error_no_data_found() == true,text:lang['BOFORMS_POPUP_CREATE_NEW_FORM_FORM_NOT_FOUND']"></span>
				<div class="clearfix" style="color: red;font-weight:bold;" data-bind="visible: all_errors.indexOf('opportunity') >= 0, text:  '{t('BOFORMS_VALIDATION_REQUIRED_FIELD')}'"> </div>
			</div>
			
			<!-- choose fields -->
			<div data-bind="visible: tblListeFields().length > 0" class="clearfix">
				<span>{t('BOFORMS_POPUP_CREATE_NEW_FORM_AVAILABLE_FIELDS')}</span>
				<div data-bind="foreach: tblListeFields()" style="overflow:auto;width:98%;height:110px;border:1px solid lightgrey;">
					<div class="clearfix">
						<input type="checkbox" style="display:inline-block;" data-bind="name: chkname,disable: isrequiredcentral, value: identifier, checked: ischecked" />&nbsp;
						<label data-bind="text:label" style="display:inline-block;"></label>&nbsp;
					 </div>
				</div>
				<div class="clearfix" style="color: red;font-weight:bold;" data-bind="visible: tblListeFields().length > 0 && all_errors.indexOf('checkboxes_fields') >= 0, text: '{t('BOFORMS_VALIDATION_SELECT_FIELDS')}'"> </div>
			</div>
			
			
			<div class="clearfix">
				<label>{t('BOFORMS_POPUP_CREATE_NEW_FORM_ADD_FIELD')}</label>
				<textarea name="formataddfields" data-bind="value:formaddfields" style="width:80%"></textarea>
				<div class="clearfix" style="color: red;font-weight:bold;" data-bind="visible: all_errors.indexOf('formaddfields') >= 0, text:  '{t('BOFORMS_VALIDATION_REQUIRED_FIELD')}'"> </div>
			</div>
			
			<div class="clearfix">
				<label>{t('BOFORMS_POPUP_CREATE_NEW_FORM_GIVE_EXAMPLE')}</label>
				<textarea name="formexample" data-bind="value:formexample" style="width:80%"></textarea>
			</div>
			
			<div class="clearfix">	
			    <input name="validationButton" type="button" class="btn_send_support_request" value="{t('BOFORMS_SUPPORT_BTN_SEND_CENTRAL_VALIDATION')}" />
			</div>		
		</form>
	<!-- /ko -->
</div>