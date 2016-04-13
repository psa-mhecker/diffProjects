{if $aData.ZONE_WEB == 1}
    <div class="sliceNew sliceMosaiqueDesk">
        <section id="{$aData.ID_HTML}" class="{$aData.ZONE_SKIN} foldbyrow simple clsMosaique">
            <div class="sep "></div>

            {if $aData.ZONE_TITRE}
                <h2 class="subtitle" {if ($aData.PRIMARY_COLOR|count_characters) == 7 }style="color:{$aData.PRIMARY_COLOR};"{/if}>
                    {$aData.ZONE_TITRE}
                </h2>
            {/if}
            {if $aData.ZONE_TITRE2}
                <h3 class="parttitle" {if ($aData.SECOND_COLOR|count_characters) == 7 }style="color:{$aData.SECOND_COLOR};"{/if}>
                    {$aData.ZONE_TITRE2}
                </h3>
            {/if}

            {if $aData.ZONE_TEXTE}
                <div class="mgchapo">
                    <p><strong>{$aData.ZONE_TEXTE}</strong></p>
                </div>
            {/if}

            {*
                Création de la mosaique :
                - Création des 16 blocks de titres
                - creation des 16 blocks de contenus

                Affichage de la mosaique :
                - 4 blocks de titres
                - 4 blocks de contenus (correspondant au 4 titres au dessus)

                L'organisation 4*4 par ligne se fait en Javascript.
                Les block de contenus sont remontés en dessous des blocks de titres leurs correspondant en JS.

                Les croix de fermeture des contenus sont ajouté en JS.

                Une class "alt" est ajoutée en JS sur les 2 contenus à droite d'une même ligne
                pour les aligner à droite afin qu'ils soit en dessous de leurs titres.
            *}
            <div class="mosaiqueRow row of12">
                {if $aMosaique|@count >= 4}
                    {assign var='num' value=1}
                    {assign var='new' value='new '}

                    {foreach from=$aMosaique key=k item=lib}
                        {if $k < 16}
                            <div class="{$new}col span3 folder" data-group="simple{$aData.ORDER}">
                                <a data-sync="box{$aData.ORDER}" href="#content{$num++}" class="activeRoll">
                                    <span>{$lib.PAGE_ZONE_MULTI_TEXT}</span>
                                </a>
                            </div>
                            {if $num%4 == 1}
                                {assign var='new' value='new '}
                            {else}
                                {assign var='new' value=''}
                            {/if}
                        {/if}
                    {/foreach}

                    {assign var='num' value=1}

                    {foreach from=$aMosaique key=k item=lib}
                        {if $k < 16}
                            <div id="content{$num++}" class="col span9 cont zonetexte" style="display: none;">
                                <div class="inner">
                                    {$lib.PAGE_ZONE_MULTI_TEXT2|nl2br}
                                </div>
                            </div>
                        {/if}
                    {/foreach}
                {/if}
            </div>

            {if $aData.ZONE_TITRE5 eq 'ROLL'}
                <small class="legal">
                    <a class="texttip" href="#cashBuyIn">{$aData.ZONE_TITRE6|escape}</a>
                </small>
                <div class="legal layertip" id="cashBuyIn">
                    {if $sVisuelML neq ''}
                        <img class="lazy" data-original="{$sVisuelML}" width="270" height="152" style="width:270px !important;height:152px !important;min-width:270px !important;max-width:270px !important;float:left"/>
                    {/if}
                    {if $aData.ZONE_TEXTE4}
                        <div class="zonetexte">
                            {$aData.ZONE_TEXTE4}
                        </div>
                    {/if}
                </div>
            {elseif $aData.ZONE_TITRE5 eq 'TEXT'}
                <div {if $sVisuelML neq ''}style="min-height:119px"{/if}>
                    <small class="caption legal">
                        {if $aData.ZONE_TITRE6 neq ''}
                            {$aData.ZONE_TITRE6|escape}<br>
                        {/if}
                        {if $sVisuelML neq ''}
                            <img class="lazy" data-original="{$sVisuelML}" width="270" height="152" style="width:270px !important;height:152px !important;min-width:270px !important;max-width:270px !important;float:left"/>
                        {/if}
                        {if $aData.ZONE_TEXTE4}
                            <div class="zonetexte">
                                {$aData.ZONE_TEXTE4}
                            </div>
                        {/if}
                    </small>
                </div>
            {elseif $aData.ZONE_TITRE5 eq 'POP_IN' && $aMentionsLegales.PAGE_CLEAR_URL neq ''}
                {if $aData.ZONE_TITRE6 neq ''}
                    <small class="caption legal">
                        <a class="simplepop" href="#creditBuyPopIn">{$aData.ZONE_TITRE6|escape}</a>
                    </small>
                {/if}
                <script type="text/template" id="creditBuyPopIn">
                    <div style="min-width:450px" >
                        <iframe src="{$aMentionsLegales.PAGE_CLEAR_URL}?popin=1" width="450px"></iframe>
                    </div>
                </script>
            {/if}

            {if $aCTA|@sizeof > 0}
                <ul class="actions" style="margin-bottom: 0px;">
                    {foreach from=$aCTA item=lib}
                        {if $lib.OUTIL}
                            {$lib.OUTIL}
                        {else}
                            <li class="cta">
                                <a data-sync="cta{$aData.ORDER}" href="{urlParser url=$lib.PAGE_ZONE_MULTI_URL}" {if $lib.PAGE_ZONE_MULTI_VALUE == 'blank'}target="_blank"{/if} class="buttonTransversalInvert activeRoll">
                                    <span>{$lib.PAGE_ZONE_MULTI_LABEL}</span>
                                </a>
                            </li>
                        {/if}
                    {/foreach}
                </ul>
            {/if}
        </section>
    </div>

    {if $aData.ZONE_LANGUETTE == 1}
        <div class="parent" id="trancheParent" style="display: none;padding:0px;"></div>
        <section class="{$aData.ZONE_SKIN} row of12 foldbyrow clslanguette">
            <div class="caption addmore folder" data-toggle="{if $aData.ZONE_LANGUETTE_TEXTE_CLOSE}{$aData.ZONE_LANGUETTE_TEXTE_CLOSE}{else}{'VOIR_MOINS'|t}{/if}">
                <a class="col span2" href="#trancheParent" {gtm action="Push" data=$aData datasup=['eventLabel' => 'LANGUETTE_MOSAIQUE']}>
                    {if $aData.ZONE_LANGUETTE_TEXTE_OPEN}
                        {$aData.ZONE_LANGUETTE_TEXTE_OPEN}
                    {else}
                        {t("Voir plus")}
                    {/if}
                </a>
            </div>
        </section>
    {/if}
{/if}
