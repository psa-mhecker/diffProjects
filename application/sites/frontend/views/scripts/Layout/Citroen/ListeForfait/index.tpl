{if $aListeForfait}<div class="sliceNew sliceListeForfaitDesk">
    <section id="{$aParams.ID_HTML}"  class=" row clslisteforfait">
        <div class="sep "></div>

        <div class="elastic">
            <div class="row gutter">
                <div class="columns column_50">
                    {if $aListeForfait.TITRE}<h2 class="subtitle" style='{if ($aParams.PRIMARY_COLOR|count_characters)==7 }color:{$aParams.PRIMARY_COLOR};{/if}'>{$aListeForfait.TITRE}</h2>{/if}
                    {if $aListeForfait.CONTENT_SUBTITLE}<h3 class="parttitle">{$aListeForfait.CONTENT_SUBTITLE}</h3>{/if}

                    {foreach $MultiForfait as $Multi}
                        <div class="parttitle">{$Multi.CONTENT_ZONE_MULTI_LABEL} {if $Multi.CONTENT_ZONE_MULTI_LABEL2 != ''}<em>{$Multi.CONTENT_ZONE_MULTI_LABEL2}</em>{/if}</div>
                        <div class="zonetexte">
                            <div class="text">
                                {if $Multi.CONTENT_ZONE_MULTI_LABEL != ''}<p><strong></strong></p>{/if}
                            </div>
                            {if $Multi.CONTENT_ZONE_MULTI_TEXT}
                                <div class="text">
                                    {$Multi.CONTENT_ZONE_MULTI_TEXT}
                                </div>
                            {/if}
                        </div>
                        {if $Multi.CONTENT_ZONE_MULTI_ID != $iNbMulti}
                            <hr />
                        {/if}
                    {/foreach}
                </div>
                <div class="columns column_50">
                    {if $MEDIA_VIDEO}
                        <figure class=" col span3">
                            <figure class="video shareable">
                                <a class="popit video" data-video="{$MEDIA_VIDEO}" href="{urlParser url=$MEDIA_VIDEO}" data-sneezy target="_blank" {gtm action='Display::Video' data=$aData datasup=['eventLabel'=>{$MEDIA_ALT}]}>
                                    <img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/16-9.png" data-original="{Pelican::$config.MEDIA_HTTP}{$MEDIA_PATH}" width="580" height="323">
                                    <noscript>
                                        <img src="{Pelican::$config.MEDIA_HTTP}{$MEDIA_PATH}" width="580" height="323" alt="{$MEDIA_ALT}" />
                                    </noscript>
                                </a>
                                <i class="icon-play"></i>
                            </figure>
                        </figure>
                    {elseif $aMEDIA_ID2}
                        <figure class=" col span3 shadow">
                            {section name=push loop=$aMEDIA_ID2}

                                {if $smarty.section.push.first}
                                    <img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/16-9.png" data-original="{$aMEDIA_ID2[push]}" width="580" height="323" alt="{$VisuelMediaGallerie[push][1]}">
                                {else}
                                    <a class="popit photo" data-sneezy="groupV0" data-original="{$aMEDIA_ID2[push]}" href="{urlParser url=$aMEDIA_ID2[push]}" target="_blank"></a>
                                {/if}
                            {/section}
                            <a class="popit photo" data-sneezy="groupV0" href="{urlParser url=$aMEDIA_ID2[0]}" target="_blank"></a>
                        </figure>
                        <!-- /.col -->
                    {/if}
                </div>

                <div class="caption">
                    {if $aParams.ZONE_TITRE6 && ($aParams.ZONE_TITRE5 == "ROLL" || ($aParams.ZONE_TITRE5 == "POP_IN" && $urlPopInMention != ""))}

                        {if $aParams.ZONE_TITRE5 == "ROLL"}
                            <small class="legal"><a href="#LegalTip_{$aParams.ZONE_ORDER}" class="texttip">{$aParams.ZONE_TITRE6}</a></small>
                            <div class="legal layertip" id="LegalTip_{$aParams.ZONE_ORDER}">
                                {if $MEDIA_PATH4 != ""}<img class="lazy noscale" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/235-100.png" data-original="{"{Pelican::$config.MEDIA_HTTP}{$MEDIA_PATH4}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_MENTION}" width="580" height="247" alt="{$MEDIA_TITLE4}" />{/if}
                                {if $aParams.ZONE_TEXTE4} <div class="zonetexte">{$aParams.ZONE_TEXTE4}</div> {/if}
                            </div>
                        {elseif $aParams.ZONE_TITRE5 == "POP_IN" && $urlPopInMention != ""}
                            <small class="legal">
                                <a href="{urlParser url={$urlPopInMention|cat:'?popin=1'}}" class="popinfos fancybox.ajax">
                                    {$aParams.ZONE_TITRE6}
                                </a>
                            </small>
                        {/if}

                    {/if}
                    {if $aParams.ZONE_TITRE5 == "TEXT" && $aParams.ZONE_TEXTE4 neq ""}
                        <div class="caption">
                            <figure>
                                {if $MEDIA_PATH4 != ""}<img class="noscale" src="{"{Pelican::$config.MEDIA_HTTP}{$MEDIA_PATH4}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_MENTION}" width="580" height="247" alt="{$MEDIA_TITLE4}" />{/if}
                            </figure>
                            <small class="legal">{$aParams.ZONE_TITRE6}<br>{if $aParams.ZONE_TEXTE4} <div class="zonetexte">{$aParams.ZONE_TEXTE4}</div> {/if}</small>
                        </div>
                    {/if}

                    {if $aMedias|@sizeof > 0}
                        <div class="thumbs">
                            {foreach $aMedias as $aMedia  key=key}
                            {if $aMedia.VIDEO}
                            {if $aMedia.VIDEO.YOUTUBE_ID}
                            <a class="popit" {if $aMedia.VIDEO.OTHER_MEDIA_PATH}data-video="{$aMedia.VIDEO.OTHER_MEDIA_PATH}"{/if} data-sneezy href="{if $aMedia.VIDEO.YOUTUBE_URL}{urlParser url=$aMedia.VIDEO.YOUTUBE_URL}{/if}" target="_blank" {gtm  action='Display::Video' data=$aData datasup=['eventLabel'=>{$aMedia.VIDEO.MEDIA_TITLE}]}>
                                {else}
                                <a class="popit" {if $aMedia.VIDEO.OTHER_MEDIA_PATH}data-video="{$aMedia.VIDEO.OTHER_MEDIA_PATH}"{/if} data-sneezy href="{if $aMedia.VIDEO.MEDIA_PATH}{urlParser url={Pelican::$config.MEDIA_HTTP|cat:$aMedia.VIDEO.MEDIA_PATH}}{/if}" target="_blank" {gtm  action='Display::Video' data=$aData datasup=['eventLabel'=>{$aMedia.VIDEO.MEDIA_TITLE}]}>
                                    {/if}
                                    <!--shadow video-->
                                    <figure class="shadow video">
                                        <i class="icon-play"></i>
                                        <img class="lazy" data-original="{if $aMedia.VIDEO.MEDIA_PATH}{"{Pelican::$config.MEDIA_HTTP}{$aMedia.VIDEO.MEDIA_PATH2}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_MENTION}{/if}" width="145" height="81" alt="{$aMedia.VIDEO.MEDIA_ALT2}">
                                    </figure>
                                    <span>{$aMedia.VIDEO.MEDIA_TITLE}</span>
                                </a>
                                {/if}
                                {if $aMedia.IMAGE}
                                    {section name=push loop=$aMedia.IMAGE}
                                        {if $smarty.section.push.first}
                                            <a class="popit" data-sneezy="group{$key}" href="{urlParser url={Pelican::$config.MEDIA_HTTP|cat:$aMedia.IMAGE[push].MEDIA_PATH}}" target="_blank" {gtm   action='Zoom' data=$aData datasup=['eventLabel'=>{$aMedia.IMAGE[push].MEDIA_TITLE}]}>
                                                <figure class="shadow">
                                                    {IF $VIGN_GALLERY}
                                                    {assign var="Vignette_Gal" value="$VIGN_GALLERY"}
                                                    {ELSE}
                                                    {assign var="Vignette_Gal" value=$aMedia.IMAGE[push].MEDIA_PATH}
                                                    {/IF}

                                                    <img class="lazy" data-original="{"{Pelican::$config.MEDIA_HTTP}{$Vignette_Gal}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_PUSH}" width="145" height="81" alt="{$aMedia.IMAGE[push].MEDIA_TITLE}">


                                                </figure>
                                                <span>{$aMedia.MEDIA_TITLE}</span>
                                            </a>
                                        {else}
                                            <a class="popit grouped" data-sneezy="group{$key}" href="{urlParser url={Pelican::$config.MEDIA_HTTP|cat:$aMedia.IMAGE[push].MEDIA_PATH}}" target="_blank" {gtm   action='Zoom' data=$aData datasup=['eventLabel'=>{$aMedia.IMAGE[push].MEDIA_ALT}]}>
                                                <figure class="shadow">
                                                    <img class="lazy" data-original="{"{Pelican::$config.MEDIA_HTTP}{$aMedia.IMAGE[push].MEDIA_PATH}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_PUSH}" width="145" height="81" alt="{$aMedia.IMAGE[push].MEDIA_ALT}" />
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
                                <li class="cta"><a data-sync="cta{$aData.ORDER}" href="{urlParser url=$aCta[cta].CONTENT_ZONE_MULTI_URL}" target="_{$aCta[cta].CONTENT_ZONE_MULTI_VALUE}" class="buttonTransversalInvert "><span>{$aCta[cta].CONTENT_ZONE_MULTI_LABEL3}</span></a></li>
                            {/section}
                        </ul>
                    {/if}
                </div>
            </div>
        </div>

    </section>
</div>
{/if}
