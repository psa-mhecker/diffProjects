{literal}
<style>
    {/literal}
    {if ($aData.SECOND_COLOR|count_characters)==7}
    {literal}
    .slice2columnsDesk .actions .buttonTransversalInvert, .slice2columnsDesk .buttonTransversalInvert{
    {/literal}{if ($aData.SECOND_COLOR|count_characters)==7 }{literal}
        background-color:{/literal}{$aData.SECOND_COLOR};{literal}
        border-color:{/literal}{$aData.SECOND_COLOR};{literal}
        color:#ffffff;
    {/literal}{/if}{literal}
    }
    .slice2columnsDesk .actions .buttonTransversalInvert:hover, .slice2columnsDesk .actions .buttonTransversalInvert:active, .slice2columnsDesk .buttonTransversalInvert:hover, .slice2columnsDesk .buttonTransversalInvert:active:hover{
    {/literal}{if ($aData.SECOND_COLOR|count_characters)==7 }{literal}
        background-color:#ffffff;
        border-color:{/literal}{$aData.SECOND_COLOR};{literal}
        color:{/literal}{$aData.SECOND_COLOR}; {literal}
    {/literal}{/if}{literal}
    }
    {/literal}
    {/if}
    {literal}
        .showroom.clslanguetteshowroom .addmore.folder a {
        filter: none;
        -webkit-filter: none;
        -moz-filter: none;
        -o-filter: none;
        -ms-filter: none;
    }
</style>
{/literal}
{if ($aData.ZONE_WEB ==1) && $aColonnes|@count > 1}
    <div class="sliceNew slice2columnsDesk">
        <section id="{$aData.ID_HTML}">
            <div class="sep"></div>

            {if $aData.ZONE_TITRE3}<h2 class="subtitle" {if ($aData.PRIMARY_COLOR|count_characters)==7 } style="color:{$aData.PRIMARY_COLOR};" {/if}>{$aData.ZONE_TITRE3|escape}</h2>{/if}
            {if $aData.ZONE_TITRE4}<h3 class="parttitle" {if ($aData.SECOND_COLOR|count_characters)==7 } style="color:{$aData.SECOND_COLOR};" {/if}>{$aData.ZONE_TITRE4|escape}</h3>{/if}

            {if $aData.ZONE_TEXTE}
                <div class="mgchapo">{$aData.ZONE_TEXTE|escape}</div>
            {else}
                <div class="col span4 no-mgchapo"></div>
            {/if}
            <div class="row gutter">
                {section name=colonne loop=$aColonnes}

                    <div class="columns column_50">

                        <div class="mgcoltitle" data-sync="title{$aData.ORDER}">
                            <h4>{$aColonnes[colonne].PAGE_ZONE_MULTI_LABEL|escape}</h4>
                        </div>
                        <figure class="visual">
                            <img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/235-100.png" data-original="{"{Pelican::$config.MEDIA_HTTP}{$aColonnes[colonne].MEDIA_PATH}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_2_COLONNES}" width="580" height="250" alt="{$aColonnes[colonne].MEDIA_ALT}" style="display: inline-block;">
                            <noscript><img src="{"{Pelican::$config.MEDIA_HTTP}{$aColonnes[colonne].MEDIA_PATH}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_2_COLONNES}" width="580" height="250" alt="{$aColonnes[colonne].MEDIA_ALT}" /></noscript>
                        </figure>
                        <!-- /.col -->
                        <div class="zonetexte">
                            {if $aColonnes[colonne].PAGE_ZONE_MULTI_TEXT}
                                {$aColonnes[colonne].PAGE_ZONE_MULTI_TEXT}
                            {/if}


                        </div>
                        {if $aData.ZONE_PARAMETERS == 1}
                            {if $aColonnes[colonne].PAGE_ZONE_MULTI_URL2 != "" && $aColonnes[colonne].PAGE_ZONE_MULTI_LABEL2 != ""}
                                <ul>
                                    <li><a class="buttonLink" href="{urlParser url=$aColonnes[colonne].PAGE_ZONE_MULTI_URL2}" {if $aColonnes[colonne].PAGE_ZONE_MULTI_VALUE == 'BLANK'}target="_blank"{/if} {gtm action='Push' data=$aData datasup=['eventLabel'=>$aColonnes[colonne].PAGE_ZONE_MULTI_LABEL2]}>{$aColonnes[colonne].PAGE_ZONE_MULTI_LABEL2|escape}</a></li>
                                </ul>
                            {/if}
                        {elseif $aData.ZONE_PARAMETERS == 2}
                            {if $aColonnes[colonne].PAGE_ZONE_MULTI_URL3 != "" || $aColonnes[colonne].PAGE_ZONE_MULTI_LABEL3 != ""
                            || $aColonnes[colonne].PAGE_ZONE_MULTI_URL5 != "" || $aColonnes[colonne].PAGE_ZONE_MULTI_LABEL5 != ""
                            || $aColonnes[colonne].PAGE_ZONE_MULTI_ATTRIBUT!="" || $aColonnes[colonne].PAGE_ZONE_MULTI_ATTRIBUT2!=""}
                                {*CPW-3679   -- Fix Isobar - Suppression des sauts de lignes *}
                                <ul class="actions" {if ($aData.PRIMARY_COLOR|count_characters)==7 } data-off="background:{$aData.SECOND_COLOR}; color:#ffffff;" data-hover="border:4px solid {$aData.SECOND_COLOR}; background:#ffffff; color:{$aData.SECOND_COLOR};" {/if}>

                                    {if $aColonnes[colonne].PAGE_ZONE_MULTI_ATTRIBUT}
                                        <li class="cta"><a class="buttonLead" {gtm action="Push" data=$aData datasup=['eventCategory'=>'Content','eventLabel' =>$aColonnes[colonne].BARRE_OUTILS_TITRE]} href="{urlParser url=$aColonnes[colonne].BARRE_OUTILS_URL_WEB}" target="_{$aColonnes[colonne].BARRE_OUTILS_MODE_OUVERTURE}"><span>{$aColonnes[colonne].BARRE_OUTILS_TITRE}</span></a></li>
                                    {elseif $aColonnes[colonne].PAGE_ZONE_MULTI_URL3 != "" && $aColonnes[colonne].PAGE_ZONE_MULTI_LABEL3 != ""}
                                        <li class="cta"><a class="buttonTransversalInvert" {gtm action="Push" data=$aData datasup=['eventCategory'=>'Content','eventLabel' =>$aColonnes[colonne].PAGE_ZONE_MULTI_LABEL3]} href="{urlParser url=$aColonnes[colonne].PAGE_ZONE_MULTI_URL3}" {if $aColonnes[colonne].PAGE_ZONE_MULTI_VALUE2 == "BLANK"}target="_blank"{/if} >{$aColonnes[colonne].PAGE_ZONE_MULTI_LABEL3}</a></li>
                                    {/if}

                                    {if $aColonnes[colonne].PAGE_ZONE_MULTI_ATTRIBUT2}
                                        <li class="cta"><a class="buttonLead" {gtm action="Push" data=$aData datasup=['eventCategory'=>'Content','eventLabel' =>$aColonnes[colonne].BARRE_OUTILS_TITRE2]} href="{urlParser url=$aColonnes[colonne].BARRE_OUTILS_URL_WEB2}" target="{$aColonnes[colonne].BARRE_OUTILS_MODE_OUVERTURE2}"><span>{$aColonnes[colonne].BARRE_OUTILS_TITRE2}</span></a></li>
                                    {elseif $aColonnes[colonne].PAGE_ZONE_MULTI_URL5 != "" && $aColonnes[colonne].PAGE_ZONE_MULTI_LABEL4 != ""}
                                        <li class="cta"><a class="buttonTransversalInvert" {gtm action="Push" data=$aData datasup=['eventCategory'=>'Content','eventLabel' =>$aColonnes[colonne].PAGE_ZONE_MULTI_LABEL4]} href="{urlParser url=$aColonnes[colonne].PAGE_ZONE_MULTI_URL5}" {if $aColonnes[colonne].PAGE_ZONE_MULTI_VALUE3 == "BLANK"}target="_blank"{/if} >{$aColonnes[colonne].PAGE_ZONE_MULTI_LABEL4}</a></li>
                                    {/if}

                                </ul>
                            {/if}
                        {/if}
                    </div>
                    <!-- /.col -->
                {/section}
            </div>


            {if $aMedias|@sizeof > 0 || $aData.ZONE_TITRE6 || $aCta|@sizeof > 0 || $aData.ZONE_TEXTE4}

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
                                <a href="{urlParser url={$urlPopInMention|cat:'?popin=1'}}" class="popinfos fancybox.ajax" {gtmjs type='toggle' data=$aData action='Display::ToolTip|' eventGTM='over'  datasup=['eventLabel'=>$aData.ZONE_TITRE6]}>
                                    {$aData.ZONE_TITRE6|escape}
                                </a>
                            </small>
                        {/if}
                    </div>
                {/if}
                {if $aData.ZONE_TITRE5 == "TEXT" && $aData.ZONE_TEXTE4 neq ""}
                    <div class="legal">
                        <figure>
                            {if $MEDIA_PATH4 != ""}<img class="noscale" src="{"{Pelican::$config.MEDIA_HTTP}{$MEDIA_PATH4}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_MENTION}" width="580" height="247" alt="{$MEDIA_ALT4}" />{/if}
                        </figure>
                        {$aData.ZONE_TITRE6|escape}<br>{if $aData.ZONE_TEXTE4} <div class="zonetexte">{$aData.ZONE_TEXTE4}</div> {/if}
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
                                        <a class="popit" data-sneezy="group2col{$key}" href="{urlParser url={Pelican::$config.MEDIA_HTTP|cat:$aMedia.IMAGE[push].MEDIA_PATH}}" target="_blank"  {gtm action='Zoom' data=$aData datasup=['eventLabel'=> $aMedia.IMAGE[push].MEDIA_TITLE]} >
                                            <figure class="shadow">
                                                <img src="{"{Pelican::$config.MEDIA_HTTP}{$aMedia.IMAGE[push].MEDIA_PATH}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_PUSH}" width="145" height="81" alt="{$aMedia.IMAGE[push].MEDIA_ALT}" />
                                            </figure>
                                        </a>
                                    {else}
                                        <a class="popit grouped" data-sneezy="group2col{$key}" href="{urlParser url={Pelican::$config.MEDIA_HTTP|cat:$aMedia.IMAGE[push].MEDIA_PATH}}" target="_blank" {gtm action='Zoom' data=$aData datasup=['eventLabel'=> $aMedia.IMAGE[push].MEDIA_TITLE]}>
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
                    <div class="row gutter" style="margin-bottom: 0px;">
                        <div class="columns column_100">
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
                            <!-- /.actions -->
                        </div>
                    </div>
                {/if}
            {/if}
        </section>
    </div>
    <div class="parent" id="trancheParent" style="display: none;"></div>

    {if $aData.ZONE_LANGUETTE == 1}
        <section class="showroom row of3 clslanguetteshowroom">
            <div class="caption addmore folder" data-off="border:4px solid {if ($aData.SECOND_COLOR|count_characters)==7}{$aData.SECOND_COLOR}{else}#afadc3{/if};color:{if ($aData.SECOND_COLOR|count_characters)==7}{$aData.SECOND_COLOR}{else}#afadc3{/if};padding:8px;" data-hover="border:6px solid {if ($aData.SECOND_COLOR|count_characters)==7}{$aData.SECOND_COLOR}{else}#afadc3{/if};color:{if ($aData.SECOND_COLOR|count_characters)==7}{$aData.SECOND_COLOR}{else}#afadc3{/if};padding:6px;" data-toggle="{if $aData.ZONE_LANGUETTE_TEXTE_CLOSE}{$aData.ZONE_LANGUETTE_TEXTE_CLOSE}{else}{'VOIR_MOINS'|t}{/if}"><a {if ($aData.PRIMARY_COLOR|count_characters)==7 } class="col span2" {/if} href="#trancheParent" {gtm action="Push" data=$aData datasup=['eventLabel' => 'LANGUETTE_1_COLONNE']}><span style="color: inherit;">{if $aData.ZONE_LANGUETTE_TEXTE_OPEN}{$aData.ZONE_LANGUETTE_TEXTE_OPEN}{else}{t("Voir plus")}{/if}</span></a></div>
        </section>
    {/if}
{/if}