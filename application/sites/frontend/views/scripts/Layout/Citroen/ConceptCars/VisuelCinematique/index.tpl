<div class="sliceNew sliceVisuelCinemascopeConceptCarDesk">
    <div id="{$aParams.ID_HTML}">
        <figure>
            <img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/banner.png" data-original="{"{Pelican::$config.MEDIA_HTTP}{$aParams.MEDIA_PATH}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_CONCEPT_CAR}" width="1440" height="545" alt="{$aParams.MEDIA_ALT|escape}" />
            <noscript><img src="{"{Pelican::$config.MEDIA_HTTP}{$aParams.MEDIA_PATH}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_CONCEPT_CAR}" width="1440" height="545" alt="{$aParams.MEDIA_ALT|escape}" /></noscript>
        </figure>
    </div>
    <h1 class="subtitle" {if ($aParams.PRIMARY_COLOR|count_characters)==7 } style="color:{$aParams.PRIMARY_COLOR};" {/if}>{$aParams.PAGE_TITLE}</h1>
</div>