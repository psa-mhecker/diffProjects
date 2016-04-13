<section class="form formproject forminscription {if !$bMessageVisible}withOutBorder{/if}">
	<h3 class="title">{$aFinalisationInscriptionRS.PAGE_ZONE_MULTI_TITRE}</h3>
	<p class="meaSubTilte zonetexte">{$aFinalisationInscriptionRS.PAGE_ZONE_MULTI_TEXT}</p>
	<p class="meaSubTilte">{'VOS_IDENTIFIANTS'|t}</p>
	<div class="row of6 fields">
		<div class="new col span3 row of4 field">
			<label class="col" for="email"><span>{'EMAIL'|t}*</span></label>
			<div class="col span3">
				<input type="text" value="{$aRsProfile.email}" id="email" name="email">
			</div>
		</div>
		<div class="alredayaccount" style="display: none;">
			<span class="error">{'CET_EMAIL_EST_DEJA_ASSOCIE_A_UN_COMPTE_CITROENID'|t}</span>
			<a href="#LOREM" class="button connect connect-citroen-id">{'CONNEXION'|t}</a>
		</div>
		<div class="new col span3 row of4 field withmarge">
			<label for="confirmEmail" class="col"><span>{'CONFIRMATION_EMAIL'|t}*</span></label>
			<div class="col span3">
				<input type="text" name="confirmEmail" id="confirmEmail" value="" />
			</div>
		</div>
		<div class="new col span3 row of4 field">
			<label class="col" for="password"><span>{'MOT_DE_PASSE'|t}*</span></label>
			<div class="col span3">
				<input type="password" value="" id="password" name="password">
			</div>
		</div>
		<div class="new col span3 row of4 field margeBottom">
			<label class="col" for="confirmPassword"><span>{'CONFIRMATION_MOT_DE_PASSE'|t}*</span></label>
			<div class="col span3">
				<input type="password" value="" id="confirmPassword" name="confirmPassword">
			</div>
		</div>
		<div class="new span3 row of6 clean">
			<p class="col span4">{'VOS_INFORMATIONS_PERSONNELLES'|t}</p>
		</div>
		<div class="new col span3 row of4 field">
			<label class="col"><span>{'CIVILITE'|t}*</span></label>
			<div class="col span3">
				<input type="hidden" name="civility" value="">
				<div class="selectZone">
					<ul class="select">
						<li><a data-value="MR" href="#0" class="on">{'MONSIEUR'|t}</a></li>
						<li><a data-value="MRS" href="#0">{'MADAME'|t}</a></li>
						<li><a data-value="MISS" href="#0">{'MADEMOISELLE'|t}</a></li>
					</ul>
				</div>
			</div>
		</div>
		<div class="new col span3 row of4 field">
			<label class="col" for="lastname"><span>{'NOM'|t}*</span></label>
			<div class="col span3">
				<input type="text" value="{$aRsProfile.last_name}" id="lastname" name="lastname">
			</div>
		</div>
		<div class="new col span3 row of4 field">
			<label class="col" for="firstname"><span>{'PRENOM'|t}*</span></label>
			<div class="col span3">
				<input type="text" value="{$aRsProfile.first_name}" id="firstname" name="firstname">
			</div>
		</div>
		<!--<div class="new col span3 row of4 field">
			<label class="col"><span>{'PAYS'|t} *</span></label>
			<div class="col span3">
				<input type="hidden" name="civility" value="france">
				<div class="selectZone">
					<ul class="select">
						<li><a data-value="france" href="#" class="on">France</a></li>
						<li><a data-value="value01" href="#">Value 01</a></li>
						<li><a data-value="value02" href="#">Value 02</a></li>
					</ul>
				</div>
			</div>
		</div>-->
		<div class="row of6 fiels">
			<div class="col span4">
				{if $aInscription.PAGE_ZONE_MULTI_TITRE}<input type="checkbox" name="dealer" id="dealer"><label for="dealer">{$aInscription.PAGE_ZONE_MULTI_TEXT}{if $aInscription.PAGE_ZONE_MULTI_LABEL}*{/if}</label>{/if}
				{if $aInscription.PAGE_ZONE_MULTI_TITRE4}<input type="checkbox" name="brand" id="brand"><label for="brand">{$aInscription.PAGE_ZONE_MULTI_TEXT4}{if $aInscription.PAGE_ZONE_MULTI_LABEL4}*{/if}</label>{/if}
				{if $aInscription.PAGE_ZONE_MULTI_TITRE2}<input type="checkbox" name="partner" id="partner"><label for="partner">{$aInscription.PAGE_ZONE_MULTI_TEXT2}{if $aInscription.PAGE_ZONE_MULTI_LABEL2}*{/if}</label>{/if}
				{if $aInscription.PAGE_ZONE_MULTI_TITRE3}<input type="checkbox" name="rules" id="rules"><label for="rules">{$aInscription.PAGE_ZONE_MULTI_TEXT3}{if $aInscription.PAGE_ZONE_MULTI_LABEL3}*{/if}</a></label>{/if}
			</div>
		</div>
		<div class="row of12 register">
			<div class="col span3">
				<a href="#" class="button big">{'VALIDER'|t}</a>
			</div>
			<div class="col span9">
				<span class="error" style="display: none;">{'MESSAGE_ERREUR_VEUILLEZ_SAISIR_LE_RENSEIGNEMENT_MANQUANT'|t}</span>
			</div>
		</div>
	</div>
	<script type="text/javascript">
		{literal}
		var validationLock = false;
		function validationInscription() {
			email = $('section.forminscription input[name=\'email\']').val();
			confirmEmail = $('section.forminscription input[name=\'confirmEmail\']').val();
			password = $('section.forminscription input[name=\'password\']').val();
			confirmPassword = $('section.forminscription input[name=\'confirmPassword\']').val();
			civility = $('section.forminscription input[name=\'civility\']').val();
			firstname = $('section.forminscription input[name=\'firstname\']').val();
			lastname = $('section.forminscription input[name=\'lastname\']').val();
			dealer = $('section.forminscription input[name=\'dealer\']').is(':checked');
			brand = $('section.forminscription input[name=\'brand\']').is(':checked');
			partner = $('section.forminscription input[name=\'partner\']').is(':checked');
			rules = $('section.forminscription input[name=\'rules\']').is(':checked');
			var erreurs = new Array();
			if (email == '') {
				erreurs.push('email');
			}
			if (confirmEmail == '') {
				erreurs.push('confirmEmail');
			}
			if (email != confirmEmail) {
				erreurs.push('email', 'confirmEmail');
			}
			if (password.length < 3) {
				erreurs.push('password');
			}
			if (password == '') {
				erreurs.push('password');
			}
			if (confirmPassword == '') {
				erreurs.push('confirmPassword');
			}
			if (password != confirmPassword) {
				erreurs.push('password', 'confirmPassword');
			}
			if (firstname == '') {
				erreurs.push('firstname');
			}
			if (lastname == '') {
				erreurs.push('lastname');
			}
			{/literal}{if $aInscription.PAGE_ZONE_MULTI_LABEL}{literal}
			if (!dealer) {
				erreurs.push('dealer');
			}
			{/literal}{/if}{literal}
				{/literal}{if $aInscription.PAGE_ZONE_MULTI_LABEL4}{literal}
			if (!brand) {
				erreurs.push('brand');
			}
			{/literal}{/if}{literal}
			{/literal}{if $aInscription.PAGE_ZONE_MULTI_LABEL2}{literal}
			if (!partner) {
				erreurs.push('partner');
			}
			{/literal}{/if}{literal}
			{/literal}{if $aInscription.PAGE_ZONE_MULTI_LABEL3}{literal}
			if (!rules) {
				erreurs.push('rules');
			}
			{/literal}{/if}{literal}
			if (erreurs.length==0) {
				$('section.forminscription span.error').hide();
				$('section.forminscription input[type=\'text\']').removeClass('error');
				if (!validationLock) {
					validationLock = true;
					callAjax({
						type: 'POST',
						url: "/_/User/inscriptionRS",
						dataType: "json",
						data: {
							USR_EMAIL: email,
							USR_PASSWORD: password,
							USR_CIVILITY: civility,
							USR_FIRST_NAME: firstname,
							USR_LAST_NAME: lastname,
							OFFER_DEALER: dealer,
							OFFER_BRAND: brand,
							OFFER_PARTNER: partner
						},
						error: function() {
							validationLock = false;
						}
					});
				}
			}
			else {
				$.each(erreurs, function(key, value) {
					$('section.forminscription span.error').show();
					$('section.forminscription input[name=\''+value+'\']').addClass('error');
				});
			}
		}
		{/literal}
	</script>