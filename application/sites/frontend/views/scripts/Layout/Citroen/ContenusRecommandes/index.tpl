{if $aParams.ZONE_WEB && $contenusRecommandes|@sizeof > 0}
    <div class="sliceNew sliceRecommandedContentDesk">
        <aside id="{$aParams.ID_HTML}" class="clscontenurecommandes" >
            <div class="inner">

            {if $aParams.ZONE_TITRE}<div class="parttitle" {if ($aDataColors.PRIMARY_COLOR|count_characters)==7 } style="color:{$aDataColors.PRIMARY_COLOR}" {/if}>{$aParams.ZONE_TITRE|escape}</div>{/if}

                <div class="row gutter">
                    <div class="columns column_75">
                        <div class="slider loop" >
                            <div class="row of3">
                                {$turns = 0}
                                {foreach from=$contenusRecommandes item=cr name=crLoop}
                                    {$turns = $turns+1}
                                <div class="col" id="contenus-recommandes_bloc_{$turns}">
                                    <a href="{urlParser url=$cr.CONTENU_RECOMMANDE_URL}"{if $cr.CONTENU_RECOMMANDE_MODE_OUVERTURE=='2'} target="_blank"{/if}
                                        {gtm action='RecommandedContent'  data=$aParams datasup=['eventLabel'=>{$cr.CONTENU_RECOMMANDE_TITRE}] }
                                        >
                                        <figure>
                                            <span class="inner">
                                                <img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/1-1.png" data-original="{Pelican::$config.MEDIA_HTTP}{$cr.MEDIA_PATH}" width="500" height="500" alt="{$cr.MEDIA_ALT|escape}" />
                                                <noscript><img src="{Pelican::$config.MEDIA_HTTP}{$cr.MEDIA_PATH}" width="500" height="500" alt="{$cr.MEDIA_ALT|escape}" /></noscript>
                                            </span>
                                        </figure>
                                        <span class="legend" {if ($aDataColors.SECOND_COLOR|count_characters)==7 } style="color:{$aDataColors.SECOND_COLOR}" {/if}>{$cr.CONTENU_RECOMMANDE_TITRE}</span>
                                    </a>
                                </div>
                                {/foreach}
                            </div>
                        </div>
                    </div>

                    {if $generateurLeads}
                    <div class="columns column_25">
                        <ul class="actions">
                            {foreach from=$generateurLeads item=outil}
                                {$outil}

                            {/foreach}
                        </ul>
                    </div>
                    {/if}
                </div>
            </div>
        </aside>
    </div>
{/if}