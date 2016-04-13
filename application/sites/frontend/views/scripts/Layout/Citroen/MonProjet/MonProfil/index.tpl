{if $user && $user->isLogged()}
<div  id="{$aParams.ID_HTML}" class="meaSubTilte">
	<p>{'VOS_DONNEES_PERSONNELLES'|t} :</p>
	<p class="maj-ok" style="display: none;">{'VOS_DONNEES_PERSONNELLES_ONT_ETE_MAJ'|t}</p>
	<p class="maj-ko" style="display: none;">{'ERREUR_PENDANT_ENREGISTREMENT_DES_DONNEES'|t}</p>
</div>
<div class="new row of6 field">
	<label class="col span1" for="email"><span>{'EMAIL'|t} * </span></label>
	<div class="col span2">
		<input type="text" disabled="" value="{$user->getEmail()}" id="email" name="email">
	</div>
</div>
<div class="new row of6 field">
	<label class="col span1"><span>{'CIVILITE'|t} *</span></label>
	<div class="col span2">
		<input type="hidden" name="civility" value="{$user->getCivility()}">
		<div class="selectZone">
			<ul class="select">
				<li><a data-value="MR" href="#0"{if $user->getCivility()=='MR' || !$user->getCivility()} class="on"{/if}>{'MONSIEUR'|t}</a></li>
				<li><a data-value="MRS" href="#0"{if $user->getCivility()=='MRS'} class="on"{/if}>{'MADAME'|t}</a></li>
				<li><a data-value="MISS" href="#0"{if $user->getCivility()=='MISS'} class="on"{/if}>{'MADEMOISELLE'|t}</a></li>
			</ul>
		</div>
	</div>
</div>
<div class="new row of6 field">
	<label class="col span1" for="email"><span>{'NOM'|t} *</span></label>
	<div class="col span2">
		<input type="text" id="lastname" name="lastname" value="{$user->getLastname()}">
	</div>
</div>
<div class="new row of6 field">
	<label class="col span1" for="email"><span>{'PRENOM'|t} * </span></label>
	<div class="col span2">
		<input type="text" id="firstname" name="firstname" value="{$user->getFirstname()}">
	</div>
</div>
<div class="row of12 register">
	<div class="col span3">
		<a href="#" class="button disabled" {gtm name='clic_sur_enregistrer' data=$aParams labelvars=['%intitule du boutton%'=>'ENREGISTRER'|t]}>{'ENREGISTRER'|t}</a>
	</div>
	<div class="col span9">
		<span class="span6 error" style="display: none;">{'MESSAGE_ERREUR_VEUILLEZ_SAISIR_LE_RENSEIGNEMENT_MANQUANT'|t}</span>
	</div>
</div>
<div class="row of6">
	<div>
		<div class="legal zonetexte"><p>{$aParams.ZONE_TEXTE2}</p></div>
	</div>
</div>
<div class="row of12 mdp">
	<div class="col span2">
		<p class="span4">{'VOTRE_MOT_DE_PASSE'|t}</p>
	</div>
	<div class="col span6">
		<a href="#" class="span6 button" {gtm name='clic_sur_modifier_mot_de_passe' data=$aParams labelvars=['%intitule du boutton%'=>'MODIFER_VOTRE_MOT_DE_PASSE'|t]}>{'MODIFER_VOTRE_MOT_DE_PASSE'|t}</a>
	</div>
</div>
<script type="text/javascript">
	{literal}
	var validationLock = false;
	function validationInscription() {
		$('section.mesPreferences .maj-ok').hide();
		$('section.mesPreferences .maj-ok').hide();
		civility = $('section.formproject input[name=\'civility\']').val();
		firstname = $('section.formproject input[name=\'firstname\']').val();
		lastname = $('section.formproject input[name=\'lastname\']').val();
		dealer = $('section.formproject input[name=\'dealer\']').is(':checked');
		brand = $('section.formproject input[name=\'brand\']').is(':checked');
		partner = $('section.formproject input[name=\'partner\']').is(':checked');
		var erreurs = new Array();
		if (firstname == '') {
			erreurs.push('firstname');
		}
		if (lastname == '') {
			erreurs.push('lastname');
		}
		if (erreurs.length==0) {
			$('section.formproject span.error').hide();
			$('section.formproject input[type=\'text\']').removeClass('error');
			if (!validationLock) {
				validationLock = true;
				callAjax({
					type: 'POST',
					url: "/_/User/maj",
					dataType: "json",
					data: {
						USR_CIVILITY: civility,
						USR_FIRST_NAME: firstname,
						USR_LAST_NAME: lastname
					},
					error: function() {
						validationLock = false;
					}
				});
			}
		}
		else {
			$.each(erreurs, function(key, value) {
				$('section.formproject span.error').show();
				$('section.formproject input[name=\''+value+'\']').addClass('error');
			});
		}
	}
	{/literal}
</script>
{/if}