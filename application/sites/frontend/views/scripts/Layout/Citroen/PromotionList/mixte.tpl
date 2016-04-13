<style>
    section.clspromotionmixte div.col ul.actions li a {ldelim}
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
    section.clspromotionmixte div.col ul.actions li a:hover {ldelim}
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
    section.clspromotionmixte div.col ul.actions li a:hover span {ldelim}
        {if ($aData.SECOND_COLOR|count_characters)==7 }
            color: {$aData.SECOND_COLOR};
        {else}
            color: #f0780a;
        {/if}
    {rdelim}
</style>

{if $promotion.CHILD|@count gt 1}
    <section id="selection-de-vehicules_2100" class="clsvehicleselector row of2" style="padding-top: 0px;">
        <div class="new col" style="margin-bottom: 0px;">
            <p>{'SEE_PROMO_DETAIL'|t}</p>
            <div class="caption row of2">
                <div class="col selectZone">
                    <ul class="select">
                        <li><a class="on" href="#0">{'CHOISISSEZ_SELECTION_DETAIL'|t}</a></li>
                            {foreach from=$promotion.CHILD item=pChild}
                                {if $pChild.VEHICULE_ID}<li><a href="{urlParser url=$pChild.PAGE_CLEAR_URL}#sticky" {gtm action='DropdownList' data=$aData datasup=['eventLabel' => {$pChild.VEHICULE_LABEL} ]} >{$pChild.VEHICULE_LABEL}</a></li>{/if}
                            {/foreach}
                    </ul>
                </div>
            </div>
        </div>
    </section>
{/if}

<section id ="{$promotion.PAGE_ID}_{$promotion.PAGE_ZONE_MULTI_ID}" class="row of2 clspromotionmixte">

    {if $promotion.PAGE_ZONE_MULTI_LABEL}
        {$sSharer}
        <h1 id="{$aData.ID_HTML}" class="subtitle" {if ($aData.PRIMARY_COLOR|count_characters)==7 } style="color:{$aData.PRIMARY_COLOR};" {/if}>
            {$promotion.PAGE_ZONE_MULTI_LABEL|upper}
        </h1>
    {/if}

    <div class="new col" style="margin-top: 20px;">
        {if $promotion.PAGE_ZONE_MULTI_LABEL2}
            <h3 class="parttitle" {if ($aData.SECOND_COLOR|count_characters)==7 } style="color:{$aData.SECOND_COLOR};" {/if}>
                {$promotion.PAGE_ZONE_MULTI_LABEL2|escape}
            </h3>
        {/if}

        {if $promotion.PAGE_ZONE_MULTI_TEXT}
            <div class="zonetexte">
                {$promotion.PAGE_ZONE_MULTI_TEXT}
            </div>
        {/if}

        {if $promotion.CTA|@sizeof > 0}
            <ul class="actions">
                {foreach from=$promotion.CTA item=pCta name=promotion_cta}
                    <li>
                        <a href="{urlParser url=$pCta.PAGE_ZONE_MULTI_URL}#sticky" {gtm action='Push' data=$aData datasup=['eventLabel' => {$pCta.PAGE_ZONE_MULTI_LABEL|escape} ]} {if $pCta.PAGE_ZONE_MULTI_VALUE == "BLANK"}target="_blank"{/if} class="{if $smarty.foreach.promotion_cta.iteration == 1}ctaFirst buttonTransversalInvert{else}buttonLead{/if}">
                            <span>{$pCta.PAGE_ZONE_MULTI_LABEL|escape}</span>
                        </a>
                    </li>
                {/foreach}
            </ul>
        {/if}

        {if $promotion.PAGE_ZONE_MULTI_URL14 && ($promotion.PAGE_ZONE_MULTI_URL13 == "ROLL" || ($promotion.PAGE_ZONE_MULTI_URL13 == "POP_IN" && $promotion.PAGE_ZONE_MULTI_URL16 != ""))}
            {if $promotion.PAGE_ZONE_MULTI_URL13 == "ROLL"}
                <small class="legal"><a href="#LegalTip" class="texttip">{$promotion.PAGE_ZONE_MULTI_URL14|escape}</a></small>
                <div class="legal layertip" id="LegalTip">
                    {if $promotion.MEDIA_PATH6 != ""}<img class="lazy noscale" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/235-100.png" data-original="{Pelican::$config.MEDIA_HTTP}{$promotion.MEDIA_PATH6}" width="580" height="247" alt="{$promotion.MEDIA_TITLE6}" />{/if}
                    <div class="zonetexte">{$promotion.PAGE_ZONE_MULTI_TEXT4}</div>
                </div>
            {elseif $promotion.PAGE_ZONE_MULTI_URL13 == "POP_IN" && $promotion.PAGE_ZONE_MULTI_URL16 != ""}
                <small class="legal"><a href="{urlParser url={$promotion.PAGE_ZONE_MULTI_URL16|cat:"?popin=1"}}" class="popinfos fancybox.ajax">{$promotion.PAGE_ZONE_MULTI_URL14|escape}</a></small>
            {/if}
        {/if}
        {if $promotion.PAGE_ZONE_MULTI_URL13 == "TEXT" && $promotion.PAGE_ZONE_MULTI_TEXT4 neq ""}
            <figure>
                {if $promotion.MEDIA_PATH6 != ""}<img class="noscale" src="{Pelican::$config.MEDIA_HTTP}{$promotion.MEDIA_PATH6}" width="580" height="247" alt="{$promotion.MEDIA_TITLE6}" />{/if}
            </figure>
            <small class="legal">
            <div class="zonetexte legal">{$promotion.PAGE_ZONE_MULTI_TEXT4}</div>
            </small>
        {/if}
    </div>

    <div class="col" style="margin-top: 20px;">

	    {if $promotion.YOUTUBE_ID}
            {if $promotion.MEDIA_TYPE_ID == 'video'}
                <figure class="col span3 shadow video shareable nomgfigure">
                    <a class="popit" data-video="{$promotion.YOUTUBE_ID}" href="{urlParser url=$promotion.YOUTUBE_ID}" data-sneezy target="_blank" {gtm   action='Display::Video' data=$aData datasup=['eventLabel'=>{$promotion.MEDIA_TITLE}]}>
                        <img src="{"{Pelican::$config.MEDIA_HTTP}{$promotion.MEDIA_PATH}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_2_COLONNES_MIXTE}" width="580" height="323">
                    </a>
                </figure>
            {elseif $promotion.MEDIA_TYPE_ID == 'youtube'}
                <figure class="col"><div class="framed">{$promotion.YOUTUBE_ID}</div></figure>
            {/if}
        {elseif $promotion.MEDIA_ID2}
            <figure class="col shadow">
                <div class="framed">
                    <object type="application/x-shockwave-flash" data="{Pelican::$config.MEDIA_HTTP}{$promotion.MEDIA_PATH_FLASH}" width="1440" height="500">
                        <param name="movie" value="{Pelican::$config.MEDIA_HTTP}{$promotion.MEDIA_PATH_FLASH}" />
                        <param name="wmode" value="transparent" />
                        <figure>
                            <img class="lazy" data-original="{$promotion.MEDIA_PATH}" width="1480" height="500" alt="" />
                        </figure>
                        {if $promotion.PAGE_ZONE_MULTI_TEXT3}<p>{$promotion.PAGE_ZONE_MULTI_TEXT3}</p>{/if}
                    </object>
                </div>
            </figure>
        {elseif $promotion.MEDIA_PATH}
            <figure class="col shadow">
                <img class="lazy" width="580" height="323" alt="{$promotion.MEDIA_ALT}" data-original="{Pelican::$config.MEDIA_HTTP}{$promotion.MEDIA_PATH}" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/235-100.png" class="lazy" style="display: inline-block;">
                <noscript><img src="{Pelican::$config.MEDIA_HTTP}{$promotion.MEDIA_PATH}" width="580" height="323" alt="{$promotion.MEDIA_ALT}" /></noscript>
            </figure>
        {elseif $promotion.PAGE_ZONE_MULTI_TEXT3 != ""}
            {$promotion.PAGE_ZONE_MULTI_TEXT3}
        {/if}
    </div>
</section>

{literal}
<style>
.col .actions li{
	padding:6px 10px 0 0 !important;
}
</style>
{/literal}
