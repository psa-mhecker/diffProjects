{if $aData.ZONE_WEB ==1} 
    {literal}
        <style>
    {/literal}
    {if ($aData.SECOND_COLOR|count_characters)==7}
    {literal}
    .slice1ColumnDesk .actions .buttonTransversalInvert, .slice1ColumnDesk .buttonTransversalInvert{
    {/literal}{if ($aData.SECOND_COLOR|count_characters)==7 }{literal}
        background-color:{/literal}{$aData.SECOND_COLOR};{literal}
        border-color:{/literal}{$aData.SECOND_COLOR};{literal}
        color:#ffffff;
    {/literal}{/if}{literal}
    }
    .slice1ColumnDesk .actions .buttonTransversalInvert:hover, .slice1ColumnDesk .actions .buttonTransversalInvert:active, .slice1ColumnDesk .buttonTransversalInvert:hover, .slice1ColumnDesk .buttonTransversalInvert:active:hover{
    {/literal}{if ($aData.SECOND_COLOR|count_characters)==7 }{literal}
        background-color:#ffffff;
        border-color:{/literal}{$aData.SECOND_COLOR};{literal}
        color:{/literal}{$aData.SECOND_COLOR}; {literal}
    {/literal}{/if}{literal}
    }
    {/literal}
    {/if}
    {literal}
    div.video-js.vjs-default-skin.vjs-fullscreen {max-width: 100%!important;max-height: 100%!important;width: 100%!important;height: 100%!important;z-index:99999;}
    div.vjs-default-skin div.vjs-control:before {font-family:VideoJSshowroom}
        </style>
    {/literal}
    <div class="sliceNew slice1ColumnDesk">
        <section class="row"  id="{$aData.ID_HTML}">
            <div class="columns column_100">
                {if $aData.ZONE_TITRE3}<h2 class="subtitle"{if ($aData.PRIMARY_COLOR|count_characters)==7 } style="color:{$aData.PRIMARY_COLOR};" {/if}>{$aData.ZONE_TITRE3|escape}</h2>{/if}
                {if $aData.ZONE_TITRE4}<h3 class="parttitle" {if ($aData.SECOND_COLOR|count_characters)==7 } style="color:{$aData.SECOND_COLOR};" {/if} >{$aData.ZONE_TITRE4|escape}</h3>{/if}

                {if $aData.ZONE_TEXTE}
                    <div class="mgchapo">{$aData.ZONE_TEXTE|escape}</div>
                {else}
                    <div class="mgchapo"></div>
                {/if}

                {if $aData.MEDIA_ID2}
                    <figure class="shareable">

                        <div class="framed">
                            <object type="application/x-shockwave-flash" data="{$MEDIA_FLASH}" width="1440" height="500">
                                <param name="movie" value="{$MEDIA_FLASH}" />
                                <param name="wmode" value="transparent" />

                                <figure>
                                    {$aData.ZONE_TEXTE5}
                                </figure>
                            </object>
                        </div>
                    </figure>
                {elseif $sMediaVideo neq '' && $aData.MEDIA_ID11 neq ''}
                    {$sMediaVideo}
                    {if ($aData.PRIMARY_COLOR|count_characters)==7 }
                        <style type="text/css">
                            {literal}
                            .vjs-default-skin .vjs-tech{ background-color: #ffffff; }
                            .vjs-default-skin { color: #ffffff; background-color: #ffffff; }
                            .vjs-default-skin .vjs-play-progress,
                            .vjs-default-skin .vjs-volume-level {  {/literal}{if ($aData.SECOND_COLOR|count_characters)==7 } background:{$aData.SECOND_COLOR}; {else}  background-color: rgba(0,0,0,1);  {/if} {literal} }
                            .vjs-default-skin .vjs-control-bar{ {/literal}{if ($aData.SECOND_COLOR|count_characters)==7 } background:{$aData.SECOND_COLOR}; {else}  background: rgba(0,0,0,1);  {/if} {literal} }
                            .vjs-default-skin .vjs-big-play-button:before { {/literal}{if ($aData.SECOND_COLOR|count_characters)==7 } color:{$aData.SECOND_COLOR}; {else}  color: rgba(0,0,0,1);  {/if} {literal} }
                            .vjs-default-skin .vjs-control:before { {/literal}font-size:15px !important;{if ($aData.SECOND_COLOR|count_characters)==7 } color:{$aData.SECOND_COLOR}; {else}  color: rgba(0,0,0,1);  {/if} {literal} }
                            .vjs-default-skin .vjs-current-time-display,
                            .vjs-default-skin .vjs-remaining-time-display,
                            .vjs-default-skin .vjs-duration-display,
                            .vjs-default-skin .vjs-time-divider{ {/literal}{if ($aData.SECOND_COLOR|count_characters)==7 } color:{$aData.SECOND_COLOR}; {else}  color: rgba(0,0,0,1);  {/if} {literal} }
                            .vjs-default-skin .vjs-control-bar {  background-color:#ffffff; }
                            {/literal}
                        </style>
                    {/if}
                {elseif $MEDIA_PATH}
                    <figure class="shareable visual">
                        <span class="noroll"></span>
                        {*<a class="popit photo" data-sneezy="" href="{urlParser url={Pelican::$config.MEDIA_HTTP|cat:$MEDIA_PATH}}" target="_blank" {gtm action='Zoom' data=$aData datasup=['eventLabel'=>{$MEDIA_TITLE}]}>*}
                            <img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/235-100.png" data-original="{"{Pelican::$config.MEDIA_HTTP}{$MEDIA_PATH}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_1_COLONNE}" width="1200" height="517" alt="{$MEDIA_ALT}">
                            <noscript>
                                <img src="{"{Pelican::$config.MEDIA_HTTP}{$MEDIA_PATH}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_1_COLONNE}" width="1200" height="517" alt="{$MEDIA_ALT}">
                            </noscript>
                        {*</a>*}
                    </figure>
                {elseif $aData.ZONE_TEXTE2 != ""}
                    <div class="col span5 zonetextehtml">{$aData.ZONE_TEXTE2}</div>

                {/if}

                {if $aData.ZONE_TEXTE3}
                    <div class="zonetexte"> <p>{$aData.ZONE_TEXTE3}</p></div>
                {/if}

                {if $aData.ZONE_TITRE6 && ($aData.ZONE_TITRE5 == "ROLL" || ($aData.ZONE_TITRE5 == "POP_IN" && $urlPopInMention != ""))}
                    {if $aData.ZONE_TITRE5 == "ROLL"}
                        <small class="legal"><a href="#LegalTip_{$aData.ZONE_ORDER}" class="texttip">{$aData.ZONE_TITRE6|escape}</a></small>
                        <div class="legal layertip" id="LegalTip_{$aData.ZONE_ORDER}">
                            {if $MEDIA_PATH4 != ""}<img class="lazy noscale" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/235-100.png" data-original="{"{Pelican::$config.MEDIA_HTTP}{$MEDIA_PATH4}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_MENTION}" width="580" height="247" alt="{$MEDIA_ALT4}" />{/if}
                            {if $aData.ZONE_TEXTE4}<div class="zonetexte">{$aData.ZONE_TEXTE4}</div>{/if}
                        </div>

                    {elseif $aData.ZONE_TITRE5 == "POP_IN" && $urlPopInMention != ""}
                        <div class="legal">
                            <a href="{urlParser url={$urlPopInMention|cat:'?popin=1'}}" class="popinfos fancybox.ajax" {gtmjs type='toggle' data=$aData action='Display::ToolTip|' eventGTM='over'  datasup=['eventLabel' => $aData.ZONE_TITRE6, 'idBouton' => 'legal']}>
                                {$aData.ZONE_TITRE6|escape}
                            </a>
                        </div>
                    {/if}
                {/if}
                {if $aData.ZONE_TITRE5 == "TEXT" && $aData.ZONE_TEXTE4 neq ""}
                    <div class="legal">
                        <figure>
                            {if $MEDIA_PATH4 != ""}<img class="lazy noscale" src="{"{Pelican::$config.MEDIA_HTTP}{$MEDIA_PATH4}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_MENTION}" width="580" height="247" alt="{$MEDIA_ALT4}" />{/if}
                        </figure>
                        <small class="legal">{$aData.ZONE_TITRE6|escape}<br><div class="zonetexte">{$aData.ZONE_TEXTE4}</div></small>
                    </div>
                {/if}

                {if $aMedias|@sizeof > 0}
                    <div class="thumbs">
                        {foreach $aMedias as $aMedia  key=key}
                        {if $aMedia.VIDEO}
                        {if $aMedia.VIDEO.YOUTUBE_ID}
                        <a class="popit" {if $aMedia.VIDEO.OTHER_MEDIA_PATH}data-video="{$aMedia.VIDEO.OTHER_MEDIA_PATH}"{/if} data-sneezy href="{if $aMedia.VIDEO.YOUTUBE_URL}{urlParser url=$aMedia.VIDEO.YOUTUBE_URL}{/if}" target="_blank"  {gtm action='Display::Video' data=$aData datasup=['eventLabel'=>{$aMedia.VIDEO.MEDIA_TITLE}]} >
                            {else}
                            <a class="popit" {if $aMedia.VIDEO.OTHER_MEDIA_PATH}data-video="{$aMedia.VIDEO.OTHER_MEDIA_PATH}"{/if} data-sneezy href="{if $aMedia.VIDEO.MEDIA_PATH}{urlParser url={Pelican::$config.MEDIA_HTTP|cat:$aMedia.VIDEO.MEDIA_PATH}}{/if}" target="_blank" {gtm data=$aData action='Display::Video'  datasup=['eventLabel'=>{$aMedia.VIDEO.MEDIA_ALT}]}>
                                {/if}
                                <figure class="shadow video">
                                    <i class="icon-play"></i>
                                    <img src="{if $aMedia.VIDEO.MEDIA_PATH}{"{Pelican::$config.MEDIA_HTTP}{$aMedia.VIDEO.MEDIA_PATH2}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_MENTION}{/if}"   width="145" height="81" alt="{$aMedia.VIDEO.MEDIA_ALT2}">
                                </figure>
                                <span class="legend">{$aMedia.VIDEO.MEDIA_TITLE|escape}</span>
                            </a>
                            {/if}

                            {if $aMedia.IMAGE}
                                {section name=push loop=$aMedia.IMAGE}
                                    {if $smarty.section.push.first}
                                        <a class="popit" data-sneezy="group1col{$key}" href="{urlParser url={Pelican::$config.MEDIA_HTTP|cat:$aMedia.IMAGE[push].MEDIA_PATH}}" target="_blank"  {gtm action='Zoom' data=$aData datasup=['eventLabel'=> $aMedia.IMAGE[push].MEDIA_TITLE]}>
                                            <figure class="shadow">
                                                <img src="{"{Pelican::$config.MEDIA_HTTP}{$aMedia.IMAGE[push].MEDIA_PATH}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_PUSH}" width="145" height="81" alt="{$aMedia.IMAGE[push].MEDIA_ALT}" />
                                            </figure>
                                        </a>
                                    {else}
                                        <a class="popit grouped" data-sneezy="group1col{$key}" href="{urlParser url={Pelican::$config.MEDIA_HTTP|cat:$aMedia.IMAGE[push].MEDIA_PATH}}" target="_blank" {gtm action='Zoom' data=$aData datasup=['eventLabel'=> $aMedia.IMAGE[push].MEDIA_TITLE]}>
                                            <figure class="shadow">
                                                <img data-original="{"{Pelican::$config.MEDIA_HTTP}{$aMedia.IMAGE[push].MEDIA_PATH}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_PUSH}" width="145" height="81" alt="{$aMedia.IMAGE[push].MEDIA_ALT}" />
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
                            {/if}
                        {/section}
                    </ul>
                    <ul class="actions">
                        {section name=cta loop=$aCta}
                            {if !$aCta[cta].OUTIL}
                                <li class="cta">
                                    <a {gtm action="Push" data=$aData datasup=['eventCategory'=>'Content','eventLabel' =>$aCta[cta].PAGE_ZONE_MULTI_LABEL]} href="{urlParser url=$aCta[cta].PAGE_ZONE_MULTI_URL}" target="_{$aCta[cta].PAGE_ZONE_MULTI_VALUE}" {*if ($aData.SECOND_COLOR|count_characters)==7 } style="background-color:{$aData.SECOND_COLOR};border-color:{$aData.SECOND_COLOR};color:{$aData.PRIMARY_COLOR};"  {/if*} class="buttonTransversalInvert ">
                                        {$aCta[cta].PAGE_ZONE_MULTI_LABEL}
                                    </a>
                                </li>
                            {/if}
                        {/section}
                    </ul>
                {/if}

                {* <ul class="actions">
                     <li class="cta">
                         <a data-gtm="eventGTM|Content|Push|Libellé CTA 2||" href="/fr/vehicules/citroen/c3.html" target="_blank" class="buttonShowroom" data-gtm-init="1">
                             <span>Découvrez les technologies</span>
                         </a>
                     </li>
                 </ul>*}
            </div>
        </section>
    </div>
    <div class="parent" id="trancheParent" style="display: none;"></div>

    {if $aData.ZONE_LANGUETTE == 1}
        <section class="showroom row of3 clslanguetteshowroom">
            <div class="caption addmore folder" {if ($aData.PRIMARY_COLOR|count_characters)==7 } data-off="border:4px solid {$aData.SECOND_COLOR}; color:{$aData.SECOND_COLOR};" data-hover="border:4px solid {$aData.SECOND_COLOR}; color:{$aData.SECOND_COLOR};"{/if} data-toggle="{if $aData.ZONE_LANGUETTE_TEXTE_CLOSE}{$aData.ZONE_LANGUETTE_TEXTE_CLOSE}{else}{'VOIR_MOINS'|t}{/if}"><a {if ($aData.PRIMARY_COLOR|count_characters)==7 } class="col span2" {/if} href="#trancheParent" {gtm action="Push" data=$aData datasup=['eventLabel' => 'LANGUETTE_1_COLONNE']}><span>{if $aData.ZONE_LANGUETTE_TEXTE_OPEN}{$aData.ZONE_LANGUETTE_TEXTE_OPEN}{else}{t("Voir plus")}{/if}</span></a></div>
        </section>
    {/if}
{/if}