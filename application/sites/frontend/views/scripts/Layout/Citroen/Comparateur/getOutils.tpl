<ul class="actions">
	{if $urlConfigurateur}<li class="red"><a href="{urlParser url=$urlConfigurateur}" target="_blank"><span>{'CONFIGURER'|t}</span></a></li>{/if}
</ul>

<ul class="actions">
	{foreach $aTools as $tool key=key}
		<li class="blue"><a href="{urlParser url=$tool.BARRE_OUTILS_URL_WEB}" {if $tool.BARRE_OUTILS_MODE_OUVERTURE == 2} target="_blank" {/if} class="activeRoll"><span>{$tool.BARRE_OUTILS_TITRE}</span></a></li>
	{/foreach}
</ul>
