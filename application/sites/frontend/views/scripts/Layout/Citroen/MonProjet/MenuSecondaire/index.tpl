{if $smarty.get.select_vehicule || ($user && $user->isLogged())}
{if $aParams.ZONE_TITRE || $aParams.ZONE_TITRE2 || $aParams.ZONE_TITRE3 || $aParams.ZONE_TITRE4 || $aParams.ZONE_TITRE5 || $aParams.ZONE_TITRE6}
	<a name="MS"></a>
<div id="{$aParams.ID_HTML}" class="stickyplaceholder keep monprojet">
	<div class="sticky">
		<div class="inner">
			<div class="logo"><a href="/">Citroën</a></div>
			<ul>
				{if $aParams.ZONE_TITRE}
				<li{if isset($smarty.get.DECOUVRIR)} class="on"{/if}><a href="?DECOUVRIR#MS"><span>{$aParams.ZONE_TITRE}</span></a></li>
				{/if}
				{if $aParams.ZONE_TITRE2}
				<li{if isset($smarty.get.COMPARER)} class="on"{/if}><a href="?COMPARER#MS"><span>{$aParams.ZONE_TITRE2}</span></a></li>
				{/if}
				{if $aParams.ZONE_TITRE3}
				<li{if isset($smarty.get.ESSAYER)} class="on"{/if}><a href="?ESSAYER#MS"><span>{$aParams.ZONE_TITRE3}</span></a></li>
				{/if}
				{if $aParams.ZONE_TITRE4}
				<li{if isset($smarty.get.TROUVER)} class="on"{/if}><a href="?TROUVER#MS"><span>{$aParams.ZONE_TITRE4}</span></a></li>
				{/if}
				{if $aParams.ZONE_TITRE5}
				<li{if isset($smarty.get.FINANCER)} class="on"{/if}><a href="?FINANCER#MS"><span>{$aParams.ZONE_TITRE5}</span></a></li>
				{/if}
				{if $aParams.ZONE_TITRE6}
				<li{if isset($smarty.get.PROFITER)} class="on"{/if}><a href="?PROFITER#MS"><span>{$aParams.ZONE_TITRE6}</span></a></li>
				{/if}
			</ul>
		</div>
	</div>
</div>
{/if}
{/if}