{if $aData.ZONE_WEB == 1}

{literal}
<style>
.sliceMobileTabletteDesk .buttonLink:hover{color: {/literal}{$aData.SECOND_COLOR}{literal}!important;}
</style>
{/literal}
<div class="sliceNew sliceMobileTabletteDesk">
    {if $aPlatformAppli|@sizeof > 0}
    <section id="{$aData.ID_HTML}" class="apps type-appli clscitroenmobiletablette">
		<div></div>
        {if $aData.ZONE_TITRE}<h2 class="subtitle" {if ($aData.PRIMARY_COLOR|count_characters)==7 } style="color:{$aData.PRIMARY_COLOR};" {/if}>{$aData.ZONE_TITRE}</h2>{/if}
		<div class="row of3">
        {foreach from=$aPlatformAppli item=Appli}
		
            <div class="col pttl">
                {if $Appli.MEDIA}<img src="{$Appli.MEDIA}" width="51" height="51" alt="" />{/if}
                {if $Appli.TITRE}<h3 class="parttitle" {if ($aData.SECOND_COLOR|count_characters)==7 } style="color:{$aData.SECOND_COLOR};" {/if}><span> {$Appli.TITRE}</span></h3>{/if}
                {if $Appli.TEXTE}<p>{$Appli.TEXTE}</p>{/if}
                <ul class="actions">
                    <li><a class="buttonLink" href="{urlParser url=$Appli.WEB_1}" target="_blank">{if $Appli.MEDIA_1}<img src="{$Appli.MEDIA_1}" alt="{$Appli.PLATFORM_1}" />{else}{$Appli.PLATFORM_1}{/if}</a></li>
                    {if $Appli.WEB_2 && $Appli.PLATFORM_2}<li><a class="buttonLink" href="{urlParser url=$Appli.WEB_2}" target="_blank">{if $Appli.MEDIA_2}<img src="{$Appli.MEDIA_2}" alt="{$Appli.PLATFORM_2}" />{else}{$Appli.PLATFORM_2}{/if}</a></li>{/if}
                    {if $Appli.WEB_3 && $Appli.PLATFORM_3}<li><a class="buttonLink" href="{urlParser url=$Appli.WEB_3}" target="_blank">{if $Appli.MEDIA_3}<img src="{$Appli.MEDIA_3}" alt="{$Appli.PLATFORM_3}" />{else}{$Appli.PLATFORM_3}{/if}</a></li>{/if}
                </ul>
            </div>
			
        {/foreach}
		</div>
    </section>
    {/if}

    {if $aPlatformWeb|@sizeof > 0}
    <section class="apps type-appli clscitroenmobiletablette">
		<div class="sep {$aData.ZONE_SKIN}"></div>
        {if $aData.ZONE_TITRE}<h2 class="subtitle" {if ($aData.PRIMARY_COLOR|count_characters)==7 } style="color:{$aData.PRIMARY_COLOR};" {/if}>{$aData.ZONE_TITRE}</h2>{/if}
		<div class="row of3">
        {foreach from=$aPlatformWeb item=Web}
		
        <div class="col pttl">
            <img src="{$Web.MEDIA}" width="51" height="51" alt="" />
            {if $Web.TITRE}<h3 class="parttitle" {if ($aData.SECOND_COLOR|count_characters)==7 } style="color:{$aData.SECOND_COLOR};" {/if}><span> {$Web.TITRE}</span></h3>{/if}
            {if $Web.TEXTE}<p>{$Web.TEXTE}</p>{/if}
            <ul class="actions">
                <li><a class="buttonLink" href="{urlParser url=$Web.WEB}" {if $Web.TARGET == 'BLANK'}target="_blank"{/if}>{$Web.Lien}</a></li>
            </ul>
        </div>
			
        {/foreach}
		</div>
    </section>
    {/if}
</div>
{/if}