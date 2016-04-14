{*

	Attention: ne pas supprimer les commentaires smarty qui servent pour eviter les sauts de ligne qui cassent l'affichage cote jira 

*}

||{t('BOFORMS_REQUEST_FIELDNAME')}||{t('BOFORMS_REQUEST_FILLEDTEXT')}||
{if $tbl_all_fields|@count gt 0}
{foreach from=$tbl_all_fields item=to_suppr}
|{$to_suppr.label}|{$to_suppr.description|replace:"\n":' \\\\ '}|
{/foreach}
{/if}{*

*}{if $anomalie_description}|{t('BOFORMS_REQUEST_ANOMALY_NOT_LINK_FIELD')}|{$anomalie_description|replace:"\n":' \\\\ '}|{/if}


* {t('BOFORMS_SUPPORT_COUNTRY')}: {$countrycode} ({$culture_str})
* {t('BOFORMS_SUPPORT_WEBMASTER_NAME')}: {$webmaster_name}
* {t('BOFORMS_SUPPORT_ENVIRONMENT')}: {$environnement}
* RPI: {$rpi}
* {t('BOFORMS_DEVICE')}: {$device}
* {t('BOFORMS_TYPE_FORMULAIRE')}: {$form_type}
* {t('BOFORMS_CONTEXT')}: {$form_context}
* {t('BOFORMS_CLIENT_TYPE')}: {$form_customer_type}
* {t('BOFORMS_SITE')}: {$site} - {$form_site_label}
