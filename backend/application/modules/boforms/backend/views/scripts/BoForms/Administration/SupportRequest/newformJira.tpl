{*

	Attention: ne pas supprimer les commentaires smarty qui servent pour eviter les sauts de ligne qui cassent l'affichage cote jira 

*}

||{t('BOFORMS_REQUEST_QUESTION')}||{t('BOFORMS_REQUEST_DESCRIPTION')}||
|{t('BOFORMS_TYPE_FORMULAIRE')}|{$form_type}|
|{t('BOFORMS_TYPE_FORM_OBJECTIVE')}|{$form_description|replace:"\n":' \\\\ '}|
|{t('BOFORMS_POPUP_CREATE_NEW_FORM_TARGET')}|{$form_target_selected}|
|{t('BOFORMS_POPUP_CREATE_NEW_FORM_DEVICE')}|{$device}|
{if $workflow_standard == '1'}
|{t('BOFORMS_POPUP_CREATE_NEW_FORM_WORKFLOW')}|{t('BOFORMS_REFERENTIAL_FORM_CONTEXT_STANDARD')}|
{/if}{*
*}{if $workflow_context_pos == '1'}
|{t('BOFORMS_POPUP_CREATE_NEW_FORM_WORKFLOW')}|{t('BOFORMS_REFERENTIAL_FORM_CONTEXT_CONTEXTUALIZED_SALE')}|
{/if}{*
*}{if $workflow_context_vehicle == '1'}
|{t('BOFORMS_POPUP_CREATE_NEW_FORM_WORKFLOW')}|{t('BOFORMS_REFERENTIAL_FORM_CONTEXT_CONTEXTUALIZED_VEHICULE')}|
{/if}
|{t('BOFORMS_REQUEST_OPPORTUNITY')}|{$opportunity}|
{if $formexample}
|{t('BOFORMS_POPUP_CREATE_NEW_FORM_GIVE_EXAMPLE')}|{$formexample|replace:"\n":' \\\\ '}|
{/if}{*
*}{if $formaddfields != ''}
|{t('BOFORMS_POPUP_CREATE_NEW_FORM_ADD_FIELD')}|{$formaddfields|replace:"\n":' \\\\ '}|
{/if}{*
*}{foreach from=$tbl_all_fields item=to_suppr}
|{t('BOFORMS_ADD_FIELD')}|{$to_suppr.label}|
{/foreach}

* {t('BOFORMS_SUPPORT_COUNTRY')}: {$countrycode} ({$culture_str})
* {t('BOFORMS_SUPPORT_WEBMASTER_NAME')}: {$webmaster_name}
* {t('BOFORMS_SUPPORT_ENVIRONMENT')}: {$environnement}
* {t('BOFORMS_SITE')}: {$site} - {$formsitelabel}	