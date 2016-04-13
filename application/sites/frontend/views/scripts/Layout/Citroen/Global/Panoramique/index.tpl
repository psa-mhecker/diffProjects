{if $MEDIA_PATH != "" || $MEDIA_TITLE != ""}
    {if $bTplPreHome}<div class="body">{/if}
    <div class="sliceNew slicePanoramiqueDesk">
        <!-- texts: modifier le 03.10.2014 -->
        <div  id="{$aData.ID_HTML}"  class="banner full invert clsgrandvisuel">
            <div class="texts">
                <h1 class="title">{$title}</h1>
            </div>
            <figure>
                <img class="lazy load" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/235-100.png" data-original="{Pelican::$config.MEDIA_HTTP}{$MEDIA_PATH}" width="1440" height="300" alt="{$MEDIA_TITLE}" style="display: inline-block;">
                <noscript><img src="{Pelican::$config.MEDIA_HTTP}{$MEDIA_PATH}" width="1440" height="300" alt="{$MEDIA_TITLE}" /></noscript>
            </figure>
        </div>
    </div>
    <!-- /texts -->
{/if}