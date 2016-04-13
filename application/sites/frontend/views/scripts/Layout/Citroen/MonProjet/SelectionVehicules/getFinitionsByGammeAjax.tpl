{if $finitionsSelect}
	<li><a {if !$finitionCurrent}class="on"{/if} href="#0" data-value="0">{'CHOISIR_UNE_FINITION'|t}</a></li>
	{foreach $finitionsSelect as $finition  key=key}
		<li><a {gtm name="monprojet_mesvehicules_choix_finition_vehicule_1" data=$aParams datasup=['value' => $sLCDV6] labelvars=['%nom du boutton%' => 'finition', '%nom du vehicule%' => $aVehicule.VEHICULE_LABEL, '%nom de finition%' => $finition.FINITION_LABEL, '%code lcdv7%' => $sLCDV6]} data-value="{$sLCDV6}|{$finition.FINITION_CODE}" href="#0"{if $finition.FINITION_CODE == $finitionCurrent} class="on"{/if}>{$finition.FINITION_LABEL}</a></li>
	{/foreach}
{/if}