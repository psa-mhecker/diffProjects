{if $aData.ZONE_WEB == 1 && $reseauxSociauxSelected|@sizeof >= 2}
<div class="sliceNew sliceAutreReseauDesk">
    <section id="{$aData.ID_HTML}" class="clsAutreReseau">
	
		<div></div>
		
        {if $aData.ZONE_TITRE}<h2 class="subtitle"  {if ($aData.PRIMARY_COLOR|count_characters)==7 } style="color:{$aData.PRIMARY_COLOR};" {/if}>{$aData.ZONE_TITRE|escape}</h2>{/if}
		
		{if $aData.ZONE_TITRE2}<h3 class="parttitle" {if ($aData.SECOND_COLOR|count_characters)==7 } style="color:{$aData.SECOND_COLOR};" {/if} >{$aData.ZONE_TITRE2|escape}</h3>{/if}
		
		{if $aData.ZONE_TEXTE}
			<div class="mgchapo"><strong>{$aData.ZONE_TEXTE}</strong></div>
		{else}
			<div class="no-mgchapo"></div> 
		{/if}
            
        {if $reseauxSociauxSelected && $reseauxSociaux}
            <ul class="caption socials" style="margin-bottom: 0px;">
            {foreach from=$reseauxSociauxSelected item=Rs}
				<li><a href="{urlParser url=$reseauxSociaux[$Rs]['RESEAU_SOCIAL_URL_WEB']}" target="_blank"><img src="{Pelican::$config.MEDIA_HTTP}{$reseauxSociaux[$Rs].MEDIA_PATH}" width="40" height="40" alt="{$reseauxSociaux[$Rs]['RS']|capitalize:true}" /></a></li>
            {/foreach}
            </ul>
        {/if}

    </section>
</div>

{/if}