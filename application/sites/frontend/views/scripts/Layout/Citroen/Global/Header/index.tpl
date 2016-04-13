{literal}
    <!-- Twitter  SDK -->
    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
    <!-- Facebook SDK -->
    <div id="fb-root"></div>
{/literal}

{literal}
<script type="text/javascript" src="{/literal}{Pelican::$config.DESIGN_HTTP}{literal}/js/TweenMax.min.js"></script>
{/literal}
<input type="hidden" id="fb-locale" value="{$sCulture}">
{if $aCookies.ZONE_TITRE4 == "1"}
    {if $bDisplayCookiesLayer == true || $aParams.preview == 1}
      <div class="cookieBarReviewDesktop sliceNew" style="display: block;">
      <div class="globalWrapper">
        <div class="row cookieBarReviewDesktopRow">
		  <a href="#" class="cross accept" data-close=".cookies" onClick="acceptCookies();sendGTM('Cookies','Close','Cross');"> </a>
            <div class="columns column_80 textContent">
              <div class="valign">
                <div>
                   {$aCookies.ZONE_TEXTE}
				   {if !empty($aCookies.ZONE_TITRE3) && !empty($aCookies.ZONE_URL)}
              <a href="#" class="activeRoll">
               {$aCookies.ZONE_TITRE3}
              </a>
                    {/if}
                </div>
              </div>
          </div>
        </div>
      </div>
    </div>
    {/if}
{/if}
<div class="sliceHeadReviewDesk sliceNew">
    <header  id="{$aParams.ID_HTML}"  class="globalWrapper">
            <nav class="row headerWrapperLvl1">
                <div class="columns column_25 headLogo">
                    <a href="/" class="homeButton">

                        {if $bTplHome && $aConfig.ZONE_TITRE24 }
                            <h1>CITROËN <span>{$aConfig.ZONE_TITRE24}</span></h1>
                        {elseif $aConfig.ZONE_TITRE24}
                            CITROËN <span>{$aConfig.ZONE_TITRE24}</span>
                        {/if}
                    </a>
                </div>

                {if $navigationPush || ($navigationShoppingTools|@sizeof > 0 && $shoppingTools.ZONE_TITRE neq '')}
                    <div class="columns column_75 headerLvl1">
                        <ul class="easyTab-nav" style="margin-top: -1px;">
                            {foreach from=$navigationPush item=navigation key=k name=navigation}
                                <li><a  {if $navigation.MEDIA_ID}class="findConcession activeRoll"{/if} {gtm action="Push::Showroom" data=$aParams datasup=['eventCategory' => 'Header', 'eventLabel' => $navigation.PAGE_ZONE_MULTI_LABEL] } href="{urlParser url=$navigation.PAGE_ZONE_MULTI_URL}"{if $navigation.PAGE_ZONE_MULTI_OPTION==2} target="_blank"{/if}>
                                    {$navigation.PAGE_ZONE_MULTI_LABEL}
                                </a></li>
                            {/foreach}
                            {if $navigationShoppingTools|@sizeof > 0 && $shoppingTools.ZONE_TITRE neq ''}
                                {assign var='buttonName' value=' '|explode:$shoppingTools.ZONE_TITRE}
                                <li class="shopping folder" data-group="nav" data-folder-speed="250" data-overlay="true" {gtmjs type='expandBar' action="Expand|" data=$aParams datasup=['eventCategory' => 'Header::ShoppingTools', 'eventLabel' => $shoppingTools.ZONE_TITRE]}>
                                    <a href="#layerTools"  data-tab="tab{$k}">
                                        {foreach from=$buttonName item=OneButton name=OneButton}

                                            {if $smarty.foreach.OneButton.first}
                                                <span>{$OneButton}</span>
                                            {else}
                                                {$OneButton}
                                            {/if}
                                        {/foreach}
                                    </a>
                                </li>
                            {/if}
                             {if $siteLangues|sizeof>1}
                                <li>
								   <div class="langWrapper">
                                    {foreach from=$siteLangues item=site key=k}
                                     
                                        {if $site.LANGUE_ID==$session.LANGUE_ID}
                                            <a class="lang activeRoll" data-tab="tabLang" href="#" style="width: 116px;">
                                                {$site.LANGUE_TRANSLATE}
                                            </a>
											 <div class="tabContainer">
                                                <div class="tabIn tabLang" style="display: none;">
                                                    <ul>
										
                                        {else}
                                           
                                                        <li {gtmjs type='toggle' action="Language|" data=$aParams datasup=['eventCategory'=>'Header' , 'eventLabel'=>$site.LANGUE_TRANSLATE]}><a  href="{urlParser url={$pageLangue.{$site.LANGUE_ID}.PAGE_CLEAR_URL}}" class="activeRoll">{$site.LANGUE_TRANSLATE}</a></li>
                                            
                                            	{/if}
                                       
										{/foreach}
									      </ul>
                                                </div>
                                            </div>
									</div>
                                </li>
                            {/if}
                            <li class="citroenLogoWrapper">
                                <a class="citroenLogo activeRoll" href="/">
                                    <span>Citroen</span>
                                </a>
                            </li>
                        </ul>
                        {if $navigationShoppingTools|@sizeof > 0 && $shoppingTools.ZONE_TITRE neq ''}
                            <div class="tabContainerWrapper">
                                <div class="tabContainer easyTab-container">
                                    {foreach from=$navigationShoppingTools key=k item=navigation1 name=navigation1}
                                        {if $navigation1.ssmenu|sizeof >= 2}
                                            <div class="tabIn tab{$k}" style="display: none;margin-top: -11px;">
                                                <a href="#" class="cross"></a>
                                                <div class="row navTabWrapper">
                                                    {foreach from=$navigationShoppingTools item=navigation1 name=navigation1}
                                                        {if $navigation1.ssmenu|sizeof >= 2}
                                                            <div class="columns column_33">
                                                              <span>{$navigation1.menu.lib}</span>
                                                                <ul>
                                                                    {foreach from=$navigation1.ssmenu item=navigation2 name=navigation2}
                                                                        {if $smarty.foreach.navigation2.iteration<=7}
                                                                            <li>
                                                                                <a {gtm action="Showroom" data=$aParams datasup=['eventCategory' => 'Header::ShoppingTools', 'eventLabel' => $navigation2.lib]} href="{urlParser url=$navigation2.url}"{if $navigation2.param=='2'} target="{$navigation2.target}"{/if}>{$navigation2.lib}</a>
                                                                            </li>
                                                                        {/if}
                                                                    {/foreach}
                                                                </ul>
                                                            </div>
                                                        {/if}
                                                    {/foreach}
                                                </div>
                                            </div>
                                        {/if}
                                    {/foreach}
                                </div>
                            </div>
                        {/if}
                    </div>
                {/if}
            </nav>

            <nav class="row headerWrapperLvl2">
                {if $navigationSite}

                    <ul class="easyTab-nav" >
                        {foreach from=$navigationSite item=navigation name=navigation key=k}
                            {if $smarty.foreach.navigation.iteration<=7}
                                {strip}
                                    {if $navigation.n1.urlExterne != ''}
                                        <li{if $navigation.n1.id==$iNav1} class="on"{/if}>
                                            <a href="{if $navigation.n1.urlExterne}{urlParser url=$navigation.n1.urlExterne}{else}{urlParser url=$navigation.n1.url}{/if}" {if $navigation.n1.urlExterneTarget == 2}target="_blank"{/if} {gtmjs type='expandBar' action="Showroom|" data=$aParams datasup=['eventCategory' => 'Header::Menu', 'eventLabel' => $navigation.n1.lib]}>
                                               {$navigation.n1.lib}
                                            </a>
                                        </li>
                                    {elseif $navigation.n1.ouvertureDirect == 1}
                                        <li{if $navigation.n1.id==$iNav1} class="on"{/if}>
                                            <a  href="{urlParser url=$navigation.n1.url}" {if $navigation.n1.urlExterneTarget == 2}target="_blank"{/if} {gtmjs type='expandBar' action="Showroom|" data=$aParams datasup=['eventCategory' => 'Header::Menu', 'eventLabel' => $navigation.n1.lib]}>
                                                {$navigation.n1.lib}
                                            </a>
                                        </li>
                                    {else}
                                        <li{if $navigation.n1.id==$iNav1} class="on"{/if}>
                                            <a  data-tab="tab{$navigation.n1.id}"  data-overlay="true" data-folder-speed="250" href="#" target="{$navigation.n1.target}" {gtmjs type='expandBar' action="Display::ExpandBar|" data=$aParams datasup=['eventCategory' => 'Header::Menu', 'eventLabel' => {$navigation.n1.lib|html_entity_decode:1:'UTF-8'}]}>
                                               {$navigation.n1.lib}
                                            </a>
                                        </li>
                                    {/if}
                                {/strip}
                            {/if}
                        {/foreach}
                        {if $activationRecherche.ZONE_TITRE2==1 || $activationRecherche.ZONE_TITRE2==3}
                            <li>
                                <a href="#" class="search">
                                    <span>Recherche</span>
                                </a>
                            </li>
                        {/if}
                    </ul>

                    {if $activationRecherche.ZONE_TITRE2==1 || $activationRecherche.ZONE_TITRE2==3}
                        <div class="searchBar">
                            <form class="search" novalidate action="{$recherche.PAGE_CLEAR_URL}" id="searchHeader" {gtmjs type='searchText' action='Search::Keyword'  data=$aParams  datasup=['eventCategory'=>'Header::SearchBar']}>
                                <input class="searchBarComponent" type="text" name="search"{if $activationRecherche.ZONE_TITRE3==0} id='searchText' class="autocomplete-off"{/if} placeholder="{'QUE_RECHERCHEZ_VOUS'|t}" />
                                <!-- <button type="submit" name="register" id='searchSubmit' value="Rechercher" />-->
                                <input class="searchBarComponent" type="submit" name="register" id='searchSubmit' value="{'FORM_RECHERCHE_VALUE'|t}" spellcheck="false" />
                                <!--button type="submit"></button>-->
                            </form>
                        </div>
                    {/if}

                    <div class="tabContainerWrapper">
                        <div class="tabContainer easyTab-container" >
                            {if $navigationSite}
                            {foreach from=$navigationSite item=navigation1 name=navigation1}
                            {if $smarty.foreach.navigation1.iteration<=7}
                            {if $navigation1.n1.expand=='1' && $navigation1.n2|sizeof > 0}
                            {* expand vehicule *}
                            <div class="tabIn tab{$navigation1.n1.id}" style="display: none;">
                                <a href="#" class="cross"></a>
                                <div class="row">
                                    <div class="sliceNew sliceNewVehiclesDesk sliceNewVehiclesExpandDesk">
                                        <div class="layer">
                                            <div class="box">
                                                <div class="tabbed">
                                                    <div class="tabs"  {gtmjs type='tabs' action='DisplayTab|' data=$aParams  datasup=['eventCategory' => 'ExpandBar']}></div>
                                                    {foreach from=$navigation1.n2 item=navigation2 name=navigation2 key=key}

                                                    {if !$expandGamme.CACHER_DS}
                                                        {assign var='itemParLigne' value=3}
                                                    {else}
                                                        {assign var='itemParLigne' value=4}
                                                    {/if}

                                                    {assign var='itemParLigneBusiness' value=4}

                                                    {if  !$tab_for_c_and_ds || ( $navigation2.vehiculeGamme != {Pelican::$config.VEHICULE_GAMME.GAMME_LIGNE_C} &&  $navigation2.vehiculeGamme != {Pelican::$config.VEHICULE_GAMME.GAMME_LIGNE_DS}  )}
                                                    {if $tab_for_c_and_ds}
                                                    {assign var='tab_for_c_and_ds' value='0'}
                                                </div>
                                                {/if}
                                                <div class="tab">
                                                    {assign var='close_tab' value='1'}
                                                    {if $navigation2.n3Actif  && !$tab_for_c_and_ds && ( ($navigation2.vehiculeGamme == {Pelican::$config.VEHICULE_GAMME.GAMME_LIGNE_C} || $navigation2.vehiculeGamme == {Pelican::$config.VEHICULE_GAMME.GAMME_LIGNE_DS} ))}

                                                        {assign var='tab_for_c_and_ds' value='1'}

                                                        {if !$expandGamme.TITLE_TAB_C_AND_DS }
                                                            {assign var='pagePath' value= Pelican_Cache::fetch("Frontend/Page/Path",[{$navigation2.id},{$session.LANGUE_ID}])}

                                                            {$navigation2.lib = $pagePath.1.1}
                                                        {else}
                                                            {$navigation2.lib= $expandGamme.TITLE_TAB_C_AND_DS}
                                                        {/if}
                                                    {/if}
                                                    {if $navigation2.n3Actif}
                                                        <p class="subtitle tabtitle"><span >{$navigation2.lib}</span></p>
                                                    {/if}
                                                    {/if}

                                                    {if $navigation2.n3Actif}
                                                        {* VEHICULE VP *}
                                                        {if $navigation2.vehiculeGamme==Pelican::$config.VEHICULE_GAMME.GAMME_LIGNE_C || $navigation2.vehiculeGamme==Pelican::$config.VEHICULE_GAMME.GAMME_LIGNE_DS}
                                                            {* VEHICULE LIGNE C *}
                                                            {if $navigation2.vehiculeGamme==Pelican::$config.VEHICULE_GAMME.GAMME_LIGNE_C}
                                                                <div class="new row ">
                                                                    <div class="new col row collapse cars vehicles {if !$expandGamme.CACHER_DS}with-ds{/if}">
                                                                        <div class="columns {if $expandGamme.CACHER_DS}column_100{else}column_75{/if} vehicle">
                                                                            <p class="subtitle"><span  data-gtm="eventGTM|onglet|click|Bloc automatique-569-expand-Citroën -ouverture|Citroën |0|||">Citroën </span></p>
                                                                            <div class="new row">
                                                                                {assign var='it' value=1}
                                                                                {foreach from=$navigation2.categ item=categ}
                                                                                    {assign var='i' value=1}
                                                                                    {foreach from=$navigation2.n3 item=navigation3 name=navigation3 key=$key2}
                                                                                        {if $categ.VEHICULE_CATEG_LABEL == $navigation3.VEHICULE_CATEG_LABEL}
                                                                                            <!-- ITEM ELEMENT. classe "new" sur tous les 4 éléments -->
                                                                                            <div class="{if $it%$itemParLigne == 1 }new {/if}  columns {if $expandGamme.CACHER_DS}column_25{else}column_33{/if} zoner bg nocategory">
                                                                                                <!-- NOUVEAU CONTENEUR ".bundle" niveau supplémentaire -->
                                                                                                <div class="bundle">
                                                                                                    <a href="{urlParser url=$navigation3.PAGE_CLEAR_URL}" target="{if $navigation3.MODE_OUVERTURE_SHOWROOM==1}_self{elseif $navigation3.MODE_OUVERTURE_SHOWROOM==2}_blank{/if}" {gtm action="Showroom::Citroen::{$navigation3.VEHICULE_LABEL}" data={$aParams} datasup=['eventCategory'=>'ExpandBar::NewCar', 'eventLabel' => {$navigation3.VEHICULE_LABEL} ]} >
                                                                                                        <figure>
                                                                                                            <img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/16-9.png" data-original="{Pelican::$config.MEDIA_HTTP}{$navigation3.VEHICULE_PATH_FORMAT}" width="224" height="126" alt="{$navigation3.MEDIA_ALT|escape}" style="display: inline-block;">
                                                                                                            <noscript><img src="{Pelican::$config.MEDIA_HTTP}{$navigation3.VEHICULE_PATH_FORMAT}" width="224" height="126" alt="{$navigation3.MEDIA_ALT|escape}" /></noscript>
                                                                                                            {strip}
                                                                                                                <figcaption>
                                                                                                                    <strong>{$navigation3.VEHICULE_LABEL}</strong>

                                                                                                                    {if $expandGamme.SHOW_PRICE && $navigation3.PRIX}

                                                                                                                        {if $aConfig.ZONE_TITRE31 == 1 && $navigation3.PRICE_MTCFG neq ''}
                                                                                                                            {'EXP_A_PARTIR'|t}
                                                                                                                        {else}
                                                                                                                            {'EXP_A_PARTIR'|t}
                                                                                                                        {/if}
                                                                                                                        <em>

                                                                                                                            {if  $navigation3.PRICE_MTCFG neq ''}
                                                                                                                                {if $aConfig.ZONE_TITRE27 neq ''}{assign var='sSeparateurDeci' value=$aConfig.ZONE_TITRE27}{else}{assign var='sSeparateurDeci' value=' '}{/if}
                                                                                                                                {if $aConfig.ZONE_TITRE28 neq ''}{assign var='sSeparateurMill' value=$aConfig.ZONE_TITRE28}{else}{assign var='sSeparateurMill' value=','}{/if}
                                                                                                                                {if $aConfig.ZONE_TITRE29 == 0}{assign var='sZeroApresVirgule' value=$aConfig.ZONE_TITRE29}{else}{assign var='sZeroApresVirgule' value=2}{/if}


                                                                                                                                {if  $aConfig.ZONE_TITRE30 == 0}{if  $aConfig.ZONE_TITRE25 == 0} €{else} {$aConfig.ZONE_TITRE26}{/if}{/if}
                                                                                                                                {$navigation3.PRICE_MTCFG|number_format:{$sZeroApresVirgule}:{$sSeparateurMill}:{$sSeparateurDeci}}
                                                                                                                                {if  $aConfig.ZONE_TITRE30 == 1}{if  $aConfig.ZONE_TITRE25 == 0} €{else} {$aConfig.ZONE_TITRE26}{/if}{/if}
                                                                                                                                {if $navigation3.VEHICULE_CASH_PRICE_TYPE && $aConfig.ZONE_TITRE32 neq '0'}
                                                                                                                                    {$navigation3.VEHICULE_CASH_PRICE_TYPE|t}
                                                                                                                                    {if $navigation2.mentionsLegales}
                                                                                                                                        *
                                                                                                                                    {/if}

                                                                                                                                {elseif $aConfig.ZONE_TITRE32 eq '0'}
                                                                                                                                    {'CASH_PRICE_HT'|t}
                                                                                                                                    {if $navigation2.mentionsLegales}
                                                                                                                                        *
                                                                                                                                    {/if}
                                                                                                                                {/if}

                                                                                                                            {else}
                                                                                                                                {$navigation3.PRIX}
                                                                                                                                {if $navigation3.VEHICULE_CASH_PRICE_TYPE}
                                                                                                                                    {$navigation3.VEHICULE_CASH_PRICE_TYPE|t}
                                                                                                                                    {if $navigation2.mentionsLegales}
                                                                                                                                        *
                                                                                                                                    {/if}
                                                                                                                                {/if}

                                                                                                                            {/if}



                                                                                                                        </em>
                                                                                                                        {if $aConfig.ZONE_TITRE31 == 0 && $navigation3.PRICE_MTCFG neq ''}<br/>{'EXP_A_PARTIR'|t}{/if}
                                                                                                                    {/if}
                                                                                                                </figcaption>
                                                                                                            {/strip}
                                                                                                        </figure>
                                                                                                    </a>
                                                                                                    {if count($navigation3.EXPAND_CTA)>0}
                                                                                                        <ul class="menu">
                                                                                                            {section name=cta loop=$navigation3.EXPAND_CTA }
																											{if !is_array($navigation3.EXPAND_CTA[cta])}
                                                                                                                {$navigation3.EXPAND_CTA[cta]}
																												{/if}
                                                                                                            {/section}
                                                                                                        </ul>
                                                                                                    {/if}
                                                                                                </div>
                                                                                                <!-- /NOUVEAU CONTENEUR ".bundle" niveau supplémentaire -->
                                                                                            </div>
                                                                                            <!-- /ITEM ELEMENT. classe "new" sur tous les 4 éléments -->
                                                                                            {assign var='i' value=0}
                                                                                            {if $it%$itemParLigne == 0}
                                                                                                {assign var='it' value=0}
                                                                                            {/if}
                                                                                            {assign var='it' value=$it+1}
                                                                                        {/if}



                                                                                    {/foreach}
                                                                                {/foreach}


                                                                            </div>
                                                                            {if $navigation2.PUSH_CONTENU_ANNEXE|count }
                                                                                <ul class="tools-expand">
                                                                                    {foreach from=$navigation2.PUSH_CONTENU_ANNEXE item=push}

                                                                                        <li>

                                                                                            <a href="{urlParser url=$push.PAGE_MULTI_URL}" {gtm action="Showroom::AnnexePush" data=$aParams datasup=['eventCategory' => 'ExpandBar::NewCar', 'eventLabel' => $push.PAGE_MULTI_LABEL]  idMulti=$push._sync} {if $push.PAGE_MULTI_OPTION=='2'}target="_blank"{/if}>

                                                                                                <span>{$push.PAGE_MULTI_LABEL}</span>
                                                                                            </a>
                                                                                        </li>
                                                                                    {/foreach}
                                                                                </ul>
                                                                            {/if}
                                                                            {if $navigation2.mentionsLegales}
                                                                                <div class="legal">{$navigation2.mentionsLegales}</div>
                                                                            {/if}
                                                                        </div>
                                                                        {* VEHICULE LIGNE DS *}
                                                                        {elseif $navigation2.vehiculeGamme==Pelican::$config.VEHICULE_GAMME.GAMME_LIGNE_DS}
                                                                        <!-- NOUVEAU CONTENEUR niveau supplémentaire POUR VEHICULES DS-->
                                                                        {if !$expandGamme.CACHER_DS}
                                                                            <div class="columns column_25 ds vehicle">
                                                                                <p class="subtitle"><span  data-gtm="eventGTM|onglet|click|Bloc automatique-569-expand-Citroën -ouverture|Citroën |0|||">DS </span></p>
                                                                                <div class="row">
                                                                                    {foreach from=$navigation2.n3 item=navigation3 name=navigation3 key=$key2}
                                                                                        <!-- ITEM ELEMENT. classe "new" sur chaque élément -->
                                                                                        <div class="columns column_100 zoner bg nocategory">
                                                                                            <!-- NOUVEAU CONTENEUR ".bundle" niveau supplémentaire -->
                                                                                            <div class="bundle">
                                                                                                <a href="{urlParser url=$navigation3.PAGE_CLEAR_URL}" {gtm action="Showroom::Ds::{$navigation3.VEHICULE_LABEL}" data=$aParams datasup=['eventCategory' => 'ExpandBar::NewCar', 'eventLabel' => $navigation3.VEHICULE_LABEL]} target='{if $navigation3.MODE_OUVERTURE_SHOWROOM==1}_self{elseif $navigation3.MODE_OUVERTURE_SHOWROOM==2}_blank{/if}'>
                                                                                                <figure>
                                                                                                    <img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/16-9.png" data-original="{Pelican::$config.MEDIA_HTTP}{$navigation3.VEHICULE_PATH_FORMAT}" width="224" height="126" alt="{$navigation3.MEDIA_ALT|escape}" style="display: inline-block;">
                                                                                                    <noscript><img src="{Pelican::$config.MEDIA_HTTP}{$navigation3.VEHICULE_PATH_FORMAT}" width="224" height="126" alt="{$navigation3.MEDIA_ALT|escape}" /></noscript>

                                                                                                    {strip}
                                                                                                        <figcaption>
                                                                                                            <strong>{$navigation3.VEHICULE_LABEL}</strong>

                                                                                                            {if $expandGamme.SHOW_PRICE && $navigation3.PRIX}

                                                                                                                {'EXP_A_PARTIR'|t}
                                                                                                                <em>
                                                                                                                    {if  $navigation3.PRICE_MTCFG neq ''}
                                                                                                                        {$navigation3.PRICE_MTCFG}{if  $aConfig.ZONE_TITRE25 == 0} €{else} {$aConfig.ZONE_TITRE26}{/if}
                                                                                                                    {else}
                                                                                                                        {$navigation3.PRIX}
                                                                                                                    {/if}
                                                                                                                    {if $navigation3.VEHICULE_CASH_PRICE_TYPE}
                                                                                                                        {$navigation3.VEHICULE_CASH_PRICE_TYPE|t}
                                                                                                                        {if $navigation2.mentionsLegales}
                                                                                                                            *
                                                                                                                        {/if}
                                                                                                                    {/if}
                                                                                                                </em>
                                                                                                            {/if}
                                                                                                        </figcaption>
                                                                                                    {/strip}
                                                                                                </figure>
                                                                                                </a>
                                                                                                 {if count($navigation3.EXPAND_CTA)>0}
                                                                                                    <ul class="menu">
                                                                                                        {section name=cta loop=$navigation3.EXPAND_CTA}
																										{if !is_array($navigation3.EXPAND_CTA[cta])}
                                                                                                            {$navigation3.EXPAND_CTA[cta]}
																											{/if}
                                                                                                        {/section}
                                                                                                    </ul>
                                                                                                {/if}

                                                                                            </div>
                                                                                            <!-- /NOUVEAU CONTENEUR ".bundle" niveau supplémentaire -->
                                                                                        </div>
                                                                                        <!-- /ITEM ELEMENT. classe "new" sur chaque élément -->
                                                                                    {/foreach}
                                                                                </div>
                                                                                {if $navigation2.PUSH_CONTENU_ANNEXE|count }
                                                                                    <ul class="tools-expand">
                                                                                        {foreach from=$navigation2.PUSH_CONTENU_ANNEXE item=push}

                                                                                            <li>
                                                                                                <a href="{urlParser url=$push.PAGE_MULTI_URL}" {gtm action="Showroom::AnnexePush" data=$aParams datasup=['eventCategory' => 'ExpandBar::NewCar', 'eventLabel' => $push.PAGE_MULTI_LABEL]  idMulti=$push._sync} {if $push.PAGE_MULTI_OPTION=='2'} target="_blank"{/if}>
                                                                                                    <span>{$push.PAGE_MULTI_LABEL}</span>
                                                                                                </a>
                                                                                            </li>
                                                                                        {/foreach}
                                                                                    </ul>
                                                                                {/if}
                                                                                {if $navigation2.mentionsLegales}
                                                                                    <div class="legal">{$navigation2.mentionsLegales}</div>
                                                                                {/if}
                                                                            </div>
                                                                        {/if}
                                                                        <!-- /NOUVEAU CONTENEUR niveau supplémentaire POUR VEHICULES DS-->
                                                                    </div>

                                                                </div>
                                                            {/if}

                                                        {else}
                                                            {* Utilitaire *}
                                                            <div class="row">
                                                                <div class="row collapse cars vehicles">
                                                                    {if $navigation2.categ|sizeof > 0}
                                                                        {assign var='it' value=1}
                                                                        {foreach from=$navigation2.categ item=categ}
                                                                            {assign var='i' value=1}
                                                                            <div class="vehicle">
                                                                                {foreach from=$navigation2.n3 item=navigation3 name=navigation3}
                                                                                    {assign var='cds' value=1}
                                                                                        {if $categ.VEHICULE_CATEG_LABEL == $navigation3.VEHICULE_CATEG_LABEL}
                                                                                            <div class="{if $it%$itemParLigneBusiness == 1}new {/if} columns column_25 zoner bg  nocategory">
                                                                                                <div class="bundle">
                                                                                                    <a href="{urlParser url=$navigation3.PAGE_CLEAR_URL}" {gtm action="Showroom::Citroen::{$navigation3.VEHICULE_LABEL}" data=$aParams datasup=['eventCategory' => 'ExpandBar', 'eventLabel' => $navigation3.VEHICULE_LABEL]} target='{if $navigation3.MODE_OUVERTURE_SHOWROOM==1}_self{elseif $navigation3.MODE_OUVERTURE_SHOWROOM==2}_blank{/if}'>
                                                                                                    <figure>
                                                                                                        <img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/16-9.png" data-original="{Pelican::$config.MEDIA_HTTP}{$navigation3.VEHICULE_PATH_FORMAT}" width="224" height="126" alt="{$navigation3.MEDIA_ALT|escape}" />
                                                                                                        <noscript><img src="{Pelican::$config.MEDIA_HTTP}{$navigation3.VEHICULE_PATH_FORMAT}" width="224" height="126" alt="{$navigation3.MEDIA_ALT|escape}" /></noscript>

                                                                                                        {strip}
                                                                                                            <figcaption>
                                                                                                                <strong>{$navigation3.VEHICULE_LABEL}</strong>
                                                                                                                {if $navigation3.PRIX}
                                                                                                                    {'EXP_A_PARTIR'|t}
                                                                                                                    <em>
                                                                                                                        {if  $navigation3.PRICE_MTCFG neq ''}
                                                                                                                            {$navigation3.PRICE_MTCFG} {if  $aConfig.ZONE_TITRE25 == 0}€{else} {$aConfig.ZONE_TITRE26}{/if}
                                                                                                                        {else}
                                                                                                                            {$navigation3.PRIX}
                                                                                                                        {/if}
                                                                                                                        {if $navigation3.VEHICULE_CASH_PRICE_TYPE}
                                                                                                                            {$navigation3.VEHICULE_CASH_PRICE_TYPE|t}
                                                                                                                            {if $navigation2.mentionsLegales}
                                                                                                                                {if $navigation2.vehiculeGamme == 'GAMME_VEHICULE_UTILITAIRE'}
                                                                                                                                    **
                                                                                                                                {else}
                                                                                                                                    *
                                                                                                                                {/if}
                                                                                                                            {/if}
                                                                                                                        {/if}
                                                                                                                    </em>
                                                                                                                {/if}
                                                                                                            </figcaption>
                                                                                                        {/strip}
                                                                                                    </figure>
                                                                                                    </a>
                                                                                                     {if count($navigation3.EXPAND_CTA)>0}
                                                                                                        <ul class="menu">
                                                                                                            {section name=cta loop=$navigation3.EXPAND_CTA}
                                                                                                                {if !is_array($navigation3.EXPAND_CTA[cta])}
                                                                                                                {$navigation3.EXPAND_CTA[cta]}
                                                                                                                {/if}
                                                                                                            {/section}
                                                                                                        </ul>
                                                                                                    {/if}
                                                                                                </div>
                                                                                            </div>
                                                                                            {assign var='i' value=0}
                                                                                            {if $it%$itemParLigneBusiness == 1}
                                                                                                {assign var='it' value=1}
                                                                                            {/if}
                                                                                            {assign var='it' value=$it+1}
                                                                                        {/if}
                                                                                {/foreach}
                                                                            </div>
                                                                        {/foreach}
                                                                    {else}
                                                                        {assign var='it' value=1}
                                                                        <div class="vehicle">
                                                                            {foreach from=$navigation2.n3 item=navigation3 name=navigation3}
                                                                                <div class="{if $it%$itemParLigneBusiness == 1}new {assign var='it' value=1} {/if} columns column_25 zoner bg  nocategory">
                                                                                    <div class="bundle">
                                                                                        <a href="{urlParser url=$navigation3.PAGE_CLEAR_URL}" {gtm action="Showroom::Citroen::{$navigation3.VEHICULE_LABEL}" data=$aParams datasup=['eventCategory' => 'ExpandBar', 'eventLabel' => $navigation3.VEHICULE_LABEL]} target='{if $navigation3.MODE_OUVERTURE_SHOWROOM==1}_self{elseif $navigation3.MODE_OUVERTURE_SHOWROOM==2}_blank{/if}'>
                                                                                        <figure>
                                                                                            <img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/16-9.png" data-original="{Pelican::$config.MEDIA_HTTP}{$navigation3.VEHICULE_PATH_FORMAT}" width="224" height="126" alt="{$navigation3.MEDIA_ALT|escape}" />
                                                                                            <noscript><img src="{Pelican::$config.MEDIA_HTTP}{$navigation3.VEHICULE_PATH_FORMAT}" width="224" height="126" alt="{$navigation3.MEDIA_ALT|escape}" /></noscript>

                                                                                            {strip}
                                                                                                <figcaption>
                                                                                                    <strong>{$navigation3.VEHICULE_LABEL}</strong>
                                                                                                    {if $navigation3.PRIX}
                                                                                                        <br/>{'EXP_A_PARTIR'|t}
                                                                                                        <em>
                                                                                                            {$navigation3.PRIX}
                                                                                                            {if $navigation3.VEHICULE_CASH_PRICE_TYPE}
                                                                                                                {$navigation3.VEHICULE_CASH_PRICE_TYPE|t}
                                                                                                                {if $navigation2.mentionsLegales}
                                                                                                                    {if $navigation2.vehiculeGamme == 'GAMME_VEHICULE_UTILITAIRE'}
                                                                                                                        **
                                                                                                                    {else}
                                                                                                                        *
                                                                                                                    {/if}
                                                                                                                {/if}
                                                                                                            {/if}
                                                                                                        </em>
                                                                                                    {/if}
                                                                                                </figcaption>
                                                                                            {/strip}
                                                                                        </figure>
                                                                                        </a>
                                                                                    </div>
                                                                                </div>
                                                                                {assign var='it' value=$it+1}
                                                                            {/foreach}
                                                                        </div>
                                                                    {/if}
                                                                    {if $navigation2.mentionsLegales}
                                                                        <div class="legal">{$navigation2.mentionsLegales}</div>
                                                                    {/if}
                                                                </div>
                                                            </div>
                                                        {/if}
                                                    {else}
                                                        {* Business lien *}
                                                         <p class="subtitle tabtitle">
                                                            <a href="{if $navigation2.urlExterne!=''}{urlParser url=$navigation2.urlExterne}{else}{urlParser url=$navigation2.url}{/if}"{if $navigation2.urlExterne!='' && $navigation2.urlExterneTarget=='2'} target="_blank"{/if}>
                                                                <span >{$navigation2.lib}</span>
                                                            </a>
                                                         </p>
                                                    {/if}
                                                    {if $close_tab  && !$tab_for_c_and_ds}

                                                    {assign var='close_tab' value='0'}
                                                </div>
                                                {/if}
                                                {/foreach}
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>



                        </div>
                        {else}
                                <div class="tabIn tab{$navigation1.n1.id}" style="display: none;">
                                    <a href="#" class="cross"></a>
                                   <div class="sliceNew sliceServicesExpandDesk">
                                   {if $navigation1.n1.MEDIA_PATH_EXPAND != ""}<img src="{$navigation1.n1.MEDIA_PATH_EXPAND}" alt="" class="illu-expand">{/if}
                                      <div class="row gutter">
                                        {assign var=previous value=0}
                                        {assign var=line value=0}
                                        {foreach from=$navigation1.n2 item=navigation2 name=navigation2 key=key}
                                             <div class="columns column_33">
                                                {if $navigation2.urlExterne != ''}
                                                    <a class="buttonExpandN2"  href="{urlParser url=$navigation2.urlExterne}" {if $navigation2.urlExterneTarget == 2}target="_blank"{/if} {gtm action="Showroom" data=$aParams datasup=['eventCategory' => 'ExpandBar', 'eventLabel' => $navigation2.lib]}>
                                                {else}
                                                    <a class="buttonExpandN2"  href="{urlParser url=$navigation2.url}" {if $navigation2.urlExterneTarget == 2}target="_blank"{/if} {gtm action="Showroom" data=$aParams datasup=['eventCategory' => 'ExpandBar', 'eventLabel' => $navigation2.lib]}>
                                                {/if}
                                                     {$navigation2.lib}
                                                 </a>
                                             </div>
                                         {/foreach}

                                  </div>
                                </div>
                            </div>
                        {/if}
                        {/if}
                        {/foreach}
                        {/if}
                    </div>
                </div>
            {/if}
        </nav>
    </div>

</header>

<div class="breadcrumbDesk sliceNew">
{if $bTplHome && $aConfiguration.FIL_DARIANE_HOME}
	<div class="path full">
	 <div class="globalWrapper">
        {if $user && $user->isLogged()}
        <a href="{urlParser url=$sURLPageConnexion}" class="text activeRoll">{'BONJOUR'|t} {$user->getFirstname()} {$user->getLastname()} {'SOUHAITEZ_VOUS_CONFIGURER_SELECTION'|t}</a>
        {else}
        <a href="{urlParser url=$sURLPageConnexion}" class="text activeRoll"><span>{'BONJOUR'|t}</span> {'SOUHAITEZ_VOUS_CONFIGURER_SELECTION'|t}</a>
        {/if}
       {if $aConfiguration.FIL_DARIANE_HOME}<h1>{$aConfiguration.FIL_DARIANE_HOME}</h1>{/if}
		</div>
		</div>
		 {elseif !$bTpl404 && !$bTplHome}
		 <div class="path full">
	 <div class="globalWrapper">
	  {if $user && $user->isLogged()}
        <a href="{urlParser url=$sURLPageConnexion}" class="text activeRoll">{'BONJOUR'|t} {$user->getFirstname()} {$user->getLastname()} {'SOUHAITEZ_VOUS_CONFIGURER_SELECTION'|t}</a>
        {else}
        <a href="{urlParser url=$sURLPageConnexion}" class="text activeRoll"><span>{'BONJOUR'|t}</span> {'SOUHAITEZ_VOUS_CONFIGURER_SELECTION'|t}</a>
        {/if}
	    <ul>
            {foreach from=$filAriane item=navigation name=navigation}
            {if $smarty.foreach.navigation.last}
            <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><strong><span itemprop="title">{$navigation.PAGE_TITLE_BO}</span></strong></li>
            {else}
            <li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a class="activeRoll" itemprop="url" {if $navigation.PAGE_URL_EXTERNE != '' and $navigation.PAGE_URL_EXTERNE_MODE_OUVERTURE==2}target='_blank'{/if}href="{urlParser url=$navigation.PAGE_CLEAR_URL}" {gtm action="Click" data=$aParams datasup=['eventCategory' => 'Breadcrumb', 'eventLabel' => $navigation.PAGE_TITLE_BO]}><span itemprop="title">{$navigation.PAGE_TITLE_BO}</span></a></li>
            {/if}
            {/foreach}
        </ul>
	 </div>
		</div>
{/if}
    </div>

<div class="body">
    {if $displayLanguettePro eq true || $displayLanguetteClient eq true}
        <div class="sliceNew sliceStripperDesk" style="padding-top:10px;padding-bottom:20px;">
            <div class="sticker stripper">
                {if $displayLanguettePro eq true}
                    <div id="strip1" class="strip fixed">
                        <div class="cont">
                            <div class="closer"></div>
                            <form name="fFormLanguetteClient" action="" method="POST">
                                <div class="parttitle">{'ETES_VOUS_CLIENT'|t}</div>
                                <div class="form-group">
                                    <input type="radio" name="isClient" id="isClientFalse" value="0">
                                    <label for="isClientFalse">{'OUI'|t}</label>
                                </div>
                                <div class="form-group">
                                    <input type="radio" name="isClient" id="isClientTrue" value="1">
                                    <label for="isClientTrue">{'NON'|t}</label>
                                </div>
                            </form>
                        </div>
                    </div>
                {/if}
                {if $displayLanguetteClient eq true}
                    <div id="strip2" class="strip fixed">
                        <div class="cont">
                            <div class="closer"></div>
                            <form name="fFormLanguettePro" action="" method="POST">
                                <div class="parttitle">{'ETES_VOUS_PRO_PARTICULIER'|t}</div>
                                <div class="form-group">
                                    <input type="radio" name="isPro" id="isProdFalse" value="0">
                                    <label for="isProdFalse">{'PARTICULIER'|t}</label>
                                </div>
                                <div class="form-group">
                                    <input type="radio" name="isPro" id="isProdTrue" value="1">
                                    <label for="isProdTrue">{'PRO'|t}</label>
                                </div>
                            </form>
                        </div>
                    </div>
                {/if}
            </div>
        </div>
    {/if}
    {if $user && $user->isLogged()}
        <div class="disconnect row of12">
            <a class="text col span2" href="#" >{'DECONNEXION'|t}</a>
        </div>
    {/if}
    {if $zoneInterstitiel.ZONE_WEB}
        <div class="sliceNew sliceInterstitielDesk">
           <div class="intersticiel-popin">
              <div class="intersticiel-content">
                 <div class="closer" {gtm action="Close" data=$aParams datasup=['eventCategory' => 'Interstitial', 'eventLabel' => 'Cross']}></div>
                 <div class="inside-content">
                    {if $zoneInterstitiel.ZONE_URL}
                        <input type="hidden" value="{$zoneInterstitiel.ZONE_URL}" id="InterUrlRedirect">
                        <a href="{urlParser url=$zoneInterstitiel.ZONE_URL}" target="{if $zoneInterstitiel.ZONE_TOOL == 2}_blank{else}_self{/if}" class="zonelink"><span>Intersticiel</span></a>
                    {/if}
                    {if $zoneInterstitiel.MEDIA_ID}
                        <input type="hidden" value="{$zoneInterstitiel.ZONE_TITRE2 * 1000}" id="InterImageDuration">
                        <figure>
                           <span class="roll"></span>
                           <img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/235-100.png" data-original="{"{Pelican::$config.MEDIA_HTTP}{$zoneInterstitiel.MEDIA_PATH}"|format:Pelican::$config.MEDIA_FORMAT_ID.INTERSTITIEL}" width="990" height="434" alt="" style="display: inline-block;">
                            <noscript><img src="{"{Pelican::$config.MEDIA_HTTP}{$zoneInterstitiel.MEDIA_PATH}"|format:Pelican::$config.MEDIA_FORMAT_ID.INTERSTITIEL}" width="990" height="434" alt="" /></noscript>
                        </figure>
                    {elseif $zoneInterstitiel.MEDIA_ID3 && $zoneInterstitiel.MEDIA_ID5}
                        <figure>
                        <object type="application/x-shockwave-flash" data="{$zoneInterstitiel.MEDIA_PATH3}" width="990" height="434" id="InterFlashAnimation">
                        <param name="movie" value="{$zoneInterstitiel.MEDIA_PATH3}" />
                        <param name="wmode" value="transparent" />
                        <param name="flashVars" value="{Pelican::$config['VARIABLE_XML_SLIDESHOW']}={$zoneInterstitiel.MEDIA_PATH4}" />
                            {if $zoneInterstitiel.ZONE_URL2 neq ''}
                            <a href="{urlParser url=$zoneInterstitiel.ZONE_URL2}" target="_self" {gtm action="Click" data=$aParams datasup=['eventCategory' =>
                            'Interstitial', 'eventLabel' => {$zoneInterstitiel.ZONE_TITRE}]}>
                            {/if}
                        <figure>
                        <img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/235-100.png" data-original="{"{Pelican::$config.MEDIA_HTTP}{$zoneInterstitiel.MEDIA_PATH5}"|format:Pelican::$config.MEDIA_FORMAT_ID.INTERSTITIEL}" width="990" height="434" alt="" style="display: inline-block;">
                             </figure>
                             <p>{$zoneInterstitiel.ZONE_TEXTE}</p>
                            {if $zoneInterstitiel.ZONE_URL2 neq ''}
                            </a>
                            {/if}
                        </object>
                        </figure>
                    {elseif $sMediaVideoYoutube}
                    <!-- Url de la video-->
                        {$sMediaVideoYoutube}
                    <!-- Fin Url de la video-->
                    {elseif $zoneInterstitiel.ZONE_TEXTE2}
                        <div class="col">
                        <div style="text-align:center; padding:50px; background:#999;" id="InterHTMLAnimation">
                            {$zoneInterstitiel.ZONE_TEXTE2}
                        </div>
                        </div>
                    {/if}
                    </div>
                    <span class="intersticielpopClose" {gtm action="Close" data=$aParams datasup=['eventCategory' => 'Interstitial', 'eventLabel' => $zoneInterstitiel.ZONE_TITRE]}><span class="intersticielpopCloseEvent">{$zoneInterstitiel.ZONE_TITRE}</span></span>
                 </div>
           </div>
        </div>
    {/if}