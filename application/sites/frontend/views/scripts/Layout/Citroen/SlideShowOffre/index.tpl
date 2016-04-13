{if $aData.ZONE_WEB ==1}
<div class="sliceNew sliceSlideShowOfferDesktop">
    <div id="slideshow-offre_1972" class="slider offers clsslideshowoffre" data-speed="1750" data-gtm-js="{literal}{&quot;type&quot;:&quot;slider&quot;,&quot;0&quot;:&quot;eventGTM|SlideshowOffer|click|||&quot;}{/literal}">
        <div class="row of3">
            {foreach from=$aMulti item=Multi}
                {if $Multi.MEDIA_ID}
                    {if $Multi.PAGE_ZONE_MULTI_ATTRIBUT == 1}
                        {assign var="target" value="_self"}
                    {elseif $Multi.PAGE_ZONE_MULTI_ATTRIBUT == 2}
                        {assign var="target" value="_blank"}
                    {/if}
                    {if $Multi.PAGE_ZONE_MULTI_TITRE neq ''}
                        {assign var="titregtm" value="{$Multi.PAGE_ZONE_MULTI_TITRE}"}
                    {else}
                        {assign var="titregtm" value="{$Multi.MEDIA_ALT}"}
                    {/if}
                    <a href="{urlParser url=$Multi.PAGE_ZONE_MULTI_URL}" target="{$target}" class="col columns" {gtm action="Click" data=$aData datasup=[ 'eventLabel' => $titregtm, 'value' => $Multi.PAGE_ZONE_MULTI_ORDER] idMulti=$Multi._sync}>
                        {if true == false}<span class="cta">{$Multi.PAGE_ZONE_MULTI_TITRE|escape}</span>{/if}
                        <strong>
                            <figure>
                                <img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/lazy-145-111.png" data-original="{$Multi.MEDIA_ID}" width="373" height="373" alt="{$Multi.MEDIA_ALT}" />
                                <noscript><img src="{$Multi.MEDIA_ID}" width="373" height="373" alt="{$Multi.MEDIA_ALT}" /></noscript>
                            </figure>
                        </strong>
                        {if $Multi.PAGE_ZONE_MULTI_TITRE}
                            <span>{$Multi.PAGE_ZONE_MULTI_TITRE|escape}</span>
                        {/if}
                    </a>
                {/if}
            {/foreach}
        </div>
    </div>
</div>
{/if}
