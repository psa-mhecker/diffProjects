{if $aParams.ZONE_WEB && !$aParams.Forfait}
{literal}
	<style>
{/literal}
{if ($aParams.SECOND_COLOR|count_characters)==7}
{literal}
.sliceContenuTexteCtaDesktop .actions .buttonTransversalInvert, .sliceContenuTexteCtaDesktop .buttonTransversalInvert{
{/literal}{if ($aParams.SECOND_COLOR|count_characters)==7 }{literal}
	background-color:{/literal}{$aParams.SECOND_COLOR};{literal}
	border-color:{/literal}{$aParams.SECOND_COLOR};{literal}
	color:#ffffff;
{/literal}{/if}{literal}
}
.sliceContenuTexteCtaDesktop .actions .buttonTransversalInvert:hover, .sliceContenuTexteCtaDesktop .actions .buttonTransversalInvert:active, .sliceContenuTexteCtaDesktop .buttonTransversalInvert:hover, .sliceContenuTexteCtaDesktop .buttonTransversalInvert:active:hover{
{/literal}{if ($aParams.SECOND_COLOR|count_characters)==7 }{literal}
	background-color:#ffffff;
	border-color:{/literal}{$aParams.SECOND_COLOR};{literal}
	color:{/literal}{$aParams.SECOND_COLOR}; {literal}
{/literal}{/if}{literal}
}
{/literal}
{/if}
{literal}
	</style>
{/literal}
<div class="sliceNew sliceContenuTexteCtaDesktop">
<section id="{$aParams.ID_HTML}" class="row of3 clscontenutextecta">
	{if $aParams.ZONE_TITRE}<h2 class="titlePart" {if ($aParams.PRIMARY_COLOR|count_characters)==7 } style="color:{$aParams.PRIMARY_COLOR};" {/if}>{$aParams.ZONE_TITRE|escape}</h2>{/if}
    
	{if $aParams.ZONE_TITRE2}<h3 class="subTitlePart" {if ($aParams.SECOND_COLOR|count_characters)==7 } style="color:{$aParams.SECOND_COLOR};" {/if}>{$aParams.ZONE_TITRE2|escape}</h3>{/if}
	<div class="row">
    
	 <div class="columns column_66 zonetexte">{$aParams.ZONE_TEXTE}</div>
	<div class="columns column_25 actionsPanel right">
	{if $aCTA|@sizeof > 0}
		<ul class="actions">
			{foreach from=$aCTA item=cta key=num}
			 {if $cta.OUTIL}
                        {$cta.OUTIL}
			{else}
				<li class="cta"><a class="buttonTransversalInvert" href="{urlParser url=$cta.PAGE_ZONE_MULTI_URL}"{if $cta.PAGE_ZONE_MULTI_VALUE=='blank'} target="_blank"{/if} {gtm name='clic_sur_CTA_N1' data=$aParams datasup=['value'=>$num] labelvars=['%nom du cta%'=>$cta.PAGE_ZONE_MULTI_LABEL, '%nom du vehicule%'=>$aParams.PAGE_META_TITLE]}>{$cta.PAGE_ZONE_MULTI_LABEL}</a></li>
			{/if}
			{/foreach}
		</ul>
	{/if}
	</div>
	</div>
</section>
</div>
{/if}