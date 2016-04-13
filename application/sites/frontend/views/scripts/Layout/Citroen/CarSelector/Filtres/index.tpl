{if $aData.ZONE_WEB eq 1 && ($bDisplayFiltre1 || $bDisplayFiltre2)}

	<div id="{$aData.ID_HTML}" class="selector" {gtmjs type='toggle' data=$aData action='Accordion|' datasup=['eventLabel'=>{$aData.ZONE_TITRE10|escape}]}>
	
		<a href="#selector" class="folder" >{$aData.ZONE_TITRE10|escape}</a>
		
	
{IF $aData.ZONE_URL && $aData.ZONE_TITRE11 == "VU"}
	<a class="filterlink" href="{urlParser url=$aData.ZONE_URL}">{'LINK_VP'|t}</a>
{ELSEIF $aData.ZONE_URL && $aData.ZONE_TITRE11 == "VP"}
	<a class="filterlink" href="{urlParser url=$aData.ZONE_URL}">{'LINK_VU'|t}</a>
{/IF}	
		
		
		
		<div id="selector" style="visibility: hidden; position: absolute;" data-loadtext="{'MAJ_RES_CS'|t}"></>
			<div class="count">{$nbResult}&nbsp;<span>{'RESULTATS'|t}</span></div> 
			<div class="reset">
				<a href="#" class="button">{'REINITIALISER'|t}</a>
			</div>
			<div class="tabbed bounded tabbed-filters">
				<div class="tabs"></div>
				{if $bDisplayFiltre1}
				<div class="tab">
					<h2 class="parttitle tabtitle">{'TYPE_FILTRE_1'|t}</h2>
					<form method="POST" action="/_/Layout_Citroen_CarSelector_Resultats/carSelectorResults" id="fFormType1" name="fFormType1">
						<input type="hidden" name="filtreType" value="1" />
						<input type="hidden" name="langueID" value="{$aData.LANGUE_ID}" />
						<input type="hidden" name="siteID" value="{$aData.SITE_ID}" />
						<input type="hidden" name="pageId" value="{$aData.PAGE_ID}" />
						<input type="hidden" name="pageVersion" value="{$aData.PAGE_VERSION}" />
						<div class="row of4 collapse">
							{if $bDisplaySilhouette}
								<div class="col">
									<div class="head">{'SILHOUETTE_FRONT'|t}</div>
									<div class="content">
										{foreach from=$aSilhouetteFiltre item=silhouette name=listeSilhouette}
										{assign var="codeSil" value="{$silhouette.CRIT_BODY_CODE}"}
										{assign var="keySil" value="{$aCheckedSil.$codeSil}"}
											<input type="checkbox" name="silhouette_{$smarty.foreach.listeSilhouette.iteration}" id="silhouette_{$smarty.foreach.listeSilhouette.iteration}" value="{$silhouette.CRIT_BODY_CODE}" {if 
											$keySil}checked{/if}/><label for="silhouette_{$smarty.foreach.listeSilhouette.iteration}" >{$silhouette.CRIT_BODY_LABEL}</label>
										{/foreach}
									</div>
								</div>
							{/if}
							{if $bDisplayEnergie}
								<div class="col">
									<div class="head">{'ENERGIE_FRONT'|t}</div>
									<div class="content">
										{foreach from=$aEnergieFiltre item=energie name=listeEnergie}
											<input type="radio" name="energy" id="{$energie.ENERGY_CODE}" value="{$energie.ENERGY_CODE}" {if ($energie.ENERGY_CODE eq $aFiltresType1.ENERGIE) || ($aFiltresType1.ENERGIE eq '' && $energie.ENERGY_CODE eq 'TOUTENERGY')}checked{/if}/><label for="{$energie.ENERGY_CODE}">{$energie.ENERGY_LABEL}</label>
										{/foreach}
									</div>
								</div>
							{/if}
							{if $bDisplayBoiteVitesse}
								<div class="col">
									<div class="head">{'BOITE_VITESSE_FRONT'|t}</div>
									<div class="content">
										{foreach from=$aTransmissionFiltre item=transmission name=listeTransmission}
											<input type="radio" name="gears" id="{$transmission.CRIT_TR_CODE}" value="{$transmission.CRIT_TR_CODE}" {if ($transmission.CRIT_TR_CODE eq $aFiltresType1.BOITE_VITESSE) || ($aFiltresType1.BOITE_VITESSE eq '' && $transmission.CRIT_TR_CODE eq 'TOUTGEARS')}checked{/if}/><label for="{$transmission.CRIT_TR_CODE}">{$transmission.CRIT_TR_LABEL}</label>
										{/foreach}
									</div>
								</div>
							{/if}
							{if $bDisplayPrix}
								<div class="col">
									<div class="head">{'PRIX_FRONT'|t}</div>
									<div class="content">
										<div class="range">
											<div class="bar"></div>
											<input type="text" value="{if $aFiltresType1.PRIX neq ""}{$aFiltresType1.PRIX}{else}{$iMaxPrix}{/if}" name="priceLimit" data-from="{$iMinPrix}" data-to="{$iMaxPrix}" data-step="{$iPasPrix}" data-unit="< _ {$sDevise}" />
										</div>
									</div>
								</div>
							{/if}
							{if $bDisplayLongueurExt}
								<div class="col">
									<div class="head">{'LONGUEUR_EXT_FRONT'|t}</div>
									<div class="content">
										<div class="range">
											<div class="bar"></div>
											<input type="text" value="{if $aFiltresType1.LONGUEUR neq ""}{$aFiltresType1.LONGUEUR}{else}7000{/if}" name="length" data-from="5000" data-to="7000" data-step="100" data-unit="< _ {$sTaille}" />
										</div>
									</div>
								</div>
							{/if}
							{if $sNouvelleColonne eq 'CONSO'}
							<div class="col span3 right row of3 collapse">
							{/if}
								{if $bDisplayConso}
									<div class="col">
										<div class="head">{'CONSO_FRONT'|t}</div>
										<div class="content">
											<div class="range">
												<div class="bar"></div>
												<input type="text" value="{if $aFiltresType1.CONSO neq ""}{$aFiltresType1.CONSO}{else}25{/if}" name="consum" data-from="1" data-to="25" data-step="0.5" data-unit="< _ {$sConso}" />
											</div>
										</div>
									</div>
								{/if}
							{if $sNouvelleColonne eq 'EMISSION_CO2'}
							<div class="col span3 right row of3 collapse">
							{/if}
								{if $bDisplayEmissionCo2}
									<div class="col">
										<div class="head">{'EMISSION_CO2_FRONT'|t}</div>
										<div class="content">
											<div class="range">
												<div class="bar"></div>
												<input type="text" value="{if $aFiltresType1.EMISSION neq ""}{$aFiltresType1.EMISSION}{else}500{/if}" name="co2" data-from="100" data-to="500" data-step="10" data-unit="< _ {$sEmission}" />
											</div>
										</div>
									</div>
								{/if}
							{if $sNouvelleColonne eq 'NB_PASSAGERS'}
							<div class="col span3 right row of3 collapse">
							{/if}
								{if $bDisplayNbPassagers}
									<div class="col">
										<div class="head">{'NB_PASSAGERS_FRONT'|t}</div>
										<div class="content">
											<div class="range">
												<div class="bar"></div>
												<input type="text" value="{if $aFiltresType1.PRIX neq ""}{$aFiltresType1.PASSAGERS}{else}0{/if}" name="passengers" data-from="0" data-to="4" data-values="0|1|2|3|4 {'CARSELECTOR_MORE_PASSENGER'|t}" />
											</div>
										</div>
									</div>
								{/if}
							</div>
						{if $sNouvelleColonne eq 'EMISSION_CO2' || $sNouvelleColonne eq 'NB_PASSAGERS' || $sNouvelleColonne eq 'CONSO'}
						</div>
						{/if}
					</form>
				</div>
			{/if}
			{if $bDisplayFiltre2}
				<div class="tab">
					<h2 class="parttitle tabtitle">{'TYPE_FILTRE_2'|t}</h2>
					<form method="POST" action="/_/Layout_Citroen_CarSelector_Resultats/carSelectorResults" id="fFormType2" name="fFormType2">
						<input type="hidden" name="filtreType" value="2" />
						<input type="hidden" name="langueID" value="{$aData.LANGUE_ID}" />
						<input type="hidden" name="siteID" value="{$aData.SITE_ID}" />
						<input type="hidden" name="pageId" value="{$aData.PAGE_ID}" />
						<input type="hidden" name="pageVersion" value="{$aData.PAGE_VERSION}" />
						<div class="row of4 collapse">
							{if $bDisplayCritere1}
                                                            <div class="col"><div class="content">
								<div class="parttitle">{'CRITERE1'|t}</div>
								<figure>
                                                                    <img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/235-100.png" data-original="{$sLogoCritere1}" width="217" height="122" alt="{'CRITERE1'|t}" />
                                                                    <noscript><img src="{$sLogoCritere1}" width="217" height="122" alt="{'CRITERE1'|t}" /></noscript>
								</figure>
                                                                {foreach from=$aCriteres.1 item=critere1 name=listeCritere1}
                                                                    <input type="radio" name="critere1" id="{$critere1.CRITERE_LABEL_PUBLIC}" value="{$critere1.CRITERE_ID}"{if $critere1.CRITERE_ID eq $aFiltresType2.CRITERE_1}checked="checked"{/if}/><label for="{$critere1.CRITERE_LABEL_PUBLIC}" >{$critere1.CRITERE_LABEL_PUBLIC}</label>
                                                                {/foreach}
                                                            </div></div>
							{/if}
							{if $bDisplayCritere2}
                                                            <div class="col"><div class="content">
								<div class="parttitle">{'CRITERE2'|t}</div>
								<figure>
                                                                    <img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/235-100.png" data-original="{$sLogoCritere2}" width="217" height="122" alt="{'CRITERE2'|t}" />
                                                                    <noscript><img src="{$sLogoCritere2}" width="217" height="122" alt="{'CRITERE2'|t}" /></noscript>
								</figure>
                                                                {foreach from=$aCriteres.2 item=critere2 name=listeCritere2}
                                                                    <input type="radio" name="critere2" id="{$critere2.CRITERE_LABEL_PUBLIC}" value="{$critere2.CRITERE_ID}"{if $critere2.CRITERE_ID eq $aFiltresType2.CRITERE_2}checked="checked"{/if}/><label for="{$critere2.CRITERE_LABEL_PUBLIC}">{$critere2.CRITERE_LABEL_PUBLIC}</label>
                                                                {/foreach}
                                                            </div></div>
							{/if}
							{if $bDisplayPrix2}
                                                            <div class="col"><div class="content">
								<div class="parttitle">{'PRIX_COMPTANT'|t}</div>
								<figure>
                                                                    <img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/235-100.png" data-original="{$sLogoPrix}" width="217" height="122" alt="{'PRIX_COMPTANT'|t}" />
                                                                    <noscript><img src="{$sLogoPrix}" width="217" height="122" alt="{'PRIX_COMPTANT'|t}" /></noscript>
								</figure>
                                                                    {assign var="ltPrice" value="lt{$iSeuilPrix1Tranche1}"}
                                                                    {assign var="bPrice1" value="b{$iSeuilPrix1Tranche2}a{$iSeuilPrix2Tranche2}"}
                                                                    {assign var="bPrice2" value="b{$iSeuilPrix2Tranche3}a{$iSeuilPrix3Tranche3}"}
                                                                    {assign var="gtPrice" value="gt{$iSeuilPrix3Tranche4}"}
                                                                    <input type="radio" name="cash" id="lt{$iSeuilPrix1Tranche1}" value="lt{$iSeuilPrix1Tranche1}" {if $aFiltresType2.PRIX eq $ltPrice}checked="checked"{/if}/><label for="lt{$iSeuilPrix1Tranche1}">< {$iSeuilPrix1Tranche1} {$sDevise}</label>
                                                                    <input type="radio" name="cash" id="b{$iSeuilPrix1Tranche2}a{$iSeuilPrix2Tranche2}" value="b{$iSeuilPrix1Tranche2}a{$iSeuilPrix2Tranche2}" {if $aFiltresType2.PRIX eq $bPrice1}checked="checked"{/if}/><label for="b{$iSeuilPrix1Tranche2}a{$iSeuilPrix2Tranche2}">{$iSeuilPrix1Tranche2} {$sDevise} - {$iSeuilPrix2Tranche2} {$sDevise}</label>
                                                                    <input type="radio" name="cash" id="b{$iSeuilPrix2Tranche3}a{$iSeuilPrix3Tranche3}" value="b{$iSeuilPrix2Tranche3}a{$iSeuilPrix3Tranche3}" {if $aFiltresType2.PRIX eq $bPrice2}checked="checked"{/if}/><label for="b{$iSeuilPrix2Tranche3}a{$iSeuilPrix3Tranche3}">{$iSeuilPrix2Tranche3} {$sDevise} - {$iSeuilPrix3Tranche3} {$sDevise}</label>
                                                                    <input type="radio" name="cash" id="gt{$iSeuilPrix3Tranche4}" value="gt{$iSeuilPrix3Tranche4}" {if $aFiltresType2.PRIX eq $gtPrice}checked="checked"{/if}/><label for="gt{$iSeuilPrix3Tranche4}">> {$iSeuilPrix3Tranche4} {$sDevise}</label>								
                                                            </div></div>
							{/if}
							{if $bDisplayCritere3}
                                                            <div class="col"><div class="content">
								<div class="parttitle">{'CRITERE3'|t}</div>
								<figure>
									<img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/235-100.png" data-original="{$sLogoCritere3}" width="217" height="122" alt="{'CRITERE3'|t}" />
									<noscript><img src="{$sLogoCritere3}" width="217" height="122" alt="{'CRITERE3'|t}" /></noscript>
								</figure>
                                                                {foreach from=$aCriteres.3 item=critere3 name=listeCritere3}
                                                                        <input type="radio" name="critere3" id="{$critere3.CRITERE_LABEL_PUBLIC}3" value="{$critere3.CRITERE_ID}"{if $critere3.CRITERE_ID eq $aFiltresType2.CRITERE_3}checked="checked"{/if}/><label for="{$critere3.CRITERE_LABEL_PUBLIC}3">{$critere3.CRITERE_LABEL_PUBLIC}</label>
                                                                {/foreach}
                                                            </div></div>
							{/if}
						</div>
					</form>
				</div>
			{/if}
			</div>
		</div>
	</div>
 
{/if}