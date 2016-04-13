{if $aVehiculesC}
<section class="mastercars row clsgamme small clsmastergamme">
    <h2 class="title">{Pelican::$config.VEHICULE_GAMME_FO.GAMME_LIGNE_C}</h2>
    <div class="slider" {gtmjs type='slider' data=$aData  action = 'Click'  datasup=['eventCategory' => 'SlideshowRange::C']}>
        <div class="row of4">
            {foreach from=$aVehiculesC item=vehicule name=vehiculesC}
            <div class="col item zoner">
                <figure>
                    <img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/16-9.png" data-original="{Pelican::$config.MEDIA_HTTP}{$vehicule.MEDIA_PATH}" width="265" height="150" alt="{$vehicule.MEDIA_ALT|escape}" />
                    <noscript><img src="{Pelican::$config.MEDIA_HTTP}{$vehicule.MEDIA_PATH}" width="265" height="150" alt="{$vehicule.MEDIA_ALT|escape}" /></noscript>
                </figure>
                <h3 class="parttitle">{$vehicule.VEHICULE_LABEL}</h3>
				<p>
                {if $vehicule.VEHICULE_DISPLAY_CASH_PRICE eq 1 }
					{if $vehicule.PRIX}{'A_PARTIR'|t} 
                      <strong>{$vehicule.PRIX}
						{if $vehicule.VEHICULE_CASH_PRICE_TYPE == 'CASH_PRICE_TTC'}
							{'CASH_PRICE_TTC'|t}
						{else}
							   {'CASH_PRICE_HT'|t}
						{/if}
					
					
						{if $aParams.ZONE_TITRE5 neq '' && $aParams.ZONE_TITRE6 neq ''  || $aParams.ZONE_TITRE5 eq '' && $aParams.ZONE_TITRE6 neq ''}*{/if}
					 </strong>
                    {/if}
				{else}
						&nbsp;
				{/if}
				</p>
                <ul class="actions clean">
                    <li class="blue"><a href="{urlParser url=$vehicule.PAGE_CLEAR_URL}" {if $vehicule.MODE_OUVERTURE_SHOWROOM==2}target="_blank"{/if} {gtm action="Click" data=$aParams datasup=['eventCategory' => 'SlideshowRange::C', 'eventLabel' => {'EN_SAVOIR_PLUS'|t}, 'value' => $smarty.foreach.vehiculesC.iteration]}>{'EN_SAVOIR_PLUS'|t}</a></li>
                    {$vehicule.CONFIGURATEUR}
                </ul>
            </div>
            {/foreach}
        </div>
    </div>
</section>
{/if}
{if $aVehiculesDS}
<section id="{$aParams.ID_HTML}" class="mastercars ds small clsgamme clsmastergamme">
    <h2 class="title">{Pelican::$config.VEHICULE_GAMME_FO.GAMME_LIGNE_DS}</h2>
    <div class="slider" {gtmjs type='slider' data=$aParams autolabels='titreTranche,idInterne,profiles'}>
        <div class="row of4">
            {foreach from=$aVehiculesDS item=vehicule name=vehiculesDS}
            <div class="col item zoner" {gtm name='clic_sur_vehicule_ou_en_savoir_plus' data=$aParams labelvars=['%nom du vehicule%'=>$vehicule.VEHICULE_LABEL, '%intitule du lien%'=>'EN_SAVOIR_PLUS'|t]}>
                <figure>
                    <img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/16-9.png" data-original="{Pelican::$config.MEDIA_HTTP}{$vehicule.MEDIA_PATH}" width="265" height="150" alt="{$vehicule.MEDIA_ALT|escape}" />
                    <noscript><img src="{Pelican::$config.MEDIA_HTTP}{$vehicule.MEDIA_PATH}" width="265" height="150" alt="{$vehicule.MEDIA_ALT|escape}" /></noscript>
                </figure>
                <h3 class="parttitle">{$vehicule.VEHICULE_LABEL}</h3>
                <p>
                    {if $vehicule.VEHICULE_DISPLAY_CASH_PRICE eq 1 }
                        {if $vehicule.PRIX}{'A_PARTIR'|t} <strong>{$vehicule.PRIX} 
                            {if $vehicule.VEHICULE_CASH_PRICE_TYPE == 'CASH_PRICE_TTC'}
                                {'CASH_PRICE_TTC'|t}
                            {else}
                                {'CASH_PRICE_HT'|t}
                            {/if}
                        {/if}
                    
                    {if $aParams.ZONE_TITRE5 neq '' && $aParams.ZONE_TITRE6 neq '' || $aParams.ZONE_TITRE5 eq '' && $aParams.ZONE_TITRE6 neq '' && $aMentionsLegales.PAGE_CLEAR_URL neq '' }*{/if}
                    
                        </strong>
                    {else}
                        &nbsp;
                    {/if}
                </p>
                <ul class="actions clean">
                    <li class="pink"><a href="{urlParser url=$vehicule.PAGE_CLEAR_URL}" {if $vehicule.MODE_OUVERTURE_SHOWROOM==2}target="_blank"{/if} {gtm name='clic_sur_vehicule_ou_en_savoir_plus' data=$aParams labelvars=['%nom du vehicule%'=>$vehicule.VEHICULE_LABEL, '%intitule du lien%'=>'EN_SAVOIR_PLUS'|t]}>{'EN_SAVOIR_PLUS'|t}</a></li>
                    {$vehicule.CONFIGURATEUR}
                </ul>
            </div>
            {/foreach}
        </div>
    </div>
</section>
{/if}
{if $aVehiculesUtilitaires}
<section class="mastercars row clsgamme">
    <h2 class="title">{Pelican::$config.VEHICULE_GAMME_FO.GAMME_VEHICULE_UTILITAIRE}</h2>
    <div class="slider" {gtmjs type='slider' data=$aData  action = 'Click'  datasup=['eventCategory' => 'SlideshowRange::Vans']}>
        <div class="row of4">
            {foreach from=$aVehiculesUtilitaires item=vehicule name=vehiculesUtilitaires}
            <div class="col item zoner" >
                <figure>
                    <img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/16-9.png" data-original="{Pelican::$config.MEDIA_HTTP}{$vehicule.MEDIA_PATH}" width="265" height="150" alt="{$vehicule.MEDIA_ALT|escape}" />
                    <noscript><img src="{Pelican::$config.MEDIA_HTTP}{$vehicule.MEDIA_PATH}" width="265" height="150" alt="{$vehicule.MEDIA_ALT|escape}" /></noscript>
                </figure>
                <h3 class="parttitle">{$vehicule.VEHICULE_LABEL}</h3>
				<p>
				{if $vehicule.VEHICULE_DISPLAY_CASH_PRICE eq 1 }
					{if $vehicule.PRIX}{'A_PARTIR'|t} 
                        <strong>{$vehicule.PRIX}
						{if $vehicule.VEHICULE_CASH_PRICE_TYPE == 'CASH_PRICE_TTC'}
							{'CASH_PRICE_TTC'|t}
						{else}
							   {'CASH_PRICE_HT'|t}
						{/if}
					
				
				        {if $aParams.ZONE_TITRE23 neq ''}**{/if}
                    </strong>
                    {/if}
				{else}
					&nbsp;
				{/if}</p>
                <ul class="actions clean">
                    <li class="blue"><a href="{urlParser url=$vehicule.PAGE_CLEAR_URL}" {if $vehicule.MODE_OUVERTURE_SHOWROOM==2}target="_blank"{/if} {gtm action="Click" data=$aParams datasup=['eventCategory' => 'SlideshowRange::Vans', 'eventLabel' => {'EN_SAVOIR_PLUS'|t}, 'value' => $smarty.foreach.vehiculesUtilitaires.iteration]} >{'EN_SAVOIR_PLUS'|t}</a></li>
                    {$vehicule.CONFIGURATEUR}
                </ul>
            </div>
            {/foreach}
        </div>
    </div>
    
    {* Mentions légales *}
    {if $aParams.ZONE_TITRE5 eq 'ROLL'}
        <small class="legal">
            <a class="texttip" href="#cashBuyInVP">{$aParams.ZONE_TITRE6|escape}</a>
        </small>
         <div class="legal layertip" id="cashBuyInVP">
            {$aParams.ZONE_TITRE6|escape}<br/>
            {if $sVisuelML neq ''}<img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/16-9.png" data-original="{$sVisuelML}" width="270" height="152" style="width:270px !important;height:152px !important;min-width:270px !important;max-width:270px !important;float:left"/>{/if}{if $aParams.ZONE_TEXTE4} <div class="zonetexte">{$aParams.ZONE_TEXTE4}</div> {/if}
        </div>
         {* Mentions légales : utilitaires *}
        {if $aParams.ZONE_TITRE23 neq ''}
            <small class="legal"><a class="texttip" href="#cashBuyInVU">{$aParams.ZONE_TITRE23|escape}</a></small>
             <div class="legal layertip" id="cashBuyInVU">
                {$aParams.ZONE_TITRE23|escape}<br/>
                {if $sVisuelML neq ''}<img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/16-9.png" data-original="{$sVisuelML}" width="270" height="152" style="width:270px !important;height:152px !important;min-width:270px !important;max-width:270px !important;float:left"/>{/if}{if $aParams.ZONE_TEXTE4} <div class="zonetexte">{$aParams.ZONE_TEXTE4}</div> {/if}
            </div>
        {/if}
       
    {else if $aParams.ZONE_TITRE5 eq 'TEXT'}
        <div {if $sVisuelML neq ''}style="min-height:119px"{/if}>
            <small class="legal">
                {$aParams.ZONE_TITRE6|escape}<br/>
                {if $aParams.ZONE_TITRE23 neq ''}{$aParams.ZONE_TITRE23|escape}<br/>{/if}
                {if $sVisuelML neq ''}<img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/16-9.png" data-original="{$sVisuelML}" width="270" height="152" style="width:270px !important;height:152px !important;min-width:270px !important;max-width:270px !important;float:left"/>{/if}{if $aParams.ZONE_TEXTE4} <div class="zonetexte">{$aParams.ZONE_TEXTE4}</div> {/if}
            </small>
             
        
        </div>
    {else if $aParams.ZONE_TITRE5 eq 'POP_IN'}
        {* Mentions légales : particuliers *}
        {* Rétro-compatibilité récupération de l'URL (CPW-3381) *}
        {if $aParams.ZONE_TITRE22}
            {assign var="legalUrl" value=$aParams.ZONE_TITRE22}
        {else}
            {assign var="legalUrl" value=$aMentionsLegales.PAGE_CLEAR_URL}
        {/if}
        {if $aParams.ZONE_TITRE6 neq ''}<small class="legal"><a class="simplepop" href="#creditBuyPopIn">{$aParams.ZONE_TITRE6|escape}</a></small>{/if}
        <script type="text/template" id="creditBuyPopIn">
            <div style="min-width:450px" >
            <iframe src="{$legalUrl}?popin=1" width="450px"></iframe>
            </div>
        </script>
        
        {* Mentions légales : utilitaires *}
        {if $aParams.ZONE_TITRE23 neq ''}<small class="legal"><a class="simplepop" href="#creditBuyPopInUtilitaire">{$aParams.ZONE_TITRE23|escape}</a></small>{/if}
        <script type="text/template" id="creditBuyPopInUtilitaire">
            <div style="min-width:450px" >
            <iframe src="{$aParams.ZONE_TITRE24}?popin=1" width="450px"></iframe>
            </div>
        </script>
    {/if}
</section>
{/if}

{if $aAutresVehicules}
<section class="row of6 others">
    <h2 class="col span4 title">{$aParams.ZONE_TITRE|escape}</h2>
	{assign var="counter" value=0}
    {foreach from=$aAutresVehicules item=vehicule name=autresVehicules}
	{if $smarty.foreach.autresVehicules.iteration%2==1}
		{assign var="counter" value=$counter+1}
	{/if}
    <div class="{if $smarty.foreach.autresVehicules.iteration%2==1}new {/if}col span3 row of7 zoner bg"  data-sync="{$aData.ORDER}line{$counter}">
        <figure class="col span3">
        		<a href="{urlParser url=$vehicule.PAGE_CLEAR_URL}" {if $vehicule.MODE_OUVERTURE_SHOWROOM==2}target="_blank"{/if} {gtm action='Push' data=$aParams datasup=['eventLabel'=>{$vehicule.PAGE_TITLE_BO}]}>
            <img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/1-1.png" data-original="{Pelican::$config.MEDIA_HTTP}{$vehicule.MEDIA_PATH}" width="179" height="179" alt="{$vehicule.MEDIA_ALT|escape}" />
            <noscript><img src="{Pelican::$config.MEDIA_HTTP}{$vehicule.MEDIA_PATH}" width="179" height="179" alt="{$vehicule.MEDIA_ALT|escape}" /></noscript>
            </a>
        </figure>
        <div class="col span4">
            <h2 class="parttitle"><a href="{urlParser url=$vehicule.PAGE_CLEAR_URL}" {if $vehicule.MODE_OUVERTURE_SHOWROOM==2}target="_blank"{/if} >{$vehicule.PAGE_TITLE_BO}</a></h2>
            <p>{$vehicule.PAGE_TEXT}</p>
        </div>
    </div>
    {/foreach}
</section>
{/if}