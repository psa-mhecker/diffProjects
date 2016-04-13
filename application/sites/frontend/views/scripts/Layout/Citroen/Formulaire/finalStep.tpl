<div>

    {if $aZone.ZONE_TITRE8 neq '' || $aZone.ZONE_TEXTE2 neq ''}
        <section class="form {if ($aData.PRIMARY_COLOR|count_characters)!=7 }row of3{/if}showroom">
            <div class="{if ($aData.PRIMARY_COLOR|count_characters)!=7 }col span2{/if}">
                {if $aZone.ZONE_TITRE8 neq ''}<br/><p><em style="color:{if ($aData.PRIMARY_COLOR|count_characters)==7 }{$aData.PRIMARY_COLOR}{else}#f0780a{/if}" class="parttitle final">{$aZone.ZONE_TITRE8}</em></p>{/if}
                {if $aZone.ZONE_TEXTE2 neq ''}<span style="text-align: left">{$aZone.ZONE_TEXTE2|nl2br|replace:"#MEDIA_HTTP#":Pelican::$config.MEDIA_HTTP}</span>{/if}
            </div>
        </section>
    {/if}

    {if $car neq "" && $dealer neq ""}
        <div class="row of2{if ($aData.PRIMARY_COLOR|count_characters)==7 } showroom{/if}" style="margin-bottom: 0px;">
            {if $car neq ""}
                <div class="col">
                    <div class="parttitle final" data-step="1"{if ($aData.PRIMARY_COLOR|count_characters)==7 } style="color:{$aData.PRIMARY_COLOR};"{/if}>{'VOTRE_MODELE'|t}</div>
                    <div class="row of3">
                        <div class="col">
                            <figure>
                                <img class="lazy" src="{$mediaPath}" data-original="{$mediaPath}" width="224" height="126" alt="{$car.VEHICULE_LABEL}" style="display: inline-block;">
                                <noscript><img src="{$mediaPath}" alt="{$car.VEHICULE_LABEL}" /></noscript>
                                <figcaption>{$car.VEHICULE_LABEL}</figcaption>
                            </figure>
                        </div>
                    </div>

                </div>
            {/if}
            {if $dealer neq ""}
                <div class="col">
                    <div class="parttitle final" data-step="2"{if ($aData.PRIMARY_COLOR|count_characters)==7 } style="color:{$aData.PRIMARY_COLOR};"{/if}>{'VOTRE_POINT_VENTE'|t}</div>
                    <div class="chosen">
                        <div>{$dealer.name}</div>
                        <small>
                            {$dealer.address}<br>
                            {'TEL'|t}{$dealer.phone}<br>
                        </small>
                    </div>
                </div>
            {/if}
            {if $car neq "" && $bActiveAddToSelection && $pelican_config.SITE.INFOS.SITE_ACTIVATION_MON_PROJET == 1}
                <div class="new col addSelectionForm">
                    <a class="button" rel="{$carId}_{$iOrder}">{'AJOUT_SELECTION'|t}</a>
                </div>
            {/if}
            {if $dealer neq "" && $pelican_config.SITE.INFOS.SITE_ACTIVATION_MON_PROJET == 1}
                <div class="col bookmarkForm">
                    <a class="button " href="javascript://" rel="{$dealer.id}">{'AJOUT_CONCESSION'|t}</a>
                </div>
            {/if}
        </div>
        <input type="hidden" id="parcours" name="parcours" value="{$parcours}"/>
    {/if}
    {if $sSharer}
        <div class="caption finalshare">
            {$aZone.ZONE_TITRE9}{$sSharer}
        </div>
    {/if}
    {if $aCta|@sizeof > 0}
        <div class="discover">
            <div class="parttitle">{$titleCTA}</div>
            <p>{$texteCTA}</p>
            <ul class="cta red row of{$aCta|@sizeof}">
                {section name=cta loop=$aCta}
                    {if $aCta[cta].OUTIL}
                        {$aCta[cta].OUTIL}
                    {else}
                        <li class='blue cta'><a class="button"  href="{urlParser url=$aCta[cta].PAGE_ZONE_MULTI_URL}" target="_{$aCta[cta].PAGE_ZONE_MULTI_VALUE}">{$aCta[cta].PAGE_ZONE_MULTI_LABEL}</a></li>
                    {/if}
                {/section}
            </ul>
        </div>
    {/if}
    {if $aZone.ZONE_PARAMETERS eq 1}
        <div class="caption back">
            <ul class="actions sep">
                <li class="grey home"><a href="/">{'RETOUR_ACCUEIL'|t}</a></li>
            </ul>
        </div>
    {/if}
    {if $aZone.ZONE_TITRE5 eq 'ROLL'}
        <small class="caption legal">
            <a class="texttip" href="#cashBuyIn">{$aZone.ZONE_TITRE6}</a>
        </small>
        <div class="legal layertip" id="cashBuyIn">
            {if $sVisuelML neq ''}<img class="lazy" src="{$sVisuelML}" width="270" height="152" style="width:270px !important;height:152px !important;min-width:270px !important;max-width:270px !important;float:left"/>{/if}{if $aZone.ZONE_TEXTE4} <div class="zonetexte">{$aZone.ZONE_TEXTE4}</div> {/if}
        </div>
    {elseif $aZone.ZONE_TITRE5 eq 'TEXT'}
        <div {if $sVisuelML neq ''}style="min-height:119px"{/if}>
            <small class="caption legal">
                {if $aZone.ZONE_TITRE6 neq ''}{$aZone.ZONE_TITRE6}<br>{/if}
                {if $sVisuelML neq ''}<img class="lazy" src="{$sVisuelML}" width="270" height="152" style="width:270px !important;height:152px !important;min-width:270px !important;max-width:270px !important;float:left"/>{/if}{if $aZone.ZONE_TEXTE4} <div class="zonetexte">{$aZone.ZONE_TEXTE4}</div> {/if}
            </small>
        </div>
    {elseif $aZone.ZONE_TITRE5 eq 'POP_IN' && $aMentionsLegales.PAGE_CLEAR_URL neq ''}
    {if $aZone.ZONE_TITRE6 neq ''}<small class="caption legal"><a class="simplepop" href="#creditBuyPopIn">{$aZone.ZONE_TITRE6}</a></small>{/if}
        <script type="text/template" id="creditBuyPopIn">
            <div style="min-width:450px" >
                <iframe src="{$aMentionsLegales.PAGE_CLEAR_URL}?popin=1" width="450px"></iframe>
            </div>
        </script>
    {/if}
    {literal}
    <script type="text/template" class="bookmark">
        <div class="prompt" id="prompt<%= id %>">
            <input type="hidden" id="SAVChosen" name="SAVChosen" value="<%= id %>" />
            <p>{/literal}{'REPLACE_FAV_PDV'|t}{literal}</p>
            <p>
                {/literal}{'ASK_REPLACE_FAV_PDV'|t}{literal}<%=name %>
            </p>
            <ul class="actions clean">
                <li class="grey"><a href="javascript://">{/literal}{'CANCEL'|t}{literal}</a></li>
                <li class="green"><a href="javascript://">{/literal}{'CONFIRM'|t}{literal}</a></li>
            </ul>
        </div>
    </script>
    {/literal}

    
      
{literal}
    <script type="text/javascript">
        // Si l'iframe a été ouverte depuis un bloc en mode déployé, on ajoute /Outspread à la fin de pageName et virtualPageURL
        var isDeployed = '{/literal}{$isDeployed}{literal}';
        var suffix ='';
        if(isDeployed == "1" ){
            suffix ="/Outspread";
        }

        var event = {
            'event': 'updatevirtualpath',
            'pageName': 'Summary/Request_{/literal}{$formTypeFull.FORM_TYPE_GTM_ID}{literal}' + suffix,
            'virtualPageURL': '/Request_{/literal}{$formTypeFull.FORM_TYPE_GTM_ID}{literal}/ThankYou' + suffix,
            {/literal}{foreach from=$aGtm[$formTypeFull.FORM_TYPE_GTM_ID] key=var_name item=foo2 name=gtm}
            '{$var_name}' : '{$foo2}',
            {/foreach}{literal}
        };
        
        {/literal}
        {if $formOriginCtaPerso}
        event["customDimension1"] = "Perso";
        {/if}
        {literal}


        var dataLayer = dataLayer || [];
        dataLayer.push(event);
    </script>
    {/literal}

</div>