{if $aParams.ZONE_WEB eq 1 and $display neq 0}
    <div class="sliceNew sliceStrongPointsDesk">
        <section id="{$aParams.ID_HTML}" class="clspointsforts">
            {if $aParams.ZONE_TITRE}
                <h2 class="subtitle" {if ($aData.PRIMARY_COLOR|count_characters)==7 } style="color:{$aData.PRIMARY_COLOR};" {/if}>
                    {$aParams.ZONE_TITRE|escape}
                </h2>
            {/if}
            {if $aParams.ZONE_TEXTE}
                <h3 class="parttitle" {if ($aData.SECOND_COLOR|count_characters)==7 } style="color:{$aData.SECOND_COLOR};" {/if}>
                    {$aParams.ZONE_TITRE2|escape}
                </h3>
            {/if}
            {if $aParams.ZONE_TEXTE}
                <div class="mgchapo" itemprop="description">
                    {$aParams.ZONE_TEXTE}
                </div>
            {else}
                <div class="mgchapo"></div>
            {/if}

            <div class="row gutter">
                <div class="columns column_50">
                    <figure>
                        <img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/16-9.png" data-original="{"{Pelican::$config.MEDIA_HTTP}{$aMediaVisuel.MEDIA_PATH}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_POINT_FORT}" width="580" height="326" alt="{$aMediaVisuel.MEDIA_ALT|escape}" />
                        <noscript><img src="{Pelican::$config.MEDIA_HTTP}{$aMediaVisuel.MEDIA_PATH}" width="580" height="326" alt="{$aMediaVisuel.MEDIA_ALT|escape}" /></noscript>
                    </figure>
                </div>
                <div class="columns column_50">
                    {if $aParams.ZONE_TITRE3}<h3 class="parttitle coltitle" {if ($aData.SECOND_COLOR|count_characters)==7 } style="color:{$aData.SECOND_COLOR};" {/if}>{$aParams.ZONE_TITRE3|escape}</h3>{/if}
                    {if $aPointsForts|@sizeof > 0}
                        <ul class="checks">
                            {foreach from=$aPointsForts item=pointfort name=pointsforts}
                                <li>{$pointfort.PAGE_ZONE_MULTI_TITRE|escape}</li>
                            {/foreach}
                        </ul>
                    {/if}
                </div>
            </div>
        </section>
    </div>
{/if}

{literal}
<style>
.sliceStrongPointsDesk .checks li:before {
    color: {/literal}{$aData.PRIMARY_COLOR};{literal}
}
</style>
{/literal}
