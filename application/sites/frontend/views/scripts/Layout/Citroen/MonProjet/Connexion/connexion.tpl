{if $smarty.get.erreur}
<section class="row alert red">
	<div class="col span12 cont">
		<div class="inner row of6">
			<h2 class="parttitle col span4">{if $smarty.get.erreur==1}{$aErreurs.PAGE_ZONE_MULTI_TITRE}{elseif $smarty.get.erreur==2}{$aErreurs.PAGE_ZONE_MULTI_TITRE2}{/if}</h2>
			<div class="col span4">{if $smarty.get.erreur==1}{$aErreurs.PAGE_ZONE_MULTI_TEXT}{elseif $smarty.get.erreur==2}{$aErreurs.PAGE_ZONE_MULTI_TEXT2}{/if}{if $smarty.get.msg}<br/>{$smarty.get.msg}{/if}</div>
		</div>
	</div>
</section>
{/if}
<section class="clsconnexion formproject {if !$bMessageVisible && !$smarty.get.erreur}withOutBorder{/if}">
	{if !$bBlocMasque}
	<div class="row of12 connectList withBorder clsmonprojconnexion">
		<div class="col span4">
			<h3 class="title">{$aNonIdentifie.PAGE_ZONE_MULTI_TITRE}</h3>
			<div class="content">
				<p>{$aNonIdentifie.PAGE_ZONE_MULTI_TITRE2}</p>
				<a class="tooltip" href="#mentionForm1">?</a>
				<a class="button connect-citroen-id" href="#" {gtm name='clic_sur_connexion_Citroen_ID' data=$aParams  labelvars=['%intitule du boutton%'=>'CONNEXION'|t]}>{'CONNEXION'|t}</a>
			</div>
		</div>
		{if $aConnexionRS}
		<div class="col span4">
			<div class="content">
				<p>{$aNonIdentifie.PAGE_ZONE_MULTI_TITRE3}</p>
				<a class="tooltip" href="#mentionForm2">?</a>
				<ul class="caption socials">
					{foreach from=$aConnexionRS item=RS}
					{if $RS == 1}
					<li><a href="{urlParser url={Pelican::$config.DOCUMENT_HTTP|cat:'/_/User/connexionFacebook'}}" target="_blank" {gtm name='clic_sur_connexion_facebook' data=$aParams  labelvars=['%intitule du boutton%'=>'Facebook']}><img src="{Pelican::$config.IMAGE_FRONT_HTTP}/picto/facebook.png" width="40" height="40" alt="Facebook" /></a></li>
					{elseif $RS == 2}
					<li><a href="{urlParser url={Pelican::$config.DOCUMENT_HTTP|cat:'/_/User/connexionTwitter'}}" target="_blank" {gtm name='clic_sur_connexion_twitter' data=$aParams  labelvars=['%intitule du boutton%'=>'Twitter']}><img src="{Pelican::$config.IMAGE_FRONT_HTTP}/picto/twitter.png" width="40" height="40" alt="Twitter" /></a></li>
					{elseif $RS == 3}
					<li><a href="{urlParser url={Pelican::$config.DOCUMENT_HTTP|cat:'/_/User/connexionGoogle'}}" target="_blank" {gtm name='clic_sur_connexion_google' data=$aParams  labelvars=['%intitule du boutton%'=>'Google+']}><img src="{Pelican::$config.IMAGE_FRONT_HTTP}/picto/google.png" width="40" height="40" alt="Google+" /></a></li>
					{/if}
					{/foreach}
				</ul>
			</div>
		</div>
		{/if}
		<div class="col span4 withBorder">
			<h3 class="title">{$aNonIdentifie.PAGE_ZONE_MULTI_TITRE4}</h3>
			<div class="content">
				<p>{$aNonIdentifie.PAGE_ZONE_MULTI_LABEL5}</p>
				<a class="button connexion" href="#"  {gtm name='ouverture/fermeture_pop_in_insription' data=$aParams  labelvars=['%intitule du boutton%'=>'CREER_MON_COMPTE'|t]}>{'CREER_MON_COMPTE'|t}</a>
			</div>
		</div>
		<div id="mentionForm1" class="layertip" style="">{$aNonIdentifie.PAGE_ZONE_MULTI_TEXT2}</div>
		<div id="mentionForm2" class="layertip" style="">{$aNonIdentifie.PAGE_ZONE_MULTI_TEXT3}</div>
		<div id="layerconnexion">
			<div class="pormpt">
				<iframe width="600" height="400"></iframe>
			</div>
		</div>
		<div id="layerregister">
			<div class="prompt register">
				<h3 class="title">{$aNonIdentifie.PAGE_ZONE_MULTI_TITRE4}</h3>
				<p>{'AVEC VOTRE_ADRESSE_EMAIL'|t}</p>
				<a href="{urlParser url={$sURLPageConnexion|cat:'?inscription'}}" class="button" {gtm name='clic_sur_inscription_Citroen_ID' data=$aParams  labelvars=['%intitule du boutton%'=>'CONTINUER'|t]}>{'CONTINUER'|t}</a>
				{if $aConnexionRS}
				<p>{'OU_DIRECTEMENT_AVEC'|t}</p>
				<div class="connects">
					<ul>
						{foreach from=$aConnexionRS item=RS}
						{if $RS == 1}
						<li><a href="{urlParser url={Pelican::$config.DOCUMENT_HTTP|cat:'/_/User/connexionFacebook'}}" target="_blank" {gtm name='clic_sur_inscription_facebook' data=$aParams  labelvars=['%intitule du boutton%'=>'Facebook']}><img width="195" height="38" alt="Facebook" src="{Pelican::$config.IMAGE_FRONT_HTTP}/picto/fb-connect.png" class="noscale"></a></li>
						{elseif $RS == 2}
						<li><a href="{urlParser url={Pelican::$config.DOCUMENT_HTTP|cat:'/_/User/connexionTwitter'}}" target="_blank" {gtm name='clic_sur_inscription_twitter' data=$aParams  labelvars=['%intitule du boutton%'=>'Twitter']}><img width="190" height="38" alt="Twitter" src="{Pelican::$config.IMAGE_FRONT_HTTP}/picto/tw-connect.png" class="noscale"></a></li>
						{elseif $RS == 3}
						<li><a href="{urlParser url={Pelican::$config.DOCUMENT_HTTP|cat:'/_/User/connexionGoogle'}}" target="_blank" {gtm name='clic_sur_inscription_google' data=$aParams  labelvars=['%intitule du boutton%'=>'Google+']}><img width="195" height="38" alt="Google+" src="{Pelican::$config.IMAGE_FRONT_HTTP}/picto/gp-connect.png" class="noscale"></a></li>
						{/if}
						{/foreach}
					</ul>
				</div>
				{/if}
			</div>
		</div>
	</div>
	{/if}