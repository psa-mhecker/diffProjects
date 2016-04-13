{if $user && $user->isLogged() && $aConnexionRS}
	<div id="{$aParams.ID_HTML}" class="row of6 withBorder">
		<p class="col span6  meaSubTilte">{$aParams.ZONE_TITRE}</p>
		<ul class="col span6 socials">
			{foreach from=$aConnexionRS item=RS}
			{if $RS == 1}
			{if !$user->getFacebookId()}<li><a target="_blank" href="{urlParser url={Pelican::$config.DOCUMENT_HTTP|cat:"/_/User/connexionFacebook"}}" {gtm name='clic_sur_FB' data=$aParams  labelvars=['%intitule du boutton%'=>'Facebook']}><img width="40" height="40" alt="Facebook" src="{Pelican::$config.IMAGE_FRONT_HTTP}/picto/facebook.png" ></a></li>{/if}
			{elseif $RS == 3}
			{if !$user->getGoogleId()}<li><a target="_blank" href="{urlParser url={Pelican::$config.DOCUMENT_HTTP|cat:"/_/User/connexionGoogle"}}" {gtm name='clic_sur_G+' data=$aParams  labelvars=['%intitule du boutton%'=>'Google+']}><img width="40" height="40" alt="Google+" src="{Pelican::$config.IMAGE_FRONT_HTTP}/picto/google.png" ></a></li>{/if}
			{elseif $RS == 2}
			{if !$user->getTwitterId()}<li><a target="_blank" href="{urlParser url={Pelican::$config.DOCUMENT_HTTP|cat:"/_/User/connexionTwitter"}}" {gtm name='clic_sur_twitter' data=$aParams  labelvars=['%intitule du boutton%'=>'Twitter']}><img width="40" height="40" alt="Twitter" src="{Pelican::$config.IMAGE_FRONT_HTTP}/picto/twitter.png" ></a></li>{/if}
			{/if}
			{/foreach}
		</ul>
	</div>
	<div class="row of6 withBorder deleteProject">
		<!--
		<p class="col span6  meaSubTilte">Suppression de votre projet ?</p>
		<p class="col span6">Saisissez votre mot de passe et validez.</p>
		<label class="col span1" for="email"><span>Mot de passe</span></label>
		<div class="col span2">
			<input type="password" id="password" name="password">
		</div>
		<div class="row of7">
			<a href="#" class="button col span2" {gtm name='clic_sur _supprimer' data=$aParams  labelvars=['%intitule du boutton%'=>'DELETE'|t]}>{'DELETE'|t}</a>
		</div>
  -->
	</div>
</section>
{/if}