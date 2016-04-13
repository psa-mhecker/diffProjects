
<div class="sliceNew sliceNewVehiclesDesk">
	<h1 class="title-01">{$aPageParentFull.PAGE_TITLE_BO}</h1>
	<div class="zonetexte">{$contenuTextCta.ZONE_TEXTE}</div>
	<div class="layer">
		<div class="box">
			<div class="tabbed">
				<div class="tabs" {gtmjs type='tabs' action='DisplayTab|' data=$aParams  datasup=['eventCategory' => 'NewCar']}></div>
				{foreach from=$aPagesN1 key=index item=n1}
					{if $n1.TEMPLATE_PAGE_ID == Pelican::$config['TEMPLATE_PAGE']['MASTER_PAGE_VEHICULES_N2']}
						<div class="tab">
							<p class="subtitle tabtitle {if $n1.PAGE_GAMME_VEHICULE == $aParams.PAGE_GAMME_VEHICULE}selectedTab{/if} masterVn"><a href="{if $n1.PAGE_GAMME_VEHICULE == $aParams.PAGE_GAMME_VEHICULE}#{else}{$n1.PAGE_CLEAR_URL}{/if}" {gtm action="NewCar::{$n1.PAGE_TITLE}" data={$aParams} datasup=['eventCategory'=>'NewCar' ,'eventLabel' =>{$n1.PAGE_TITLE} , value =>$index ]}><span>{$n1.PAGE_TITLE}</span></a></p>
							{if $n1.PAGE_GAMME_VEHICULE == $aParams.PAGE_GAMME_VEHICULE}
								<div class="new row ">
									<div class="new col row collapse cars vehicles">
										<div class="vehicle">
											<div class="new row">
											{assign var=lineReturn value=0}
											{foreach from=$aVehicules key=index item=vehicule}
												{if $lineReturn == 4}
													{assign var=lineReturn value=0}
												{/if}
												<div class="{if $lineReturn==0}new{/if} columns column_25 zoner bg nocategory">
													{assign var=lineReturn value=$lineReturn+1}
													<div class="bundle">
														<a href="{$vehicule.PAGE_CLEAR_URL}" {gtm action="Showroom::{$n1.PAGE_TITLE|escape:htmlall:'UTF-8'|regex_replace:'/&(.)(acute|grave|circ|uml|cedil|ring|tilde|slash);/':'\1'|replace:' ':'+'}::{$vehicule.VEHICULE_LABEL}" data={$aParams} datasup=['eventCategory'=>'NewCar' ,'eventLabel' =>{$vehicule.VEHICULE_LABEL} , value =>$index ]} target="_self">
															<figure>
																<img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/16-9.png" data-original="{Pelican::$config.MEDIA_HTTP}{$vehicule.MEDIA_PATH}" width="224" height="126" alt="{$vehicule.MEDIA_ALT|escape}">
																<noscript><img src="{Pelican::$config.MEDIA_HTTP}{$vehicule.MEDIA_PATH}" width="224" height="126" alt="{$vehicule.MEDIA_ALT|escape}" /></noscript>
																<figcaption>
																	{if $vehicule.VEHICULE_LABEL}<strong>{$vehicule.VEHICULE_LABEL}</strong>{/if}
																	{if $vehicule.VEHICULE_DISPLAY_CASH_PRICE eq 1 }
																		{'A_PARTIR'|t}<em>{$vehicule.PRIX}</em>
																		{if $vehicule.VEHICULE_CASH_PRICE_TYPE == 'CASH_PRICE_TTC'}
																			<em>{'CASH_PRICE_TTC'|t}
																			{if $aPage.ZONE_TITRE23 neq '' && $aParams.PAGE_GAMME_VEHICULE eq 'GAMME_VEHICULE_UTILITAIRE'}**{elseif $aPage.ZONE_TITRE6 neq '' && $aParams.PAGE_GAMME_VEHICULE neq 'GAMME_VEHICULE_UTILITAIRE'}
																				*
																			{/if}
																			</em>
																		{else}
																			<em>{'CASH_PRICE_HT'|t}{if $aPage.ZONE_TITRE23 neq '' && $aParams.PAGE_GAMME_VEHICULE eq 'GAMME_VEHICULE_UTILITAIRE'}**{elseif $aPage.ZONE_TITRE6 neq '' && $aParams.PAGE_GAMME_VEHICULE neq 'GAMME_VEHICULE_UTILITAIRE'}*{/if}</em>
																		{/if}
																	{else}
																		&nbsp;
																	{/if}
																</figcaption>
															</figure>
														</a>
															<ul class="menu">
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
									</div>
								</div>
							{/if}

							{if $aZoneGalerieNiveau2.ZONE_TITRE5 eq 'ROLL'}

								<div class="legal">
									{if $aParams.PAGE_GAMME_VEHICULE neq 'GAMME_VEHICULE_UTILITAIRE'}
										<a class="texttip" href="#cashBuyIn">{$aZoneGalerieNiveau2.ZONE_TITRE6|escape}</a>
									{/if}
									{if  $aParams.PAGE_GAMME_VEHICULE eq 'GAMME_VEHICULE_UTILITAIRE'}
										<a class="texttip" href="#cashBuyIn">{$aZoneGalerieNiveau2.ZONE_TITRE23|escape}</a>
									{/if}
								</div>

								<div class="legal layertip" id="cashBuyIn">
									{$aZoneGalerieNiveau2.ZONE_TITRE6|escape}<br/>
									{if $sVisuelML neq ''}<img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/16-9.png" data-original="{$sVisuelML}" width="270" height="152" style="width:270px !important;height:152px !important;min-width:270px !important;max-width:270px !important;float:left"/>{/if}{if $aZoneGalerieNiveau2.ZONE_TEXTE4} <div class="zonetexte">{$aZoneGalerieNiveau2.ZONE_TEXTE4}</div> {/if}
								</div>
							{elseif $aZoneGalerieNiveau2.ZONE_TITRE5 eq 'TEXT'}
								<div {if $sVisuelML neq ''}style="min-height:119px"{/if}>
									<div class="legal">
										{if $aPage.ZONE_TITRE23 neq '' && $aParams.PAGE_GAMME_VEHICULE eq 'GAMME_VEHICULE_UTILITAIRE'}
											{$aZoneGalerieNiveau2.ZONE_TITRE23|escape}<br/>
										{elseif $aPage.ZONE_TITRE6 neq '' && $aParams.PAGE_GAMME_VEHICULE neq 'GAMME_VEHICULE_UTILITAIRE'}
											{$aZoneGalerieNiveau2.ZONE_TITRE6|escape}<br/>
										{/if}
										{if $sVisuelML neq ''}<img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/16-9.png" data-original="{$sVisuelML}" width="270" height="152" style="width:270px !important;height:152px !important;min-width:270px !important;max-width:270px !important;float:left"/>{/if}{if $aZoneGalerieNiveau2.ZONE_TEXTE4} <div class="zonetexte">{$aZoneGalerieNiveau2.ZONE_TEXTE4}</div> {/if}
									</div>
								</div>
							{elseif $aZoneGalerieNiveau2.ZONE_TITRE5 eq 'POP_IN' && $aMentionsLegales.PAGE_CLEAR_URL neq '' ||  $aMentionsLegales.PAGE_CLEAR_URL neq '' && $aZoneGalerieNiveau2.ZONE_TITRE6 neq ''}
							{if $aZoneGalerieNiveau2.ZONE_TITRE6 neq ''}<small class="legal"><a class="simplepop" href="#creditBuyPopIn">{$aZoneGalerieNiveau2.ZONE_TITRE6|escape}</a></small>{/if}
								<script type="text/template" id="creditBuyPopIn">
									<div style="min-width:450px" >
										<iframe src="{$aMentionsLegales.PAGE_CLEAR_URL}?popin=1" width="450px"></iframe>
									</div>
								</script>
							{/if}
						</div>
					{/if}
				{/foreach}
			</div>
		</div>
	</div>
	{if $aAutresVehicules && $aParams.PAGE_GAMME_VEHICULE eq 'GAMME_LIGNE_C'}
		<div class="sliceNew sliceGalerieLvl2vehiculesDesktop">
			<section class="others" style="padding-top: 0px;">
				<h2 class="span4 parttitle" {if ($aData.SECOND_COLOR|count_characters)==7 } style="color:{$aData.SECOND_COLOR};" {/if}>{$aZoneGalerieNiveau2.ZONE_TITRE|escape}</h2>
				<div class="row wrapperGalerieLvl2vehicules">
					{assign var="counter" value=0}
					{foreach from=$aAutresVehicules item=vehicule name=autresVehicules}
						{if $smarty.foreach.autresVehicules.iteration%2==1}
							{assign var="counter" value=$counter+1}
						{/if}
						<div data-sync="{$aData.ORDER}line{$counter}" class="columns column_50 zoner bg{if $smarty.foreach.autresVehicules.iteration%2==1 } new{/if}">
							<div class="row">
								<figure class="columns column_40">
									<a href="{urlParser url=$vehicule.PAGE_CLEAR_URL}" {if $vehicule.MODE_OUVERTURE_SHOWROOM==2}target="_blank"{/if} {gtm action='Push' data=$aParams datasup=['eventLabel'=>{$vehicule.PAGE_TITLE_BO}]}>
										<img width="179" height="179" alt="{$vehicule.MEDIA_ALT|escape}" data-original="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/1-1.png" src="{Pelican::$config.MEDIA_HTTP}{$vehicule.MEDIA_PATH}" class="" style="display: inline-block;">
										<noscript>&lt;img src="{Pelican::$config.MEDIA_HTTP}{$vehicule.MEDIA_PATH}" width="179" height="179" alt="{$vehicule.MEDIA_ALT|escape}" /&gt;</noscript>
									</a>
								</figure>
								<div class="columns column_60 textContainer">
									<div class="valign">
										<div>
											<h3 class="titleBlock"><a href="{urlParser url=$vehicule.PAGE_CLEAR_URL}" {if $vehicule.MODE_OUVERTURE_SHOWROOM==2}target="_blank"{/if}>{$vehicule.PAGE_TITLE_BO}</a></h3>
											<p>{$vehicule.PAGE_TEXT}</p>
										</div>
									</div>
								</div>
							</div>
						</div>
					{/foreach}
				</div>
			</section>
		</div>
	{/if}
</div>


