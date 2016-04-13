{if $aVehicules.VEHICULES|@sizeof > 0}
	<a id="carSelectorFiltre"></a>
	<div id="{$aParams.ID_HTML}" class="mastercars-group">
	{foreach from=$aVehicules.VEHICULES item=ligne name=listeLigne}
            {if $ligne.CARS|@sizeof > 0}
              {assign var=cpt value=0}
                            {foreach from=$ligne.CARS item=car name=listeCar}

                                            {if $cpt == 0}

                                                <section class="row mastercars{if $ligne.LABEL eq 'LA_LIGNE_DS'} ds{/if} clsgalerien3v clsgamme clsmastergamme small">
                                                        {if $ligne.LABEL}<h2 class="title">{$ligne.LABEL|t}</h2>{/if}
                                                        <div class="row of4">
                                                        {if $ligne.LABEL == 'LA_LIGNE_DS'}
                                                            {assign var=bt_1 value=pink}
                                                            {assign var=bt_2 value=brown}
                                                        {else}
                                                            {assign var=bt_1 value=blue}
                                                            {assign var=bt_2 value=grey}
                                                        {/if}
                                                  {assign var=cpt value=1}
                                             {/if}


                                            <div class="col item zoner" >
                                                    <figure>
                                                            <img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/16-9.png" data-original="{Pelican::$config.MEDIA_HTTP}{$car.MEDIA_PATH}" width="186" height="139" alt="{$car.VEHICULE_LABEL}" />
                                                            <noscript><img src="{Pelican::$config.MEDIA_HTTP}{$car.MEDIA_PATH}" width="186" height="139" alt="{$car.VEHICULE_LABEL}" /></noscript>
                                                    </figure>
                                                    <h2 class="parttitle">{$car.VEHICULE_LABEL}</h2>
                                                    {if $car.VEHICULE_DISPLAY_CASH_PRICE eq 1 }
                                                    <p>{'A_PARTIR_DE'|t}<strong>{$car.REGLE_PRIX.VEHICULE.CASH_PRICE} {$car.REGLE_PRIX.VEHICULE.CASH_PRICE_TYPE}{if $aData.ZONE_TITRE5}*{/if}</strong></p>
                                                    {else}
                                                    <p>&nbsp;</p>
                                                    {/if}							

                                                    {if $car.FINANCEMENT.VEHICULE_CREDIT_PRICE_NEXT_RENT && $car.VEHICULE_DISPLAY_CREDIT_PRICE 
                                                                    && $car.VEHICULE_USE_FINANCIAL_SIMULATOR}
                                                            <p class="last">{'OU_A_PARTIR_DE'|t}<strong> {$car.FINANCEMENT.VEHICULE_CREDIT_PRICE_NEXT_RENT}</strong></p>							
                                                    {/if}

                                                    {if $car.VEHICULE_CREDIT_PRICE_NEXT_RENT && $car.VEHICULE_DISPLAY_CREDIT_PRICE && !$car.VEHICULE_USE_FINANCIAL_SIMULATOR}
                                                            <p class="last">{'OU_A_PARTIR_DE'|t}<strong> {$car.VEHICULE_CREDIT_PRICE_NEXT_RENT} </strong>{'PER_MONTH'|t}</p>							
                                                    {/if}

                                                    <ul class="actions clean">
                                                            <li class="{$bt_1}"><a href="{urlParser url=$car.URL_DETAIL}" >{'EN_SAVOIR_PLUS'|t}</a></li>
                                                            {$car.CONFIGURATEUR}

                                                    </ul>
                                                    {if $useCompareVehicule}
                                                    <ul class="links">
                                                            <li><a href="javascript://" data-source="CARSELECTOR" class="addtoCompare" id="{$car.VEHICULE_ID}" >{'COMPARE_MODEL'|t}</a></li>
                                                    </ul>
                                                    {/if}
                                            </div>


                                    {/foreach}
                            </div>
                            {if $smarty.foreach.listeLigne.last}
                                    {if $aData.ZONE_TITRE5 eq 'ROLL'}
                                            <small class="legal">
                                              <a class="texttip" href="#cashBuyIn">{$aData.ZONE_TITRE6|escape}</a>
                                            </small>
                                            <div class="legal layertip" id="cashBuyIn">
                                                    {if $sVisuelML neq ''}<img class="lazy"  src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/16-9.png"  data-original="{$sVisuelML}" width="270" height="152" style="width:270px !important;height:152px !important;min-width:270px !important;max-width:270px !important;float:left"/>{/if}{if $aData.ZONE_TEXTE4} <div class="zonetexte">{$aData.ZONE_TEXTE4}</div> {/if}
                                            </div>
                                    {elseif $aData.ZONE_TITRE5 eq 'TEXT'}
                                             <div {if $sVisuelML neq ''}style="min-height:119px"{/if}>
                                                     <small class="legal">
                                                            {$aData.ZONE_TITRE6|escape}<br>
                                                            {if $sVisuelML neq ''}<img class="lazy"  src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/16-9.png"  data-original="{$sVisuelML}" width="270" height="152" style="width:270px !important;height:152px !important;min-width:270px !important;max-width:270px !important;float:left"/>{/if}{if $aData.ZONE_TEXTE4} <div class="zonetexte">{$aData.ZONE_TEXTE4}</div> {/if}
                                                     </small>
                                             </div>
                                    {elseif $aData.ZONE_TITRE5 eq 'POP_IN' && $aMentionsLegales.PAGE_CLEAR_URL neq ''}
                                            {if $aData.ZONE_TITRE6 neq ''}<small class="legal"><a class="simplepop" href="#creditBuyPopIn">{$aData.ZONE_TITRE6|escape}</a></small>{/if}
                                            <script type="text/template" id="creditBuyPopIn">
                                                    <div style="min-width:450px" >
                                                            <iframe src="{$aMentionsLegales.PAGE_CLEAR_URL}?popin=1" width="450px"></iframe>
                                                    </div>
                                            </script>
                                    {/if}
                            {/if}
                    </section>
            {/if}
	{/foreach}
	</div>
{else}
    <section class="{$aData.ZONE_SKIN}">
	{if $aData.ZONE_TEXTE} <div class="zonetexte">{$aData.ZONE_TEXTE}</div> {/if}

{/if}
{if $aAutresVehicules|@sizeof > 0}
	<section class="row of3 foldbyrow">
		<h2 class="col span2 title">{'AUTRES_VEHICULES_PRO'|t}</h2>
			{foreach from=$aAutresVehicules item=otherCar name=listeOtherCar}
				<div class="{if $smarty.foreach.listeOtherCar.iteration % 3 == 0 || $smarty.foreach.listeOtherCar.first}new {/if}col mosaic">
					<a href="{urlParser url=$otherCar.PAGE_ZONE_MULTI_URL}">
						<figure>
							<span>
								<img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/16-9.png" data-original="{$otherCar.MEDIA_PATH}" width="288" height="162" alt="{$otherCar.PAGE_ZONE_MULTI_TITRE}" />
								<noscript><img src="{$otherCar.MEDIA_PATH}" width="288" height="162" alt="{$otherCar.PAGE_ZONE_MULTI_TITRE}" /></noscript>
							</span>
							<figcaption>{$otherCar.PAGE_ZONE_MULTI_TITRE}</figcaption>
						</figure>
					</a>
				</div>
			{/foreach}
	</section>
{/if}
