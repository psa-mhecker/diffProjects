<div class="sliceNew sliceIframeDesk">
    {if $aData.ZONE_WEB ==1}
        <section id="{$aData.ID_HTML}" class="clsiframe">
            <div class="sep {$aData.ZONE_SKIN}"></div>

            {if $aData.ZONE_TITRE3}<h2 class="subtitle" {if ($aData.PRIMARY_COLOR|count_characters)==7 } style="color:{$aData.PRIMARY_COLOR};font-size:40px!important;" {/if}>{$aData.ZONE_TITRE3|escape|upper}</h2>{/if}
            {if $aData.ZONE_TITRE4}<h3 class="parttitle" {if ($aData.SECOND_COLOR|count_characters)==7 } style="color:{$aData.SECOND_COLOR};" {/if}>{$aData.ZONE_TITRE4|escape}</h3>{/if}

            {if $aData.ZONE_TEXTE}
                <span class="mgchapo">{$aData.ZONE_TEXTE|escape}</span>
            {else}
                <div></div>
            {/if}

            <div class="iframeClear">
                <iframe id="iframeContainer" data-iframe="{$dataHide}" class="caption" style="width: {$aData.ZONE_TITRE10}{$aData.ZONE_TITRE9};{if $aData.ZONE_TITRE14 neq ''}height:{$aData.ZONE_TITRE14}px;{/if}" frameborder="0" src="{$aData.ZONE_URL}" {if $aData.ZONE_TITRE14 eq ''} scrolling="no" onload="resize_iframe(this)"{/if} onmouseover="this.contentWindow.focus();"></iframe>
                <div id="alterFrame" class="caption" style="display:none;">{$aData.ZONE_TEXTE2}</div>
            </div>

            {if $aData.ZONE_TITRE6 && ($aData.ZONE_TITRE5 == "ROLL" || ($aData.ZONE_TITRE5 == "POP_IN" && $urlPopInMention != ""))}
                <div class="caption">
                    {if $aData.ZONE_TITRE5 == "ROLL"}
                        <small class="legal"><a href="#LegalTip_{$aData.ZONE_ORDER}" 
                                                class="texttip">{$aData.ZONE_TITRE6}</a></small>
                        <div class="legal layertip" id="LegalTip_{$aData.ZONE_ORDER}">
                            {if $MEDIA_PATH4 != ""}<img class="lazy noscale" 
                                 src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/235-100.png" 
                                 data-original="{"{Pelican::$config.MEDIA_HTTP}{$MEDIA_PATH4}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_MENTION}" width="580" 
                                 height="247" alt="{$MEDIA_ALT4}" />{/if}
                                {if $aData.ZONE_TEXTE4} <div class="zonetexte">{$aData.ZONE_TEXTE4}</div> {/if}
                            </div>
                        {elseif $aData.ZONE_TITRE5 == "POP_IN" && $urlPopInMention != ""}
                            <small class="legal">
                                <a href="{urlParser url={$urlPopInMention|cat:'?popin=1'}}" class="popinfos fancybox.ajax">
                                    {$aData.ZONE_TITRE6|escape}
                                </a>
                            </small>
                        {/if}
                    </div>
                {/if}
                {if $aData.ZONE_TITRE5 == "TEXT" && $aData.ZONE_TEXTE4 neq ""}
                    <div class="caption">
                        <figure>
                            {if $MEDIA_PATH4 != ""}<img class="noscale" 
                                 src="{"{Pelican::$config.MEDIA_HTTP}{$MEDIA_PATH4}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_MENTION}" width="580" height="247" 
                                 alt="{$MEDIA_ALT4}" />{/if}
                            </figure>
                            <small class="legal">{$aData.ZONE_TITRE6|escape}<br>{if $aData.ZONE_TEXTE4} <div class="zonetexte">{$aData.ZONE_TEXTE4}</div> {/if}</small>
                        </div>
                    {/if}
                    {if $aCta|@sizeof > 0}
                        <ul class="actions" {if ($aData.PRIMARY_COLOR|count_characters)==7 } data-off="background:{$aData.SECOND_COLOR}; color:#ffffff;" data-hover="border:4px solid {$aData.SECOND_COLOR}; background:#ffffff; color:{$aData.SECOND_COLOR};" {/if}>
                            {section name=cta loop=$aCta}
                                {if $aCta[cta].OUTIL}
                                    {$aCta[cta].OUTIL}
                                {else}
                                    <li class="cta"><a class="buttonTransversalInvert" data-sync="cta{$aData.ORDER}" href="{urlParser url=$aCta[cta].PAGE_ZONE_MULTI_URL}" 
                                                            target="_{$aCta[cta].PAGE_ZONE_MULTI_VALUE}"><span>{$aCta[cta].PAGE_ZONE_MULTI_LABEL}</span></a></li>
                                            {/if}
                                        {/section}
                        </ul>
                    {/if}

                    {if $sSharerIframe neq ""}
                        {$sSharerIframe}
                    {/if}

                </section>
            {/if}
        </div>
        {if $aData.ZONE_TITRE14 eq ''}
            {literal}
                <script type="text/javascript">

                    function resize_iframe(iframe) {

                        var iframeid = iframe.id;
                        //find the height of the internal page
                        var the_height = document.getElementById(iframeid).contentWindow.document.body.scrollHeight;
                        //change the height of the iframe
                        document.getElementById(iframeid).height = the_height;
                        $('div.loading').remove();

                    }
                </script>   
            {/literal}
        {/if}
