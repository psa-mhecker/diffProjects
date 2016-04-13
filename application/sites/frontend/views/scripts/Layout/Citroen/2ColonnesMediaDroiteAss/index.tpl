{if ($aData.ZONE_WEB ==1)}
{literal}
<style>
    {/literal}
    {if ($aData.SECOND_COLOR|count_characters)==7}
    {literal}
    .sliceNew  .actions .buttonTransversalInvert, .sliceNew  .buttonTransversalInvert{
    {/literal}{if ($aData.SECOND_COLOR|count_characters)==7 }{literal}
        background-color:{/literal}{$aData.SECOND_COLOR};{literal}
        border-color:{/literal}{$aData.SECOND_COLOR};{literal}
        color:#ffffff;
    {/literal}{/if}{literal}
    }
.sneezies .vjs-default-skin .vjs-big-play-button{
    color:{/literal}{$aData.SECOND_COLOR}!important; {literal}
}
    .sliceNew  .actions .buttonTransversalInvert:hover, .sliceNew  .actions .buttonTransversalInvert:active, .sliceNew  .buttonTransversalInvert:hover, .sliceNew  .buttonTransversalInvert:active:hover{
    {/literal}{if ($aData.SECOND_COLOR|count_characters)==7 }{literal}
        background-color:#ffffff;
        border-color:{/literal}{$aData.SECOND_COLOR};{literal}
        color:{/literal}{$aData.SECOND_COLOR}; {literal}
    {/literal}{/if}{literal}
    }
    div.slice2ColonnesMixteTelephoneDesk .icon-play{
        color:{/literal}{$aData.SECOND_COLOR} {literal}
    }
    {/literal}
    {/if}
    {literal}
</style>
{/literal}
<div class="sliceNew slice2ColonnesMixteTelephoneDesk">
    <section class="cls2colonnesmediadroiteass" id="{$aData.ID_HTML}">
        {if $aData.ZONE_TITRE3 || $aData.ZONE_TITRE4}
            {if $aData.ZONE_TITRE3}<h2 class="subtitle" {if ($aData.PRIMARY_COLOR|count_characters)==7 } style="color:{$aData.PRIMARY_COLOR};" {/if}>{$aData.ZONE_TITRE3|escape}</h2>{/if}
            {if $aData.ZONE_TITRE4}<h3 class="parttitle" {if ($aData.SECOND_COLOR|count_characters)==7 } style="color:{$aData.SECOND_COLOR};" {/if}>{$aData.ZONE_TITRE4|escape}</h3>{/if}
        {/if}

        <div class="row gutter">
            <div class="columns column_50">
                <p class="mgchapo">
                    {$aData.ZONE_TEXTE|escape}
                </p>
                <div class="zonetexte">
                    {$aData.ZONE_TEXTE3}
                </div>
                {if $aData.ZONE_TITRE12 || $aData.ZONE_TITRE13 || $aData.ZONE_TITRE14 }
                <div class="caller" {if ($aData.SECOND_COLOR|count_characters)==7 } style="background-color:{$aData.SECOND_COLOR};" {/if}>
                    {$aData.ZONE_TITRE12|escape} <strong>{$aData.ZONE_TITRE13|escape}</strong> <small>{$aData.ZONE_TITRE14|escape}</small>
                </div>
                {/if}
            </div>
            <div class="columns column_50">
                {if $MEDIA_VIDEO}
                    <figure class="video shareable mgfigure">
                            <a class="popit {if $MEDIA_VIDEO}video{else}photo{/if}" {if $MEDIA_VIDEO}data-video="{$MEDIA_VIDEO}"{/if} data-sneezy href="{if $MEDIA_VIDEO}{urlParser url=$MEDIA_VIDEO}{else}{urlParser url={Pelican::$config.MEDIA_HTTP|cat:$MEDIA_PATH}}{/if}" target="_blank"
                                    {if $MEDIA_VIDEO}
                                        {gtm action='Display::Video' data=$aData datasup=['eventLabel'=>{$MEDIA_TITLE}]}
                                    {else}
                                        {gtm action='Zoom' data=$aData datasup=['eventLabel'=>{$MEDIA_TITLE}]}
                                    {/if}
                            >

                                <img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/16-9.png" data-original="{"{Pelican::$config.MEDIA_HTTP}{$MEDIA_PATH}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_2_COLONNES_MIXTE}" width="580" height="323" alt="{$MEDIA_ALT}" style="display: inline-block;">
                                <noscript><img src="{"{Pelican::$config.MEDIA_HTTP}{$MEDIA_PATH}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_2_COLONNES_MIXTE}" width="580" height="323" alt="{$MEDIA_ALT}" /></noscript>
                            </a>
                            <i class="icon-play"></i>

                    </figure>
                {else}
                    <img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/16-9.png" data-original="{"{Pelican::$config.MEDIA_HTTP}{$MEDIA_PATH}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_2_COLONNES_MIXTE}" width="580" height="323" alt="{$MEDIA_ALT}" style="display: inline-block;">
                    <noscript><img src="{"{Pelican::$config.MEDIA_HTTP}{$MEDIA_PATH}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_2_COLONNES_MIXTE}" width="580" height="323" alt="{$MEDIA_ALT}" /></noscript>
                {/if}
            </div>
        </div>


        {if $aData.ZONE_TITRE6 && ($aData.ZONE_TITRE5 == "ROLL" || ($aData.ZONE_TITRE5 == "POP_IN" && $urlPopInMention != ""))}
            <div class="caption">
                {if $aData.ZONE_TITRE5 == "ROLL"}
                    <small class="legal"><a href="#LegalTip_{$aData.ZONE_ORDER}" class="texttip">{$aData.ZONE_TITRE6|escape}</a></small>
                    <div class="legal layertip" id="LegalTip_{$aData.ZONE_ORDER}">
                        {if $MEDIA_PATH4 != ""}<img class="lazy noscale" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/235-100.png" data-original="{"{Pelican::$config.MEDIA_HTTP}{$MEDIA_PATH4}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_MENTION}" width="580" height="247" alt="{$MEDIA_ALT4}" />{/if}
                        {if $aData.ZONE_TEXTE4} <div class="zonetexte">{$aData.ZONE_TEXTE4}</div> {/if}
                    </div>
                {elseif $aData.ZONE_TITRE5 == "POP_IN" && $urlPopInMention != ""}
                    <small class="legal">
                        <a href="{urlParser url={$urlPopInMention|cat:'?popin=1'}}" class="popinfos fancybox.ajax" {gtmjs type='toggle' action='Display::ToolTip|' eventGTM='over'  data=$aData datasup=['eventLabel' => $aData.ZONE_TITRE6]}>
                            {$aData.ZONE_TITRE6|escape}
                        </a>
                    </small>
                {/if}
            </div>
        {/if}
        {if $aData.ZONE_TITRE5 == "TEXT" && $aData.ZONE_TEXTE4 neq ""}
            <div class="caption">
                <figure>
                    {if $MEDIA_PATH4 != ""}<img class="noscale" src="{"{Pelican::$config.MEDIA_HTTP}{$MEDIA_PATH4}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_MENTION}" width="580" height="247" alt="{$MEDIA_ALT4}" />{/if}
                </figure>
                <small class="legal">{$aData.ZONE_TITRE6|escape}<br>{if $aData.ZONE_TEXTE4} <div class="zonetexte">{$aData.ZONE_TEXTE4}</div> {/if}</small>
            </div>
        {/if}

        {if $aMedias|@sizeof > 0}
            <div class="thumbs">
                {foreach $aMedias as $aMedia  key=key}
                {if $aMedia.VIDEO}

                {if $aMedia.VIDEO.YOUTUBE_ID}

                <a class="popit" {if $aMedia.VIDEO.OTHER_MEDIA_PATH}data-video="{$aMedia.VIDEO.OTHER_MEDIA_PATH}"{/if} data-sneezy href="{if $aMedia.VIDEO.YOUTUBE_URL}{urlParser url=$aMedia.VIDEO.YOUTUBE_URL}{/if}" target="_blank" {gtm action='Display::Video' data=$aData datasup=['eventLabel'=>{$aMedia.VIDEO.MEDIA_ALT}]}>
                    {else}
                    <a class="popit" {if $aMedia.VIDEO.OTHER_MEDIA_PATH}data-video="{$aMedia.VIDEO.OTHER_MEDIA_PATH}"{/if} data-sneezy href="{if $aMedia.VIDEO.MEDIA_PATH}{urlParser url={Pelican::$config.MEDIA_HTTP|cat:$aMedia.VIDEO.MEDIA_PATH}}{/if}" target="_blank" {gtm action='Display::Video' data=$aData datasup=['eventLabel'=>{$aMedia.VIDEO.MEDIA_ALT}]}>
                        {/if}

                        <!--shadow video-->
                        <figure class="shadow video">
                            <i class="icon-play" {if ($aData.SECOND_COLOR|count_characters)==7 } style="color:{$aData.SECOND_COLOR};" {/if}></i>
                            <img src="{if $aMedia.VIDEO.MEDIA_PATH}{"{Pelican::$config.MEDIA_HTTP}{$aMedia.VIDEO.MEDIA_PATH2}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_MENTION}{/if}" width="145" height="81" alt="{$aMedia.VIDEO.MEDIA_ALT2}">
                        </figure>
                        {if $aMedia.VIDEO.MEDIA_TITLE}<span>{$aMedia.VIDEO.MEDIA_TITLE}</span>{/if}
                    </a>
                    {/if}

                    {if $aMedia.IMAGE}
                        {section name=push loop=$aMedia.IMAGE}
                            {if $smarty.section.push.first}
                                <a class="popit" data-sneezy="group2CM{$key}" href="{urlParser url={Pelican::$config.MEDIA_HTTP|cat:$aMedia.IMAGE[push].MEDIA_PATH}}" target="_blank" {gtm  action='Zoom' data=$aData datasup=['eventLabel'=>{$aMedia.MEDIA_TITLE}]}>
                                    <figure class="shadow">
                                        {IF $VIGN_GALLERY}
                                        {assign var="Vignette_Gal" value=$VIGN_GALLERY}
                                        {ELSE}
                                        {assign var="Vignette_Gal" value=$aMedia.IMAGE[push].MEDIA_PATH}
                                        {/IF}
                                        <img src="{"{Pelican::$config.MEDIA_HTTP}{$Vignette_Gal}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_PUSH}" width="145" height="81" alt="{$aMedia.IMAGE[push].MEDIA_ALT}">

                                    </figure>
                                    {if $aMedia.MEDIA_TITLE}<span>{$aMedia.MEDIA_TITLE}</span>{/if}
                                </a>
                            {else}
                                <a class="popit grouped" data-sneezy="group2CM{$key}" href="{urlParser url={Pelican::$config.MEDIA_HTTP|cat:$aMedia.IMAGE[push].MEDIA_PATH}}" target="_blank" {gtm action='Zoom' data=$aData datasup=['eventLabel'=>{$aMedia.IMAGE[push].MEDIA_ALT}]}>
                                    <figure class="shadow">
                                        <img src="{"{Pelican::$config.MEDIA_HTTP}{$aMedia.IMAGE[push].MEDIA_PATH}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_PUSH}" width="145" height="81" alt="{$aMedia.IMAGE[push].MEDIA_ALT}" />
                                    </figure>
                                </a>
                            {/if}
                        {/section}
                    {/if}
                    {/foreach}
            </div>
        {/if}

        {if $aCta|@sizeof > 0}
            <ul class="actions">
                {section name=cta loop=$aCta}
                    {if $aCta[cta].OUTIL}
                        {$aCta[cta].OUTIL}
                    {else}
                        <li data-services=""><a class="buttonTransversalInvert" data-sync="cta{$aData.ORDER}" {gtm action="Push" data=$aData datasup=['eventLabel' =>  $aCta[cta].PAGE_ZONE_MULTI_LABEL]} href="{urlParser url=$aCta[cta].PAGE_ZONE_MULTI_URL}" target="_{$aCta[cta].PAGE_ZONE_MULTI_VALUE}">{$aCta[cta].PAGE_ZONE_MULTI_LABEL}</a></li>
                    {/if}
                {/section}
            </ul>
        {/if}
    </section>
    <div class="parent" id="trancheParent" style="display: none;"></div>
</div>
{/if}
