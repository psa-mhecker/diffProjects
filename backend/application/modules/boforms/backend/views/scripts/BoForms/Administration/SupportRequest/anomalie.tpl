<h2>{t('BOFORMS_REQUEST_JIRA_ANOMALY_CREATED')|replace:'XXXXX':$key_jira_created}</h2>

<p>{t('BOFORMS_SUPPORT_REQUEST_TITLE')}: {$request_title}</p> 
<p>{t('BOFORMS_SUPPORT_PRIORITY')}: {$priority}</p>

<table style=" border-width:1px; border-style:solid; border-color:black;">
  <tr>
  	<th  style="border-width:1px; border-style:solid; border-color:ligthgrey;">{t('BOFORMS_REQUEST_FIELD')}</th>
  	<th style="border-width:1px; border-style:solid; border-color:ligthgrey;">{t('BOFORMS_SUPPORT_DESCRIBE_ANOMALY')}</th>
  </tr>
  
{if $tbl_all_fields|@count gt 0}
	{foreach from=$tbl_all_fields item=to_suppr} 
	  <tr>
	    <td style="border-width:1px; border-style:solid; border-color:ligthgrey;">{$to_suppr.label}</td>
	    <td style="border-width:1px; border-style:solid; border-color:ligthgrey;">{$to_suppr.description|nl2br}</td>
	  </tr>
	{/foreach}
{/if}

{if $anomalie_description}
	<tr>
		<td style="border-width:1px; border-style:solid; border-color:ligthgrey;">{t('BOFORMS_REQUEST_ANOMALY_NOT_LINK_FIELD')}</td>
		<td style="border-width:1px; border-style:solid; border-color:ligthgrey;">{$anomalie_description|nl2br}</td>
	</tr>
{/if}

</table>

<ul>
	<li>{t('BOFORMS_SUPPORT_COUNTRY')}: {$countrycode} ({$culture_str})</li>
	<li>{t('BOFORMS_SUPPORT_WEBMASTER_NAME')}: {$webmaster_name}</li>
	<li>{t('BOFORMS_SUPPORT_ENVIRONMENT')}: {$environnement}</li>
	<li>RPI: {$rpi}</li>
	<li>{t('BOFORMS_DEVICE')}: {$device}</li>
	<li>{t('BOFORMS_TYPE_FORMULAIRE')}: {$form_type}</li>
	<li>{t('BOFORMS_CONTEXT')}: {$form_context}</li>
	<li>{t('BOFORMS_CLIENT_TYPE')}: {$form_customer_type}</li>
	<li>{t('BOFORMS_SITE')}: {$site} - {$form_site_label}</li>
</ul>	
