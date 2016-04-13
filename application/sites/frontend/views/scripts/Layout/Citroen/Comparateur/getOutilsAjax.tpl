<ul class="actions">
	{$configurateur}
</ul>
<ul class="actions">
	{foreach $aTools as $tool key=key name=outils}
		<li class="blue"><a {gtm name="comparateur_click_cta" data=$aParams datasup=['value' => $smarty.foreach.listFin.iteration+3] labelvars=['%position%' => $smarty.foreach.listFin.iteration+3, '%nom du cta%' => $tool.BARRE_OUTILS_TITRE, '%id du lien%' => $smarty.foreach.listFin.iteration+3]} href="{urlParser url=$tool.BARRE_OUTILS_URL_WEB}" {if $tool.BARRE_OUTILS_MODE_OUVERTURE == 2} target="_blank" {/if}><span>{$tool.BARRE_OUTILS_TITRE}</span></a></li>
	{/foreach}
</ul>
{if $btnAjout}
<ul class="actions">
	{if $element === 0}<li class="grey"><a class="confirmMAJ" data-value="{$element}" href="#MS"><span>{'METTRE_A_JOUR_MA_SELECTION'|t}</span></a></li>{/if}
	<li class="grey"><a class="confirmAdd" data-value="{$element}" href="#MS"><span>{'AJOUTER_A_LA_SELECTION'|t}</span></a></li>
</ul>
{/if}