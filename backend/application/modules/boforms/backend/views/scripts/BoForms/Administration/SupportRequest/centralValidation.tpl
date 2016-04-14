<h2>{t('BOFORMS_REQUEST_CENTRAL_NOTIFICATION_CREATED')}</h2>

<ul>
	<li>{t('BOFORMS_SUPPORT_COUNTRY')}: {$countrycode} ({$culture_str})</li>
	<li>{t('BOFORMS_SUPPORT_WEBMASTER_NAME')}: {$webmaster_name}</li>
	<li>{t('BOFORMS_SUPPORT_REQUEST_DESCRIPTION')}: {$request_description|nl2br}</li>
</ul>

<ul>
	<li>{t('BOFORMS_SUPPORT_ENVIRONMENT')}: {$environnement}</li>
	<li>RPI: {$rpi}</li>
	<li>{t('BOFORMS_DEVICE')}: {$device}</li>
	<li>{t('BOFORMS_TYPE_FORMULAIRE')}: {$form_type}</li>
	<li>{t('BOFORMS_CONTEXT')}: {$form_context}</li>
	<li>{t('BOFORMS_CLIENT_TYPE')}: {$form_customer_type}</li>
	<li>{t('BOFORMS_SITE')}: {$site} - {$form_site_label}</li>
</ul>	
		
