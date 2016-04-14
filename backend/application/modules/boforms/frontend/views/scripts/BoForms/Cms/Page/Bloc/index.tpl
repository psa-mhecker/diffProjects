<style>
{literal}.boforms label {
 display: block;
 margin: 10px;
}{/literal}
{$css}
</style>
<div class="boforms">
<form id="{$formid}" action="/_/module/boforms/BoForms_Cms_Page_Bloc/save" method="post" class="boforms-form">
<input type="hidden" name="FORMBUILDER_ID" value="{$FORMBUILDER_ID}" />
	<p id="p-{$formid}">
	{if $form.name != ''}<h1 class="boforms-title">{$form.name}</h1>{/if}
	{if $form.description != ''}<p>{$form.description}</p>{/if}
{foreach from=$form.fields item=field}
 {if $field.type == "text"} 
 <label for="{$field.type}-{$field.name}" class="boforms-label">{$field.title}{if $field.is_required}<span class="boforms-required">*</span>{/if}</label>
 <input type="{$field.type}" name="{$field.name}" id="{$formid}--{$field.name}" value="" class="validate[{if $field.regexp}required{/if}{if $field.regexp}{if $field.regexp},{/if}custom[{$formid}_{$field.name}]{/if}] inputbox] boforms-{$field.type}" placeholder="{$field.placeholder}" />
 {if $field.regexp}
 <script>
 $.validationEngineLanguage.allRules["{$formid}_{$field.name}"] = {literal}{{/literal}
			"regex" : {$field.regexp},
			"alertText" : "{$field.regexp_msg}"
{literal}};{/literal}
 </script>
 {/if} 
 {elseif $field.type == "number"} 
 <label for="{$field.type}-{$field.name}" class="boforms-label">{$field.title}{if $field.is_required}<span class="boforms-required">*</span>{/if}</label>
 <input type="text" name="{$field.name}" id="{$formid}--{$field.name}" value="" class="validate[{if $field.is_required}required,{/if}custom[number]] inputbox boforms-{$field.type}" placeholder="{$field.placeholder}" /> 
 {elseif $field.type == "textarea"} 
 <label for="{$field.type}-{$field.name}" class="boforms-label">{$field.title}{if $field.is_required}<span class="boforms-required">*</span>{/if}</label>
 <textarea name="{$field.name}" id="{$formid}--{$field.name}" class="{if $field.is_required}validate[required] inputbox{/if} boforms-{$field.type}" placeholder="{$field.placeholder}"></textarea> 
 {elseif $field.type == "checkbox"} 
 <label for="{$field.type}-{$field.name}" class="boforms-label">{$field.title}{if $field.is_required}<span class="boforms-required">*</span>{/if}</label>
 {foreach from=$field.choices item=choice key=k}
 		<input type="{$field.type}" name="{$field.name}[]" id="{$formid}--{$field.name}-{$k}" value="{$choice.choice}" class="{if $field.is_required}validate[required] inputbox{/if} boforms-{$field.type}" placeholder="{$field.placeholder}" />&nbsp;{$choice.choice}
 {/foreach} 
 {elseif $field.type == "radio"} 
 <label for="{$field.type}-{$field.name}" class="boforms-label">{$field.title}{if $field.is_required}<span class="boforms-required">*</span>{/if}</label>
 {foreach from=$field.choices item=choice key=k}
 		<input type="{$field.type}" name="{$field.name}[]" id="{$formid}--{$field.name}-{$k}" value="{$choice.choice}" class="{if $field.is_required}validate[required] inputbox{/if} boforms-{$field.type}" placeholder="{$field.placeholder}" />&nbsp;{$choice.choice}
 {/foreach} 
 {elseif $field.type == "select"} 
 <label for="{$field.type}-{$field.name}" class="boforms-label">{$field.title}{if $field.is_required}<span class="boforms-required">*</span>{/if}</label>
 <select name="{$field.name}" id="{$formid}--{$field.name}" value="" class="{if $field.is_required}validate[required] inputbox{/if} boforms-{$field.type}" placeholder="{$field.placeholder}" /> 
 {foreach from=$field.choices item=choice key=k}
 		<option value="{$choice.choice}">&nbsp;{$choice.choice}</option>
 {/foreach} 
			</select>
{elseif $field.type == "civility"} 
 <label for="{$field.type}-{$field.name}" class="boforms-label">{$field.title}{if $field.is_required}<span class="boforms-required">*</span>{/if}</label>
 <select name="{$field.name}" id="{$formid}--{$field.name}" value="" class="{if $field.is_required}validate[required] inputbox{/if} boforms-{$field.type}" placeholder="{$field.placeholder}" /> 
 {foreach from=$field.choices item=choice key=k}
 		<option value="{$choice.choice}">&nbsp;{$choice.choice}</option>
 {/foreach} 
			</select>
 {elseif $field.type == "section"} 
 <p id="{$formid}--{$field.name}" class="boforms-{$field.type}"><h1>{$field.title}</h1>{$field.placeholder}</p> 
 {elseif $field.type == "page"} 
 <hr name="{$field.name}" id="{$formid}--{$field.name}" class="boforms-{$field.type}" placeholder="{$field.placeholder}" /> 
 {elseif $field.type == "shortname"} 
 <label for="{$field.type}-{$field.name}" class="boforms-label">{$field.title}{if $field.is_required}<span class="boforms-required">*</span>{/if}</label>
 <input type="text" name="{$field.name}_FIRSTNAME" id="{$formid}--{$field.name}-firstname" value="" class="[{if $field.is_required}required{/if}] inputbox boforms-{$field.type}" placeholder="{$field.placeholder}" />&nbsp;<input type="text" name="{$field.name}_SECONDNAME" id="{$formid}--{$field.name}-secondname" value="" class="{if $field.is_required}validate[required] inputbox{/if} boforms-{$field.type}" placeholder="{$field.placeholder}" />
 {elseif $field.type == "file"} 
 <label for="{$field.type}-{$field.name}" class="boforms-label">{$field.title}{if $field.is_required}<span class="boforms-required">*</span>{/if}</label>
 <input type="{$field.type}" name="{$field.name}" id="{$formid}--{$field.name}" value="" class="{if $field.is_required}validate[required] inputbox{/if} boforms-{$field.type}" placeholder="{$field.placeholder}" /> 
 {elseif $field.type == "address"} 
 <label for="{$field.type}-{$field.name}" class="boforms-label">{$field.title}{if $field.is_required}<span class="boforms-required">*</span>{/if}</label>
 <input type="text" name="{$field.name}" id="{$formid}--{$field.name}" value="" class="validate[{if $field.is_required}required,{/if},custom[address]] inputbox boforms-{$field.type}" placeholder="{$field.placeholder}" /> 
 {elseif $field.type == "date"} 
 <label for="{$field.type}-{$field.name}" class="boforms-label">{$field.title}{if $field.is_required}<span class="boforms-required">*</span>{/if}</label>
 <input type="{$field.type}" name="{$field.name}" id="{$formid}--{$field.name}" value="" class="validate[{if $field.is_required}required,{/if}custom[date]] inputbox boforms-{$field.type}" placeholder="{$field.placeholder}" /> 
 {elseif $field.type == "email"} 
 <label for="{$field.type}-{$field.name}" class="boforms-label">{$field.title}{if $field.is_required}<span class="boforms-required">*</span>{/if}</label>
 <input type="{$field.type}" name="{$field.name}" id="{$formid}--{$field.name}" value="" class="validate[{if $field.is_required}required,{/if}custom[email]] inputbox boforms-{$field.type}" placeholder="{$field.placeholder}" /> 
 {elseif $field.type == "phone"} 
 <label for="{$field.type}-{$field.name}" class="boforms-label">{$field.title}{if $field.is_required}<span class="boforms-required">*</span>{/if}</label>
 <input type="text" name="{$field.name}" id="{$formid}--{$field.name}" value="" class="validate[{if $field.is_required}required,{/if}custom[phone]] inputbox boforms-{$field.type}" placeholder="{$field.placeholder}" /> 
 {elseif $field.type == "url"} 
 <label for="{$field.type}-{$field.name}" class="boforms-label">{$field.title}{if $field.is_required}<span class="boforms-required">*</span>{/if}</label>
 <input type="text" name="{$field.name}" id="{$formid}--{$field.name}" value="" class="validate[{if $field.is_required}required,{/if}custom[url]] inputbox boforms-{$field.type}" placeholder="{$field.placeholder}" /> 
 {elseif $field.type == "iban"} 
 <label for="{$field.type}-{$field.name}" class="boforms-iban">{$field.title}</label>
 <input type="text" name="{$field.name}" id="{$formid}--{$field.name}" value="" class="validate[{if $field.is_required}required,{/if}custom[iban]] inputbox boforms-{$field.type}" placeholder="{$field.placeholder}" size="30" maxlength="120"/> 
 {elseif $field.type == "postcode"} 
 <label for="{$field.type}-{$field.name}" class="boforms-postcode">{$field.title}</label>
 <input type="text" name="{$field.name}" id="{$formid}--{$field.name}" value="" class="validate[{if $field.is_required}required,{/if}custom[postcode]] inputbox boforms-{$field.type}" placeholder="{$field.placeholder}" /> 
 {elseif $field.type == "captcha"} 
 <label for="{$field.type}-{$field.name}" class="boforms-captcha">{$field.title}</label>
 <script type="text/javascript">
 var RecaptchaOptions = {literal}{{/literal}
   lang : '{$lang}',
 {literal}}{/literal};
 </script>
 {$captcha} 
  {elseif $field.type == "submit"} 
 <input type="{$field.type}" name="{$field.name}" class="boforms-{$field.type}" id="{$formid}--{$field.name}" value="{$field.title}" class="{if $field.is_required}validate[required] inputbox{/if} boforms-{$field.type}" placeholder="{$field.placeholder}"> 
	{/if}
{/foreach}
	</p>
</form>
</div>
<script>
var formid='{$formid}';
{literal}

$('#' + formid).submit(function(event) {
	event.preventDefault();
});

/*$(document).ready(function() {
	$('#' + formid).validationEngine({
		success : false,
		failure : function() {
			return false;
		}
	})
})*/


jQuery('#' + formid).validationEngine('attach', {
  onValidationComplete: function(form, status){
    if (status) {
    	var data = form.serializeArray();
		$.post(form.attr('action'), data, function(data) {
			if (data) {
				var response = jQuery.parseJSON(data);
				alert(response);
				// $('#'+formid).html() = '<h1>'.response.'</h1>';
			}
		});
    }
  }  
});

{/literal}
</script>