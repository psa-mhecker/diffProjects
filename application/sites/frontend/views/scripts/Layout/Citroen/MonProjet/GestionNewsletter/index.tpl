{if $user && $user->isLogged()}
<div id="{$aParams.ID_HTML}" class="row of6 withBorder">
	<div class="col span6 margeBottom">
		<p class="meaSubTilte lvl2">{$aParams.ZONE_TITRE}</p>
		{if $aParams.ZONE_TITRE2}
		{if $user->getOptinDealer()}
		{assign var='etat' value='active'}
		{else}
		{assign var='etat' value='inactive'}
		{/if}
		<input type="checkbox" id="dealer" name="dealer"{if $user->getOptinDealer()} checked="checked"{/if} {gtm name='Optin_1' data=$aParams datasup=['value'=>$etat] labelvars=['%choix optin%'=>$aParams.ZONE_TEXTE3]}>
		<label for="dealer">{$aParams.ZONE_TEXTE3}</label>
		{/if}
	</div>
	{if $aParams.ZONE_TITRE6}
	{if $user->getOptinBrand()}
	{assign var='etat' value='active'}
	{else}
	{assign var='etat' value='inactive'}
	{/if}
	<div class="col span6">
		<input type="checkbox" id="brand" name="brand"{if $user->getOptinBrand()} checked="checked"{/if} {gtm name='Optin_3' data=$aParams datasup=['value'=>$etat] labelvars=['%choix optin%'=>$aParams.ZONE_TEXTE2]}>
		<label for="brand">{$aParams.ZONE_TEXTE2}</label>
	</div>
	{/if}
	{if $aParams.ZONE_TITRE4}
	{if $user->getOptinPartner()}
	{assign var='etat' value='active'}
	{else}
	{assign var='etat' value='inactive'}
	{/if}
	<div class="col span6">
		<input type="checkbox" id="partner" name="partner"{if $user->getOptinPartner()} checked="checked"{/if} {gtm name='Optin_2' data=$aParams datasup=['value'=>$etat] labelvars=['%choix optin%'=>$aParams.ZONE_TEXTE4]}>
		<label for="partner">{$aParams.ZONE_TEXTE4}</label>
	</div>
	{/if}
</div>
{/if}