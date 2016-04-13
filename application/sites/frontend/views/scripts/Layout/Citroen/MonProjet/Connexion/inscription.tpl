<section class="clsinscription form forminscription {if !$bMessageVisible}withOutBorder{/if}">
	<div class="row of12">
		<h3 class="title col span4">{'CREER_VOTRE_COMPTE'|t}</h3>
		<div class="col span8 right alreadySubscribe">
			<p>{'DEJA_INSCRIT'|t}</p>
			<a class="button BIG connexion connect-citroen-id" href="#" {gtm name='ouverture/fermeture_pop_in_connexion' data=$aParams  labelvars=['%intitule du boutton%'=>'CONNEXION'|t]}>{'CONNEXION'|t}</a>
		</div>
	</div>
	<div class="row of6 fields">
		<div class="row of6">
			<p class="col span4 noMarge">{'VOS_IDENTIFIANTS'|t}</p>
		</div>
		<div class="new col span3 row of4 field">
			<label for="email" class="col"><span>{'EMAIL'|t}*</span></label>
			<div class="col span3">
				<input type="text" name="email" id="email" value="" />
			</div>
		</div>
		<div class="alredayaccount" style="display: none;">
			<span class="error">{'CET_EMAIL_EST_DEJA_ASSOCIE_A_UN_COMPTE_CITROENID'|t}</span>
			<a href="#" class="button connect connect-citroen-id">{'CONNEXION'|t}</a>
		</div>
		<div class="new col span3 row of4 field withmarge">
			<label for="confirmEmail" class="col"><span>{'CONFIRMATION_EMAIL'|t}*</span></label>
			<div class="col span3">
				<input type="text" name="confirmEmail" id="confirmEmail" value="" />
			</div>
		</div>
		<div class="new col span3 row of4 field">
			<label for="password" class="col"><span>{'MOT_DE_PASSE'|t}*</span></label>
			<div class="col span3">
				<input type="password" name="password" id="password" value="" />
			</div>
		</div>
		<div class="new col span3 row of4 field last">
			<label for="confirmPassword" class="col"><span>{'CONFIRMATION_MOT_DE_PASSE'|t}*</span></label>
			<div class="col span3">
				<input type="password" name="confirmPassword" id="confirmPassword" value="" />
			</div>
		</div>
		<div class="new span3 row of6 clean">
			<p class="col span4 noMarge">{'VOS_INFORMATIONS_PERSONNELLES'|t}</p>
		</div>
		<div class="new col span3 row of4 field">
			<label class="col"><span>{'CIVILITE'|t}*</span></label>
			<div class="col span3">
				<input type="hidden" name="civility" />
				<div class="selectZone">
					<ul class="select">
                                                <li><a href="#0" id="select_civility" data-value="" class="on">{'CHOISISSEZ'|t}</a></li>
						<li><a href="#0" data-value="MR">{'MONSIEUR'|t}</a></li>
						<li><a href="#0" data-value="MRS">{'MADAME'|t}</a></li>
						<li><a href="#0" data-value="MISS">{'MADEMOISELLE'|t}</a></li>
					</ul>
				</div>
			</div>
		</div>
		<div class="new col span3 row of4 field">
			<label for="firstname" class="col"><span>{'PRENOM'|t}*</span></label>
			<div class="col span3">
                            <input type="text" name="firstname" id="firstname" value="" />
			</div>
		</div>
		<div class="new col span3 row of4 field">
			<label for="lastname" class="col"><span>{'NOM'|t}*</span></label>
			<div class="col span3">
                            <input type="text" name="lastname" id="lastname" value="" />
			</div>
		</div>
		<div class="row of6 fiels">
			<div class="col span4">
			{if $aInscription.PAGE_ZONE_MULTI_TITRE}<input type="checkbox" name="dealer" id="dealer"><label for="dealer">{$aInscription.PAGE_ZONE_MULTI_TEXT}{if $aInscription.PAGE_ZONE_MULTI_LABEL}*{/if}</label>{/if}
			{if $aInscription.PAGE_ZONE_MULTI_TITRE4}<input type="checkbox" name="brand" id="brand"><label for="brand">{$aInscription.PAGE_ZONE_MULTI_TEXT4}{if $aInscription.PAGE_ZONE_MULTI_LABEL4}*{/if}</a></label>{/if}
			{if $aInscription.PAGE_ZONE_MULTI_TITRE2}<input type="checkbox" name="partner" id="partner"><label for="partner">{$aInscription.PAGE_ZONE_MULTI_TEXT2}{if $aInscription.PAGE_ZONE_MULTI_LABEL2}*{/if}</label>{/if}
			{if $aInscription.PAGE_ZONE_MULTI_TITRE3}<input type="checkbox" name="rules" id="rules"><label for="rules">{$aInscription.PAGE_ZONE_MULTI_TEXT3}{if $aInscription.PAGE_ZONE_MULTI_LABEL3}*{/if}</a></label>{/if}
			</div>
		</div>
	</div>
	<a class="button valid" href="#">{'VALIDER'|t}</a>
	<span class="error" style="display: none;">{'MESSAGE_ERREUR_VEUILLEZ_SAISIR_LE_RENSEIGNEMENT_MANQUANT'|t}</span>
	{if $aConnexionRS}
	<div class="row of6 socials">
		<p class="col span4">{'FACILITEZ_VOTRE_INSCRIPTION_AVEC_VOS_COMPTES_SOCIAUX'|t}</p>
	</div>
	<div class="caption connects">
		{foreach from=$aConnexionRS item=RS}
		{if $RS == 1}
		<li><a href="{urlParser url={Pelican::$config.DOCUMENT_HTTP|cat:"/_/User/connexionFacebook"}}" {gtm name='clic_sur_inscription_facebook' data=$aParams  labelvars=['%intitule du boutton%'=>'Facebook']}><img class="noscale" src="{Pelican::$config.IMAGE_FRONT_HTTP}/picto/fb-register.png" width="195" height="38" alt="Facebook" /></a></li>
		{elseif $RS == 3}
		<li><a href="{urlParser url={Pelican::$config.DOCUMENT_HTTP|cat:"/_/User/connexionGoogle"}}"  {gtm name='clic_sur_inscription_google' data=$aParams  labelvars=['%intitule du boutton%'=>'Google+']}><img class="noscale" src="{Pelican::$config.IMAGE_FRONT_HTTP}/picto/gp-register.png" width="195" height="38" alt="Google+" /></a></li>
		{elseif $RS == 2}
		<li><a href="{urlParser url={Pelican::$config.DOCUMENT_HTTP|cat:"/_/User/connexionTwitter"}}"  {gtm name='clic_sur_inscription_twitter' data=$aParams  labelvars=['%intitule du boutton%'=>'Twitter']}><img class="noscale" src="{Pelican::$config.IMAGE_FRONT_HTTP}/picto/tw-register.png" width="195" height="38" alt="Twitter" /></a></li>
		{/if}
		{/foreach}
	</div>
	{/if}
	<div id="layerconnexion">
		<div class="pormpt">
			<iframe width="600" height="400"></iframe>
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
				erreurs.push('email', 'confirmEmail', 'mailError');
			}
                        if (civility == ''){
                                erreurs.push('civility');
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
				erreurs.push('password', 'confirmPassword', 'passwordError');
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
						url: "/_/User/inscription",
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
				var errorMessage = '';
                                $('section.forminscription input[type=\'text\']').removeClass('error');
				$('label[for=\'dealer\']').removeClass('error_check');
				$('label[for=\'brand\']').removeClass('error_check');
				$('label[for=\'partner\']').removeClass('error_check');
				$('label[for=\'rules\']').removeClass('error_check');
                                
                                $('#select_civility').css("color", "#868689");
                                $('#select_civility').css("font-style", "normal");                                                      
                                $('.select').css("border-color", "#D0D0D3");
                                
				$.each(erreurs, function(key, value) {
                    errorMessage = '{/literal}{'MESSAGE_ERREUR_VEUILLEZ_SAISIR_LE_RENSEIGNEMENT_MANQUANT'|t:'js'}{literal}';
					switch(value)
					{
						case 'dealer' :
							$('label[for=\'dealer\']').addClass('error_check');
							break;
						case 'brand' :
							$('label[for=\'brand\']').addClass('error_check');
							break;
						case 'partner' :
							$('label[for=\'partner\']').addClass('error_check');
							break;
						case 'rules' :
							$('label[for=\'rules\']').addClass('error_check');
							break;
                                                case 'civility' :
							$('#select_civility').css("color", "#DC002E");
                                                        $('#select_civility').css("font-style", "italic");                                                        
                                                        $('.select').css("border-color", "#DC002E");
							break;
						default:
							$('section.forminscription input[name=\''+value+'\']').addClass('error');
							break;
					}
				});

                if (jQuery.inArray('mailError', erreurs) != -1) {
                    if (errorMessage) errorMessage += '<br/>';
                    errorMessage += '{/literal}{'MESSAGE_ERREUR_MAIL_CONFIRMATION'|t:'js'}{literal}';
                }

                if (jQuery.inArray('passwordError', erreurs) != -1) {
                    if (errorMessage) errorMessage += '<br/>';
                    errorMessage += '{/literal}{'MESSAGE_ERREUR_PASSWORD_CONFIRMATION'|t:'js'}{literal}';
                }

                $('section.forminscription span.error').html(errorMessage).show();
			}
		}
		{/literal}
	</script>