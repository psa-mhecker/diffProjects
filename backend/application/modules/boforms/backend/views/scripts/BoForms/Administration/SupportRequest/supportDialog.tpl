<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Bo Forms</title>
  {$header}

{literal}
<style type="text/css">
  	   .a_fieldset { border: 2px solid lightgrey; width: 80%; padding-left:5px;padding-right:5px;padding-top: 5px;padding-bottom:5px;}
	   .a_legend { padding-left:5px;padding-right: 5px;font-weight: bold;font-size:1.2em;}
	   .removal_href {text-decoration: none; color:red; font-weight: bold;font-size: 1.2em;border: 1px solid red;padding: 2px;}
	   textarea { width:60%; }
	   .a_overflow {overflow:auto;width:80%;max-height:200px;border:1px solid lightgrey;}
	   .selectTypeDemande {width: 235px;}
</style>
{/literal}

</head>
<body>


<div class="content" style="width:900;height: 690px;">
	<!-- ko with: request_datas -->
	
	<form id="frm_to_post" class="form-stacked" method="post" enctype="multipart/form-data" action="postSupportRequest">
		<input type="hidden" id="support_request_data" name="datas" data-bind="value: $root.jsonRes" />
		
		<div class="clearfix">	
			<select class="selectTypeDemande" name="typeDemande" data-bind="options:tblTypeDemande, optionsText:'name', optionsValue:'id', optionsCaption: lang['BOFORMS_SUPPORT_CHOOSE_REQUEST_TYPE'], value:type_demande,event:{literal}{ change: $data.type_demande_changed}{/literal}"></select>
			&nbsp;	
			<select name="listePriorite" data-bind="options:tblListePriorite, optionsText:'name', optionsValue:'id', optionsCaption: lang['BOFORMS_SUPPORT_CHOOSE_PRIORITY'], value:priorite,visible: type_demande() != Type_Demande.VALIDATION_CENTRAL"></select>
		</div>
	
	
		<div class="block_form_validation_central" data-bind="visible: type_demande() == Type_Demande.VALIDATION_CENTRAL">
			<div class="clearfix">
				<label>{t('BOFORMS_SUPPORT_COUNTRY')}</label>
				<input type="text" data-bind="enable: false,value: countrycode" />
			</div>
			<div class="clearfix">
				<label>{t('BOFORMS_SUPPORT_WEBMASTER_NAME')}</label>
				<input type="text" data-bind="enable: false,value: webmaster_name" />
			</div>
			
			<div class="clearfix">
				<label>{t('BOFORMS_SUPPORT_REQUEST_DESCRIPTION')}</label>
				<textarea data-bind="enable: type_demande() == Type_Demande.VALIDATION_CENTRAL,value:request_description" rows="5" cols="20"></textarea>
				<div class="clearfix" style="color: red;font-weight:bold;" data-bind="visible: all_errors.indexOf('request_description') >= 0, text: '{t('BOFORMS_VALIDATION_REQUIRED_FIELD')}'"> </div>
			</div>
		</div>
		
		
		
		<div class="block_form_dem_evolution" data-bind="visible: (type_demande() == Type_Demande.EVOLUTION_FORMULAIRE ||  type_demande() == Type_Demande.NOTIFICATION_ANOMALIE) && priorite() >= 0">
			<div class="clearfix">
				<label>{t('BOFORMS_SUPPORT_REQUEST_TITLE')}&nbsp;<span style="font-weight:normal;font-style: italic;" data-bind="visible: type_demande() == Type_Demande.NOTIFICATION_ANOMALIE">{t('BOFORMS_ANOMALY_TITLE_HELP')}</span></label>
				<input type="text" data-bind="value: request_title" />
				<div class="clearfix" style="color: red;font-weight:bold;" data-bind="visible: all_errors.indexOf('request_title') >= 0, text: '{t('BOFORMS_VALIDATION_REQUIRED_FIELD')}'"> </div>
			</div>	
		</div>
		
		<div class="block_form_dem_evolution" data-bind="visible: type_demande() == Type_Demande.EVOLUTION_FORMULAIRE && priorite() >= 0">
			<div id="notification_all_blocs">
				<div id="notification_bloc_0" data-bind="visible: $data.displayBlockNotification(0) == true">
					<fieldset class="a_fieldset">
	    			<legend class="a_legend">{t('BOFORMS_NOTIFICATION_NEW_FIELDS')} 
	    			{literal}<a href="#" class="removal_href" data-bind="click:function(data,event) {notificationDeleteRequest(0);}">X</a></legend>{/literal}					
					<div class="clearfix">
						<label>{t('BOFORMS_SUPPORT_JUSTIFY_REQUEST')}</label>
						<textarea data-bind="value: request_more_description_0"></textarea>
					</div>	
					<div class="clearfix" style="color: red;font-weight:bold;" data-bind="visible: all_errors.indexOf('error_notification_0') >= 0, text: '{t('BOFORMS_VALIDATION_FILL_THIS_FIELD')}'"> </div>
					</fieldset>
				</div>
				
				
				<div id="notification_bloc_1" data-bind="visible: $data.displayBlockNotification(1) == true" class="clearfix">
					<fieldset class="a_fieldset">
	    			<legend class="a_legend">{t('BOFORMS_NOTIFICATION_DEL_MANDATORY_FIELD')} 
	    			{literal}<a href="#" class="removal_href" data-bind="click:function(data,event) {notificationDeleteRequest(1);}">X</a></legend>{/literal}
					<div class="clearfix" style="color: red;font-weight:bold;" data-bind="visible: all_errors.indexOf('error_notification_1') >= 0, text: '{t('BOFORMS_VALIDATION_PICK_VALUE_AND_JUSTIFY')}'"> </div>
						<div data-bind="foreach: tbl_required_fields()">
							<div class="clearfix">
								<input type="checkbox" style="display:inline-block;" data-bind="value: identifier, click: toggleAssociation" />&nbsp;
								<label data-bind="text:label" style="display:inline-block;"></label>&nbsp;
							 </div>
						</div>
						<div data-bind="foreach: tbl_required_fields()">
							<div class="clearfix" data-bind="visible:ischecked()">
								<span>{t('BOFORMS_SUPPORT_JUSTIFY_REMOVE_FIELD')} - </span> 
								<label data-bind="text:label" style="display:inline-block;"></label><br />
								<textarea rows="3" cols="20" data-bind="value:description"></textarea>
							 </div>
						</div>
					</fieldset>
				</div>
				
				<div id="notification_bloc_2" data-bind="visible: $data.displayBlockNotification(2) == true">
					<fieldset class="a_fieldset">
	    			<legend class="a_legend">{t('BOFORMS_NOTIFICATION_MODIFY_IMPRINT')} 
	    			{literal}<a href="#" class="removal_href" data-bind="click:function(data,event) {notificationDeleteRequest(2);}">X</a></legend>{/literal}
					<div class="clearfix">
						<label>{t('BOFORMS_SUPPORT_EXPLAIN_REQUEST')}</label>
						<textarea data-bind="value: request_more_description_2"></textarea>
					</div>	
					<div class="clearfix" style="color: red;font-weight:bold;" data-bind="visible: all_errors.indexOf('error_notification_2') >= 0, text: '{t('BOFORMS_VALIDATION_FILL_THIS_FIELD')}'"> </div>
					</fieldset>
				</div>
				
				<div id="notification_bloc_3" data-bind="visible: $data.displayBlockNotification(3) == true">
					<fieldset class="a_fieldset">
	    			<legend class="a_legend">{t('BOFORMS_NOTIFICATION_UPD_BUSINESS_COMPONENT')} 
	    			{literal}<a href="#" class="removal_href" data-bind="click:function(data,event) {notificationDeleteRequest(3);}">X</a></legend>{/literal}
					<div class="clearfix" style="color: red;font-weight:bold;" data-bind="visible: all_errors.indexOf('error_notification_3') >= 0, text: '{t('BOFORMS_VALIDATION_PICK_COMPONENT_AND_EXPLAIN')}'"> </div>

						<div data-bind="foreach: tbl_result_compos()" class="a_overflow">
							<div class="clearfix">
								<input type="checkbox" style="display:inline-block;" data-bind="value: label, click: toggleAssociation" />&nbsp;
								<label data-bind="text:label" style="display:inline-block;"></label>&nbsp;
							 </div>
						</div>
						<div data-bind="foreach: tbl_result_compos()">
							 {literal}
							 <div class="clearfix" data-bind="style: { marginTop:'5px', display: ischecked() ? 'block' : 'none' }">
							 {/literal}
							 	<span>{t('BOFORMS_SUPPORT_JUSTIFY_UPDATE_COMPONENT')} - </span>
							 	<label data-bind="text: label" style="display:inline-block;"></label><br />
								<textarea rows="3" cols="20" data-bind="value:description"></textarea>
							 </div>
						</div>
					
					<div class="clearfix">
						<label>{t('BOFORMS_SUPPORT_EXPLAIN_REQUEST')}</label>
						<textarea data-bind="value: request_more_description_3"></textarea>
					</div>
					</fieldset>	
				</div>
				
				
				<div id="notification_bloc_4" data-bind="visible: $data.displayBlockNotification(4) == true">
					<fieldset class="a_fieldset">
	    			<legend class="a_legend">{t('BOFORMS_NOTIFICATION_UPD_USER_INTERFACE')} 
	    			{literal}<a href="#" class="removal_href" data-bind="click:function(data,event) {notificationDeleteRequest(4);}">X</a></legend>{/literal}
					<div class="clearfix">
						<label>{t('BOFORMS_SUPPORT_EXPLAIN_REQUEST')}</label>
						<textarea data-bind="value: request_more_description_4"></textarea>
					</div>
					<div class="clearfix" style="color: red;font-weight:bold;" data-bind="visible: all_errors.indexOf('error_notification_4') >= 0, text: '{t('BOFORMS_VALIDATION_FILL_THIS_FIELD')}'"> </div>
					</fieldset>	
				</div>
				<div id="notification_bloc_5" data-bind="visible: $data.displayBlockNotification(5) == true">
					<fieldset class="a_fieldset">
	    			<legend class="a_legend">{t('BOFORMS_NOTIFICATION_MODIFY_OPT_IN')} 
	    			{literal}<a href="#" class="removal_href" data-bind="click:function(data,event) {notificationDeleteRequest(5);}">X</a></legend>{/literal}
					<div class="clearfix">
						<label>{t('BOFORMS_SUPPORT_EXPLAIN_REQUEST')}</label>
						<textarea data-bind="value: request_more_description_5"></textarea>
					</div>
					<div class="clearfix" style="color: red;font-weight:bold;" data-bind="visible: all_errors.indexOf('error_notification_5') >= 0, text: '{t('BOFORMS_VALIDATION_FILL_THIS_FIELD')}'"> </div>
					</fieldset>		
				</div>
				
				<div id="notification_bloc_6" data-bind="visible: $data.displayBlockNotification(6) == true">
					<fieldset class="a_fieldset">
	    			<legend class="a_legend">{t('BOFORMS_NOTIFICATION_MODIFY_STEP_ORDER')} 
	    			{literal}<a href="#" class="removal_href" data-bind="click:function(data,event) {notificationDeleteRequest(6);}">X</a></legend>{/literal}
					<div class="clearfix">
						<label>{t('BOFORMS_SUPPORT_DESCRIBE_NEEDS')}</label>
						<textarea data-bind="value: request_more_description_6"></textarea>
					</div>
					<div class="clearfix" style="color: red;font-weight:bold;" data-bind="visible: all_errors.indexOf('error_notification_6') >= 0, text: '{t('BOFORMS_VALIDATION_FILL_THIS_FIELD')}'"> </div>
					</fieldset>	
				</div>
				
				<div id="notification_bloc_7" data-bind="visible: $data.displayBlockNotification(7) == true">
					<fieldset class="a_fieldset">
	    			<legend class="a_legend">{t('BOFORMS_NOTIFICATION_OTHER_REQUEST')} 
	    			{literal}<a href="#" class="removal_href" data-bind="click:function(data,event) {notificationDeleteRequest(7);}">X</a></legend>{/literal}
					<div class="clearfix">
						<label>{t('BOFORMS_SUPPORT_DESCRIBE_NEEDS')}</label>
						<textarea data-bind="value: request_more_description_7"></textarea>
					</div>
					<div class="clearfix" style="color: red;font-weight:bold;" data-bind="visible: all_errors.indexOf('error_notification_7') >= 0, text: '{t('BOFORMS_VALIDATION_FILL_THIS_FIELD')}'"> </div>
					</fieldset>	
				</div>
				
			</div>
			
			<div class="clearfix">
			 	<label>{t('BOFORMS_SUPPORT_MODIFICATION_TYPE')}</label>
			 	<select data-bind="disable: type_demande() != Type_Demande.EVOLUTION_FORMULAIRE,options:tblTypeNotification(), optionsText:'name', optionsValue:'id', optionsCaption: lang['BOFORMS_SUPPORT_CHOOSE_MODIFICATION_TYPE'], value:type_notification" style="width:273px;"></select>
				<!-- ko if:  typeof(type_notification()) !== 'undefined' && $data.notificationDisplayAddButton() == true -->
		        	<input type="button" class="btn_send_support_request2" value="{t('BOFORMS_SUPPORT_BTN_ADD_REQUEST')}" data-bind="click: notificationAddRequest"/>
				<!-- /ko -->
			</div>	
			
			
			<div class="clearfix" style="color: red;font-weight:bold;" data-bind="visible: all_errors.indexOf('no_request_added') >= 0, text: '{t('BOFORMS_VALIDATION_ADD_A_NOTIFICATION')}'"> </div>
		</div>	
		
		
		<div class="block_form_anomalie_description" data-bind="visible: type_demande() == Type_Demande.NOTIFICATION_ANOMALIE && priorite() >= 0">
			<div class="clearfix">
				<label>{t('BOFORMS_SUPPORT_DESCRIBE_ANOMALY')}</label>
				<p style="margin-right: 10px; font-style: italic; text-align: justify;">{t('BOFORMS_VALIDATION_HELP_DESCRIBE_ANOMALY')}</p>
				<textarea data-bind="enable: type_demande() == Type_Demande.NOTIFICATION_ANOMALIE, value: anomalie_description" ></textarea>
			</div>
		</div>
		
		<div class="clearfix" style="margin-top:5px;" data-bind="visible: type_demande() >= 0 && priorite() >= 0">	
			<label>{t('BOFORMS_SUPPORT_ADD_FILE')}</label>
			<input type="file" name="fileToUpload" id="fileToUpload">
		</div>	
		
		<div class="block_form_validation_central" data-bind="visible: type_demande() == Type_Demande.VALIDATION_CENTRAL">
			<div class="clearfix">
				<label>{t('BOFORMS_SUPPORT_XML_SAVED_VERSION')}</label>
				<input type="text" data-bind="enable: false, value: xml_registered_version" />
			</div>
		</div>
		
		<div class="block_form_anomalie_description" data-bind="visible: type_demande() == Type_Demande.NOTIFICATION_ANOMALIE && priorite() >= 0">	
			
			<div class="clearfix">
				<label>{t('BOFORMS_SUPPORT_ANOMALY_ALL_FIELDS')}</label>	
				<div data-bind="foreach: tbl_all_fields()" class="a_overflow">
					<div class="clearfix">
						<input type="checkbox" style="display:inline-block;" data-bind="value: identifier, click: toggleAssociation" />&nbsp;
						<label data-bind="text:label" style="display:inline-block;"></label>&nbsp;
					 </div>
				</div>
			</div>
			
			<div class="clearfix" style="color: red;font-weight:bold;" data-bind="visible: all_errors.indexOf('evo_description_empty') >= 0, text: '{t('BOFORMS_VALIDATION_PLEASE_FILL_ABOVE_FIELDS')}'"> </div>
			<div data-bind="foreach: tbl_all_fields()">
				 {literal}
				 <div class="clearfix" data-bind="style: { marginTop:'5px', display: ischecked() ? 'block' : 'none' }">
				 {/literal}
				 	<span>{t('BOFORMS_SUPPORT_DESCRIBE_ANOMALY')} - </span>
				 	<label data-bind="text: label" style="display:inline-block;"></label><br />
					<textarea rows="3" cols="20" data-bind="value:description"></textarea>
				 </div>
			</div>			
		
			<div class="clearfix" style="color: red;font-weight:bold;" data-bind="visible: all_errors.indexOf('evo_form_empty') >= 0, text: '{t('BOFORMS_VALIDATION_PLEASE_FILL_THIS_FORM')}'"> </div>
		</div>		
		
		<div class="clearfix" data-bind="visible: type_demande() == Type_Demande.VALIDATION_CENTRAL || (priorite() > 0 && (type_demande() == Type_Demande.NOTIFICATION_ANOMALIE || type_demande() == Type_Demande.EVOLUTION_FORMULAIRE))"</div>	
			<input type="button" class="btn_send_support_request" value="{t('BOFORMS_SUPPORT_BTN_SEND_SUPPORT_REQUEST')}" style="color: black;" />
		</div>	
		
	</form>
	<!-- /ko -->

</div>

</html>
