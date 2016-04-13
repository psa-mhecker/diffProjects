<div class="sliceNew sliceOngletDesk">
{if ($aParams.PRIMARY_COLOR|count_characters)==7 }
    <section id="{$aParams.ID_HTML}" class="form clsonglet">
        <div class="sep"></div>
        {if $aParams.ZONE_TITRE}<h2 class="subtitle" {if ($aParams.PRIMARY_COLOR|count_characters)==7 } style="color:{$aParams.PRIMARY_COLOR};font-size:40px!important;" {/if}>{$aParams.ZONE_TITRE|escape|upper}</h2>{/if}
        {if $aParams.ZONE_TITRE2}<h3 class="parttitle" {if ($aParams.SECOND_COLOR|count_characters)==7 } style="color:{$aParams.SECOND_COLOR};" {/if}>{$aParams.ZONE_TITRE2|escape}</h3>{/if}
        {if $aParams.ZONE_TEXTE}
            <div class="mgchapo"><strong>{$aParams.ZONE_TEXTE}</strong></div>
        {/if}
        {if isset($aOnglets) && $aOnglets|count > 0}
            <div class="tabbed carchoice">
                <div class="tabs"{if ($aParams.PRIMARY_COLOR|count_characters)==7 }
                     data-off="border-top:4px solid {$aParams.SECOND_COLOR}; background:#ffffff; color:#868689;"
                     data-hover="border-top:4px solid {$aParams.SECOND_COLOR}; background:#ffffff; color:{$aParams.SECOND_COLOR};"
                     data-on="border-top:4px solid {$aParams.SECOND_COLOR}; background:{$aParams.SECOND_COLOR}; color:#ffffff;" {/if} {gtmjs type='tabs'  action='Open::Tab|' data=$aParams datasup=['eventLabel'=>{$onglet.PAGE_ZONE_MULTI_LABEL}]}></div>
                {foreach from=$aOnglets item=onglet name=onglets}
                    <div class="tab onglet-{$aParams.AREA_ID}-{$aParams.ZONE_ORDER}-{$onglet.PAGE_ZONE_MULTI_ID}">
                        <h4 class="subtitle tabtitle"><span>{$onglet.PAGE_ZONE_MULTI_LABEL|escape}</span></h4>
                    </div>
                {/foreach}
            </div>
        {/if}

    </section>

    <div class="parent" id="trancheParent" style="display: none;"></div>
    {if $aParams.ZONE_LANGUETTE == 1}
        <section class="{$aParams.ZONE_SKIN} {if ($aParams.PRIMARY_COLOR|count_characters)==7 }showroom{/if} row of6 clslanguette{if ($aParams.PRIMARY_COLOR|count_characters)==7 }showroom{/if}">
            <div class="caption addmore folder" {if ($aParams.PRIMARY_COLOR|count_characters)==7 } data-off="border:4px solid {$aParams.SECOND_COLOR};  color:{$aParams.SECOND_COLOR};" data-hover="border:4px solid {$aParams.SECOND_COLOR};  color:{$aParams.SECOND_COLOR};"{/if} data-toggle="{if $aParams.ZONE_LANGUETTE_TEXTE_CLOSE}{$aParams.ZONE_LANGUETTE_TEXTE_CLOSE}{else}{'VOIR_MOINS'|t}{/if}"><a {if ($aParams.PRIMARY_COLOR|count_characters)==7 } class="col span2" {/if} href="#trancheParent" {gtm action="Push" data=$aParams datasup=['eventLabel' => 'LANGUETTE_ONGLET']}><span>{if $aParams.ZONE_LANGUETTE_TEXTE_OPEN}{$aParams.ZONE_LANGUETTE_TEXTE_OPEN}{else}{t("Voir plus")}{/if}</span></a></div>
        </section>
    {/if}

{else}


    <section id="{$aParams.ID_HTML}" class="form clsonglet">
        <div class="sep "></div>

        {if $aParams.ZONE_TITRE}<h2 class="subtitle">{$aParams.ZONE_TITRE|escape}</h2>{/if}

        {if $aParams.ZONE_TITRE2}<h3 class="parttitle">{$aParams.ZONE_TITRE2|escape}</h3>{/if}

        {if $aParams.ZONE_TEXTE}
            <div class="mgchapo"><strong>{$aParams.ZONE_TEXTE}</strong></div>
        {/if}

        {if isset($aOnglets) && $aOnglets|count > 0}
            <div class="tabbed carchoice">
                <div class="tabs" {gtmjs type='tabs'  action='Open::Tab|' data=$aParams datasup=['eventLabel'=>{$onglet.PAGE_ZONE_MULTI_LABEL}]}></div>
                {foreach from=$aOnglets item=onglet name=onglets}
                    <div class="tab onglet-{$aParams.AREA_ID}-{$aParams.ZONE_ORDER}-{$onglet.PAGE_ZONE_MULTI_ID}" >
                        <h4 class="subtitle tabtitle"><span>{$onglet.PAGE_ZONE_MULTI_LABEL|escape}</span></h4>
                    </div>
                {/foreach}
            </div>
        {/if}

    </section>

    <div class="parent" id="trancheParent" style="display: none;"></div>
    {if $aParams.ZONE_LANGUETTE == 1}
        <section class="{$aParams.ZONE_SKIN} {if ($aParams.PRIMARY_COLOR|count_characters)==7 }showroom{/if} row of6 clslanguette{if ($aParams.PRIMARY_COLOR|count_characters)==7 }showroom{/if}">
            <div class="caption addmore folder" data-toggle="{if $aParams.ZONE_LANGUETTE_TEXTE_CLOSE}{$aParams.ZONE_LANGUETTE_TEXTE_CLOSE}{else}{'VOIR_MOINS'|t}{/if}"><a {if ($aParams.PRIMARY_COLOR|count_characters)==7 } class="col span2" {/if} href="#trancheParent" {gtm action="Push" data=$aParams datasup=['eventLabel' => 'LANGUETTE_ONGLET']}><span>{if $aParams.ZONE_LANGUETTE_TEXTE_OPEN}{$aParams.ZONE_LANGUETTE_TEXTE_OPEN}{else}{t("Voir plus")}{/if}</span></a></div>
        </section>
    {/if}

{/if}
</div>