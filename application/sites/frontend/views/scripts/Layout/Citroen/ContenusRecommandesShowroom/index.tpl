{if $aData.ZONE_WEB == 1 && $aRecommandes|@sizeof > 0}
    <div class="sliceRecommandedContentDesk">
        <div id="DDM_contenus_recommandes">
            {literal}
                <style>
            {/literal}
            {if ($aDataColors.PRIMARY_COLOR|count_characters)==7}
            {literal}

            .sliceRecommandedContentDesk .showroom.clscontenurecoshowroom .bx-prev , .sliceRecommandedContentDesk .showroom.clscontenurecoshowroom .bx-next{
                color: {/literal}{$aDataColors.SECOND_COLOR}{literal}!important;
                border: 2px solid {/literal}{$aDataColors.SECOND_COLOR}{literal}!important;
            }
             .sliceRecommandedContentDesk .clscontenurecoshowroom .bx-next:hover, .sliceRecommandedContentDesk .clscontenurecoshowroom .bx-prev:hover{
                 color: #fff!important;
                 background-color: {/literal}{$aDataColors.SECOND_COLOR}{literal}!important;
             }
            .sliceRecommandedContentDesk .bx-wrapper .bx-controls .bx-pager-item .bx-pager-link.active {
                background: {/literal}{$aDataColors.SECOND_COLOR}{literal}!important;
            }
            {/literal}
            {/if}
            {literal}
                </style>
            {/literal}
            <div id="{$aData.ID_HTML}" class="sliceNew sliceRecommandedContentDesk">
                <div id="DDM_contenus_recommandes">
                    <aside id="contenus-recommandes-showroom_2169" class=" clscontenurecoshowroom showroom">
                        <div class="inner">
                            <h3 class="parttitle" {if ($aDataColors.SECOND_COLOR|count_characters)==7 } style="color:{$aDataColors.SECOND_COLOR};" {/if}>
                                {$aData.ZONE_TITRE|escape}
                            </h3>
                            <div class="row of4">
                                <div class="slider loop caption" >
                                    <div class="row of4">
                                        {$cpt = 1}
                                        {foreach from=$aRecommandes item=crs}
                                            <div class="col" id="contenus-recommandes_bloc_{$cpt++}">
                                                <a href="{urlParser url=$crs.CONTENU_RECOMMANDE_URL}" {gtm action='RecommandedContent'  data=$aData datasup=['eventLabel'=>{$crs.CONTENU_RECOMMANDE_TITRE}]  } {if $crs.CONTENU_RECOMMANDE_MODE_OUVERTURE=='2'} target="_blank"{/if}>
                                                    <figure>
                                                        <img class="lazy" style="width:275px;height:275px;" src="{Pelican::$config.MEDIA_HTTP}{$crs.MEDIA_PATH}" data-original="{Pelican::$config.MEDIA_HTTP}{$crs.MEDIA_PATH}" width="500" height="500" alt="{$cr.MEDIA_ALT|escape}" />
                                                        <noscript><img src="{Pelican::$config.MEDIA_HTTP}{$cr.MEDIA_PATH}" width="500" height="500" alt="{$cr.MEDIA_ALT|escape}" /></noscript>
                                                    </figure>
                                                    <span {if ($aDataColors.PRIMARY_COLOR|count_characters)==7 } style="color:{$aDataColors.PRIMARY_COLOR};" {/if}>{$crs.CONTENU_RECOMMANDE_TITRE}</span>
                                                </a>
                                            </div>
                                        {/foreach}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </div>
{/if}