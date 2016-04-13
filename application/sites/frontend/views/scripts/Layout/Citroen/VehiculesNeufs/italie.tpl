{if ($aData.PRIMARY_COLOR|count_characters)==7 }
{literal}
	<style>
		.stocks.showroom figure[data-minus]::before {
		background: {/literal}{$aData.PRIMARY_COLOR}{literal} none repeat scroll 0 0;
}

.stocks.showroom .red > a
{
	background-color : #ffffff;
	border: 4px solid {/literal}{$aData.SECOND_COLOR}{literal};
	color: {/literal}{$aData.SECOND_COLOR}{literal};
}

.clslanguetteshowroom .addmore.folder a, .showroom.clsaccessoires .seeMoreAccessories.addmore.folder a
{
	background-color : #ffffff;
border: 4px solid {/literal}{$aData.PRIMARY_COLOR}{literal} !important;
	color: {/literal}{$aData.PRIMARY_COLOR}{literal};
		}
.sliceCarStoreDesk .clsvehiculeneuf .item a.tooltip:hover:before {
color:{/literal}{$aData.PRIMARY_COLOR}{literal}!important;
		}
		.sliceCarStoreDesk .clsvehiculeneuf .item .cost .economy{
color:{/literal}{$aData.PRIMARY_COLOR}{literal}!important;
		}
	</style>
{/literal}
{/if}

{literal}
<style>
	.sliceCarStoreDesk .tabbed .tabs li.on > * {
		padding-left: 10px;
		padding-right: 10px;
		background-color:{/literal}{$aData.SECOND_COLOR}{literal};
		color: #FFF!important;
	}

	.sliceCarStoreDesk .tabbed .tabs li.on span:hover {
		color: #FFF!important;
	}

	.sliceCarStoreDesk .tabbed .tabs li > * {
		background-image: none;
		border: none;
		border-radius: 0;
		margin: 0;
		font-size: 17px;
		font-family: citroen;
		font-weight: bold;
		text-transform: uppercase;
		border-top: 2px solid {/literal}{$aData.SECOND_COLOR}{literal};
		margin-right: 12px;
		text-align: left;
	}
	.sliceCarStoreDesk .clsvehiculeneuf .item .cost .actions li a, .sliceCarStoreMobile .clsvehiculeneuf .item .cost .actions li a {

		border: 4px solid {/literal}{$aData.SECOND_COLOR}{literal}!important;
		background-color: {/literal}{$aData.SECOND_COLOR}{literal}!important;

	}
	.sliceCarStoreDesk .clsvehiculeneuf .item .cost .actions li a:hover{
		background-color:#fff!important;
		color:{/literal}{$aData.SECOND_COLOR}{literal}!important;
	}
	.sliceCarStoreDesk .clsvehiculeneuf .item figure[data-minus]:before, .sliceCarStoreMobile .clsvehiculeneuf .item figure[data-minus]:before{
		background-color:{/literal}{$aData.PRIMARY_COLOR}{literal}!important;
	}
	.sliceCarStoreDesk .addmore a, .sliceCarStoreMobile .addmore a{
		border: 4px solid {/literal}{$aData.SECOND_COLOR}{literal}!important;
		background-color: {/literal}{$aData.SECOND_COLOR}{literal}!important;
		color:#fff!important;
	}
	.sliceCarStoreDesk .addmore a:hover, .sliceCarStoreMobile .addmore a:hover{
		background-color:#fff!important;
		color:{/literal}{$aData.SECOND_COLOR}{literal}!important;
	}
	.sliceCarStoreDesk .tabs li span:hover {
		color:{/literal}{$aData.SECOND_COLOR}{literal};
	}


</style>
{/literal}

{if $bTrancheVisible}
	<div class="sliceNew sliceCarStoreDesk">
		<section id="{$aData.ID_HTML}" class="stocks clsstocks">
			{if $aData.ZONE_TITRE neq '' || $aData.ZONE_TITRE2 neq '' || $aData.ZONE_TEXTE neq ''}
				<div class="row of3 wrapperHead">
					{if $aData.ZONE_TITRE neq ''}<h2 class="subtitle" {if ($aData.PRIMARY_COLOR|count_characters)==7 } style="color:{$aData.PRIMARY_COLOR};" {/if}>{$aData.ZONE_TITRE|escape}</h2>{/if}
					{if $aData.ZONE_TITRE2 neq ''}<h3 class="parttitle" {if ($aData.SECOND_COLOR|count_characters)==7 } style="color:{$aData.SECOND_COLOR};" {/if}>{$aData.ZONE_TITRE2|escape}</h3>{/if}
					{if $aData.ZONE_TEXTE neq ''}<div class="zonetexte">{$aData.ZONE_TEXTE|replace:"#MEDIA_HTTP#":Pelican::$config.MEDIA_HTTP}</div>{/if}
				</div>
			{/if}

			{if $aData.ZONE_ATTRIBUT2 eq 2}
				{if $aVehicules.CARS|@sizeof > 0}
					<div id="resultVN" class="clsvehiculeneuf VN">
					<div id='allNewCar' class="stocks">
						<input type="hidden" name="iCount" id="iCount" value="2"/>
						<input type="hidden" name="zid" id="zid" value="{$aData.ZONE_ID}"/>
						<input type="hidden" name="zType" id="zType" value="{$aData.ZONE_TITRE3}"/>
						<input type="hidden" name="zidVN" id="zidVN" value="{$aData.ZONE_ID}"/>
						<input type="hidden" name="zorderVN" id="zorderVN" value="{$aData.ZONE_ORDER}"/>
						<input type="hidden" name="zareaVN" id="zareaVN" value="{$aData.AREA_ID}"/>
						<input type="hidden" name="ZONE_SKIN" id="ZONE_SKIN" value="{$aData.ZONE_SKIN}"/>
						<input type="hidden" name="zType" id="zType" value="{$aData.ZONE_TITRE3}"/>
						<input type="hidden" name="countryCode" id="countryCode" value="{$countryCode}"/>
						{foreach from=$aVehicules.CARS item=car name=listeCar}
							<div class="row of4 item {$aData.ZONE_SKIN}">
								<figure class="col" {if $car.POURCENTAGE && $active && $car.StockLevel!=60}data-minus="-{$car.POURCENTAGE}%"{/if}>
									<img class="lazy" src="{$car.IMAGE}" data-original="{$car.IMAGE}" width="206" height="116"
										 alt="{$car.CommercialLabel}" />
									<noscript><img src="{$car.IMAGE}" width="206" height="116"
												   alt="{$car.CommercialLabel}" /></noscript>
									<figcaption>
										<p {if ($aData.PRIMARY_COLOR|count_characters)==7 } style="color:{$aData.PRIMARY_COLOR};" {/if}>{$car.DISPONIBLE_SOUS}</p>
										{if $car.StockLevel==60}<div class="infosup">{'VEHICULE_FAIBLE_KILOMETRAGE'|t}</div>{/if}
									</figcaption>
								</figure>
								<div class="col span2 detail">
									<h4 class="parttitle" {if ($aData.SECOND_COLOR|count_characters)==7 } style="font-weight:bold;color:{$aData.SECOND_COLOR};" {/if}>{$car.CommercialLabel}</h4>
									<p>
										{'COLOR'|t} : {$car.ExtFeatureLabel}<br />
										{'GARNISSAGE'|t} : {$car.IntFeatureLabel}<br />
									</p>
									{if $car.FEATURES|@sizeof > 0}
										<p>
											<span class="colored" {if ($aData.PRIMARY_COLOR|count_characters)==7 } style="color:{$aData.PRIMARY_COLOR};" {/if}>{if $OptionTraduction}{$OptionTraduction}{else}Option(s){/if}</span><!-- CPW-3861 CLEF DE TRADUCTION -->
											<a class="tooltip" href="#carStoreTooltip_{$smarty.foreach.listeCar.iteration}">?</a>
										<div class="legal layertip" id="carStoreTooltip_{$smarty.foreach.listeCar.iteration}">
											{foreach from=$car.FEATURES item=carFeature name=listeCarFeature}

												{$carFeature}<br/>
											{/foreach}
										</div>
										</p>
									{/if}
									<div class="legal legaltext"><p>
											{'CONSO_MIXTE'|t} ({$sConso}): {$car.ConsoMixte}<br />
											{'EMISSION_CO2_FRONT'|t} ({$sEmission}) : {$car.CO2Rate} {if $active}<img src="{Pelican::$config.MEDIA_HTTP}/design/frontend/images/picto/{$car.BILAN_CARBONE}.gif" width="25" height="12" alt="A" />{/if}<br /><p>{$car.RecoveryMention}</p>
										</p></div>
								</div>
								<div class="col cost">
									<div class="price" {if ($aData.PRIMARY_COLOR|count_characters)==7 } style="color:{$aData.PRIMARY_COLOR};" {/if}>{if
										$car.InternetPrice}{$car.PRIX_WEBSTORE_STRING|number_format:0:".":" "} {$sDevise} {'CASH_PRICE_TTC'|t} {if $sMentionsLegales}<sup>{$car.PricebackLink}</sup>{/if}{else}{$car.PRIX_CATALOGUE_STRING} {if $sMentionsLegales}<sup>{$car.PricebackLink}</sup>{/if}{/if}</div>

									{assign var="carstore_mentions" value=$car.RecoveryMention|strpos:" "}
									{if $car.RecoveryMention}<p class="offer">{'OFFRE_SOUS_CONDITION'|t}<sup>{$car.RecoveryMention|substr:0:$carstore_mentions|strip_tags:false}</sup></p>{/if}

									{if $car.InternetPrice neq 0 && $car.InternetPrice neq $car.CatalogPrice}
										<div class="advice">
											{'PRIX_CONSEILLE'|t}
											<strong class="strike">{$car.PRIX_CATALOGUE_STRING|number_format:0:".":" "} {$sDevise}</strong>
										</div>
										{if $car.SOIT_ECO}
											<div class="economy">
												{$car.SOIT_ECO}
											</div>
										{/if}
									{/if}
									{if $car.StoreDetailUrl}
										<ul class="actions">
											<li class="red"><a href="{urlParser url=$car.StoreDetailUrl}" {gtm action="Carstore" data=$aData datasup=['eventCategory'=>'Showroom::Carstore','eventLabel'=>{'PROFITER_OFFRE'|t}]} target="_blank">{'PROFITER_OFFRE'|t}</a></li>
										</ul>
									{/if}
								</div>
							</div>
						{/foreach}
					</div>
					</div>
					{*if $aVehicules.COUNT > 4*}
					<section class="{$aData.ZONE_SKIN} {if ($aData.PRIMARY_COLOR|count_characters)==7 }showroom{/if} row of6 clslanguette{if ($aData.PRIMARY_COLOR|count_characters)==7 }showroom{/if}">
						<div class="caption addmore voirplus"  {if ($aData.PRIMARY_COLOR|count_characters)==7 }  data-off="border:4px solid {$aData.PRIMARY_COLOR}; color:{$aData.PRIMARY_COLOR};" data-hover="border:4px solid {$aData.PRIMARY_COLOR}; color:{$aData.PRIMARY_COLOR};"{/if} data-toggle="{'VOIR_STOCK'|t}">
							<a {if ($aData.PRIMARY_COLOR|count_characters)==7 } style="border:4px solid {$aData.PRIMARY_COLOR}; color:{$aData.PRIMARY_COLOR};" class="col span2" {/if} href="{urlParser url=$sUrlCarStore}" target="_blank" {gtm action="SeeMoreResults" data=$aData datasup=['eventCategory' =>'Showroom::Carstore','eventLabel'=>{'VOIR_STOCK'|t}]}>{'VOIR_STOCK'|t}</a>
						</div>
					</section>
					{*/if*}
					{if $sMentionsLegales}
						<div class="legal" style="margin-bottom: 0px;">
							<p>
								{$sMentionsLegales}
							</p>
						</div>
					{/if}
				{else}
					<div class="row of3">
						<div class="col span2">
							{'NO_RESULTS_VEHICULES_NEUFS'|t}
						</div>
					</div>
				{/if}
			{elseif $aVehicules.VN|@sizeof > 0 || $aVehicules.LOW_KM|@sizeof > 0}
				<div class="tabbed">
					<div class="tabs " {gtmjs type='tabs' action='DisplayTab|' data=$aParams  datasup=['eventCategory' => 'NewCar']}></div>
					<br/>


					{if $aVehicules.VN|@sizeof > 0}
						<div id="resultVN" class="tab clsvehiculeneuf VN">
							<p class="subtitle tabtitle  masterVn"><span>VÉHICULES NEUFS</span></p>
							<div id='allNewCar' class="stocks">
								<input type="hidden" name="iCount" id="iCount" value="2"/>
								<input type="hidden" name="zid" id="zid" value="{$aData.ZONE_ID}"/>
								<input type="hidden" name="zType" id="zType" value="{$aData.ZONE_TITRE3}"/>
								<input type="hidden" name="zidVN" id="zidVN" value="{$aData.ZONE_ID}"/>
								<input type="hidden" name="zorderVN" id="zorderVN" value="{$aData.ZONE_ORDER}"/>
								<input type="hidden" name="zareaVN" id="zareaVN" value="{$aData.AREA_ID}"/>
								<input type="hidden" name="ZONE_SKIN" id="ZONE_SKIN" value="{$aData.ZONE_SKIN}"/>
								<input type="hidden" name="zType" id="zType" value="{$aData.ZONE_TITRE3}"/>
								<input type="hidden" name="countryCode" id="countryCode" value="{$countryCode}"/>
								{foreach from=$aVehicules.VN item=car name=listeCar}
									<div class="row of4 item {$aData.ZONE_SKIN}">
										<figure class="col" {if $car.POURCENTAGE && $active && $car.StockLevel!=60}data-minus="-{$car.POURCENTAGE}%"{/if}>
											<img class="lazy" src="{$car.IMAGE}" data-original="{$car.IMAGE}" width="206" height="116"
												 alt="{$car.CommercialLabel}" />
											<noscript><img src="{$car.IMAGE}" width="206" height="116"
														   alt="{$car.CommercialLabel}" /></noscript>
											<figcaption>
												<p {if ($aData.PRIMARY_COLOR|count_characters)==7 } style="color:{$aData.PRIMARY_COLOR};" {/if}>{$car.DISPONIBLE_SOUS}</p>
												{if $car.StockLevel==60}<div class="infosup">{'VEHICULE_FAIBLE_KILOMETRAGE'|t}</div>{/if}
											</figcaption>
										</figure>
										<div class="col span2 detail">
											<h4 class="parttitle" {if ($aData.SECOND_COLOR|count_characters)==7 } style="font-weight:bold;color:{$aData.SECOND_COLOR};" {/if}>{$car.CommercialLabel}</h4>
											<p>
												{'COLOR'|t} : {$car.ExtFeatureLabel}<br />
												{'GARNISSAGE'|t} : {$car.IntFeatureLabel}<br />
											</p>
											{if $car.FEATURES|@sizeof > 0}
												<p>
													<span class="colored" {if ($aData.PRIMARY_COLOR|count_characters)==7 } style="color:{$aData.PRIMARY_COLOR};" {/if}>{if $OptionTraduction}{$OptionTraduction}{else}Option(s){/if}</span><!-- CPW-3861 CLEF DE TRADUCTION -->
													<a class="tooltip" href="#carStoreTooltip_{$smarty.foreach.listeCar.iteration}">?</a>
												<div class="legal layertip" id="carStoreTooltip_{$smarty.foreach.listeCar.iteration}">
													{foreach from=$car.FEATURES item=carFeature name=listeCarFeature}

														{$carFeature}<br/>
													{/foreach}
												</div>
												</p>
											{/if}
											<div class="legal legaltext"><p>
													{'CONSO_MIXTE'|t} ({$sConso}): {$car.ConsoMixte}<br />
													{'EMISSION_CO2_FRONT'|t} ({$sEmission}) : {$car.CO2Rate} {if $active}<img src="{Pelican::$config.MEDIA_HTTP}/design/frontend/images/picto/{$car.BILAN_CARBONE}.gif" width="25" height="12" alt="A" />{/if}<br /><p>{$car.RecoveryMention}</p>
												</p></div>
										</div>
										<div class="col cost">
											<div class="price" {if ($aData.PRIMARY_COLOR|count_characters)==7 } style="color:{$aData.PRIMARY_COLOR};" {/if}>{if
												$car.InternetPrice}{$car.PRIX_WEBSTORE_STRING|number_format:0:".":" "} {$sDevise} {'CASH_PRICE_TTC'|t} {if $sMentionsLegales}<sup>{$car.PricebackLink}</sup>{/if}{else}{$car.PRIX_CATALOGUE_STRING} {if $sMentionsLegales}<sup>{$car.PricebackLink}</sup>{/if}{/if}</div>

											{assign var="carstore_mentions" value=$car.RecoveryMention|strpos:" "}
											{if $car.RecoveryMention}<p class="offer">{'OFFRE_SOUS_CONDITION'|t}<sup>{$car.RecoveryMention|substr:0:$carstore_mentions|strip_tags:false}</sup></p>{/if}

											{if $car.InternetPrice neq 0 && $car.InternetPrice neq $car.CatalogPrice}
												<div class="advice">
													{'PRIX_CONSEILLE'|t}
													<strong class="strike">{$car.PRIX_CATALOGUE_STRING|number_format:0:".":" "} {$sDevise}</strong>
												</div>
												{if $car.SOIT_ECO}
													<div class="economy">
														{$car.SOIT_ECO}
													</div>
												{/if}
											{/if}
											{if $car.StoreDetailUrl}
												<ul class="actions">
													<li class="red"><a href="{urlParser url=$car.StoreDetailUrl}" {gtm action="Carstore" data=$aData datasup=['eventCategory'=>'Showroom::Carstore','eventLabel'=>{'PROFITER_OFFRE'|t}]} target="_blank">{'PROFITER_OFFRE'|t}</a></li>
												</ul>
											{/if}
										</div>
									</div>
								{/foreach}
							</div>
						</div>

					{elseif $aVehicules.LOW_KM|@sizeof ==0 }
						<div class="row of3">
							<div class="col span2">
								{'NO_RESULTS_VEHICULES_NEUFS'|t}
							</div>
						</div>
					{/if}
					{if $aVehicules.LOW_KM|@sizeof > 0}
						<div id="resultVN" class="tab clsvehiculeneuf VN">
							<p class="subtitle tabtitle  masterVn"><span>VÉHICULES FAIBLE KILOMÉTRAGE</span></p>
							<div id='allNewCar' class="stocks">
								<input type="hidden" name="iCount" id="iCount" value="2"/>
								<input type="hidden" name="zid" id="zid" value="{$aData.ZONE_ID}"/>
								<input type="hidden" name="zType" id="zType" value="{$aData.ZONE_TITRE3}"/>
								<input type="hidden" name="zidVN" id="zidVN" value="{$aData.ZONE_ID}"/>
								<input type="hidden" name="zorderVN" id="zorderVN" value="{$aData.ZONE_ORDER}"/>
								<input type="hidden" name="zareaVN" id="zareaVN" value="{$aData.AREA_ID}"/>
								<input type="hidden" name="ZONE_SKIN" id="ZONE_SKIN" value="{$aData.ZONE_SKIN}"/>
								<input type="hidden" name="zType" id="zType" value="{$aData.ZONE_TITRE3}"/>
								<input type="hidden" name="countryCode" id="countryCode" value="{$countryCode}"/>
								{foreach from=$aVehicules.LOW_KM item=car name=listeCar}
									<div class="row of4 item {$aData.ZONE_SKIN}">
										<figure class="col" {if $car.POURCENTAGE && $active}data-minus="-{$car.POURCENTAGE}%"{/if}>
											<img class="lazy" src="{$car.IMAGE}" data-original="{$car.IMAGE}" width="206" height="116"
												 alt="{$car.CommercialLabel}" />
											<noscript><img src="{$car.IMAGE}" width="206" height="116"
														   alt="{$car.CommercialLabel}" /></noscript>
											<figcaption>
												<p {if ($aData.PRIMARY_COLOR|count_characters)==7 } style="color:{$aData.PRIMARY_COLOR};" {/if}>{$car.DISPONIBLE_SOUS}</p>
												{if $car.StockLevel==60}<div class="infosup">{'VEHICULE_FAIBLE_KILOMETRAGE'|t}</div>{/if}
											</figcaption>
										</figure>
										<div class="col span2 detail">
											<h4 class="parttitle" {if ($aData.SECOND_COLOR|count_characters)==7 } style="font-weight:bold;color:{$aData.SECOND_COLOR};" {/if}>{$car.CommercialLabel}</h4>
											<p>
												{'COLOR'|t} : {$car.ExtFeatureLabel}<br />
												{'GARNISSAGE'|t} : {$car.IntFeatureLabel}<br />
											</p>
											{if $car.FEATURES|@sizeof > 0}
												<p>
													<span class="colored" {if ($aData.PRIMARY_COLOR|count_characters)==7 } style="color:{$aData.PRIMARY_COLOR};" {/if}>{if $OptionTraduction}{$OptionTraduction}{else}Option(s){/if}</span><!-- CPW-3861 CLEF DE TRADUCTION -->
													<a class="tooltip" href="#carStoreTooltip_{$smarty.foreach.listeCar.iteration}">?</a>
												<div class="legal layertip" id="carStoreTooltip_{$smarty.foreach.listeCar.iteration}">
													{foreach from=$car.FEATURES item=carFeature name=listeCarFeature}

														{$carFeature}<br/>
													{/foreach}
												</div>
												</p>
											{/if}
											<div class="legal legaltext"><p>
													{'CONSO_MIXTE'|t} ({$sConso}): {$car.ConsoMixte}<br />
													{'EMISSION_CO2_FRONT'|t} ({$sEmission}) : {$car.CO2Rate} {if $active}<img src="{Pelican::$config.MEDIA_HTTP}/design/frontend/images/picto/{$car.BILAN_CARBONE}.gif" width="25" height="12" alt="A" />{/if}<br /><p>{$car.RecoveryMention}</p>
												</p></div>
										</div>
										<div class="col cost">
											<div class="price" {if ($aData.PRIMARY_COLOR|count_characters)==7 } style="color:{$aData.PRIMARY_COLOR};" {/if}>{if
												$car.InternetPrice}{$car.PRIX_WEBSTORE_STRING|number_format:0:".":" "} {$sDevise} {'CASH_PRICE_TTC'|t} {if $sMentionsLegales}<sup>{$car.PricebackLink}</sup>{/if}{else}{$car.PRIX_CATALOGUE_STRING} {if $sMentionsLegales}<sup>{$car.PricebackLink}</sup>{/if}{/if}</div>

											{assign var="carstore_mentions" value=$car.RecoveryMention|strpos:" "}
											{if $car.RecoveryMention}<p class="offer">{'OFFRE_SOUS_CONDITION'|t}<sup>{$car.RecoveryMention|substr:0:$carstore_mentions|strip_tags:false}</sup></p>{/if}

											{if $car.InternetPrice neq 0 && $car.InternetPrice neq $car.CatalogPrice}
												<div class="advice">
													{'PRIX_CONSEILLE'|t}
													<strong class="strike">{$car.PRIX_CATALOGUE_STRING|number_format:0:".":" "} {$sDevise}</strong>
												</div>
												{if $car.SOIT_ECO}
													<div class="economy">
														{$car.SOIT_ECO}
													</div>
												{/if}
											{/if}
											{if $car.StoreDetailUrl}
												<ul class="actions">
													<li class="red"><a href="{urlParser url=$car.StoreDetailUrl}" {gtm action="Carstore" data=$aData datasup=['eventCategory'=>'Showroom::Carstore','eventLabel'=>{'PROFITER_OFFRE'|t}]} target="_blank">{'PROFITER_OFFRE'|t}</a></li>
												</ul>
											{/if}
										</div>
									</div>
								{/foreach}
							</div>
						</div>
					{/if}

					{if $aVehicules.LOW_KM|@sizeof > 0 ||  $aVehicules.VN|@sizeof > 0}
						{*if $aVehicules.COUNT > 4*}
						<section class="{$aData.ZONE_SKIN} row of6 clslanguette">
							<div class="caption addmore folder"  {if ($aData.PRIMARY_COLOR|count_characters)==7 }  data-off="border:4px solid {$aData.PRIMARY_COLOR}; color:{$aData.PRIMARY_COLOR};" data-hover="border:4px solid {$aData.PRIMARY_COLOR}; color:{$aData.PRIMARY_COLOR};"{/if} data-toggle="{'VOIR_STOCK'|t}">
								<a  href="{urlParser url=$sUrlCarStore}" target="_blank" {gtm action="SeeMoreResults" data=$aData datasup=['eventCategory' =>'Showroom::Carstore','eventLabel'=>{'VOIR_STOCK'|t}]}>{'VOIR_STOCK'|t}</a>
							</div>
						</section>
						{*/if*}
						{if $sMentionsLegales}
							<div class="legal" style="margin-bottom: 0px;">
								<p>
									{$sMentionsLegales}
								</p>
							</div>
						{/if}
					{/if}
				</div>
			{/if}


		</section>
	</div>

{/if}

{literal}
	<script type="application/javascript">
		$('.sliceCarStoreDesk .tabbed').each(function () {
			new Tabbs($(this));
		});
	</script>
{/literal}