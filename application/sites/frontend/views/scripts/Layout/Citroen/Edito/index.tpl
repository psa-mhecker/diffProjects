{if $aData.ZONE_WEB == 1}
    <div class="sliceNew sliceEditoDesk">
        <section id="{$aData.ID_HTML}" class="clsedito">
		
            {if $aData.ZONE_TITRE}<h2 class="subtitle" {if ($aData.PRIMARY_COLOR|count_characters)==7 } style="color:{$aData.PRIMARY_COLOR};" {/if}>{$aData.ZONE_TITRE|escape|upper}</h2>{/if}
            {if $aData.ZONE_TITRE2}<h3 class="parttitle" {if ($aData.SECOND_COLOR|count_characters)==7 } style="color:{$aData.SECOND_COLOR};" {/if}>{$aData.ZONE_TITRE2|escape}</h3>{/if}

            {if $aData.ZONE_TEXTE}
                <div class="zonetexte">
                    <p>{$aData.ZONE_TEXTE}</p>
                </div>
            {/if}
        </section>
    </div>
{/if}