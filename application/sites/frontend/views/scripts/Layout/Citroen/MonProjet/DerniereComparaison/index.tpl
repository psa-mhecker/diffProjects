<a name="COMPARER"></a>
{if isset($smarty.get.COMPARER)}
{if $aVehiculeInSession|sizeof > 0}
<section id="{$aParams.ID_HTML}" class="row of4 historique">
	<div class="caption parttitle">{$aParams.ZONE_TITRE2}</div>
	<div class="col span1">
		<a href="?lastcomp&COMPARER#MS" class="button">{'COMPARER_A_NOUVEAU'|t}</a>
	</div>
	{section name=foo loop=3}
	{assign var=vehicules value=$smarty.section.foo.index}
	<div class="col span1">
		<ul>
			<li>{$aVehiculesFromNavigation[$aVehiculeInSession[$vehicules].LCDV6]}&nbsp;</li>
			<li>{$finitionsSelect[$vehicules][$aVehiculeInSession[$vehicules].FINITION_CODE].FINITION_LABEL}&nbsp;</li>
			<li>{$engineSelect[$aVehiculeInSession[$vehicules].ENGINE_CODE].ENGINE_LABEL}&nbsp;</li>
		</ul>
	</div>
	{/section}
</section>
{elseif !$user || !$user->isLogged()}
<section id="{$aParams.ID_HTML}" class="row of12 historique">
	<h3 class="caption parttitle">{$aParams.ZONE_TITRE}</h3>
	<div class="col span8 zonetexte">{$aParams.ZONE_TEXTE}</div>
</section>
{$aZoneConnexion}
{/if}
{/if}