<section class="formproject withOutBorder">
	<div class="row of12 botBorder">
		<h3 class="caption title">{$aConnecte.PAGE_ZONE_MULTI_TITRE}</h3>
		<div class="introtext col span8 zonetexte">{$aConnecte.PAGE_ZONE_MULTI_TEXT}</div>
		{if $aConnexionRS}
		<div class="col span6">
			<ul class="socials">
				{foreach from=$aConnexionRS item=RS}
				{if $RS == 1}
				{if !$user->getFacebookId()}<li><a href="{urlParser url={Pelican::$config.DOCUMENT_HTTP|cat:'/_/User/connexionFacebook'}}" target="_blank" {gtm name='clic_sur_connexion_facebook' data=$aParams  labelvars=['%intitule du boutton%'=>'Facebook']}><img src="{Pelican::$config.IMAGE_FRONT_HTTP}/picto/facebook.png" width="40" height="40" alt="Facebook" /></a></li>{/if}
				{elseif $RS == 2}
				{if !$user->getTwitterId()}<li><a href="{urlParser url={Pelican::$config.DOCUMENT_HTTP|cat:'/_/User/connexionTwitter'}}" target="_blank" {gtm name='clic_sur_connexion_twitter' data=$aParams  labelvars=['%intitule du boutton%'=>'Twitter']}><img src="{Pelican::$config.IMAGE_FRONT_HTTP}/picto/twitter.png" width="40" height="40" alt="Twitter" /></a></li>{/if}
				{elseif $RS == 3}
				{if !$user->getGoogleId()}<li><a href="{urlParser url={Pelican::$config.DOCUMENT_HTTP|cat:'/_/User/connexionGoogle'}}" target="_blank" {gtm name='clic_sur_connexion_google' data=$aParams  labelvars=['%intitule du boutton%'=>'Google+']}><img src="{Pelican::$config.IMAGE_FRONT_HTTP}/picto/google.png" width="40" height="40" alt="Google+" /></a></li>{/if}
				{/if}
				{/foreach}
			</ul>
		</div>
		{/if}
	</div>