{if $aVehicules.VEHICULES|@sizeof > 0}
	<div class="mastercars-group">
		{foreach from=$aVehicules.VEHICULES item=ligne name=listeLigne}
			{if $ligne.CARS|@sizeof > 0}
			{assign var=cpt value=0}
				{foreach from=$ligne.CARS item=car name=listeCar}
				{if $cpt == 0}
				<section class=" mastercars{if $ligne.LABEL eq 'LA_LIGNE_DS'} ds{/if} row clsgalerien3v clsgamme clsmastergamme small">
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
						
							<div class="col item zoner" {gtm name='clic_sur_vehicule_ou_en_savoir_plus' data=$aPost datasup=['value'=>$car.LCDV6] labelvars=['%intitule du lien%'=>'EN_SAVOIR_PLUS'|t,'%nom du vehicule%'=>$car.VEHICULE_LABEL, '%code LCDV 6%'=>$car.LCDV6]}>
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
									<li class="{$bt_1}"><a href="{urlParser url=$car.URL_DETAIL}" {gtm name='clic_sur_vehicule_ou_en_savoir_plus' data=$aPost datasup=['value'=>$car.LCDV6] labelvars=['%intitule du lien%'=>'EN_SAVOIR_PLUS'|t,'%nom du vehicule%'=>$car.VEHICULE_LABEL, '%code LCDV 6%'=>$car.LCDV6]}>{'EN_SAVOIR_PLUS'|t}</a></li>
									{if $car.URL_CONFIGURATEUR}<li class="{$bt_2}"><a href="{urlParser url=$car.URL_CONFIGURATEUR}" {gtm name='clic_sur_configurer' data=$aPost datasup=['value'=>$car.LCDV6] labelvars=['%intitule du lien%'=>'CONFIGURER'|t,'%nom du vehicule%'=>$car.VEHICULE_LABEL, '%code lcdv 6%'=>$car.LCDV6]} target="_blank">{'CONFIGURER'|t}</a></li>{/if}
								</ul>
								{if $useCompareVehicule}
								<ul class="links">
									<li><a href="javascript://" data-source="CARSELECTOR" class="addtoCompare" id="{$car.VEHICULE_ID}" {gtm name='comparer_ce_modele' data=$aPost datasup=['value'=>$car.LCDV6] labelvars=['%intitule du lien%'=>'EN_SAVOIR_PLUS'|t,'%nom du vehicule%'=>$car.VEHICULE_LABEL, '%code lcdv 6%'=>$car.LCDV6]}>{'COMPARE_MODEL'|t}</a></li>
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
								{if $sVisuelML neq ''}<img class="lazy" data-original="{$sVisuelML}" width="270" height="152" style="width:270px !important;height:152px !important;min-width:270px !important;max-width:270px !important;float:left"/>{/if}{if $aData.ZONE_TEXTE4} <div class="zonetexte">{$aData.ZONE_TEXTE4}</div> {/if}
							</div>
						{else if $aData.ZONE_TITRE5 eq 'TEXT'}
							 <div {if $sVisuelML neq ''}style="min-height:119px"{/if}>
								 <small class="legal">
									{$aData.ZONE_TITRE6|escape}<br>
									{if $sVisuelML neq ''}<img class="lazy" data-original="{$sVisuelML}" width="270" height="152" style="width:270px !important;height:152px !important;min-width:270px !important;max-width:270px !important;float:left"/>{/if}{if $aData.ZONE_TEXTE4} <div class="zonetexte">{$aData.ZONE_TEXTE4}</div> {/if}
								 </small>
							 </div>
						{else if $aData.ZONE_TITRE5 eq 'POP_IN' && $aMentionsLegales.PAGE_CLEAR_URL neq ''}
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
	{if $aData.ZONE_TEXTE} <div class="zonetexte">{$aData.ZONE_TEXTE}</div> {/if}
{/if}
{literal}

<input id="nbcars" type="hidden" value="{/literal}{$nbResults}{literal}">

{/literal}