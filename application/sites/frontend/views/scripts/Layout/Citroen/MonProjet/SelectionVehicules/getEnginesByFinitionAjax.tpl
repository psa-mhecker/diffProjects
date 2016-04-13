{if $enginesSelect}
	<li><a {if !$engineCurrent}class="on"{/if} href="#0" data-value="0">{'CHOISIR_UNE_VERSION'|t}</a></li>
	{foreach $enginesSelect as $engine  key=key}
		<li><a {gtm name="monprojet_mesvehicules_choix_version_vehicule_1" data=$aParams datasup=['value' => $sFinitionCode|cat:$engine.ENGINE_CODE] labelvars=['%nom du boutton%' => 'version', '%nom du vehicule%' => $aVehicule.VEHICULE_LABEL, '%nom de version%' => $engine.ENGINE_LABEL, '%code lcdv10%' => $sFinitionCode|cat:$engine.ENGINE_CODE]} data-value="{$sFinitionCode}|{$engine.ENGINE_CODE}" href="#0" {if $engine.ENGINE_CODE == $engineCurrent} class="on"{/if}>{$engine.ENGINE_LABEL}</a></li>
	{/foreach}
{/if}