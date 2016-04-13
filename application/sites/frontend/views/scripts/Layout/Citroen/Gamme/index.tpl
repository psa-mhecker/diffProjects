{if $aZone.ZONE_WEB}
{if $aVehiculesC}
<div class="sliceNew mastercarsReviewDesktop">
<section class="mastercars small clsgamme">
	<div class="headPart">
      <h2>
       {'GAMME_LIGNE_C_FO'|t}
      </h2>
      <div class="panelActuHead">
        <a href="{urlParser url=$aZone.ZONE_URL}" class="activeRoll">{$aZone.ZONE_LABEL|escape}</a>
      </div>
    </div>
    <div class="slider" {gtmjs type='slider' data=$aData action="click" datasup=['eventCategory' => 'SlideshowRange::C']}>
        <div class="row of4 collapse">
            {foreach from=$aVehiculesC item=vehicule name=vehiculesC}
            <div class="col columns item zoner bg">
			  <div class="itemIner">
                <figure>
	                    <img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/16-9.png" data-original="{Pelican::$config.MEDIA_HTTP}{$vehicule.MEDIA_PATH}" width="182" height="103" alt="{$vehicule.MEDIA_ALT|escape}" />
	                    <noscript><img src="{Pelican::$config.MEDIA_HTTP}{$vehicule.MEDIA_PATH}" width="182" height="103" alt="{$vehicule.MEDIA_ALT|escape}" /></noscript>
                </figure>
                <h3 class="parttitle"><a href="{urlParser url=$vehicule.PAGE_CLEAR_URL}" {if $vehicule.MODE_OUVERTURE_SHOWROOM==2}target="_blank"{/if} {gtm action="Click" data=$aParams datasup=['eventCategory' => 'SlideshowRange::C', 'eventLabel' => $vehicule.VEHICULE_LABEL, 'value' => $smarty.foreach.vehiculesC.iteration]}>{$vehicule.VEHICULE_LABEL}</a></h3>

                {if $vehicule.VEHICULE_DISPLAY_CASH_PRICE == 1}
                    <p>{'A_PARTIR'|t}<em> <strong>{$vehicule.PRIX}{if $aZoneGalerieNiveau2.ZONE_TITRE6 neq ''}*{/if}{$vehicule.VEHICULE_CASH_PRICE_TYPE|t}</strong> </em></p>
                {/if}
				  <ul class="menu clean">
				  {if is_array($vehicule.CTA) && sizeof($vehicule.CTA)>0}
					 {foreach from=$vehicule.CTA item=ctagamme name=vehiculecta}
						{$ctagamme}
					 {/foreach}	
				{/if}			
				 </ul>
				</div>
            </div>
            {/foreach}
        </div>
    </div>
</section>
</div>
{/if}


{if $aVehiculesDS}
<br>
<div class="sliceNew mastercarsReviewDesktop ds">
  <section class="mastercars small clsgamme">
  <div class="headPart">
      <h2>
       {'GAMME_LIGNE_DS_FO'|t}
      </h2>
    </div>
    <div class="slider" {gtmjs type='slider' data=$aData action="click" datasup=['eventCategory' => 'SlideshowRange::DS']}>

        <div class="row of4 collapse">
            {foreach from=$aVehiculesDS item=vehicule name=vehiculesDS}
             <div class="col columns item zoner bg">
			  <div class="itemIner">
                <figure>
                        <img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/16-9.png" data-original="{Pelican::$config.MEDIA_HTTP}{$vehicule.MEDIA_PATH}" width="182" height="103" alt="{$vehicule.MEDIA_ALT|escape}" />
                        <noscript><img src="{Pelican::$config.MEDIA_HTTP}{$vehicule.MEDIA_PATH}" width="182" height="103" alt="{$vehicule.MEDIA_ALT|escape}" /></noscript>
                </figure>
                <h3 class="parttitle"><a href="{urlParser url=$vehicule.PAGE_CLEAR_URL}" {if $vehicule.MODE_OUVERTURE_SHOWROOM==2}target="_blank"{/if} {gtm action="Click" data=$aParams datasup=['eventCategory' => 'SlideshowRange::DS', 'eventLabel' => $vehicule.VEHICULE_LABEL, 'value' => $smarty.foreach.vehiculesDS.iteration]}>{$vehicule.VEHICULE_LABEL}</a></h3>
                {if $vehicule.VEHICULE_DISPLAY_CASH_PRICE == 1}
                    <p>{'A_PARTIR'|t} <em><strong>{$vehicule.PRIX}{if $aZoneGalerieNiveau2.ZONE_TITRE6 neq ''}*{/if}{$vehicule.VEHICULE_CASH_PRICE_TYPE|t}</strong></em></p>
                {/if}
					 <ul class="menu clean">
					 {if is_array($vehicule.CTA) && sizeof($vehicule.CTA)>0}
						 {foreach from=$vehicule.CTA item=ctagamme name=vehiculecta}
							{$ctagamme}
						 {/foreach}	
					{/if}	  
					</ul>
				 </div>
            </div>
            {/foreach}
        </div>
    </div>
	</section>
</div>
{/if}


{if $aVehiculesUtilitaires}
<div class="sliceNew mastercarsReviewDesktop">
  <section class="mastercars small clsgamme">
    <div class="headPart">
      <h2>
        {'GAMME_VEHICULE_UTILITAIRE_FO'|t}
      </h2>
    </div>
    <div class="slider" {gtmjs type='slider' data=$aData action="click" datasup=['eventCategory' => 'SlideshowRange::Vans']}>
       <div class="row of4 collapse">
            {foreach from=$aVehiculesUtilitaires item=vehicule name=vehiculesUtilitaires}
            {if $smarty.foreach.vehiculesUtilitaires.iteration <= 4}
             <div class="col columns item zoner bg">
			 <div class="itemIner">
                <figure>
                        <img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/16-9.png" data-original="{Pelican::$config.MEDIA_HTTP}{$vehicule.MEDIA_PATH}" width="182" height="103" alt="{$vehicule.MEDIA_ALT|escape}" />
                        <noscript><img src="{Pelican::$config.MEDIA_HTTP}{$vehicule.MEDIA_PATH}" width="182" height="103" alt="{$vehicule.MEDIA_ALT|escape}" /></noscript>
                </figure>
                <h3 class="parttitle"><a href="{urlParser url=$vehicule.PAGE_CLEAR_URL}" {if $vehicule.MODE_OUVERTURE_SHOWROOM==2}target="_blank"{/if} {gtm action="Click" data=$aParams datasup=['eventCategory' => 'SlideshowRange::Vans', 'eventLabel' => $vehicule.VEHICULE_LABEL, 'value' => $smarty.foreach.vehiculesUtilitaires.iteration]}>{$vehicule.VEHICULE_LABEL}</a></h3>
                {if $vehicule.VEHICULE_DISPLAY_CASH_PRICE == 1}
                    <p>{'A_PARTIR'|t}  <em><strong>{$vehicule.PRIX}{if $aZoneGalerieNiveau2.ZONE_TITRE23 neq ''}**{/if}{$vehicule.VEHICULE_CASH_PRICE_TYPE|t}</strong> </em></p>
                {/if}
					 <ul class="menu clean">
					 {if is_array($vehicule.CTA) && sizeof($vehicule.CTA)>0}
						 {foreach from=$vehicule.CTA item=ctagamme name=vehiculecta}
							{$ctagamme}
						 {/foreach}	
					{/if}	  
				 </ul>
            </div>
			</div>
            {/if}
            {/foreach}
            {if $aMediaPushUtil.MEDIA_PATH}
               <a href="{urlParser url=$aZone.ZONE_URL2}"{if $aZone.ZONE_PARAMETERS==2} target="_blank"{/if} class="col bonus">
                    <figure>
                        <img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/4-3.png" data-original="{Pelican::$config.MEDIA_HTTP}{$aMediaPushUtil.MEDIA_PATH}" width="220" height="320" alt="{$aMediaPushUtil.MEDIA_ALT|escape}" />
                        <noscript><img src="{Pelican::$config.MEDIA_HTTP}{$aMediaPushUtil.MEDIA_PATH}" width="220" height="320" alt="{$aMediaPushUtil.MEDIA_ALT|escape}" /></noscript>
                    </figure>
                </a>
            {/if}

       </div>
    </div>
    
    {if $aZoneGalerieNiveau2.ZONE_TITRE5 eq 'ROLL'}
        <small class="legal">
            <a class="texttip" href="#cashBuyInVP">{$aZoneGalerieNiveau2.ZONE_TITRE6|escape}</a>
        </small>
        <div class="legal layertip" id="cashBuyInVP">
            {$aZoneGalerieNiveau2.ZONE_TITRE6|escape}<br/>
            {if $sVisuelML neq ''}<img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/16-9.png" data-original="{$sVisuelML}" width="270" height="152" style="width:270px !important;height:152px !important;min-width:270px !important;max-width:270px !important;float:left"/>{/if}{if $aZoneGalerieNiveau2.ZONE_TEXTE4} <div class="zonetexte">{$aZoneGalerieNiveau2.ZONE_TEXTE4}</div> {/if}
        </div>
          {if $aZoneGalerieNiveau2.ZONE_TITRE23 neq ''}
            <small class="legal"><a class="texttip" href="#cashBuyInVU">{$aZoneGalerieNiveau2.ZONE_TITRE23|escape}</a></small>
             <div class="legal layertip" id="cashBuyInVU">
                {$aZoneGalerieNiveau2.ZONE_TITRE23|escape}<br/>
                {if $sVisuelML neq ''}<img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/16-9.png" data-original="{$sVisuelML}" width="270" height="152" style="width:270px !important;height:152px !important;min-width:270px !important;max-width:270px !important;float:left"/>{/if}{if $aZoneGalerieNiveau2.ZONE_TEXTE4} <div class="zonetexte">{$aZoneGalerieNiveau2.ZONE_TEXTE4}</div> {/if}
            </div>
        {/if}
    {else if $aZoneGalerieNiveau2.ZONE_TITRE5 eq 'TEXT'}
        <div {if $sVisuelML neq ''}style="min-height:119px"{/if}>
            <small class="legal">
                {$aZoneGalerieNiveau2.ZONE_TITRE6|escape}<br/>
                  {if $aZoneGalerieNiveau2.ZONE_TITRE23 neq ''}{$aZoneGalerieNiveau2.ZONE_TITRE23|escape}<br/>{/if}
                {if $sVisuelML neq ''}<img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/16-9.png" data-original="{$sVisuelML}" width="270" height="152" style="width:270px !important;height:152px !important;min-width:270px !important;max-width:270px !important;float:left"/>{/if}{if $aZoneGalerieNiveau2.ZONE_TEXTE4} <div class="zonetexte">{$aZoneGalerieNiveau2.ZONE_TEXTE4}</div> {/if}
            </small>
         </div>
    {else if $aZoneGalerieNiveau2.ZONE_TITRE5 eq 'POP_IN'}
        {* Mentions légales : particuliers *}
        {* Rétro-compatibilité récupération de l'URL (CPW-3381) *}
        {if $aZoneGalerieNiveau2.ZONE_TITRE22}
            {assign var="legalUrl" value=$aZoneGalerieNiveau2.ZONE_TITRE22}
        {else}
            {assign var="legalUrl" value=$aMentionsLegales.PAGE_CLEAR_URL}
        {/if}
        {if $aZoneGalerieNiveau2.ZONE_TITRE6 neq ''}<small class="legal"><a class="simplepop" href="#creditBuyPopIn">{$aZoneGalerieNiveau2.ZONE_TITRE6|escape}</a></small>{/if}
        <script type="text/template" id="creditBuyPopIn">
            <div style="min-width:450px" >
            <iframe src="{$legalUrl}?popin=1" width="450px"></iframe>
            </div>
        </script>
        
        {* Mentions légales : utilitaires *}
        {if $aZoneGalerieNiveau2.ZONE_TITRE23 neq ''}<small class="legal"><a class="simplepop" href="#creditBuyPopInUtilitaire">{$aZoneGalerieNiveau2.ZONE_TITRE23|escape}</a></small>
        <script type="text/template" id="creditBuyPopInUtilitaire">
            <div style="min-width:450px" >
            <iframe src="{$aZoneGalerieNiveau2.ZONE_TITRE24}?popin=1" width="450px"></iframe>
            </div>
        </script>
        {/if}
    {/if}
</section>
</div>
{/if}

{/if}
