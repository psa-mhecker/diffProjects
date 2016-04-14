<h2>{t('BOFORMS_REQUEST_JIRA_EVOLUTION_CREATED')|replace:'XXXXX':$key_jira_created}</h2>

<p>{t('BOFORMS_SUPPORT_REQUEST_TITLE')}: {$request_title}</p> 
<p>{t('BOFORMS_SUPPORT_PRIORITY')}: {$priority}</p>

<table style=" border-width:1px; border-style:solid; border-color:black;">
  <tr>
    <th style="border-width:1px; border-style:solid; border-color:ligthgrey;">{t('BOFORMS_SUPPORT_MODIFICATION_TYPE')}</th>
    <th style="border-width:1px; border-style:solid; border-color:ligthgrey;">{t('BOFORMS_SUPPORT_JUSTIFY_REQUEST')}</th>
    <th style="border-width:1px; border-style:solid; border-color:ligthgrey;">{t('BOFORMS_SUPPORT_DESCRIBE_NEEDS')}</th>
  </tr>
{if $description_0}
  <tr>
    <td style="border-width:1px; border-style:solid; border-color:ligthgrey;">{t('BOFORMS_NOTIFICATION_NEW_FIELDS')}</td>
    <td style="border-width:1px; border-style:solid; border-color:ligthgrey;">{$description_0|nl2br}</td>
    <td style="border-width:1px; border-style:solid; border-color:ligthgrey;"></td>
  </tr>
{/if}

{if $tbl_required|@count gt 0}
	{foreach from=$tbl_required item=to_suppr} 
	  <tr>
	    <td style="border-width:1px; border-style:solid; border-color:ligthgrey;">{t('BOFORMS_NOTIFICATION_DEL_MANDATORY_FIELD')}</td>
	    <td style="border-width:1px; border-style:solid; border-color:ligthgrey;"><b>{$to_suppr.label}</b><br/>{$to_suppr.description|nl2br}</td>
	    <td style="border-width:1px; border-style:solid; border-color:ligthgrey;"></td>
	  </tr>
	{/foreach}
{/if}

{if $description_2}
  <tr>
    <td style="border-width:1px; border-style:solid; border-color:ligthgrey;">{t('BOFORMS_NOTIFICATION_MODIFY_IMPRINT')}</td>
    <td style="border-width:1px; border-style:solid; border-color:ligthgrey;"></td>
    <td style="border-width:1px; border-style:solid; border-color:ligthgrey;">{$description_2|nl2br}</td>
  </tr>
{/if}

{if $tbl_result_compos|@count gt 0}
	{foreach from=$tbl_result_compos item=to_suppr} 
	  <tr>
	    <td style="border-width:1px; border-style:solid; border-color:ligthgrey;">{t('BOFORMS_NOTIFICATION_UPD_BUSINESS_COMPONENT')}</td>
	    <td style="border-width:1px; border-style:solid; border-color:ligthgrey;"><b>{$to_suppr.label}</b><br/>{$to_suppr.description|nl2br}</td>
	    <td style="border-width:1px; border-style:solid; border-color:ligthgrey;"></td>
	  </tr>
	{/foreach}
{/if}

{if $description_3}
  <tr>
    <td style="border-width:1px; border-style:solid; border-color:ligthgrey;">{t('BOFORMS_NOTIFICATION_UPD_BUSINESS_COMPONENT')}</td>
    <td style="border-width:1px; border-style:solid; border-color:ligthgrey;"></td>
    <td style="border-width:1px; border-style:solid; border-color:ligthgrey;">{$description_3|nl2br}</td>
  </tr>
{/if}

{if $description_4}
  <tr>
    <td style="border-width:1px; border-style:solid; border-color:ligthgrey;">{t('BOFORMS_NOTIFICATION_UPD_USER_INTERFACE')}</td>
    <td style="border-width:1px; border-style:solid; border-color:ligthgrey;"></td>
    <td style="border-width:1px; border-style:solid; border-color:ligthgrey;">{$description_4|nl2br}</td>
  </tr>
{/if}

{if $description_5}
  <tr>
    <td style="border-width:1px; border-style:solid; border-color:ligthgrey;">{t('BOFORMS_NOTIFICATION_MODIFY_OPT_IN')}</td>
    <td style="border-width:1px; border-style:solid; border-color:ligthgrey;"></td>
    <td style="border-width:1px; border-style:solid; border-color:ligthgrey;">{$description_5|nl2br}</td>
  </tr>
{/if}

{if $description_6}
  <tr>
    <td style="border-width:1px; border-style:solid; border-color:ligthgrey;">{t('BOFORMS_NOTIFICATION_MODIFY_STEP_ORDER')}</td>
    <td style="border-width:1px; border-style:solid; border-color:ligthgrey;"></td>
    <td style="border-width:1px; border-style:solid; border-color:ligthgrey;">{$description_6|nl2br}</td>
  </tr>
{/if}  

{if $description_7}
  <tr>
    <td style="border-width:1px; border-style:solid; border-color:ligthgrey;">{t('BOFORMS_NOTIFICATION_OTHER_REQUEST')}</td>
    <td style="border-width:1px; border-style:solid; border-color:ligthgrey;"></td>
    <td style="border-width:1px; border-style:solid; border-color:ligthgrey;">{$description_7|nl2br}</td>
  </tr>
{/if}

</table>

<h2>Informations sur la requete:</h2>

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
