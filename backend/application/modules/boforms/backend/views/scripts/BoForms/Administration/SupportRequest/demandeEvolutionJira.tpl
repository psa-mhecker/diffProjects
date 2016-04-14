{*

	Attention: ne pas supprimer les commentaires smarty qui servent pour eviter les sauts de ligne qui cassent l'affichage cote jira 

*}

{t('BOFORMS_SUPPORT_REQUEST_TITLE')}: {$request_title}

||{t('BOFORMS_SUPPORT_MODIFICATION_TYPE')}||{t('BOFORMS_SUPPORT_JUSTIFY_REQUEST')}||{t('BOFORMS_SUPPORT_DESCRIBE_NEEDS')}||
{if $description_0}
|{t('BOFORMS_NOTIFICATION_NEW_FIELDS')}|{$description_0|replace:"\n":' \\\\ '}| |
{/if}{*


*}{if $tbl_required|@count gt 0}
{foreach from=$tbl_required item=to_suppr}
|{t('BOFORMS_NOTIFICATION_DEL_MANDATORY_FIELD')}|*{$to_suppr.label}* \\ {$to_suppr.description|replace:"\n":' \\\\ '}| |
{/foreach}
{/if}{*


*}{if $description_2}
|{t('BOFORMS_NOTIFICATION_MODIFY_IMPRINT')}| |{$description_2|replace:"\n":' \\\\ '}|
{/if}{*

*}{if $tbl_result_compos|@count gt 0}
{foreach from=$tbl_result_compos item=to_suppr}
|{t('BOFORMS_NOTIFICATION_UPD_BUSINESS_COMPONENT')}|*{$to_suppr.label}* \\ {$to_suppr.description|replace:"\n":' \\\\ '}| |
{/foreach}
{/if}{*

*}{if $description_3}
|{t('BOFORMS_NOTIFICATION_UPD_BUSINESS_COMPONENT')}| |{$description_3|replace:"\n":' \\\\ '}|
{/if}{*

*}{if $description_4}
|{t('BOFORMS_NOTIFICATION_UPD_USER_INTERFACE')}| |{$description_4|replace:"\n":' \\\\ '}|
{/if}{*

*}{if $description_5}
|{t('BOFORMS_NOTIFICATION_MODIFY_OPT_IN')}| |{$description_5|replace:"\n":' \\\\ '}|
{/if}{*

*}{if $description_6}
|{t('BOFORMS_NOTIFICATION_MODIFY_STEP_ORDER')}| |{$description_6|replace:"\n":' \\\\ '}|
{/if}{*

*}{if $description_7}
|{t('BOFORMS_NOTIFICATION_OTHER_REQUEST')}| |{$description_7|replace:"\n":' \\\\ '}|
{/if}

* {t('BOFORMS_SUPPORT_COUNTRY')}: {$countrycode} ({$culture_str})
* {t('BOFORMS_SUPPORT_WEBMASTER_NAME')}: {$webmaster_name}
* {t('BOFORMS_SUPPORT_ENVIRONMENT')}: {$environnement}
* RPI: {$rpi}
* {t('BOFORMS_DEVICE')}: {$device}
* {t('BOFORMS_TYPE_FORMULAIRE')}: {$form_type}
* {t('BOFORMS_CONTEXT')}: {$form_context}
* {t('BOFORMS_CLIENT_TYPE')}: {$form_customer_type}
* {t('BOFORMS_SITE')}: {$site} - {$form_site_label}	
