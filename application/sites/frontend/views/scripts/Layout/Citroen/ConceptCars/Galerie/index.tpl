<style>
{if ($aParams.PRIMARY_COLOR|count_characters)==7 }
    div.sliceConceptCarsGalerieDesk .subtitle{literal}{{/literal}
        color:{$aParams.PRIMARY_COLOR};
    {literal}}{/literal}
    div.sliceConceptCarsGalerieDesk .actions .buttonTransversalInvert{literal}{{/literal}
        background-color:{$aParams.SECOND_COLOR};
        border-color:{$aParams.SECOND_COLOR};
        color:#ffffff;
    {literal}}{/literal}
    div.sliceConceptCarsGalerieDesk .actions .buttonTransversalInvert:hover{literal}{{/literal}
        background-color:#ffffff;
        border-color:{$aParams.SECOND_COLOR};
        color:{$aParams.SECOND_COLOR};
    {literal}}{/literal}
{/if}
</style>
<div class="sliceNew sliceConceptCarsGalerieDesk">
    <section id="{$aParams.ID_HTML}" class="{$aParams.ZONE_SKIN} row of4 collection separated clsconceptcarsgalerie">

        {assign var='itemConceptCarIndex' value=0}

        {foreach from=$galerieConceptCars item=galerie name=conceptItem}

            {  $itemConceptCarIndex =  $smarty.foreach.conceptItem.iteration }
            {if $smarty.foreach.conceptItem.iteration is div by 2}
                {$itemConceptCarIndex = $itemConceptCarIndex-1}
            {/if}

            <div class="col span2 zoner" data-sync="forcesync_{$itemConceptCarIndex}">
                <figure>
                    <img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/banner-tall.png" data-original="{"{Pelican::$config.MEDIA_HTTP}{$galerie.MEDIA_PATH}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_CONCEPT_CAR_GALERIE}" width="580" height="247" alt="{$galerie.MEDIA_ALT|escape}" />
                    <noscript><img src="{"{Pelican::$config.MEDIA_HTTP}{$galerie.MEDIA_PATH}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_CONCEPT_CAR_GALERIE}" width="580" height="247" alt="{$galerie.MEDIA_ALT|escape}" /></noscript>
                </figure>

                {if $galerie.PAGE_TITLE}<h2 class="subtitle">{$galerie.PAGE_TITLE}</h2>{/if}
                <ul class="actions">
                    <li class="grey"><a class="buttonTransversalInvert" href="{urlParser url=$galerie.PAGE_CLEAR_URL}">{'EN_SAVOIR_PLUS'|t}</a></li>
                </ul>
            </div>
        {/foreach}
    </section>
</div>