{literal}
    <style>
        .showroom.clslanguetteshowroom .addmore.folder a {
            filter: none;
            -webkit-filter: none;
            -moz-filter: none;
            -o-filter: none;
            -ms-filter: none;
        }
        div.sliceSlideShowDesktop div.inner div.texts{
            z-index:auto!important;
        }
    </style>
{/literal}
{*DDM*}
{if $aData.TEMPLATE_PAGE_ID == Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_ACCUEIL']}
    <div ID="DDM_showroom"></div>
{/if}
{if $aData.ZONE_WEB eq 1 && $aSlideShow|@sizeof > 0 && $web_img_count > 0}
    <div class="sliceNew sliceSlideShowDesktop">
        <div id="{$aData.ID_HTML}" class=" redux adapt-margin banner invert slider loop pushed " data-auto="5000" {gtmjs type='slider' data=$aData  action='Click' _perso={$usingPerso}}>
            <div class="row of1">
                {$turns = 0}
                {foreach from=$aSlideShow item=slideShow name=listSlideShow}
                    {$turns = $turns+1}
                    {if $slideShow.PAGE_ZONE_MULTI_VALUE eq 'IMAGE'}
                        {if $slideShow.MEDIA_PATH neq ''}
                            <div class="col" id="{$aData.ID_HTML_SLIDESHOW}_{$turns}">
                                {if $slideShow.URL_CLIC neq ''  && $slideShow.PAGE_ZONE_MULTI_MODE3 eq 1}
                                            {if $slideShow.PAGE_ZONE_MULTI_TITRE neq ''}
                                                <a href="{urlParser url=$slideShow.URL_CLIC}" target="{$slideShow.TARGET_CLIC}" {gtm action='Click' data=$aData datasup=['value'=>$slideShow.PAGE_ZONE_MULTI_ORDER,'eventLabel'=>$slideShow.PAGE_ZONE_MULTI_TITRE] idMulti=$slideShow._sync}>
                                                {else}
                                                    <a href="{urlParser url=$slideShow.URL_CLIC}" target="{$slideShow.TARGET_CLIC}" {gtm action='Click' data=$aData datasup=['value'=>$slideShow.PAGE_ZONE_MULTI_ORDER,'eventLabel'=>$slideShow.MEDIA_ALT] idMulti=$slideShow._sync}>

                                                    {/if}
                                                {/if}
                                <div style="width:100%;height:100%;position:relative;z-index:100;"></div>
                                {if $slideShow.URL_CLIC neq ''  && $slideShow.PAGE_ZONE_MULTI_MODE3 eq 1}
                                                </a>
                                            {/if}
                                
                                <div class="inner">
                                    {if $slideShow.PAGE_ZONE_MULTI_LABEL neq '' || $slideShow.PAGE_ZONE_MULTI_TITRE neq '' || $slideShow.PAGE_ZONE_MULTI_TITRE2 neq '' || $slideShow.PAGE_ZONE_MULTI_LABEL2 neq ''}
                                        {literal}
                                            <style>
                                            {/literal}
                                            {if ($slideShow.COULEUR_CTA|count_characters)==7}
                                                {literal}
                                                {/literal}#a_{$aData.ID_HTML_SLIDESHOW}_{$turns}{literal}{
                                                    {/literal}{if ($slideShow.COULEUR_CTA|count_characters)==7 }{literal}
                                                            background-color:{/literal}{$slideShow.COULEUR_CTA}{literal};
                                                            border-color:{/literal}{$slideShow.COULEUR_CTA}{literal};
                                                            color:{/literal}{$slideShow.COULEUR_TYPO}{literal};
                                                    {/literal}{/if}{literal}
                                                    }
                                                {/literal}#a_{$aData.ID_HTML_SLIDESHOW}_{$turns}{literal}:hover, {/literal}#a_{$aData.ID_HTML_SLIDESHOW}_{$turns}{literal}:active, {/literal}#a_{$aData.ID_HTML_SLIDESHOW}_{$turns}{literal}:hover, {/literal}#a_{$aData.ID_HTML_SLIDESHOW}_{$turns}{literal}:active:hover{
                                                    {/literal}{if ($slideShow.COULEUR_CTA|count_characters)==7 }{literal}
                                                            background-color:{/literal}{$slideShow.COULEUR_TYPO}{literal};
                                                            border-color:{/literal}{$slideShow.COULEUR_CTA}{literal};
                                                            color:{/literal}{$slideShow.COULEUR_CTA}{literal};
                                                    {/literal}{/if}{literal}
                                                    }
                                                {/literal}
                                            {else}

                                            {/if}
                                            {literal}
                                            </style>
                                        {/literal}



                                        <div class="texts {$aData.ID_HTML_SLIDESHOW}_{$turns} {if $slideShow.position.centre neq ''}centre{/if} {if $slideShow.position.milieu neq ''}middle{/if}"
                                             style="
                                             {if $slideShow.position.top neq ''}top:{$slideShow.position.top};{/if}
                                             {if $slideShow.position.bottom neq ''}bottom:{$slideShow.position.bottom};{/if}
                                             {if $slideShow.position.left neq ''}left:{$slideShow.position.left};{/if}
                                             {if $slideShow.position.right neq ''}right:{$slideShow.position.right};{/if}
                                             ">
                                            <div style="z-index:60!important;position:relative;">
                                            {if $slideShow.PAGE_ZONE_MULTI_TITRE}<p class="title {$slideShow.COULEUR_TYPO}" data-text="{$slideShow.PAGE_ZONE_MULTI_TITRE|escape}">{$slideShow.PAGE_ZONE_MULTI_TITRE|escape}</p>{/if}
                                            {if $slideShow.PAGE_ZONE_MULTI_TITRE2}<p class="parttitle {$slideShow.COULEUR_TYPO}">{$slideShow.PAGE_ZONE_MULTI_TITRE2|escape}</p>{/if}
                                            </div>
                                            <ul class="actions" {if ($aData.PRIMARY_COLOR|count_characters)==7 } data-off="background:{$aData.SECOND_COLOR}; color:#ffffff;" data-hover="border:4px solid {$aData.SECOND_COLOR}; background:#ffffff; color:{$aData.SECOND_COLOR};" {/if}>
                                                {if $slideShow.PAGE_ZONE_MULTI_LABEL neq '' &&  $slideShow.PAGE_ZONE_MULTI_URL neq ''}
                                                    <li ><a style="z-index:250!important;position:relative;" id="a_{$aData.ID_HTML_SLIDESHOW}_{$turns}" href="{urlParser url=$slideShow.PAGE_ZONE_MULTI_URL}" target="{if $slideShow.PAGE_ZONE_MULTI_MODE eq 2}_blank{else}_self{/if}"
                                                             {if $slideShow.PAGE_ZONE_MULTI_TITRE neq ''}
                                                                 {gtm action='Click' data=$aData datasup=['value'=>$slideShow.PAGE_ZONE_MULTI_ORDER,'eventLabel'=>$slideShow.PAGE_ZONE_MULTI_TITRE|cat:'::'|cat:$slideShow.PAGE_ZONE_MULTI_LABEL]  idMulti=$slideShow._sync}
                                                             {else}
                                                                 {gtm action='Click' data=$aData datasup=['value'=>$slideShow.PAGE_ZONE_MULTI_ORDER,'eventLabel'=>$slideShow.MEDIA_ALT|cat:'::'|cat:$slideShow.PAGE_ZONE_MULTI_LABEL]  idMulti=$slideShow._sync}
                                                             {/if}
                                                             >{$slideShow.PAGE_ZONE_MULTI_LABEL}</a></li>
                                                    {/if}
                                                    {if $slideShow.PAGE_ZONE_MULTI_LABEL2 neq '' &&  $slideShow.PAGE_ZONE_MULTI_URL2 neq ''}
                                                    <li ><a style="z-index:250!important;position:relative;" id="a_{$aData.ID_HTML_SLIDESHOW}_{$turns}" href="{urlParser url=$slideShow.PAGE_ZONE_MULTI_URL2}" target="{if $slideShow.PAGE_ZONE_MULTI_MODE2 eq 2}_blank{else}_self{/if}"
                                                             {if $slideShow.IS_PERSO}
                                                                 {gtm action='Click' data=$aData datasup=['value'=>$slideShow.PAGE_ZONE_MULTI_ORDER,'eventLabel'=>$slideShow.MEDIA_ALT|cat:$slideShow.PAGE_ZONE_MULTI_TITRE|cat:'::'|cat:$slideShow.PAGE_ZONE_MULTI_LABEL2]  idMulti=$slideShow._sync}>{$slideShow.PAGE_ZONE_MULTI_LABEL2}</a></li>
                                                        {else}
                                                            {gtm action='Click' data=$aData datasup=['value'=>$slideShow.PAGE_ZONE_MULTI_ORDER,'eventLabel'=>$slideShow.PAGE_ZONE_MULTI_TITRE|cat:'::'|cat:$slideShow.PAGE_ZONE_MULTI_LABEL2]  idMulti=$slideShow._sync}>{$slideShow.PAGE_ZONE_MULTI_LABEL2}</a></li>
                                                    {/if}
                                                {/if}
                                            </ul>
                                        </div>
                                    {/if}

                                    <figure>

                                        
                                                <img src="{$slideShow.MEDIA_PATH}" width="1480" height="500" alt="{$slideShow.MEDIA_ALT|escape}" />
                                                
                                    </figure>
                                </div>
                            </div>
                        {/if}
                    {/if}
                    {if $slideShow.PAGE_ZONE_MULTI_VALUE eq 'VIDEO'}
                        {if $slideShow.VIDEOS.MEDIA_REFERENT neq ''}
                            <div class="col" id="{$aData.ID_HTML_SLIDESHOW}_{$turns}">
                                <div class="inner">
                                    {literal}
                                        <style>
                                        {/literal}
                                        {if ($slideShow.COULEUR_CTA|count_characters)==7}
                                            {literal}
                                            {/literal}#a_{$aData.ID_HTML_SLIDESHOW}_{$turns}{literal}{
                                                {/literal}{if ($slideShow.COULEUR_CTA|count_characters)==7 }{literal}
                                                        background-color:{/literal}{$slideShow.COULEUR_CTA}{literal};
                                                        border-color:{/literal}{$slideShow.COULEUR_CTA}{literal};
                                                        color:{/literal}{$slideShow.COULEUR_TYPO}{literal};
                                                {/literal}{/if}{literal}
                                                }
                                            {/literal}#a_{$aData.ID_HTML_SLIDESHOW}_{$turns}{literal}:hover, {/literal}#a_{$aData.ID_HTML_SLIDESHOW}_{$turns}{literal}:active, {/literal}#a_{$aData.ID_HTML_SLIDESHOW}_{$turns}{literal}:hover, {/literal}#a_{$aData.ID_HTML_SLIDESHOW}_{$turns}{literal}:active:hover{
                                                {/literal}{if ($slideShow.COULEUR_CTA|count_characters)==7 }{literal}
                                                        background-color:{/literal}{$slideShow.COULEUR_TYPO}{literal};
                                                        border-color:{/literal}{$slideShow.COULEUR_CTA}{literal};
                                                        color:{/literal}{$slideShow.COULEUR_CTA}{literal};
                                                {/literal}{/if}{literal}
                                                }
                                            {/literal}
                                        {else}

                                        {/if}
                                        {literal}
                                        </style>
                                    {/literal}
                                    <div class="texts {$aData.ID_HTML_SLIDESHOW}_{$turns} {if $slideShow.position.centre neq ''}centre{/if} {if $slideShow.position.milieu neq ''}middle{/if}"
                                         style="
                                         {if $slideShow.position.top neq ''}top:{$slideShow.position.top};{/if}
                                         {if $slideShow.position.bottom neq ''}bottom:{$slideShow.position.bottom};{/if}
                                         {if $slideShow.position.left neq ''}left:{$slideShow.position.left};{/if}
                                         {if $slideShow.position.right neq ''}right:{$slideShow.position.right};{/if}
                                         ">
                                        {if $slideShow.PAGE_ZONE_MULTI_TITRE}<p class="title {$slideShow.COULEUR_TYPO}" data-text="{$slideShow.PAGE_ZONE_MULTI_TITRE|escape}">{$slideShow.PAGE_ZONE_MULTI_TITRE|escape}</p>{/if}
                                        {if $slideShow.PAGE_ZONE_MULTI_TITRE2}<p class="parttitle {$slideShow.COULEUR_TYPO}">{$slideShow.PAGE_ZONE_MULTI_TITRE2|escape}</p>{/if}
                                        <ul class="actions" {if ($aData.PRIMARY_COLOR|count_characters)==7 } data-off="background:{$aData.SECOND_COLOR}; color:#ffffff;" data-hover="border:4px solid {$aData.SECOND_COLOR}; background:#ffffff; color:{$aData.SECOND_COLOR};" {/if}>
                                            {if $slideShow.PAGE_ZONE_MULTI_LABEL neq '' &&  $slideShow.PAGE_ZONE_MULTI_URL neq ''}
                                                <li {if $slideShow.COULEUR_CTA}style="{$slideShow.COULEUR_CTA}font-weight:bold;"{else}class="greyLavender"{/if}><a  id="a_{$aData.ID_HTML_SLIDESHOW}_{$turns}" {if $slideShow.COULEUR_CTA || $slideShow.COULEUR_TYPO}style="{$slideShow.COULEUR_CTA}{$slideShow.COULEUR_TYPO}"{/if}href="{urlParser url=$slideShow.PAGE_ZONE_MULTI_URL}" target="{if $slideShow.PAGE_ZONE_MULTI_MODE eq 2}_blank{else}_self{/if}"
                                                                                                                                                                    {gtm action='Click' data=$aData datasup=['value'=>$slideShow.PAGE_ZONE_MULTI_ORDER,'eventLabel'=>$slideShow.MEDIA_ALT|cat:'::'|cat:$slideShow.PAGE_ZONE_MULTI_LABEL] idMulti=$slideShow._sync}
                                                                                                                                                                    >{$slideShow.PAGE_ZONE_MULTI_LABEL}</a></li>
                                                {/if}
                                                {if $slideShow.PAGE_ZONE_MULTI_LABEL2 neq '' &&  $slideShow.PAGE_ZONE_MULTI_URL2 neq ''}
                                                <li {if $slideShow.COULEUR_CTA}style="{$slideShow.COULEUR_CTA}font-weight:bold;"{else}class="greyLavender"{/if}><a  id="a_{$aData.ID_HTML_SLIDESHOW}_{$turns}" {if $slideShow.COULEUR_CTA || $slideShow.COULEUR_TYPO}style="{$slideShow.COULEUR_CTA}{$slideShow.COULEUR_TYPO}"{/if} href="{urlParser url=$slideShow.PAGE_ZONE_MULTI_URL2}" target="{if $slideShow.PAGE_ZONE_MULTI_MODE2 eq 2}_blank{else}_self{/if}"
                                                                                                                                                                    {gtm action='Click' data=$aData datasup=['value'=>$slideShow.PAGE_ZONE_MULTI_ORDER,'eventLabel'=>$slideShow.MEDIA_ALT|cat:'::'|cat:$slideShow.PAGE_ZONE_MULTI_LABEL2] idMulti=$slideShow._sync}
                                                                                                                                                                    >{$slideShow.PAGE_ZONE_MULTI_LABEL2}</a></li>
                                                {/if}
                                        </ul>
                                    </div>
                                    <div class="videoWrapper">
                                        {*if $slideShow.URL_CLIC neq ''*}
                                        {*	<a href="{$slideShow.URL_CLIC}" target="{$slideShow.TARGET_CLIC}" 	{gtm action='Click' data=$aData datasup=['value'=>$slideShow.PAGE_ZONE_MULTI_ORDER,'eventLabel'=>$slideShow.MEDIA_ALT] idMulti=$slideShow._sync}> *}
                                        {*/if*}
                                        {$slideShow.VIDEOS}
                                        {if ($aData.PRIMARY_COLOR|count_characters)==7 }
                                            <style type="text/css">
                                                {literal}
                                                    .vjs-default-skin .vjs-tech{ background-color: #ffffff; }
                                                    .vjs-default-skin { color: #ffffff; background-color: #ffffff; }
                                                    .vjs-default-skin .vjs-play-progress,
                                                    .vjs-default-skin .vjs-volume-level {  {/literal}{if ($aData.SECOND_COLOR|count_characters)==7 } background:{$aData.SECOND_COLOR}; {else}  background-color: rgba(0,0,0,1);  {/if} {literal} }
                                                    .vjs-default-skin .vjs-control-bar{ {/literal}{if ($aData.SECOND_COLOR|count_characters)==7 } background:{$aData.SECOND_COLOR}; {else}  background: rgba(0,0,0,1);  {/if} {literal} }
                                                    .vjs-default-skin .vjs-big-play-button:before { {/literal}{if ($aData.SECOND_COLOR|count_characters)==7 } color:{$aData.SECOND_COLOR}; {else}  color: rgba(0,0,0,1);  {/if} {literal} }
                                                    .vjs-default-skin .vjs-control:before { {/literal}{if ($aData.SECOND_COLOR|count_characters)==7 } color:{$aData.SECOND_COLOR}; {else}  color: rgba(0,0,0,1);  {/if} {literal} }
                                                    .vjs-default-skin .vjs-current-time-display,
                                                    .vjs-default-skin .vjs-remaining-time-display,
                                                    .vjs-default-skin .vjs-duration-display,
                                                    .vjs-default-skin .vjs-time-divider{ {/literal}{if ($aData.SECOND_COLOR|count_characters)==7 } color:{$aData.SECOND_COLOR}; {else}  color: rgba(0,0,0,1);  {/if} {literal} }
                                                    .vjs-default-skin .vjs-control-bar { font-size: 113%; background-color:#ffffff; }
                                                {/literal}
                                            </style>
                                        {/if}

                                        {*if $slideShow.URL_CLIC neq ''*}
                                        {*	</a> *}
                                        {*/if*}
                                    </div>
                                </div>
                            </div>
                        {/if}
                    {/if}
                    {if $slideShow.PAGE_ZONE_MULTI_VALUE eq 'FLASH'}
                        {if $slideShow.MEDIA_PATH3 neq '' || $slideShow.MEDIA_PATH5 neq ''}
                            <div class="col" id="{$aData.ID_HTML_SLIDESHOW}_{$turns}">
                                <div class="inner">
                                    <figure>
                                        <object type="application/x-shockwave-flash" data="{$slideShow.MEDIA_PATH3}" width="1440" height="500">
                                            <param name="movie" value="{$slideShow.MEDIA_PATH3}" />
                                            <param name="wmode" value="transparent" />
                                            <param name="flashVars" value="{Pelican::$config['VARIABLE_XML_SLIDESHOW']}={$slideShow.MEDIA_PATH4}" />
                                            {if $slideShow.PAGE_ZONE_MULTI_URL4 neq ''}
                                                <a href="{urlParser url=$slideShow.PAGE_ZONE_MULTI_URL4}" target="_self"
                                                   {gtm action='Click' data=$aData datasup=['value'=>$slideShow.PAGE_ZONE_MULTI_ORDER,'eventLabel'=>$slideShow.MEDIA_ALT] idMulti=$slideShow._sync}
                                                   >
                                                {/if}
                                                <figure>
                                                    <img src="{$slideShow.MEDIA_PATH5}" width="1480" height="500" alt="{$slideShow.MEDIA_ALT5|escape}" />
                                                </figure>
                                                <p>{$slideShow.PAGE_ZONE_MULTI_TEXT2}</p>
                                                {if $slideShow.PAGE_ZONE_MULTI_URL4 neq ''}
                                                </a>
                                            {/if}
                                        </object>
                                    </figure>
                                </div>
                            </div>
                        {/if}
                    {/if}
                    {if $slideShow.PAGE_ZONE_MULTI_VALUE eq 'HTML5'}
                        {if $slideShow.PAGE_ZONE_MULTI_TEXT neq '' }
                            <div class="col" id="{$aData.ID_HTML_SLIDESHOW}_{$turns}">
                                <div style="text-align:center; padding:50px; background:#999;">
                                    {$slideShow.PAGE_ZONE_MULTI_TEXT}
                                </div>
                            </div>
                        {/if}
                    {/if}
                {/foreach}
            </div>
        </div>
    </div>

    {if $aData.TEMPLATE_PAGE_ID == Pelican::$config['TEMPLATE_PAGE']['SHOWROOM_ACCUEIL']}
    </div>
{/if}

<div class="body">
{/if}

<div id="DDM_showroom">
    <div class="parent" id="trancheParent" style="display: none;"></div>
</div>

{if $aData.ZONE_LANGUETTE == 1}
    <section class="showroom row of6 clslanguette showroom">
        <div class="caption addmore folder" data-off="border:4px solid {if ($aData.SECOND_COLOR|count_characters)==7}{$aData.SECOND_COLOR}{else}#afadc3{/if};color:{if ($aData.SECOND_COLOR|count_characters)==7}{$aData.SECOND_COLOR}{else}#afadc3{/if};padding:8px;" data-hover="border:6px solid {if ($aData.SECOND_COLOR|count_characters)==7}{$aData.SECOND_COLOR}{else}#afadc3{/if};color:{if ($aData.SECOND_COLOR|count_characters)==7}{$aData.SECOND_COLOR}{else}#afadc3{/if};padding:6px;" data-toggle="{if $aData.ZONE_LANGUETTE_TEXTE_CLOSE}{$aData.ZONE_LANGUETTE_TEXTE_CLOSE}{else}{'VOIR_MOINS'|t}{/if}"><a {if ($aData.PRIMARY_COLOR|count_characters)==7 } class="col span2" {/if} href="#trancheParent" {gtm action="Push" data=$aData datasup=['eventLabel' => 'LANGUETTE_SLIDESHOW']}><span style="color: inherit;">{if $aData.ZONE_LANGUETTE_TEXTE_OPEN}{$aData.ZONE_LANGUETTE_TEXTE_OPEN}{else}{t("Voir plus")}{/if}</span></a></div>
    </section>
{/if}