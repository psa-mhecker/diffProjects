<style>
    section.clspromotionvisuel div.caption ul.actions li a {ldelim}
        {if ($aData.SECOND_COLOR|count_characters)==7 }
            background-color: {$aData.SECOND_COLOR};
            border-color: {$aData.SECOND_COLOR};
            color: #ffffff;
        {else}
            background-color: #f0780a;
            border-color: #f0780a;
            color: #ffffff;
        {/if}
    {rdelim}
    section.clspromotionvisuel div.caption ul.actions li a:hover {ldelim}
        {if ($aData.SECOND_COLOR|count_characters)==7 }
            background-color: #ffffff;
            border-color: {$aData.SECOND_COLOR};
            color: {$aData.SECOND_COLOR};
        {else}
            background-color: #ffffff;
            border-color: #f0780a;
            color: #f0780a;
        {/if}
    {rdelim}
    section.clspromotionvisuel div.caption ul.actions li a:hover span {ldelim}
        {if ($aData.SECOND_COLOR|count_characters)==7 }
            color: {$aData.SECOND_COLOR};
        {else}
            color: #f0780a;
        {/if}
    {rdelim}
</style>

<section id="{$promotion.PAGE_ID}_{$promotion.PAGE_ZONE_MULTI_ID}" class="{if ($aData.PRIMARY_COLOR|count_characters)==7 }showroom{/if} clspromotionvisuel">

    {if $promotion.PAGE_ZONE_MULTI_LABEL}
        <h1 class="subtitle" {if ($aData.PRIMARY_COLOR|count_characters)==7 } style="color:{$aData.PRIMARY_COLOR};" {/if}>
            {$promotion.PAGE_ZONE_MULTI_LABEL}
        </h1>
    {/if}

    {if $promotion.PAGE_ZONE_MULTI_LABEL2}
        <h3 class="parttitle" {if ($aData.SECOND_COLOR|count_characters)==7 } style="color:{$aData.SECOND_COLOR};" {/if}>
            {$promotion.PAGE_ZONE_MULTI_LABEL2}
        </h3>
    {/if}

    {if $promotion.PAGE_ZONE_MULTI_TEXT}
        <div class="zonetexte">
            {$promotion.PAGE_ZONE_MULTI_TEXT}
        </div>
    {/if}

    <div class="caption visual">
   
        {if $promotion.YOUTUBE_ID}
            <figure>
               {$promotion.YOUTUBE_ID}
            </figure>
        {elseif $promotion.MEDIA_ID2}       
            <figure>
                <div class="framed">
                    <object type="application/x-shockwave-flash" data="{Pelican::$config.MEDIA_HTTP}{$promotion.MEDIA_PATH_FLASH}" width="1440" height="500">
                        <param name="movie" value="{Pelican::$config.MEDIA_HTTP}{$promotion.MEDIA_PATH_FLASH}" />
                        <param name="wmode" value="transparent" />

                        <figure>
                                <img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/235-100.png" data-original="{Pelican::$config.MEDIA_HTTP}{$promotion.MEDIA_PATH}"  width="1480" height="500" alt="" />
                                <noscript><img src="{Pelican::$config.MEDIA_HTTP}{$promotion.MEDIA_PATH}" width="1200" height="517" alt="{$promotion.MEDIA_ALT}" /></noscript>
                        </figure>

                        {if $promotion.PAGE_ZONE_MULTI_TEXT3}<p>{$promotion.PAGE_ZONE_MULTI_TEXT3}</p>{/if}

                    </object>
                </div>
            </figure>
        {elseif $promotion.MEDIA_PATH}
            <figure>
                <img class="lazy" width="1200" height="517" alt="{$promotion.MEDIA_ALT}" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/235-100.png" data-original="{Pelican::$config.MEDIA_HTTP}{$promotion.MEDIA_PATH}" class="" style="display: inline-block;">
                 <noscript><img src="{Pelican::$config.MEDIA_HTTP}{$promotion.MEDIA_PATH}" width="1200" height="517" alt="{$promotion.MEDIA_ALT}" /></noscript>
            </figure>
        {elseif $promotion.PAGE_ZONE_MULTI_TEXT3 != ""}
            {$promotion.PAGE_ZONE_MULTI_TEXT3}
        {/if}

        {if $promotion.CHILD|@count gt 1}
            <div class="over">
                <p>{'SEE_PROMO_DETAIL'|t}</p>
                    <div class="selectZone">
                    <ul class="select">
                        <li><a href="#0" class="on">{'ALL_VEHICLES'|t}</a></li>
                        {foreach from=$promotion.CHILD item=pChild}
                            {if $pChild.VEHICULE_ID}<li><a href="{urlParser url="{$pChild.PAGE_CLEAR_URL}?vid={$pChild.VEHICULE_ID}"}">{$pChild.VEHICULE_LABEL}</a></li>{/if}
                        {/foreach}
                    </ul>
                </div>
            </div>
        {/if}
    </div>

    <div class="caption" style="margin-bottom: 0px;">
        {if $promotion.CTA|@sizeof > 0}
            <ul class="actions">
                {foreach from=$promotion.CTA item=pCta name=promotion_cta}
                    <li><a class="buttonTransversalInvert {if $smarty.foreach.promotion_cta.iteration == 1}ctaFirst{/if}" href="{urlParser url=$pCta.PAGE_ZONE_MULTI_URL}" {if $actionContext != ''}{gtm action='Redirection' data=$aData datasup=['eventCategory'=>$actionContext , 'eventLabel' => {$pCta.PAGE_ZONE_MULTI_URL} ]}{/if} {if $pCta.PAGE_ZONE_MULTI_VALUE == "BLANK"}target="_blank"{/if}><span>{$pCta.PAGE_ZONE_MULTI_LABEL}</span></a></li>
                {/foreach}
            </ul>
        {/if}

        {if $promotion.PAGE_ZONE_MULTI_URL14}
            {if $promotion.PAGE_ZONE_MULTI_URL13 == "ROLL"}
                <small class="legal"><a href="#LegalTip" class="texttip">{$promotion.PAGE_ZONE_MULTI_URL14}</a></small>
                <div class="legal layertip" id="LegalTip">
                    {if $promotion.MEDIA_PATH6 != ""}<img class="lazy noscale" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/235-100.png" data-original="{Pelican::$config.MEDIA_HTTP}{$promotion.MEDIA_PATH6}" width="580" height="247" alt="{$promotion.MEDIA_TITLE6}" />{/if}
                   <div class="zonetexte">{$promotion.PAGE_ZONE_MULTI_TEXT4}</div>
                </div>
            {elseif $promotion.PAGE_ZONE_MULTI_URL13 == "POP_IN" && $promotion.PAGE_ZONE_MULTI_URL16 != ""}
                <small class="legal"><a href="{urlParser url={$promotion.PAGE_ZONE_MULTI_URL16|cat:"?popin=1"}}" class="popinfos fancybox.ajax">{$promotion.PAGE_ZONE_MULTI_URL14}</a></small>
            {elseif $promotion.PAGE_ZONE_MULTI_URL13 == "TEXT"}
                <figure>
                    {if $promotion.MEDIA_PATH6 != ""}<img class="noscale" src="{Pelican::$config.MEDIA_HTTP}{$promotion.MEDIA_PATH6}" width="580" height="247" alt="{$promotion.MEDIA_TITLE6}" />{/if}
                </figure>
                <small class="legal">{$promotion.PAGE_ZONE_MULTI_URL14}<br><div class="zonetexte">{$promotion.PAGE_ZONE_MULTI_TEXT4}</div></small>
            {/if}
        {/if}
    </div>
</section>