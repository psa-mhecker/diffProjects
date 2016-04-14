<h2>{t('BOFORMS_REQUEST_JIRA_NEW_FORM_CREATED')|replace:'XXXXX':$key_jira_created}</h2>

<p>{t('BOFORMS_SUPPORT_REQUEST_TITLE')}: {$request_title}</p> 
<p>{t('BOFORMS_SUPPORT_PRIORITY')}: {$priority}</p>

<table  style=" border-width:1px; border-style:solid; border-color:black;">
<tr>
	<th style="border-width:1px; border-style:solid; border-color:ligthgrey;">{t('BOFORMS_REQUEST_QUESTION')}</th>
	<th style="border-width:1px; border-style:solid; border-color:ligthgrey;">{t('BOFORMS_REQUEST_DESCRIPTION')}</th>
</tr>

<tr>
	<td  style="border-width:1px; border-style:solid; border-color:ligthgrey;">{t('BOFORMS_TYPE_FORMULAIRE')}</td>
	<td  style="border-width:1px; border-style:solid; border-color:ligthgrey;">{$form_type}</td>
</tr>

<tr>
	<td  style="border-width:1px; border-style:solid; border-color:ligthgrey;">{t('BOFORMS_TYPE_FORM_OBJECTIVE')}</td>
	<td  style="border-width:1px; border-style:solid; border-color:ligthgrey;">{$form_description}</td>
</tr>

<tr>
	<td  style="border-width:1px; border-style:solid; border-color:ligthgrey;">{t('BOFORMS_POPUP_CREATE_NEW_FORM_TARGET')}</td>
	<td  style="border-width:1px; border-style:solid; border-color:ligthgrey;">{$form_target_selected}</td>
</tr>

<tr>
	<td  style="border-width:1px; border-style:solid; border-color:ligthgrey;">{t('BOFORMS_POPUP_CREATE_NEW_FORM_DEVICE')}</td>
	<td  style="border-width:1px; border-style:solid; border-color:ligthgrey;">{$device}</td>
</tr>

{if $workflow_standard == '1'}
<tr>
	<td  style="border-width:1px; border-style:solid; border-color:ligthgrey;">{t('BOFORMS_POPUP_CREATE_NEW_FORM_WORKFLOW')}</td>
	<td  style="border-width:1px; border-style:solid; border-color:ligthgrey;">{t('BOFORMS_REFERENTIAL_FORM_CONTEXT_STANDARD')}</td>
</tr>
{/if}

{if $workflow_context_pos == '1'}
<tr>
	<td  style="border-width:1px; border-style:solid; border-color:ligthgrey;">{t('BOFORMS_POPUP_CREATE_NEW_FORM_WORKFLOW')}</td>
	<td  style="border-width:1px; border-style:solid; border-color:ligthgrey;">{t('BOFORMS_REFERENTIAL_FORM_CONTEXT_CONTEXTUALIZED_SALE')}</td>
</tr>
{/if}

{if $workflow_context_vehicle == '1'}
<tr>
	<td  style="border-width:1px; border-style:solid; border-color:ligthgrey;">{t('BOFORMS_POPUP_CREATE_NEW_FORM_WORKFLOW')}</td>
	<td  style="border-width:1px; border-style:solid; border-color:ligthgrey;">{t('BOFORMS_REFERENTIAL_FORM_CONTEXT_CONTEXTUALIZED_VEHICULE')}</td>
</tr>
{/if}

<tr>
	<td  style="border-width:1px; border-style:solid; border-color:ligthgrey;">{t('BOFORMS_REQUEST_OPPORTUNITY')}</td>
	<td  style="border-width:1px; border-style:solid; border-color:ligthgrey;">{$opportunity}</td>
</tr>

{if $formexample}
<tr>
	<td  style="border-width:1px; border-style:solid; border-color:ligthgrey;">{t('BOFORMS_POPUP_CREATE_NEW_FORM_GIVE_EXAMPLE')}</td>
	<td  style="border-width:1px; border-style:solid; border-color:ligthgrey;">{$formexample|nl2br}</td>
</tr>
{/if}

<tr>
	<td  style="border-width:1px; border-style:solid; border-color:ligthgrey;">{t('BOFORMS_POPUP_CREATE_NEW_FORM_ADD_FIELD')}</td>
	<td  style="border-width:1px; border-style:solid; border-color:ligthgrey;">{$formaddfields|nl2br}</td>
</tr>

{foreach from=$tbl_all_fields item=to_suppr}
<tr>
	<td  style="border-width:1px; border-style:solid; border-color:ligthgrey;">{t('BOFORMS_ADD_FIELD')}</td>
	<td  style="border-width:1px; border-style:solid; border-color:ligthgrey;">{$to_suppr.label}</td>
</tr>
{/foreach}

</table>

<ul>
	<li>{t('BOFORMS_SUPPORT_COUNTRY')}: {$countrycode} ({$culture_str})</li>
	<li>{t('BOFORMS_SUPPORT_WEBMASTER_NAME')}: {$webmaster_name}</li>
	<li>{t('BOFORMS_SUPPORT_ENVIRONMENT')}: {$environnement}</li>
	<li>{t('BOFORMS_SITE')}: {$site} - {$formsitelabel}</li>
</ul>	
