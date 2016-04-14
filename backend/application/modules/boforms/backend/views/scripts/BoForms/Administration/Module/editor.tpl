<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
 
  <title>Bo Forms</title>
	{$header}
	
	 <style>
	  {literal}
	  .dialogContactSupport .ui-widget-header {background: none; background-color:#ff0000;}
	  .dialogContactSupport .ui-dialog-title {color: #fff;}
	  .textarea_limited {height: 100px;width: 454px;max-height: 150px;max-width: 120%;}
	  .ulemptyblock {height: 160px;} 
	  {/literal}
	  </style>	

 <script type="text/javascript" src="{Pelican_Plugin::getMediaPath('boforms')}js/tinymce/js/tinymce/tinymce.min.js"></script>

 <script type="text/javascript">
	{literal}
	
	var GENERIC_STEPS = {/literal}{$aStepsGeneric}{literal};
	var FORM_STEPS = {/literal}{$aSteps}{literal};
	var DEVICE_ID = {/literal}{$device_id}{literal};
	//var DEFAULT_LANG = {/literal}{$iDefaultLangID}{literal};
	var ALISTENED = {/literal}{$aListened}{literal}; 
	var ALISTENING = {/literal}{$aListening}{literal};
	var BRAND_ID = '{/literal}{$brand_id}{literal}';

	{/literal}
</script>

</head>
<body>

<div id="page_loader"></div>
<div id='global_div' class="container" style="display:none">

 
  <div class="content">
    <div class="page-header row" >
      <div class="pull-left" style='width:100%;'><h1 style="font-size:24px;">{$form_title} - {$form_part_pro} {$form_plateforme} {$form_contexte} - {$form_device}</h1>
	  <div style="font-size:14px;font-weight:bold;">{$ABtestingName}</div>
	  <div class="pull-right">{$comment_group}</div>
	</div>
	
		
    </div>
	<div style="float:right;"> 
		<i><font color="red">{$messageVersion}</font></i>
	</div>

	<div>
	
	<ul class="tabs" data-tabs="tabs" data-bind="tab: $root.currentTabNiv1">
		<li class="active"><a data-bind="click:clickForm" href="#add-step-pane">{t('BOFORMS_TAB_ORDER')}</a></li>
		<li><a id='tab_versions' data-bind="click:clickVersions" href="#versions-pane">{t('BOFORMS_TAB_VERSIONS')}{literal}</a></li>
	</ul>

   <div class="row">
	
      <div class="span8 sidebar">
       
       <script type="text/html" id="tmpl-versions" data-bind="visible:displayVersionsTab"><b>{/literal}{t('BOFORMS_FORM_REFERENCE')}{literal}</b>
		<ul class="versions">
		
			
		
			{/literal}
			{if $state_id>1 || $get_version=='CURRENT'}
				
				{if !$hasDraft && !$hasPublish && !$hasN1}
				{literal}<li>{/literal}{t('BOFORMS_NO_VERSIONS')}{literal}.</li>{/literal}
				{/if}
				
				
				{if $hasDraft}
				{literal}
				<li>
					<div>{/literal}{t('BOFORMS_DRAFT_VERSION')}{literal} : </div>
					
					<a href="editor?code_instance={/literal}{$sCode}{literal}" style="display:block;float:left" class="btn versions-preview">{/literal}{t('BOFORMS_EDIT')}{literal}</a>					

					<a href="#" style="display:block;float:left" class="btn previewDraft versions-preview">{/literal}{t('BOFORMS_PREVIEW')}{literal}</a>
					
					<a href="#" style="display:block;float:left" class="btn deleteDraft versions-preview">{/literal}{t('BOFORMS_DELETE')}{literal}</a>					
				</li>
				{/literal}
				{/if}
			{/if}
			
			{if $hasPublish}
			{literal}
			<li> 
				<div>{/literal}{t('BOFORMS_PUBLISHED_VERSION')}{literal} : </div>
				
				<a href="editor?code_instance={/literal}{$sCode}{literal}&version=CURRENT" style="display:block;float:left" class="btn versions-preview">{/literal}{t('BOFORMS_EDIT')}{literal}</a>				
				
				<a target="_blank" style="display:block;float:left" href="preview?code_instance={/literal}{$sCode}{literal}&display=current&version=CURRENT" class="btn versions-preview">{/literal}{t('BOFORMS_PREVIEW')}{literal}</a>

			</li>
				{/literal}
			{/if}
			
			{if $hasN1}
				{literal}
			<li> 
				<div>{/literal}{t('BOFORMS_VERSION_N-1')}{literal} : </div>
		
				{/literal}{if $hasN1}{literal}
					<a href="#" class="btn restorePreviousVersion versions-preview" style="display:block;float:left" id="restorePreviousVersion">{/literal}{t('BOFORMS_RESTORE_PREVIOUS_VERSION')}{literal}</a>
				{/literal}{/if}{literal}

				<a target="_blank" href="preview?code_instance={/literal}{$sCode}{literal}&display=current&version=N-1" class="btn versions-preview">{/literal}{t('BOFORMS_PREVIEW')}{literal}</a>

			</li>
				{/literal}

			{/if}
				{literal}
		</ul>
		
		{/literal}
		{if $sTypeInstance == 0 && !$isABtesting}
			{literal}
			<b>{/literal}{t('BOFORMS_VAR_ABTESTING')}{literal}</b>
			
			{/literal}
			{if $aABtesting}
			{literal}				
				<ul class="versions">
					{/literal}
					{foreach from=$aABtesting item=ab}
					{literal}
					<li>
						<div>{/literal}{$ab.FORM_NAME}{literal}</div>
						<input type='hidden' value="{/literal}{$ab.FORM_NAME}{literal}" id="ABTestingFormName_{/literal}{$ab.FORM_AB_TESTING}{literal}" />
						<a target="_blank" href="preview?code_instance={/literal}{$ab.FORM_INCE}{literal}&display=current&version=DRAFT" class="btn versions-preview">{/literal}{t('BOFORMS_PREVIEW')}{literal}</a>
						<a href="editor?code_instance={/literal}{$get_code}{literal}&abtesting={/literal}{$ab.FORM_AB_TESTING}{literal}" class="btn" >{/literal}{t('BOFORMS_EDIT')}{literal}</a>
						<a href="#" class="btn removeABtesting" id="{/literal}{$ab.FORM_INCE}{literal}" >{/literal}{t('FORM_BUTTON_DELETE')}{literal}</a>
					</li>
					{/literal}
					{/foreach}
					{literal}
					
				</ul>
				{/literal}
				
		  	{/if}
		  	
		  	<div>
		  	{if sizeof($aABtesting)<9}
				{literal}
				<a id='abtesting' href="editor?code_instance={/literal}{$get_code}{literal}&abtesting=new" class="btn new_abtesting">{/literal}{t('BOFORMS_NEW_VERSION')}{literal}</a>
		  		{/literal}
		  	{/if}
		  	{if sizeof($aABtesting)>0}
				{literal}
				<a id='abtesting_DIG' href="#" class="btn">{/literal}{t('BOFORMS_ABTESTING_DIG')}{literal}</a>
		  		{/literal}
		  	{/if}
		  	</div>
		  	
	  	{/if}
	  	{literal}
	</script>
        <ul class="tabs tabsNiv2" data-tabs="tabsOrder" data-bind="tab: $root.currentTabNiv2, visible:$root.displayStepsTab" >
          <li class="active"><a data-bind="click:clickStepOrder"  href="#add-step-pane">{/literal}{t('BOFORMS_ORDER')}{literal}</a></li>
          <li data-bind="visible: $root.displayStepParam() == true"><a  href="#modif-step-pane">{/literal}{t('BOFORMS_STEP_PARAM')}{literal}</a></li>
          
          {/literal}
		  {if $sTypeInstance == 0}
		  {literal}
          <li data-bind="visible: $root.displayFields() == true"><a  href="#add-field-pane">{/literal}{t('BOFORMS_MODIFY_FIELD')}{literal}</a></li>
          {/literal}
		  {/if}
		  {literal}
        </ul>
        
         <ul class="tabs" data-tabs="tabs" data-bind="tab: $root.currentTab, visible:displayFieldsTab" >
          <li class="active"><a href="#add-field-pane">{/literal}{t('BOFORMS_ADD_FIELD')}{literal}</a></li>
          {/literal}{if $sTypeInstance == 0}{literal}<li data-bind="visible: $root.selectedField "><a href="#field-settings-pane">{/literal}{t('BOFORMS_FIELD_PARAM')}{literal}</a></li>{/literal}{/if}{literal}
          
        </ul>

     

 	
        <div class="tab-content span6">
          	<!-- ADD STEP  --------------------------------------------------------------------------->
			<div class="tab-pane active" id="add-step-pane">
		    	<div data-bind="template: 'tmpl-add-step', visible:displayStepsGeneric"></div>
		  	</div>
		  	
		  	<!-- MODIF STEP  --------------------------------------------------------------------------->
			<div class="tab-pane" id="modif-step-pane">
		    	<div data-bind="template: 'tmpl-modif-step', data:stepTitle,  visible:displayStepParam"></div>
		  	</div>
          
          	<!-- ADD FIELD --------------------------------------------------------------------------->
			<div class="tab-pane" id="add-field-pane">
		    	<div data-bind="template: 'tmpl-add-field', visible:displayFields"></div>
		  	</div>
		  	
		  	<!-- MODIF FORM -------------------------------------------------------------------------->
		  	<div class="tab-pane" id="field-settings-pane">
		    	<div data-bind="template: {name: 'tmpl-field-settings', data: selectedField }"></div>
		  	</div>
		  	
		  	
		  	<!-- VERSIONS ---------------------------------------------------------------------------->
			<div class="tab-pane" id="versions-pane">
	            <div data-bind="template: {name: 'tmpl-versions', data:versions }"></div>
	        </div>
		</div>
		
		
	</div>

	<!------------------------------------------------------------------------------------------------>
	<!-- PANNEAU DROIT ------------------------------------------------------------------------------->
	<!------------------------------------------------------------------------------------------------>

    
	<!-- ETAPES ---------------------------------------------------------------->
	<div class="span8 main-content" data-bind="visible:$root.displayStepsForm">
		<a href="#" class="btn primary preview previewDraft">{/literal}{t('BOFORMS_PREVIEW')}{literal}</a><br/><br/>
		
		<div class="selectedElement-pane" style="margin-top: 5px;";>
        <div class="clearfix">
            <h3>{/literal}{t('BOFORMS_INTRO')}{literal}</h3>
            <textarea style="height:50px;width:240px;margin-left:10px;" data-bind="value: $root.formCommentary"></textarea>
        </div>
        <div class="clearfix" style="margin-left:10px;">  
            <input type="checkbox" data-bind="checked: $root.formCommentaryVisible" />
            <span>{/literal}{t('BOFORMS_VISIBLE')}{literal}</span>
        </div>          
    </div>
		
		<div class="selectedElement-pane" style="margin-top: 19px;";>
			<h3>{/literal}{t('BOFORMS_SELECTED_STEP')}{literal}</h3>
			<form class="form-stacked" style="margin-top:10px;margin-left:10px;">
				<div  data-bind="foreach: $root.stepsList" >
					<div class="clearfix" style="margin-bottom:5px;"> 
						<button class="btn info" data-bind="text: title, click: $root.clickStepParam" ></button>
						
						{/literal}
						{if $sTypeInstance == 0}
						{literal}
						<button class="btn success" data-bind="click: function(data, event) { $root.setStepFields($data) }" >{/literal}{t('BOFORMS_MODIFY_FIELD')}{literal}</button>
						
						{/literal}
						{/if}
						{literal}
						<br/><br/>
					</div>
				</div>
			</form>
        	
        </div>
	</div>


	
	<!-- ETAPE PREVIEW -----------------------------------------------------
	<script type="text/html" id="tmpl-steps-preview"><div  data-bind="sortable: {data:$root.stepsList, options:{containment: 'parent'}, afterMove: function(data, event){ $root.afterMovedStep(data, event, $root.stepsList) }}">
			<div>
			<button class="btn info" data-bind="text: title, click: $root.clickStepParam" ></button>
			
			<button class="btn success" data-bind="click: function(data, event) { $root.setStepFields($data) }" >{/literal}{t('BOFORMS_MODIFY_FIELD')}{literal}</button>
			<br/><br/>
			</div>
		</div>
	</script>-->
	
	<!-- FORM STANDARD OU PERSO ----------------------------------------------->
	<div class="span8 main-content" data-bind="visible:displayFields">
		<a href="#" class="btn primary preview previewDraft">{/literal}{t('BOFORMS_PREVIEW')}{literal}</a><br/><br/>
		
		<!-- ko if: $root.stepTitle-->
			<div class="alert-message" data-bind="text:$root.stepTitle"></div>
		<!-- /ko-->
		<!-- ko ifnot : $root.stepTitle-->
			<div style="margin-bottom:72px;"></div>
		<!-- /ko-->

		<div  style="margin-top:35px">
			<h3>{/literal}{t('BOFORMS_SELECTED_ITEMS')}{literal}</h3>
		</div>
        <form data-bind="template: {name: 'tmpl-field-preview'}" class="form-stacked"></form>
	
	</div>

	<!-- STEP TAGS UNDER QUESTION -------------------------------------->
	<script type="text/html" id="tmpl-step-tags-under-question-preview">
		<ul>		
			<fieldset  style="border:1px solid #ddd;border-radius:6px">
 				<!-- ko foreach: tagsUnderQuestion --> 				
					<div class="clearfix field-wrapper">				
						<label>
 							<span>
								<span data-bind="text: name"></span>
							</span>
						</label>
						<div style="float:left">
	 						<input class="xlarge" type="text" data-bind="value: label" />
						</div>
					</div>
				<!-- /ko -->
			</fieldset>		
		</ul>
	</script>

	<!-- STEP CONFIGURATION -------------------------------------->
	<script type="text/html" id="tmpl-step-configuration-preview">
		<ul>		
			<fieldset  style="border:1px solid #ddd;border-radius:6px">
 				<div class="clearfix field-wrapper" data-bind="visible: stepConfiguration().next_label_display">				
					<label>
 						<span>
							<span>{/literal}{t('BOFORMS_CONFIGURATION_NEXT_LABEL')}{literal}</span>
						</span>
					</label>
					<div style="float:left">
	 					<input class="xlarge" type="text" data-bind="value: stepConfiguration().next_label" />
					</div>
				</div>
				<div class="clearfix field-wrapper" data-bind="visible: stepConfiguration().previous_label_display">				
					<label>
 						<span>
							<span>{/literal}{t('BOFORMS_CONFIGURATION_PREVIOUS_LABEL')}{literal}</span>
						</span>
					</label>
					<div style="float:left">
	 					<input class="xlarge" type="text" data-bind="value: stepConfiguration().previous_label" />
					</div>
				</div>
			</fieldset>		
		</ul>
	</script>
	
	
	<!-- STEP GENERIC CONFIGURATION  -------------------------------------->
	<script type="text/html" id="tmpl-step-configuration-generic-preview">
		<ul>		
			<fieldset  style="border:1px solid #ddd;border-radius:6px">
 				<div class="clearfix field-wrapper" data-bind="visible: stepConfigurationGeneric().next_label_display">				
					<label>
 						<span>
							<span>{/literal}{t('BOFORMS_CONFIGURATION_NEXT_LABEL')}{literal}</span>
						</span>
					</label>
					<div style="float:left">
	 					<span data-bind="text: stepConfigurationGeneric().next_label" ></span>
					</div>
				</div>
				<div class="clearfix field-wrapper" data-bind="visible: stepConfigurationGeneric().previous_label_display">				
					<label>
 						<span>
							<span>{/literal}{t('BOFORMS_CONFIGURATION_PREVIOUS_LABEL')}{literal}</span>
						</span>
					</label>
					<div style="float:left">
	 					<span data-bind="text: stepConfigurationGeneric().previous_label" ></span>
					</div>
				</div>
			</fieldset>		
		</ul>
	</script>
	
	
	
	<!-- GENERIC FIELD PREVIEW -------------------------------------->
	<script type="text/html" id="tmpl-field-preview"><style>
	  	.prevent{background-color:#778CBD;}
	  	.sortable-placeholder{border:1px dotted black;min-height:50px;background:white}
	  	
	  </style>
	  

	  <ul id='root_sortable' data-bind="sortable: {data:fieldsets, options:{containment: 'parent'}, connectClass:false, afterMove: function(data, event){ $root.afterMovedFieldset(data, event, $data) }}">
		    <fieldset  style="border:1px solid #ddd;border-radius:6px" data-bind="visible: displayFieldset">
		        <legend class="fieldsetLegend">
					<div class="legend_div" >
						<div class="legend_div_hand">
							<a href="#" class="btn info" data-bind="attr:{alt: name}">
		        				<img src="/modules/boforms/images/hand.png" class="legend_div_img"/>
		        			</a>
							&nbsp;
							
							<a data-bind="visible:fieldsetIsCollapsed,click: $data.addQuestionAtTop" class="btn" id="btn_add_block" href="#">
								<p style="float:left;"><img src="/modules/boforms/images/addQuestion.png" class="legend_div_img" /></p>
								<p id="btn_add_block_text">&nbsp;{/literal}{t('BOFORMS_NEW_BLOCK')}{literal}&nbsp;</p>
							</a>
						</div>

						<div class="legend_div_collapse">	
		        			<a data-bind="click: $root.displayFieldset" class="btn displayFieldset" href="#">
								<img src="/modules/boforms/images/moins.png" class="legend_div_img" />
							</a>
						</div>						
					</div>
		        </legend>
		        <ul  class="fieldset" data-bind="sortable: {data:questions, allowDrop:true, connectClass:false,options:{containment: 'parent', opacity: 0.5}, afterMove: function(data, event){ $root.afterMovedQuestion(data, event, $data) }}, attr:{id:classid}">
		        	<fieldset  style="background-color:#ddd" data-bind="visible: displayQuestion, contextMenu: {
'{/literal}{t('BOFORMS_ADD_BLOCK')}{literal}': function(e) {  $parent.addQuestionHere(e, $data); }, 
'{/literal}{t('BOFORMS_DELETE_BLOCK')}{literal}': {'visible': isNewQuestion && hasNoLines,'action': function(e) {  $parent.deleteThisQuestion(e, $data); }} }">
		            	<legend style="width:95%"> <a href="#"class="btn primary" data-bind="visible: $parent.questions().length > 1">
		        	<img src="/modules/boforms/images/hand.png" data-bind="attr:{alt: name}"/>
		        	</a>
        			<button style="display:inline-block;float:right;" class="btn xsmall danger" data-bind="visible:isNewQuestion && hasNoLines,click: function(data, e) {  $parent.deleteThisQuestion(e, $data); }">-</button>
					</legend>
		            	
		            	<a href="#" data-bind="click: function(data, event) { $root.selectCompoAv($parent, $data, event) }" ><img  style="width:400px;padding:5px" data-bind="visible:template,attr:{src:template}"/></a>
		            	
				            <ul style="padding-top:5px;padding-bottom:5px" data-bind="css: { ulemptyblock: hasNoLines }, sortable: {data:lines, options: {axis:'y',containment:'#root_sortable', opacity: 0.5, placeholder: 'sortable-placeholder' }, beforeMove: function(arg) { $root.beforeMoveLine(arg);}, afterMove: function(data, event){ $root.afterMovedLine(data, event, $data) }}">
				            	
					            <fieldset data-bind="visible: displayLine" style="min-height:50px;display:block;border-radius:6px;background:#eee;margin-right:5px" >
								<div style="background-color: #bbcff9;border-radius:2px;"><img style="margin:2px;" src="/modules/boforms/images/hand.png" title="tmptosupprimag"/></div>
								<div data-bind="sortable:{data:fieldsStandard, options:{cancel: '.prevent',  containment:'parent', opacity: 0.5, placeholder: 'sortable-placeholder' },  afterMove: function(data, event){ $root.afterMoved(data, event, $data); }, connectClass:'fieldsStandard'}">									
									<div data-bind="attr:{'class':listener},ifnot: isAlternativ, css: { prevent: preventSort} ">
						            	<div class="clearfix field-wrapper"
									    	   data-bind="{/literal}{if $sTypeInstance ==0}{literal}click: function(data, event) { $root.selectField($parent, $data, event) }{/literal}{/if}{literal},
									                  css: { selected: $data == $root.selectedField() }">
										    	<label>
											    	{/literal}{* <img src="/modules/boforms/images/html-icon.png" alt="HTML Bloc" data-bind="visible:displayHtmlIcon"/> *}{literal}
											    	<span data-bind="if: is_required" class="required">*</span>

													<a href="#" style="text-decoration:none;color:#404040;" data-bind="if:type() == 'html' || type() == 'button',event: {keypress: function (data, event) { return block_keydown_rightpane(data, event, $root,$parent); }}">
											      		<span data-bind="text: TitleStripped,visible:displayTitleStripped"></span>
													</a>

										      		<span data-bind="if:type() != 'html' && type() != 'button'">
														<span data-bind="text: TitleStripped,visible:displayTitleStripped"></span>
													</span>
										    	</label>

										    	<div style="float:left" data-bind="template: previewTemplateName"></div>
												
									    		<div class="pull-right" data-bind="visible: field_is_removable">
									      			<button data-bind="click: function(data, event) { $root.removeField($parent, data, event, true) }, attr: { 'data-type': type }, visible:required_central" class="btn xsmall danger">-</button>
									    		</div>

											</div>
								  		</div>
							  	</div>
								  	
							  	<div data-bind="attr:{title:name}, sortable:{cancel: '.prevent',  data:fieldsStandard, options:{containment:'parent', opacity: 0.5, placeholder: 'sortable-placeholder' }, afterMove: function(data, event){ $root.afterMoved(data, event, $data) }, connectClass:'fieldsStandard'}">
							  		<div  data-bind="if:isAlternativ, css: { prevent: preventSort }"> 
					            		
					            		<button data-bind="visible:isDisplayed,click: function(data, event) { $root.selectField($parents[1], $data, event) }" class="btn"> {/literal}{$label_phone}{literal} </button> <br/>
					            		
					            		<!-- Si au moins 1 des champs n est pas removable alors l ensemble ne l est pas -->
					            		<div class="pull-right" >
									      <button data-bind="click: function(data, event) { $root.removeFieldAlternativ($parent, data, event, true) }, attr: { 'data-type': type }, visible:required_central && isDisplayed" class="btn xsmall danger">-</button>
									    </div>
					            	</div>
				            		
								</div>
					            
					            
					        </ul>
					
				    </fieldset>
		        </ul>
		    </fieldset>
		</ul></script>
		
		
		
  </div>

	
<div class="row" data-bind="visible: displayFields && (stepConfigurationGeneric().configuration_ok || stepConfiguration().configuration_ok)  && displayFields">
	<div class="span8" >
		<div  style="margin-top:35px" data-bind="visible: stepConfigurationGeneric().configuration_ok">
			<h3>{/literal}{t('BOFORMS_STEP_CONFIGURATION_GENERIQUE')}{literal}</h3>
		</div>        
		<form data-bind="visible: stepConfigurationGeneric().configuration_ok,template: {name: 'tmpl-step-configuration-generic-preview'}" class="form-stacked"></form>		
	</div>

	<div class="span8 main-content" >
		<div  style="margin-top:35px" data-bind="visible: stepConfiguration().configuration_ok">
			<h3>{/literal}{t('BOFORMS_STEP_CONFIGURATION')}{literal}</h3>
		</div>        
		<form data-bind="visible: stepConfiguration().configuration_ok,template: {name: 'tmpl-step-configuration-preview'}" class="form-stacked"></form>
	</div>
</div>	

<div class="row" data-bind="visible: displayFields && displayTagsUnderQuestion">
	<div class="span8" >
		<div  style="margin-top:10px"></div>        
	</div>

	<div class="span8 main-content" >
		<div  style="margin-top:10px">
			<h3>{/literal}{t('BOFORMS_STEP_TAGS_UNDER_QUESTION')}{literal}</h3>
		</div>        
		<form data-bind="template: {name: 'tmpl-step-tags-under-question-preview'}" class="form-stacked"></form>
	</div>
</div>	
	
</div>
<div id="dialog-confirm"></div>

{/literal}{if $isABtesting}{literal}
<div id="dialog-ABtesting" style="display:none;">
	<div>
		<label>Intitulé de l'ABtesting :</label> 
		<input type="text" value="{/literal}{$form_name}{literal}" classe="titleField" id="ABtesting_title" />
	</div>
</div>
{/literal}{/if}{literal}
<div style="text-align:center; margin:10px 5px 10px 5px"> 
	<i><font color="red">{/literal}{$messageVersion}{literal}</font></i>
</div>
<footer class="footer">
	
  <div class="container">
  
  <form id='form_result' name='form_result' action="#" method="POST" onsubmit="return false;">
  	<center>
  	<input id='culture_id' type="hidden" name="culture_id" value ="{/literal}{$culture_id}{literal}"/> 
	<input id='result' type="hidden" name="result" data-bind="value:$root.jsonRes"/>
	<input id='formCommentary' type="hidden" name="formCommentary" data-bind="value:$root.formCommentary" />
	<input id='formCommentaryVisible' type="hidden" name="formCommentaryVisible" data-bind="value:$root.formCommentaryVisible" />
	<input id='xmlPerso' type="hidden" name="xmlPerso" value="{/literal}{$xmlPerso}{literal}"/>
	
	{/literal}{if ! $isABtesting}{literal}
	<input id="doPublishBtn" type="submit" name='publier' value="{/literal}{t('BOFORMS_BUTTON_PUBLISH')}{literal}" class="btn primary doPublishBtn"> 
	{/literal}{/if}{literal}
	
	{/literal}{if ! $isABtesting}{literal}
	<input id="doSaveBtn" type="button" name='draft' value="{/literal}{t('BOFORMS_BUTTON_SAVE')}{literal}" class="btn primary">
	{/literal}{else}{literal}
	<input id="doSaveBtnABtesting" type="button" name='draft' value="{/literal}{t('BOFORMS_BUTTON_SAVE')}{literal}" class="btn primary">
	{/literal}{/if}{literal}
	
	{/literal}{if ! $isABtesting}{literal}
		<!--input id="doResetBtn" type="button" name='reset' value="{/literal}{t('BOFORMS_BUTTON_RESET')}{literal}" class="btn btnResetForm"--> 
	{/literal}{/if}{literal}
	
	{/literal}{if $isABtesting}{literal}
		<button onclick="if(confirm('{/literal}{t('BOFORMS_CONFIRM_CLOSE')}{literal}')){ window.location = '{/literal}{$REDIRECT_URL}{literal}?code_instance={/literal}{$get_code}{literal}&tab=versions';}else{return false}" id="btncancel" class="btn cancel">{/literal}{t('POPUP_BUTTON_CANCEL')}{literal}</button>
	{/literal}{else}{literal}
		<button onclick="if(confirm('{/literal}{t('BOFORMS_CONFIRM_CLOSE')}{literal}')){ window.close();}else{return false}" id="btncancel" class="btn cancel">{/literal}{t('BOFORMS_CANCEL')}{literal}</button>
	{/literal}{/if}{literal}
	
	{/literal}{if ! $isABtesting}{literal}
	<a href="#" id="opener" class="btn info">{/literal}{t('BOFORMS_SUPPORT_CONTACT')}{literal}</a>
	{/literal}{/if}{literal}
	<center>
	</form>
	
	
	<div id="dialog" >
		<iframe id="iframe_contact" height="100%" width="100%" frameborder=0 src="/_/module/boforms/BoForms_Administration_SupportRequest/emptyTask"></iframe>
	</div>
	
	<script>
	var redisplayContactSupport = false;
	$( "#dialog" ).dialog({ 
			title:"{/literal}{$form_title} - {$form_part_pro} {$form_plateforme} {$form_contexte} - {$form_device}{literal}",
			autoOpen: false, 
			width:700, height: 550, 
			modal:false, dialogClass: "dialogContactSupport",
			closeText: "hide",

			open: function (event, ui) {
						$("#dialog iframe").attr("src", "/_/module/boforms/BoForms_Administration_SupportRequest/supportDialog?sCode={/literal}{$sCode}{literal}");
					    $('#dialog').css('overflow', 'hidden'); //this line does the actual hiding

						// Sauvegarde du formulaire dans le BO PHP Factory et non dans la base Forms
						redisplayContactSupport = true;
					    saveForm('draft',true);			    
			},
			buttons: {
				"{/literal}{t('BOFORMS_CLOSE_POPUP')}{literal}": function() { 
				      $(this).dialog("close");
				}
			}, 
			close: function (event, ui) {
				// empty the iframe
				$("#dialog iframe").html("/_/module/boforms/BoForms_Administration_SupportRequest/emptyTask");
			}					 
	});
	$( "#opener" ).click(function() {
		$.get( "/_/module/boforms/BoForms_Administration_Module/checkFormEditable?code_instance={/literal}{$sCode}{literal}", function( data ) {
			if (data == '1') {
				$("#dialog").dialog("open");
			} else {
				alert(data);
			}
		});
	});
	</script>
	
	<br/><!-- pre data-bind="text:$root.jsonRes"></pre-->
    
  <!--<center><button id="btnok" onclick="submitForm()" class="btn primary">Valider</button>&nbsp;<button onclick="if (confirm('Etes-vous sÃ»r(e) de fermer cette fenÃªtre ?')) window.close();" id="btncancel" class="btn cancel">Annuler</button></center>-->
    <!--<h6>Keyboard shortcuts</h6>
    <ul>
      <li><strong>j</strong> Select next field</li>
      <li><strong>k</strong> Select previous field</li>
      <li><strong>DEL or BACKSPACE</strong> Delete selected field</li>
    </ul>-->
    
    
    
  </div>
</footer>


<!------------------------------------------------------------------------------------------------>
<!-- PANNEAU GAUCHE ------------------------------------------------------------------------------>
<!------------------------------------------------------------------------------------------------>


<!-- FORM GENERIC ----------------------------------------------->
<script type="text/html" id="tmpl-add-field">
<h3>{/literal}{t('BOFORMS_AVAILABLE_ITEMS')}{literal}</h3>
		<form class="form-stacked">
			<ul data-bind="foreach: fieldsetsGeneric">
			    <fieldset  style="border:1px solid #ddd;border-radius:6px"  data-bind="visible: displayFieldset">

			        <ul data-bind="foreach: questionsGeneric">
			        	
			        	<fieldset  style="background-color:#ddd" data-bind="visible: displayQuestion">
			        		<div style="opacity: 0.5;filter: alpha(opacity=50);" data-bind="visible:template">
			            	<img style="width:280px;padding:5px" data-bind="visible:template,attr:{src:template}"/>
			            	</div>
				            <ul data-bind="foreach:linesGeneric"> 
				            	<div data-bind="visible: displayLine">

					            <fieldset  style="border-radius:6px;background:#eee;padding-left:5px;margin-right:5px">
					            
					            
					             <div class="genericFields" data-bind="foreach: fieldsGeneric">
					            		<!-- Gestion des types de field alternatifs -->
					            		<span  data-bind="if:isAlternativ">
					            			<button data-bind="visible:isDisplayed, attr: { 'data-name': name }, enable:isEnabled,click: function(data, event) { $root.addFieldAlternativ($parent, $data, event) }"class="btn info"> {/literal}{$label_phone}{literal} </button> <br/>
					            		</span>
					            	
					            		<!-- Gestion des types de field normaux -->
					            		<span data-bind="ifnot:isAlternativ">
						            		<span data-bind="foreach: title, visible:isNotHidden">
						            			<span data-bind="if:$parent.isEnabled">
					            					<button class="btn primary" data-bind="css: { profitWarning: $parent.isEnabled, majorHighlight: !$parent.isEnabled },text: titleStripTagged, visible:isDefaultLanguage, attr: { 'data-name': $parent.name},  click: function(data, event) { $root.addField($parents[1], $parent, event) }{/literal}{if $sTypeInstance ==0}{literal}, enable:$parent.isEnabled{/literal}{/if}{literal}" {/literal}{if $sTypeInstance !=0}disabled="disabled"{/if}{literal} ></button><br/>
						            			</span>
						            			<span data-bind="ifnot:$parent.isEnabled">
													<button class="btn info"    data-bind="text: titleStripTagged, visible:isDefaultLanguage, attr: { 'data-name': $parent.name},  click: function(data, event) { $root.addField($parents[1], $parent, event) }{/literal}{if $sTypeInstance ==0}{literal}, enable:$parent.isEnabled{/literal}{/if}{literal}" {/literal}{if $sTypeInstance !=0}disabled="disabled"{/if}{literal} ></button><br/>
						            			</span>
											</span>
										
						            		<button class="btn info" data-bind="text: name, visible:!isNotHidden,  attr: { 'data-name': name},  click: function(data, event) { $root.addField($parent, data, event) }{/literal}{if $sTypeInstance ==0}{literal}, enable:$parent.isEnabled{/literal}{/if}{literal}" {/literal}{if $sTypeInstance !=0}disabled="disabled"{/if}{literal} ></button><br/>
										</span>
								</div>
					            
								</div>
					        </ul>
					    </fieldset>
			        </ul>
			    </fieldset>
			</ul>
		</form>
</script>

<!-- STEPS GENERIC --------------------------------------------->
<script type="text/html" id="tmpl-add-step">
<h3 style="margin-top:143px;">{/literal}{t('BOFORMS_AVAILABLE_STEP')}{literal}</h3>
	
		<div style="padding:10px"  class="genericSteps" data-bind="foreach: $root.genericSteps" >
			<button disabled class="btn info" data-bind="text: title"></button><br/><br/>
		</div>
</script>

<!-- STEP MODIF ------------------------------------------------->
<script type="text/html" id="tmpl-modif-step">
<form class="form-stacked">
			<div class="clearfix">
				<label>{/literal}{t('BOFORMS_LABEL_TITLE')}{literal}</label>
				<input type="text" data-bind="value: stepTitle, event: { focusout: setStepFieldTitle}" >
			</div>
		</form>
</script>

<!-- FORM PARAM FIELD ------------------------------------------>
<script type="text/html" id="tmpl-field-settings-form">
<form name="form_param_field" id="form_param_field" class="form-stacked">
		<span data-bind="visible:$root.displayCommonForm">
			<div class="clearfix">
				<label>{/literal}{t('BOFORMS_LABEL_CODE')}{literal}</label>
				<input id="param_field_code" type="text" readonly="true" data-bind="value: name">
			</div>
			<!--div class="clearfix">
				<label>{/literal}{t('BOFORMS_LABEL_TITLE')}{literal}</label>
				
					<div id="content">
					    <ul id="tabs" class="tabs" data-tabs="tabs">
					    	{/literal}{foreach from=$aLanguages item=lang}
					    		<li {if $lang['id'] == $iDefaultLangID}class="active"{/if}><a href="#{$lang['id']}" data-toggle="tab">{$lang['label']}
					    			<img src="/modules/boforms/images/{$lang['id']}-langflag.png" alt=""/>
					    		</a></li>
					    	{/foreach}
					        {literal}
					       
					    </ul>
					    
					    <div id="my-tab-content" class="tab-content" data-bind="foreach: title" >
					        <div class="tab-pane " data-bind="attr:{id:id}, css: { active: isDefaultLanguage}">
					        	<textarea data-bind="value: title, event: { focusout: function(data, event) { $root.setTitleFieldValues($parent, data, event) } }, attr:{id:id}" class="textarea_limited"></textarea>
					        </div>
					    </div>
					</div>
			</div-->
			
			<!-- ko if: name() != 'SBS_USR_OFFER' && name() != 'SBS_COM_OFFER'-->

				<div class="clearfix">
					<label>{/literal}{t('BOFORMS_LABEL_TITLE')}{literal}</label>
					<textarea  data-bind="value: titre, event: { focusout: function(data, event) { $root.setTitleFieldValues($parent, data, event) } }" class="textarea_limited"></textarea>
				</div>
				
				<!-- ko if: ((name() == 'USR_CIVILITY' || name() == 'USR_PHONE_TYPE' || name() == 'USR_PLAN_RENEW_DATE' || name() == 'COM_RENEWAL_VP') && (type() == 'radio' || type() == 'checkbox' || type() == 'dropdown')) -->
					<div class="clearfix">
						<label>{/literal}{t('BOFORMS_CHANGE_FIELD_TYPE')}{literal}</label>
						<select data-bind="options:$root.tblSettingType, optionsText:'name', optionsValue:'id', value:type"></select>
					</div>
				<!-- /ko-->
				
				{/literal}
				
				{literal}
					<div class="clearfix">
						<label>{/literal}{t('BOFORMS_LABEL_ALIGNEMENT')}{literal} </label>
						{/literal}{t('BOFORMS_LABEL_ALIGNEMENT_LEFT')}{literal} <input type="radio" value="left" data-bind="checked:align" name="align"/><br/>
						{/literal}{t('BOFORMS_LABEL_ALIGNEMENT_TOP')}{literal} <input type="radio" value="top" data-bind="checked:align" name="align"/>
					</div>
					
					<div class="clearfix">
						<label>{/literal}{if $bLP}{t('BOFORMS_LABEL_INSTRUCTIONS')}{else}{t('BOFORMS_LABEL_INSTRUCTIONS')}{/if}{literal}</label>
						<textarea data-bind="value: instructions,event: { focusout: $root.setStepFieldsValues}" class="textarea_limited"></textarea>
					</div>
				{/literal}
				
				{literal}
				
			
				

			<!-- /ko -->


			<!-- ko if: name() == 'USR_EMAIL' -->
			        {/literal}{if $form_opportunity != 'SUBSCRIBE_NEWSLETTER' && $form_opportunity != 'UNSUBSCRIBE_NEWSLETTER'}{literal}
				    	<label>{/literal}{t('BOFORMS_USR_EMAIL_LISTENER_MESSAGE2')}{literal}</label>
				    	<textarea class="textarea_limited" data-bind="value: emailParamMessageValue"></textarea>
					{/literal}{/if}{literal}
			<!-- /ko-->



			<div class="clearfix">
				<label>
					<input type="checkbox" data-bind="checked: is_required, enable: required_central,event: { focusout: $root.setStepFieldsValues}">
					<span>{/literal}{t('BOFORMS_LABEL_REQUIRED?')}{literal}</span>
				</label>
			</div>
			
			<!-- ko if: name() != 'SBS_USR_OFFER' && name() != 'SBS_COM_OFFER'-->

					<div class="clearfix">
						<label>{/literal}{t('BOFORMS_LABEL_REQUIRED_MSG')}{literal}</label>
						<textarea data-bind="value: required_msg, event: { focusout: $root.setStepFieldsValues}" class="textarea_limited"></textarea>
		
					</div>

			<!-- /ko -->
			
		</span>

		<div data-bind="template: settingsTemplateName"></div>
		

		<div class="clearfix" data-bind="if:change_etape">
			<label>{/literal}{t('BOFORMS_LABEL_CHANGE_STEP')}{literal}</label>
	      	<ul data-bind="foreach: $root.stepsList, visible:change_etape" class="unstyled">
				<input type="radio" data-bind="value:id, attr:{id:id}, disable:isSelected, click: function(data, event) { $root.setStepField($parent, data, event)}" name="steps_field" >
				<label data-bind="text: title, attr:{for:id}" style="display: inline;font-weight: normal;" ></label><br/>
					
			</ul>
	     </div>

		<span data-bind="if: $data.datePickerVisible">
			<span data-bind="with: datePicker">
				<div class="clearfix">
					<label>{/literal}{t('BOFORMS_DATEPICKER_START_DATE')}{literal}</label>
					<input type="text" data-bind="datepicker: {showButtonPanel: true, dateFormat: 'yy-mm-dd',beforeShow: beforeShowPicker},value: dateStart" class="xlarge titleField"/>
				</div>
				<div class="clearfix">
					<label>{/literal}{t('BOFORMS_DATEPICKER_END_DATE')}{literal}</label>
					<input type="text" data-bind="datepicker: {dateFormat: 'yy-mm-dd',beforeShow: beforeShowPicker},value: dateEnd" class="xlarge titleField"/>
				</div>
				
				<div class="clearfix">
					<label>{/literal}{t('BOFORMS_DATEPICKER_OPENING_START_DATE')}{literal}</label>
					<input type="text" data-bind="value:openingStart" class="xlarge titleField" style="width:120px;" />&nbsp;<span>Format: hh:mm:ss</span>
					<div class="clearfix" style="color: red;font-weight:bold;" data-bind='visible: openingStart.hasError, text: openingStart.validationMessage'> </div>					
				</div>

				<div class="clearfix">
					<label>{/literal}{t('BOFORMS_DATEPICKER_OPENING_END_DATE')}{literal}</label>
					<input type="text" data-bind="value: openingEnd" class="xlarge titleField" style="width:120px;" />&nbsp;<span>Format: hh:mm:ss</span>
					<div class="clearfix" style="color: red;font-weight:bold;" data-bind='visible: openingEnd.hasError, text: openingEnd.validationMessage'> </div>
				</div>

				<!-- referentiel non modifiable -->
				<!--span data-bind="with: libeletEnumeration">
					<div class="clearfix">
						<label>{/literal}{t('BOFORMS_DATEPICKER_LIBELET_ENUM')}{literal}</label>
						<span data-bind="foreach: items" >
							<input type="text" data-bind="value: value" class="xlarge titleField"/>
						</span>					
					</div>
				</span-->

				<!--span data-bind="with: dayEnumeration">
					<div class="clearfix">
						<label>{/literal}{t('BOFORMS_DATEPICKER_DAYS')}{literal}</label>
						<span data-bind="foreach: items" >
							<input type="text" data-bind="value: value" class="xlarge titleField"/>
						</span>					
					</div>
				</span-->

				<!--span data-bind="with: monthEnumeration">
					<div class="clearfix">
						<label>{/literal}{t('BOFORMS_DATEPICKER_MONTH')}{literal}</label>
						<span data-bind="foreach: items" >
							<input type="text" data-bind="value: value" class="xlarge titleField"/>
						</span>					
					</div>
				</span-->

				<span data-bind="with: forbiddenDays">
					<span>{/literal}{t('BOFORMS_DATEPICKER_FORBIDDEN_DAYS')}{literal}</span>
				
					<div class="clearfix" data-bind="with: day">
						<label>{/literal}{t('BOFORMS_DATEPICKER_DAYS')}{literal}</label>
						<span data-bind="foreach: items" >
							<input type="text" data-bind="datepicker: {dateFormat: 'dd/mm/yy'},value: value" class="xlarge titleField"/>
        					<button data-bind="click: function(data, event) { $parent.removeForbidden($parent, $data) }" class="xsmall btn danger">-</button>
						</span>
						<button data-bind="click: function(data, event) { $data.addForbidden($data) }" class="btn xsmall success">+</button>						
					</div>

					<!--div class="clearfix" data-bind="with: month">
						<label>{/literal}{t('BOFORMS_DATEPICKER_MONTH')}{literal}</label>
						<span data-bind="foreach: items" >
							<input type="text" data-bind="value: value" class="xlarge titleField"/>
        					<button data-bind="click: function(data, event) { $parent.removeForbidden($parent, $data) }" class="xsmall btn danger">-</button>
						</span>
						<button data-bind="click: function(data, event) { $data.addForbidden($data) }" class="btn xsmall success">+</button>						
					</div-->

					<!--div class="clearfix" data-bind="with: year">
						<label>{/literal}{t('BOFORMS_DATEPICKER_YEARS')}{literal}</label>
						<span data-bind="foreach: items" >
							<input type="text" data-bind="value: value" class="xlarge titleField"/>
        					<button data-bind="click: function(data, event) { $parent.removeForbidden($parent, $data) }" class="xsmall btn danger">-</button>
						</span>
						<button data-bind="click: function(data, event) { $data.addForbidden($data) }" class="btn xsmall success">+</button>						
					</div-->

					<!--div class="clearfix"  data-bind="with: date">
						<label>{/literal}{t('BOFORMS_TYPE_DATE')}{literal}</label>
						<span data-bind="foreach: items" >
							<input type="text" data-bind="datepicker: {dateFormat: 'dd/mm/yy'},value: value" class="xlarge titleField"/>
        					<button data-bind="click: function(data, event) { $parent.removeForbidden($parent, $data) }" class="xsmall btn danger">-</button>
    					</span>	
						<button data-bind="click: function(data, event) { $data.addForbidden($data) }" class="btn xsmall success">+</button>					
					</div-->

					<div class="clearfix" data-bind="with: weekday">
						<label>{/literal}{t('BOFORMS_DATEPICKER_WEEKDAY')}{literal}</label>
						<span data-bind="foreach: items" >
							<select data-bind="options: weekdayoptions, value: value" class="xlarge titleField"></select>
							<button data-bind="click: function(data, event) { $parent.removeForbidden($parent, $data) }" class="xsmall btn danger">-</button>
						</span>	
						<button data-bind="click: function(data, event) { $data.addForbidden($data, 'weekday') }" class="btn xsmall success">+</button>					
					</div>

					<div class="clearfix"  data-bind="with: period">
						<label>{/literal}{t('BOFORMS_DATEPICKER_PERIOD')}{literal}</label>
						<span data-bind="foreach: items" >
							<input type="text" data-bind="value: value" /> 
							<button data-bind="click: function(data, event) { $parent.removeForbidden($parent, $data) }" class="xsmall btn danger">-</button>
						</span>
						<button data-bind="click: function(data, event) { $data.addForbidden($data, 'period') }" class="btn xsmall success">+</button>

						<div class="clearfix" data-bind="visible: items().length > 0">
							<label>Exemple: <br></label>
							<div style="color:red;">27/05/2014 to 29/05/2014</div>
							<div style="color:red;">today to 29/05/2014</div>    
							<div style="color:red;">before 29/05/2014</div>
							<div style="color:red;">before today</div>
							<div style="color:red;">before today+3</div>   
							<div style="color:red;">before today-3</div>   
							<div style="color:red;">after today</div>
							<div style="color:red;">after 18/05/2014</div>
						</div>
					</div>

					<!--div class="clearfix"  data-bind="with: recursiveDay">
						<label>{/literal}{t('BOFORMS_DATEPICKER_RECURSIV_DAY')}{literal}</label>
						<span data-bind="foreach: items" >
							<input type="text" data-bind="value: value" class="xlarge titleField"/>
        					<button data-bind="click: function(data, event) { $parent.removeForbidden($parent, $data) }" class="xsmall btn danger">-</button>
						</span>
						<button data-bind="click: function(data, event) { $data.addForbidden($data) }" class="btn xsmall success">+</button>					
					</div-->
					
				</span>

				<div class="clearfix">
					<label>{/literal}{t('BOFORMS_DATEPICKER_HOUR_LABEL')}{literal}</label>
					<input type="text" data-bind="value: hourlabel" class="xlarge titleField"/>
				</div>

				<div class="clearfix">
					<label>Format</label>
					<input type="text" data-bind="value: format" class="xlarge titleField"/>
				</div>

				<div class="clearfix">
					<label>{/literal}{t('BOFORMS_FIELD_EXAMPLE')}<br/>{literal}</label>
					<div style="color:red;">DD/MM/YYYY HH</div>
					<div style="color:red;">DD/MM/YYYY</div>
				</div>

			</span>
		</span>
	</form>
</script>

<!-- FORM CONNECTOR PARAM FIELD ------------------------------------------>
<script type="text/html" id="tmpl-connector-settings-form">
	<form name="form_param_field" id="form_param_field" class="form-stacked" data-bind="visible:{/literal}{$is_sup_adm_bo}{literal}">
		<span data-bind="visible:$root.displayCommonForm">
			<div class="clearfix">
				<label>{/literal}{t('BOFORMS_LABEL_CODE')}{literal}</label>
				<input id="param_field_code" type="text" readonly="true" data-bind="value: name">
			</div>
			
			<div class="clearfix">
				<label>{/literal}{if $bLP}{t('BOFORMS_LABEL_INSTRUCTIONS')}{else}{t('BOFORMS_LABEL_INSTRUCTIONS')}{/if}{literal}</label>
				<textarea data-bind="value: instructions,event: { focusout: $root.setStepFieldsValues}" class="textarea_limited"></textarea>
			</div>

			<div data-bind="template: settingsTemplateName"></div>
	</form>
</script>

<!-- FORM SETTING FIELD ----------------------------------------->
<script type="text/html" id="tmpl-field-settings">
<div data-bind="if: $data">
			<div data-bind="ifnot:isAlternativ">
				<!-- ko if: type() != 'connector' -->
					<div data-bind="template: {name: 'tmpl-field-settings-form', data: $data }"></div>
				<!-- /ko -->

				<!-- ko if: type() == 'connector' -->	
					<div data-bind="template: {name: 'tmpl-connector-settings-form', data: $data }"></div>
				<!-- /ko -->				
			</div>
			
			<div data-bind="if:isAlternativ">
				
				<div data-bind="foreach: $root.selectedFieldAlternativ">
					<fieldset style="padding:10px;background:#ddd;">
						
						<legend style="padding-left:0"><button type="button" class="btn" data-bind="text:lang['BOFORMS_LABEL_'+name()]"></button>
						<a data-bind="click:$root.displayFieldset" class="btn displayFieldset" href="#">+</a>
						</legend>
						<div class="fieldset" style="display:none" data-bind="template: {name: 'tmpl-field-settings-form', data: $data }"></div>
					
					</fieldset>
				
					
				</div>
		
			
			</div>
		</div>
		<div data-bind="ifnot: $data">
			{/literal}{t('BOFORMS_SELECT_FIELD')}{literal}
		</div>

</script>

<!-- HTML BLOC PREVIEW -------------------------------------->
<script type="text/html" id="tmp-field-preview-html"></script>
<script type="text/html" id="tmp-field-settings-html">
<div class="clearfix">
	<label>Html</label>
 	<textarea rows="10" data-bind="tinymce: titre, event: { focusout: function(data, event) { $root.setTitleFieldValues($parent, data, event) } }" class="xlarge tinymce"></textarea>
</div>
</script>

<!-- TOGGLE BLOC PREVIEW -------------------------------------->
<script type="text/html" id="tmp-field-preview-toggle"></script>
<script type="text/html" id="tmp-field-settings-toggle">
  <div class="clearfix">
	  <div class="clearfix">
		  <label>{/literal}{t('BOFORMS_LABEL_TITLE')}{literal}</label>
		  <textarea  data-bind="value: titre, event: { focusout: function(data, event) { $root.setTitleFieldValues($parent, data, event) } }" class="textarea_limited"></textarea>
	  </div>
	  <div class="clearfix">
		<label>{/literal}{t('BOFORMS_LABEL_CONTENT')}{literal}</label>

	  	<textarea rows="10" data-bind="tinymce: content, event: { focusout: function(data, event) { $root.setTitleFieldValues($parent, data, event) } }" class="xlarge tinymce"></textarea>
	  </div>
  </div>
</script>

<!-- HIDDEN FIELD PREVIEW -------------------------------------->

<script type="text/html" id="tmp-field-preview-hidden"></script>

<script type="text/html" id="tmp-field-preview-hidden-old">
	<img src="/modules/boforms/images/hidden-icon.png" alt="Hidden field"/>
	<span data-bind="text: name"></span>
</script>


<!-- BUTTON FIELD PREVIEW -------------------------------------->
<script type="text/html" id="tmp-field-preview-button"></script>

<script type="text/html" id="tmp-field-settings-button">
	<div class="clearfix">
		<label>Code</label>
		<input type="text" readonly="true" data-bind="value: name">
	</div>
	<!--div class="clearfix">
		<label>{/literal}{t('BOFORMS_LABEL_TITLE')}{literal}</label>
				
				
			<div id="content">
			    <ul id="tabs" class="tabs" data-tabs="tabs">
			    	{/literal}{foreach from=$aLanguages item=lang}
			    		<li {if $lang['id'] == $iDefaultLangID}class="active"{/if}><a href="#{$lang['id']}" data-toggle="tab">{$lang['label']}
			    			<img src="/modules/boforms/images/{$lang['id']}-langflag.png" alt=""/>
			    		</a></li>
			    	{/foreach}
			        {literal}
			       
			    </ul>
					    
			    <div id="my-tab-content" class="tab-content" data-bind="foreach: title" >
			        <div class="tab-pane " data-bind="attr:{id:id}, css: { active: isDefaultLanguage}">
			        	
			            <textarea data-bind="value: title, event: { focusout: function(data, event) { $root.setTitleFieldValues($parent, data, event) } }" class="textarea_limited"></textarea>
			        </div>
			        
			    </div>
			</div>
 
 

		</div-->
		
	<div class="clearfix">
		<label>{/literal}{t('BOFORMS_LABEL_TITLE')}{literal}</label>
		<textarea data-bind="value: titre, event: { focusout: function(data, event) { $root.setTitleFieldValues($parent, data, event) } }" class="textarea_limited"></textarea>
	</div>


	<div class="clearfix" data-bind="visible: name() == 'TECHNICAL_SEND_REQUEST'">
		<label>{/literal}{t('BOFORMS_GLOBAL_PAGE_ERROR_MESSAGE')}{literal}</label>		
		<input type="text" name="page_error" data-bind="value: pageErrorLabel" />
	</div>
</script>

<!-- RADIO FIELD PREVIEW -------------------------------------->
<script type="text/html" id="tmp-field-preview-radio">
	<a style="text-decoration:none;" href="#" data-bind="event: {keypress: function (data, event) { return block_keydown_rightpane(data, event, $root,$parent); }}">
	<ul data-bind="foreach: choices" class="unstyled">
		<label>
			<input type="radio" data-bind="value: id, attr: {name: $parent.FieldId}, event: { focusout: $root.setStepFieldsValues}, checked: $parent.selected_choice" disabled readonly>
			<span data-bind="text: choiceLabel"></span>
		</label>
	</ul>
	</a>
</script>

<script type="text/html" id="tmp-field-settings-radio">
	<label>{/literal}{t('BOFORMS_LABEL_DEFAULT_VALUE_MSG')}{literal} (<a href="#" data-bind="click: $data.clearRadios">{/literal}{t('BOFORMS_LABEL_CLEAR_DEFAULT_VALUE')}{literal}</a>)</label>
	<ul data-bind="foreach: choices" class="unstyled">
		<label>
    	  	<input type="radio" data-bind="value: id, attr: {name: $parent.FieldId}, checked: $parent.selected_choice">
      		<span data-bind="text: choiceLabel"></span>
    	</label>
	</ul>
	
	<!-- ko if: choices().length == 1 -->
		<label>{/literal}{t('BOFORMS_LABEL_MODIFY_OPTIN_TEXT')}{literal}</label>
   		<textarea  data-bind="value: choices()[0].choice" class="textarea_limited"></textarea>
	<!-- /ko -->

	<!-- PATCH JIRA 710 -->
	<label style="margin-top: 10px;">Choose items for this referential *</label>
	<div data-bind="with: choicesRadios">
		<div data-bind="foreach: choicesRadios" >
			<div data-bind="foreach: $data" style="margin-top: 10px;">
				<input type="radio" data-bind="attr: { name: id },value: choiceLabel,checked: selectedValue" />&nbsp;<span data-bind="text:choiceLabel" /><br />	
			</div>
		</div>	
	</div>
	<!-- FIN PATCH JIRA 710 -->
</script>

<!-- CONNECTOR FIELD PREVIEW -------------------------------------->
<script type="text/html" id="tmp-field-preview-connector">
 	<input type="text" class="xlarge"  data-bind="event: {keypress: function (data, event) { return block_text_keydown_rightpane(data, event, $root,$parent); }}">
</script>
<script type="text/html" id="tmp-field-settings-connector">
	<div class="clearfix">
	    <label>{/literal}{t('BOFORMS_LABEL_BTN_NAME')}{literal}</label>
		<input type="text" class="name" data-bind="value: buttonName, event: { focusout: $root.setStepFieldsValues}" />
    </div>
	<div class="clearfix">
	    <label>{/literal}Tag GTM label{literal}</label>
		<input type="text" class="name" data-bind="value: labelTagGtm, event: { focusout: $root.setStepFieldsValues}" />
    </div>

</script>

<!-- TEXTBOX FIELD PREVIEW -------------------------------------->
<script type="text/html" id="tmp-field-preview-textbox">
 	<input type="text" class="xlarge" data-bind="event: {keypress: function (data, event) { return block_text_keydown_rightpane(data, event, $root,$parent); }}">
</script>
<script type="text/html" id="tmp-field-settings-textbox">
<div class="clearfix">
        <label>{/literal}{t('BOFORMS_LABEL_REGEXP')}{literal}</label>
        
        <input type="text" class="regexp" data-bind="value: regexp, event: { focusout: $root.setStepFieldsValues}" />
        
        <div class="clearfix">
        	<br />
	        <label>{/literal}{t('BOFORMS_LABEL_REGEXP_SAMPLE')}{literal}</label>
        	<p style="color:red;">
        	/^[0-9\ ]+$/<br />
			/^[a-zA-Z\ \']+$/
			</p>
        </div>
      </div>
      

	      <div class="clearfix">
	        <label>{/literal}{t('BOFORMS_LABEL_REGEXP_MSG')}{literal}</label>
			<textarea data-bind="value: regexp_msg, event: { focusout: $root.setStepFieldsValues}" class="textarea_limited"></textarea>
	      </div>


	      <!--div class="clearfix">
	        <label>{/literal}{t('BOFORMS_FIELD_BLOCK_INSTRUCTION')}{literal}</label>
			<input type="text" class="regexp" data-bind="value: default_value,  event: { focusout: $root.setStepFieldsValues}" />
	      </div-->
    
		<div class="clearfix">
        	<label>{/literal}{t('BOFORMS_LABEL_INPUTMASK')}{literal}</label>
        	<input type="text" class="regexp" data-bind="value: inputmask, event: { focusout: $root.setStepFieldsValues}" />
		</div>

      </div>
</script>

<!-- CHECKBOX FIELD PREVIEW -------------------------------------->
<script type="text/html" id="tmp-field-preview-checkbox">
<a style="text-decoration:none;" href="#" data-bind="event: {keypress: function (data, event) { return block_keydown_rightpane(data, event, $root,$parent); }}">
<ul data-bind="foreach: choices" class="unstyled">
    <label>
      <input type="checkbox" data-bind="value: id, attr: {name: $parent.FieldId}, checked: $parent.selected_choice" disabled readonly>
      <span data-bind="text: choiceLabel"></span>
    </label>
</ul>
</a>
</script>

<script type="text/html" id="tmp-field-settings-checkbox">
	<label>{/literal}{t('BOFORMS_LABEL_DEFAULT_VALUE_MSG')}{literal}</label>
	<ul data-bind="foreach: choices" class="unstyled">
		<label>
      	<input type="checkbox" data-bind="value: id, attr: {name: $parent.FieldId}, checked: $parent.selected_choice" />
      	<span data-bind="text: choiceLabel"></span>
    	</label>
	</ul>

	<!-- ko if: choices().length == 1 -->
		<label>{/literal}{t('BOFORMS_LABEL_MODIFY_OPTIN_TEXT')}{literal}</label>
   		<textarea  data-bind="value: choices()[0].choice" class="textarea_limited"></textarea>
	<!-- /ko -->	
</script>

<!-- TEXTAREA FIELD PREVIEW -------------------------------------->
<script type="text/html" id="tmp-field-preview-textarea">
	<textarea data-bind="event: {keypress: function (data, event) { return block_text_keydown_rightpane(data, event, $root,$parent); }}"></textarea>
</script>
<script type="text/html" id="tmp-field-settings-textarea">
	<div class="clearfix">
        <label>{/literal}{t('BOFORMS_LABEL_REGEXP')}{literal}</label>
        <input type="text" class="regexp" data-bind="value: regexp, event: { focusout: $root.setStepFieldsValues}" />
        
        <div class="clearfix">
        	<br />
	        <label>{/literal}{t('BOFORMS_LABEL_REGEXP_SAMPLE')}{literal}</label>
        	<p style="color:red;">
        	/^[0-9\ ]+$/<br />
			/^[a-zA-Z\ \']+$/
			</p>
        </div>
     </div>

      
     <div class="clearfix">
        <label>{/literal}{t('BOFORMS_LABEL_REGEXP_MSG')}{literal}</label>
		<textarea data-bind="value: regexp_msg, event: { focusout: $root.setStepFieldsValues}" class="textarea_limited"></textarea>
     </div>

	<!--div class="clearfix">
	    <label>{/literal}{t('BOFORMS_FIELD_BLOCK_INSTRUCTION')}{literal}</label>
		<input type="text" class="regexp" data-bind="value: default_value,  event: { focusout: $root.setStepFieldsValues}" />
	</div-->
</script>

<!-- RICHTEXTEDITOR FIELD PREVIEW -------------------------------->
<script type="text/html" id="tmp-field-preview-richtexteditor">
	<textarea class="textarea_limited"></textarea>
</script>
<script type="text/html" id="tmp-field-settings-richtexteditor">
	<!--div class="clearfix">
		<label>{/literal}{t('BOFORMS_FIELD_BLOCK_INSTRUCTION')}{literal}</label>
		<input type="text" class="regexp" data-bind="value: default_value, event: { focusout: $root.setStepFieldsValues}" />
	</div-->
</script>

<!-- FILE FIELD PREVIEW ------------------------------------------>
<script type="text/html" id="tmp-field-preview-file"><!--input type="file"--></script>
<script type="text/html" id="tmp-field-settings-file"></script>

<!-- DATEPICKER FIELD PREVIEW ------------------------------------------>
<script type="text/html" id="tmp-field-preview-datepicker"><a style="text-decoration:none;" href="#" data-bind="event: {keypress: function (data, event) { return block_keydown_rightpane(data, event, $root,$parent); }}">
<img style="width:32px;" src="/modules/boforms/images/calendar.png" data-bind="attr:{alt: 'date picker'}"/></a>	        	</script>
<script type="text/html" id="tmp-field-settings-datepicker">
  	<!--div class="clearfix">
	    <label>{/literal}{t('BOFORMS_FIELD_BLOCK_INSTRUCTION')}{literal}</label>
		<input type="text" class="regexp" data-bind="value: default_value,  event: { focusout: $root.setStepFieldsValues}" />
	</div-->
</script>

<!-- SLIDER FIELD PREVIEW ------------------------------------------>
<script type="text/html" id="tmp-field-preview-slider">GESTION FIELD SLIDER</script>
<script type="text/html" id="tmp-field-settings-slider"></script>

<!-- PANELPICKER FIELD PREVIEW ------------------------------------------>
<script type="text/html" id="tmp-field-preview-panelPicker">GESTION FIELD PANELPICKER</script>
<script type="text/html" id="tmp-field-settings-panelPicker"></script>

<!-- DROPDOWN FIELD PREVIEW -------------------------------------->
<script type="text/html" id="tmp-field-preview-dropdown"><select data-bind="optionsValue: 'id',value: selected_choice,options: choices, optionsText: 'choice', optionsCaption: '---',event: {keydown: function (data, event) { return block_keydown_rightpane(data, event, $root, $parent); }},keydownBubble: false"></select></script>
<script type="text/html" id="tmp-field-settings-dropdown">
	<label>{/literal}{t('BOFORMS_LABEL_DEFAULT_VALUE_MSG')}{literal}</label>
	<select data-bind="options: choices, optionsValue: 'id', optionsText: 'choice',value: selected_choice, optionsCaption: '---'"></select>

	<!-- ko if: choices().length == 1 -->
		<label>{/literal}{t('BOFORMS_LABEL_MODIFY_OPTIN_TEXT')}{literal}</label>
   		<textarea  data-bind="value: choices()[0].choice" class="textarea_limited"></textarea>
	<!-- /ko -->	

	<!-- PATCH JIRA 710 -->
	<label style="margin-top: 10px;">Choose items for this referential *</label>
	<div data-bind="with: choicesRadios">
		<div data-bind="foreach: choicesRadios" >
			<div data-bind="foreach: $data" style="margin-top: 10px;">
				<input type="radio" data-bind="attr: { name: id },value: choiceLabel,checked: selectedValue" />&nbsp;<span data-bind="text:choiceLabel" /><br />	
			</div>
		</div>	
	</div>
	<!-- FIN PATCH JIRA 710 -->

</script>

<!-- DROPDOWNLIST FIELD PREVIEW -------------------------------------->
<script type="text/html" id="tmp-field-preview-dropdownlist"><select data-bind="options: choices, optionsText: 'choiceLabel'"></select></script>
<script type="text/html" id="tmp-field-settings-dropdownlist">
	<label>{/literal}{t('BOFORMS_LABEL_DEFAULT_VALUE_MSG')}{literal}</label>
	<select data-bind="options: choices, optionsValue: 'id', optionsText: 'choiceLabel',value: selected_choice, optionsCaption: '---'""></select>
</script>

<!-- CAPTCHA FIELD PREVIEW --------------------------------------->
<script type="text/html" id="tmp-field-preview-captcha"><img src="http://phpfactory-media.interakting.com/modules/formbuilder/images/recaptcha.gif" width="250" /></script>
<script type="text/html" id="tmp-field-settings-captcha"></script>


<!-- Helpers -->
<script type="text/html" id="tmp-choices"><div class="clearfix">
    <!--label>Choix</label-->
    <!--input type="text" value="Texte par defaut"/--><br/>
    <ul data-bind="foreach: choices" class="unstyled">
      <li>
      	
        <!--input readonly="true" data-bind="value: choice, event: { focusout: function(data, event) { $root.setStepFieldsChoicesValues($parent, data, event) }} " class=""-->
        <!--button data-bind="click: function(data, event) { $parent.addChoice($parent) }" class="btn xsmall success">+</button-->
        <!--button data-bind="click: function(data, event) { $parent.removeChoice($parent, $data) }" class="xsmall btn danger">-</button-->
        
      </li>
    </ul>
    <div data-bind="ifnot: hasChoices">
      <button data-bind="click: addChoice" class="btn xsmall success">+ Ajouter un choix</button>
    </div>
    
 
  </div></script>

</body>

{/literal}
<script>
{literal}
	
var builder = new EditorViewModel();

$('<style type="text/css">.ui-datepicker-close { display: none; } </style>').appendTo("head");
$('<style type="text/css">button.ui-datepicker-current { display: none; } </style>').appendTo("head");

// add the today button to the picker (it displays "Today" in the textfield)
function beforeShowPicker(input, inst) {
	    setTimeout(function() {
            var buttonPane = $( input )
                .datepicker( "widget" )
                .find( ".ui-datepicker-buttonpane" );

 		    var txt = $('<input style="width:100px;" type="text" class="ui-priority-secondary ui-corner-all" value="" id="pickerModifyValueText">');  
			var btn2 = $('<button class="ui-state-default ui-priority-secondary ui-corner-all" type="button">{/literal}{t('BOFORMS_EDIT')}{literal}</button>');  

			btn2  
		       .unbind("click")  
		       .bind("click", function () {  
			       $(input).datepicker("hide");
			       $(input).val($('#pickerModifyValueText').val()).trigger('change');;
		    });  

			txt.appendTo(buttonPane);
			btn2.appendTo( buttonPane );  

			$('#pickerModifyValueText').val($(input).val());
        }, 1 );
}


	ko.applyBindings(builder);

	function submitForm() {
		window.opener.$("[id=BOFORMS_STRUCTURE]").val($('#result').html());	
		window.close();	
	}

	function saveAuto(bPreview){
			//console.log($( "#result" ).val());	
			//console.log($( "#xmlPerso" ).val());

			var textJson = $( "#result" ).val();
			var textXML = $( "#xmlPerso" ).val();
			var formCommentary = $("#formCommentary").val();
			var formCommentaryVisible = $("#formCommentaryVisible").val();
			
			$.ajax({
				type : "POST",
				url: "/_/module/boforms/BoForms_Administration_Module/saveAutoAjax",
                data:  {result : textJson, xmlPerso : textXML, formCommentary: formCommentary,formCommentaryVisible: formCommentaryVisible},
                    
				async: true,
				success: function(data) {
					
				},
				complete: function(data) {
					
					if(bPreview)
					{
						window.open('preview?code_instance={/literal}{$sCode}{literal}&display=current&version=DRAFT');
						$.loader('close');
					}
				}
				
			});
				
		}

	/* if hideConfirmation is false then dont display the confirmation */
	function saveForm(mode, hideConfirmation,redirect,reload){
		
		$.loader({
        	className:"blue-with-image-2",
        	content:''
   	 	});	
														
		var textJson = $( "#result" ).val();
		var textXML = $( "#xmlPerso" ).val();
		var ABTitle = $( "#ABtesting_title" ).val();
		var formCommentary = $("#formCommentary").val();
		var formCommentaryVisible = $("#formCommentaryVisible").val();
		
		if(mode=='draft')
		{
			var data = {result : textJson, xmlPerso : textXML, draft:true,ABTestingTitle:ABTitle, formCommentary: formCommentary, formCommentaryVisible: formCommentaryVisible};			
			var text = "{/literal}{t('BOFORMS_CONFIRM_DRAFT_SAVED')}{literal}";
		}else if(mode=='publier')
		{
			var data = {result : textJson, xmlPerso : textXML, publier:true,ABTestingTitle:ABTitle, formCommentary: formCommentary, formCommentaryVisible: formCommentaryVisible};
			var text = "{/literal}{t('BOFORMS_CONFIRM_PUBLISH_SAVED')}{literal}";
		}
									
		$.ajax({
			type : "POST",
			url: "/_/module/boforms/BoForms_Administration_Module/beforeSave",
            data:  data,
                    
			async: true,
			success: function(data) {
			
				//console.log(data);
				
				if (typeof(hideConfirmation) !== 'undefined' &&  hideConfirmation == true) {
					
					
					if(typeof(redirect) !== 'undefined' &&  redirect == true)
					{
    					if("{/literal}{t('BOFORMS_CONFIRM_PUBLISH_SAVED')}{literal}"==data || "{/literal}{t('BOFORMS_CONFIRM_DRAFT_SAVED')}{literal}"==data) {
                            window.location = "{/literal}{$REDIRECT_URL}{literal}?code_instance={/literal}{$get_code}{literal}&tab=versions";
                        } else {
                            $("#dialog-confirm").html("{/literal}{t('BOFORMS_SAVE_FAILED_ABTESTING')}{literal}<br/><a style='color: #0069d6;' href='#' onclick='$( \"#dialog\" ).dialog( \"open\" );' >{/literal}{t('BOFORMS_TITLE_SUPPORT_CONTACT')}{literal}</a>");
                            $("#dialog-confirm").dialog({
                                resizable: false,
                                modal: true,
                                title: '{/literal}{t('BOFORMS_LABEL_SUCCESS')}{literal}',
                                height: 165,
                                width: 300,
                                buttons: {
                                    "ok": function () {
                                                                    
                                            $(this).dialog('close');
                                                        
                                    }
                                }
                            });
                        }
                        
                          
						
					}else if(typeof(reload) !== 'undefined' &&  reload == true){
					
						window.location = "{/literal}{$REDIRECT_URL}{literal}?code_instance={/literal}{$get_code}{literal}";
					}
					
					$.loader('close');
					
				} else {	
								
					if("{/literal}{t('BOFORMS_CONFIRM_PUBLISH_SAVED')}{literal}"==data || "{/literal}{t('BOFORMS_CONFIRM_DRAFT_SAVED')}{literal}"==data) {
						$("#dialog-confirm").html(data);
					} else if ("{/literal}{t('BOFORMS_DUPL_INSTANCE_FORM_NAME_ALREADY_EXIST')}{literal}"==data || "{/literal}{t('BOFORMS_DUPL_INSTANCE_INSTANCE_NAME_ALREADY_EXIST')}{literal}"==data) {
						$("#dialog-confirm").html("{/literal}{t('BOFORMS_SAVE_FAILED')}{literal}.<br/>" + data + "<br/><a style='color: #0069d6;' href='#' onclick='$( \"#dialog\" ).dialog( \"open\" );' >{/literal}{t('BOFORMS_TITLE_SUPPORT_CONTACT')}{literal}</a>");
					} else {
						$("#dialog-confirm").html("{/literal}{t('BOFORMS_SAVE_FAILED')}{literal}<br/><a style='color: #0069d6;' href='#' onclick='$( \"#dialog\" ).dialog( \"open\" );' >{/literal}{t('BOFORMS_TITLE_SUPPORT_CONTACT')}{literal}</a>");
					}
					
					
	    			$.loader('close');
				    $("#dialog-confirm").dialog({
				        resizable: false,
				        modal: true,
				        title: '{/literal}{t('BOFORMS_LABEL_SUCCESS')}{literal}',
				        height: 165,
				        width: 300,
				        buttons: {
				            "ok": function () {
				            				            	
				                	$(this).dialog('close');
				            	                
				            },
				                "fermer le formulaire": function () {
				                
				                if(typeof(redirect) !== 'undefined' &&  redirect == true)
								{
									window.location = "{/literal}{$REDIRECT_URL}{literal}?code_instance={/literal}{$get_code}{literal}&tab=versions";
								}else{
				              	  window.close();
				               	}
				            }
				        },
				        beforeClose: function( event, ui ) {
				        	$.loader({
					        	className:"blue-with-image-2",
					        	content:''
					   	 	});	
					   	 	
					   	 	if(typeof(redirect) !== 'undefined' &&  redirect == true)
								{
									window.location = "{/literal}{$REDIRECT_URL}{literal}?code_instance={/literal}{$get_code}{literal}&tab=versions";
								}else{
			        				window.location = "{/literal}{$REDIRECT_URL}{literal}?code_instance={/literal}{$get_code}{literal}";
			        			}
			        	}
				    });
				}
			
			},
			complete: function(data) {
				if (redisplayContactSupport == true) {
				    redisplayContactSupport = false;
				    $("#dialog").dialog("option", 'position', "top");
			    }
			}
			
		});
			
		return false;	
	}

	// empeche la saisie dans les champs cote droit (formulaire contextualisé)
	// sauf tabulation pour permettre le déplacement
	function block_keypress_rightpane(data, event, root, parent) {
		var code = event.keyCode ? event.keyCode : event.which;

		// si tabulation
		if (code == 9) { 
			return true;
		}

		if (code == 13) {
			root.selectField(parent, data, event);
			$('#param_field_code').focus();
		}
		return false;
	}

	function block_keydown_rightpane(data, event, root, parent) {
		var code = event.keyCode ? event.keyCode : event.which;

		if (code == 13) {
			root.selectField(parent, data, event);
			$('#param_field_code').focus();
			return false;
		}
		return true;
	}

	function block_text_keydown_rightpane(data, event, root, parent) {
		var code = event.keyCode ? event.keyCode : event.which;

		if (code == 13) {
			root.selectField(parent, data, event);
			$('#param_field_code').focus();
		}

		return (code == 9); // allow only tab
	}

	
	$( document ).ready(function() {

		/*$( ":button" ).keydown(function(event){  
			if ( event.which == 13 ) 
			{
				$(this).trigger( "click" );
			} 
		});

		$( "a[href~='#']" ).keydown(function(event){  
			if ( event.which == 13 ) 
			{
				$(this).trigger( "click" );
			} 
		});

		$( "input[type='button']" ).keydown(function(event){  
			if ( event.which == 13 ) 
			{
				$(this).trigger( "click" );
			} 
		});*/
	$( ".btn" ).keydown(function(event){  
			if ( event.which == 13 ) 
			{
				$(this).trigger( "click" );
			} 
		});
		
	/*	sauvegarde automatique */
	//setInterval("saveAuto()", 60000);
 	
 	
 	//ouverture sur l'onglet versions au chargement de la page
 	{/literal}{if $load_tab=='versions'}{literal}
 		document.getElementById('tab_versions').click()
 	{/literal}{/if}{literal}	
 	
		
	$( ".removeABtesting").click(function() {
	 	
	 	var version_id = $(this).attr('id');
	 	var version_num = version_id.substr(8, 1);
	 		 	 		 	 	
	 		 	
	 	$("#dialog-confirm").html("{/literal}{t('BOFORMS_CONFIRM_DELETE_ABTESTING')}{literal} "+$('#ABTestingFormName_'+version_num).val()+" ?");
	    
	    $("#dialog-confirm").dialog({
	        resizable: false,
	        modal: true,
	        title: "{/literal}{t('BOFORMS_LABEL_SUCCESS')}{literal}",
	        height: 200,
	        width: 300,
	        buttons: {
	            "Oui": function () {
	                $(this).dialog('close');
	                removeABtesting(version_id);
	            },
	                "Non": function () {
	                $(this).dialog('close');
	               
	            }
	        }
	    });
	 	
	 
	});
	
	$( ".doPublishBtn" ).on('click', function() {
		$.get( "/_/module/boforms/BoForms_Administration_Module/checkFormEditable?code_instance={/literal}{$sCode}{literal}&bNewInstance={/literal}{$bNewInstance}{literal}&isNewABTesting={/literal}{$isNewABTesting}{literal}", function( data ) {
			if (data == '1') {
				saveForm('publier');
			} else {
				alert(data);
			}
		});	});
	$( "#doSaveBtn").on('click', function() {
		$.get( "/_/module/boforms/BoForms_Administration_Module/checkFormEditable?code_instance={/literal}{$sCode}{literal}&bNewInstance={/literal}{$bNewInstance}{literal}&isNewABTesting={/literal}{$isNewABTesting}{literal}", function( data ) {
			if (data == '1') {
				saveForm('draft');
			} else {
				alert(data);
			}
		});
	});
	
	$( "#abtesting_DIG").on('click', function() {
	
		$.loader({
        	className:"blue-with-image-2",
        	content:''
   		 });

		$.ajax({
			type : "POST",
			url: "/_/module/boforms/BoForms_Administration_Module/ABTestingSendDIG",
            data:  {code_instance : '{/literal}{$get_code}{literal}'},
                
			async: true,
			success: function(data) {
				
			},
			complete: function(data) {
				$.loader('close');
				$("#dialog-confirm").html("{/literal}{t('BOFORMS_ABTESTING_DIG_SEND')}{literal}");

	    
				$("#dialog-confirm").dialog({
			        resizable: false,
			        modal: true,
			        title: "{/literal}{t('BOFORMS_LABEL_SUCCESS')}{literal}",
			        height: 150,
			        width: 300,
			        buttons: {
			            "Ok": function () {
			               $(this).dialog('close');			               
			            }
			        }
			    });
				
				
			}
			
		});
	
	});
	
	
	$( "#doSaveBtnABtesting").on('click', function() {
		
    
	    $("#dialog-ABtesting").dialog({
	        resizable: false,
	        modal: true,
	        title: "{/literal}{t('BOFORMS_ABTESTING_POPIN_TITLE')}{literal}",
	        height: 180,
	        width: 300,
	        buttons: {
	            "OK": function () {
	            
	                if($("#ABtesting_title").val())
	                {
	               		               											
						$.ajax({
							type : "POST",
							url: "/_/module/boforms/BoForms_Administration_Module/CheckABTestingTitleExist",
				            data:  {title : $("#ABtesting_title").val(), id: '{/literal}{$form_id}{literal}'},
				            							
							success: function(data) {
							
								if(data == 'false')
								{
									//window.parent.location.reload();
									$("#dialog-ABtesting").dialog('close');
									saveForm('publier',true,true);
										
								}else{
									alert("{/literal}{t('BOFORMS_ABTESTING_TITLE_EXIST')}{literal}");
								}
							
							}
						});
					               		
	               		
	               		
	               		
	               		
	                }else{
	               		alert("{/literal}{t('BOFORMS_EMPTY_FIELD')}{literal}");
	                }
	               
	            },
	                "{/literal}{t('BOFORMS_CANCEL')}{literal}": function () {
	                $(this).dialog('close');
	               
	            }
	        }
	    });
	    
		//saveForm('draft');
	});
	
	
	$( "#testsaveAuto" ).click(function() {
		saveAuto();
	});
	
	$( ".previewDraft" ).click(function() {
		
		 $.loader({
        	className:"blue-with-image-2",
        	content:''
   		 });
		saveAuto(true);
		
	});


	
	$( ".restorePreviousVersion" ).click(function() {
		if (confirm('{/literal}{t("BOFORMS_CONFIRM_RESTORE_PREVIOUS_VERSION")}{literal}')) {
	
			$.loader({
	        	className:"blue-with-image-2",
	        	content:''
	   		});
			
			$.ajax({
				type : "POST",
				url: "/_/module/boforms/BoForms_Administration_Module/restorePreviousVersionAjax",
	            data:  {scode : '{/literal}{$sCode}{literal}'},
	                
				async: true,
				success: function(data) {
					
				},
				complete: function(data) {
					window.location.reload();
					$.loader('close');
				}	
			});
		}
	});
	

	$( ".deleteDraft" ).click(function() {
		$.loader({
        	className:"blue-with-image-2",
        	content:''
   		});
		
		$.ajax({
			type : "POST",
			url: "/_/module/boforms/BoForms_Administration_Module/deleteDraftAjax",
            data:  {scode : '{/literal}{$sCode}{literal}'},
                
			async: true,
			success: function(data) {
				
			},
			complete: function(data) {
				window.location.reload();
				$.loader('close');
			}	
		});
	});
	
	$( ".overwriteAction" ).click(function() {
	
		var sParam = $(this).attr('id');
	
			
		$("#dialog-confirm").html("{/literal}{t('BOFORMS_CONFIRM_OVERWRITE_DRAFT')}{literal}");

	    
	    $("#dialog-confirm").dialog({
	        resizable: false,
	        modal: true,
	        title: "{/literal}{t('BOFORMS_LABEL_SUCCESS')}{literal}",
	        height: 150,
	        width: 300,
	        buttons: {
	            "Oui": function () {
	                $(this).dialog('close');
	                overwrite(sParam);
	            },
	                "Non": function () {
	                $(this).dialog('close');
	               
	            }
	        }
	    });
	});

	function overwrite(version)
	{
		
		 $.loader({
        	className:"blue-with-image-2",
        	content:''
   		 });
   		 
   		 $.ajax({
					type : "GET",
					url: "/_/module/boforms/BoForms_Administration_Module/overwrite",
                    data:  {code_instance : '{/literal}{$sCode}{literal}', version : version},
                        
					async: true,
					success: function(data) {
						
					},
					complete: function(data) {
						
							window.location = "{/literal}{$REDIRECT_URL}{literal}?code_instance={/literal}{$get_code}{literal}";
							
					}
					
				});
	}


	function removeABtesting(code_instance){
				
				$.loader({
		        	className:"blue-with-image-2",
		        	content:''
		   		 });
	
				$.ajax({
					type : "POST",
					url: "/_/module/boforms/BoForms_Administration_Module/RemoveABtesting",
                    data:  {code_instance : code_instance},
                    async: true,
					success: function(data) {
						
					},
					complete: function(data) {
						if (data.responseText == 'OK') {
							window.location = "{/literal}{$Location}{literal}&tab=versions";
						} else {
							$.loader('close');
							alert(data.responseText);
						}
					}					
				});
		
	}

			
	

	/* reset button */
	
	$( ".btnResetForm" ).click(function() {
		$("#dialog-confirm").html("{/literal}{t('BOFORMS_CONFIRM_RESET')}{literal}");

	    
	    $("#dialog-confirm").dialog({
	        resizable: false,
	        modal: true,
	        title: "{/literal}{t('BOFORMS_LABEL_SUCCESS')}{literal}",
	        height: 150,
	        width: 300,
	        buttons: {
	            "Oui": function () {
	                $(this).dialog('close');
	        	   	$.loader({
		             	className:"blue-with-image-2",
		             	content:''
	        		 });
	     	
	                doResetToGenerique();
	            },
	                "Non": function () {
	                $(this).dialog('close');
	               
	            }
	        }
	    });
	});

	function doResetToGenerique() {
		var sCode = '{/literal}{$sCode}{literal}'; // personnalisé
		
		/* url parameters */
		
		var get_code =  '{/literal}{$code_parent}{literal}'; // générique
		
		$.post('reset', { code_parent: get_code, scode: sCode }).done(function (data) {
				$.loader('close');

				$("#dialog-confirm").html("{/literal}{t('BOFORMS_CONFIRM_RESET_DONE')}{literal}");
			    
			    $("#dialog-confirm").dialog({
			        resizable: false,
			        modal: true,
			        title: "Résultat",
			        height: 150,
			        width: 300,
			        beforeClose: function( event, ui ) {
			        	window.location = "{/literal}{$REDIRECT_URL}{literal}?code_instance={/literal}{$get_code}{literal}";
			        },
			        buttons: {
			            "OK": function () {
			            	window.location = "{/literal}{$REDIRECT_URL}{literal}?code_instance={/literal}{$get_code}{literal}";
			            }
			        }
			    });
		});
	}

});	

$(window).load(function () {
  $('#page_loader').fadeOut();
  $('#global_div').fadeIn();
  
});

// ko bindings for tinymce
(function ($) {
    var instances_by_id = {}; // needed for referencing instances during updates.
    var init_queue = $.Deferred(); // jQuery deferred object used for creating TinyMCE instances synchronously
    init_queue.resolve();

    ko.bindingHandlers.tinymce = {
        init: function (element, valueAccessor, allBindingsAccessor, context) {
            var options = allBindingsAccessor().tinymceOptions || {};
            options.menubar = false;
options.plugins = [  "advlist autolink lists link image charmap print preview anchor",
                     "searchreplace visualblocks code fullscreen",
                     "insertdatetime media table contextmenu paste"];
options.toolbar = ["undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link code preview"]; 

// on traduit en francais si besoin
if (session_lang_id == 1) {
	options.language = 'fr_FR';
}            
            /*var options = {
                   mode : "specific_textareas",
                   editor_selector : /(tinymce)/,
                   inline: true,
                   toolbar: 'bold italic underscore',
                   
            };*/
            var modelValue = valueAccessor();
            var value = ko.utils.unwrapObservable(valueAccessor());
            var $element = $(element);

            options.setup = function (ed) {
                ed.on('change', function (e) {
                    if (ko.isWriteableObservable(modelValue)) {
                        var current = modelValue();
                        if(current !== this.getContent()) {
                            modelValue(this.getContent());
                        }
                    }
                });
                ed.on('keyup', function (e) {
                    if (ko.isWriteableObservable(modelValue)) {
                        var current = modelValue();
                        var editorValue = this.getContent({ format: 'raw' });
                        if(current !== editorValue) {
                            modelValue(editorValue);
                        }
                    }
                });
                ed.on('beforeSetContent', function (e, l) {
                    if (ko.isWriteableObservable(modelValue)) {
                        if (typeof (e.content) != 'undefined') {
                           var current = modelValue();
                           if(current !== e.content) {
                               modelValue(e.content);
                           }
                        }
                    }
                });
            };

            //handle destroying an editor 
            ko.utils.domNodeDisposal.addDisposeCallback(element, function () {
                $(element).parent().find("span.mceEditor,div.mceEditor").each(function (i, node) {
                    var tid = node.id.replace(/_parent$/, ''),
                        ed = tinymce.get(tid);
                    if (ed) {
                        ed.remove();
                        // remove referenced instance if possible.
                        if (instances_by_id[tid]) {
                            delete instances_by_id[tid];
                        }
                    }
                });
            });

            setTimeout(function () {
                if (!element.id) {
                    element.id = tinymce.DOM.uniqueId();
                }
                tinyMCE.init(options);
                tinymce.execCommand("mceAddEditor", true, element.id);
            }, 0);
            $element.html(value);

        },
        update: function (element, valueAccessor, allBindingsAccessor, context) {
            var $element = $(element),
                value = ko.utils.unwrapObservable(valueAccessor()),
                id = $element.attr('id');

            //handle programmatic updates to the observable
            // also makes sure it doesn't update it if it's the same. 
            // otherwise, it will reload the instance, causing the cursor to jump.
            if (id !== undefined) {
                var tinymceInstance = tinyMCE.get(id);
                if (!tinymceInstance)
                    return;
                var content = tinymceInstance.getContent({ format: 'raw' });
                if (content !== value) {
                    $element.html(value);
                    //this should be more proper but ctr+c, ctr+v is broken, above need fixing
                    //tinymceInstance.setContent(value,{ format: 'raw' })
                }
            }
        }
    };
}(jQuery));


$( document ).ready(function() {

		$( ":button" ).keydown(function(event){  
			if ( event.which == 13 ) 
			{
				$(this).trigger( "click" );
			} 
		});

		$( "a" ).keydown(function(event){  
			if ( event.which == 13 ) 
			{
				$(this).trigger( "click" );
			} 
		});

		$( "input[type='button']" ).keydown(function(event){  
			if ( event.which == 13 ) 
			{
				$(this).trigger( "click" );
			} 
		});

	
});
//	tinymce.init({selector:'textarea'});
</script>
{/literal}

</html>
