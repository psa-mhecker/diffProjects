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

</style>
{/literal}
{/if}

{if $aVehicules.CARS|@sizeof > 0}
<div id='allNewCar' class="stocks {if ($aData.PRIMARY_COLOR|count_characters)==7 }showroom{/if}">
	<input type="hidden" name="iCount" id="iCount" value="2"/>
	<h3 class="parttitle bold" {if ($aData.SECOND_COLOR|count_characters)==7 } style="color:{$aData.SECOND_COLOR};" {/if}>{'VEHICULES_DISPO_CONCESSIONNAIRE'|t} {$aDealer.name}</h3>
	{foreach from=$aVehicules.CARS item=car name=listeCar}
		<div class="row of4 item {$aData.ZONE_SKIN}">

			<figure class="col" {if $car.POURCENTAGE && $active && $car.StockLevel!=60}data-minus="-{$car.POURCENTAGE}%"{/if}>
				<img class="lazy" src="{$car.VehicleImage}" data-original="{$car.VehicleImage}" width="206" height="116" alt="{$car.VehicleCommercialLabel}" />
				<noscript><img src="{$car.VehicleImage}" width="206" height="116" alt="{$car.VehicleCommercialLabel}" /></noscript>
				<figcaption>
					<p {if ($aData.PRIMARY_COLOR|count_characters)==7 } style="color:{$aData.PRIMARY_COLOR};" {/if}>{'DISPO_SOUS'|t|replace:'#STOCK#':$car.VehicleAvailability}</p>
					{if $car.VehicleStockLevel==60}<div class="infosup">{'VEHICULE_FAIBLE_KILOMETRAGE'|t}</div>{/if}
				</figcaption>
			</figure>
			<div class="col span2 detail">
				<h4 class="parttitle" {if ($aData.SECOND_COLOR|count_characters)==7 } style="color:{$aData.SECOND_COLOR};" {/if}>{$car.VehicleCommercialLabel}</h4>
				<p>
					{'COLOR'|t} : {$car.VehicleFeatureExtLabel}<br />
					{'GARNISSAGE'|t} : {$car.VehicleFeatureIntLabel}<br />
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
				<div class="legal"><p>
					{'CONSO_MIXTE'|t} ({$sConso}): {$car.VehicleUrbanConsoMixt}<br />
					{'EMISSION_CO2_FRONT'|t} ({$sEmission}) : {$car.VehicleCO2Rate} {if $active}<img src="{Pelican::$config.MEDIA_HTTP}/design/frontend/images/picto/{$car.BILAN_CARBONE}.gif" width="25" height="12" alt="{$car.BILAN_CARBONE}" />{/if}<br />
				</p></div>
				{if $aDealer.name}
					<div class="adress legal">
						<p>
							{$aDealer.name}<br/>
							{$aDealer.address}<br/>
							
						</p>
					</div>
				{/if}
			</div>
			<div class="col cost">
				<div class="price" {if ($aData.PRIMARY_COLOR|count_characters)==7 } style="color:{$aData.PRIMARY_COLOR};" {/if}>{if $car.VehicleWebstorePrice}{$car.PRIX_WEBSTORE_STRING|number_format:0:".":" "} {$sDevise} {if $sMentionsLegales}<sup>{$car.PricebackLink}</sup>{/if}{else}{$car.PRIX_CATALOGUE_STRING}{if $sMentionsLegales}<sup>{$car.PricebackLink}</sup>{/if}{/if}</div>
				{if $car.VehicleWebstorePrice neq 0 && $car.VehiclePriceCatalogue neq $car.VehicleWebstorePrice}
					<p>
						{'PRIX_CONSEILLE'|t} <strong class="strike">{$car.PRIX_CATALOGUE_STRING|number_format:0:".":" "} {$sDevise}</strong><br />
						{if $car.SOIT_ECO}{$car.SOIT_ECO}<br />{/if}
					</p>
				{/if}
				{if $car.VehicleWebstoreLink}
				<ul class="actions">
					<li class="red"><a href="{urlParser url=$car.VehicleWebstoreLink}" target="_blank" {gtm action="Carstore" data=$aData datasup=['eventCategory' =>'Showroom::Carstore','eventLabel'=>{'PROFITER_OFFRE'|t}]}>{'PROFITER_OFFRE'|t}</a></li>
				</ul>
				{/if}
			</div>
		</div>
	{/foreach}
</div>
{*if $aVehicules.COUNT > 4*}
		<section class="{$aData.ZONE_SKIN} {if ($aData.PRIMARY_COLOR|count_characters)==7 }showroom{/if} row of6 clslanguette{if ($aData.PRIMARY_COLOR|count_characters)==7 }showroom{/if}">
			<div class="caption addmore folder" {if ($aData.PRIMARY_COLOR|count_characters)==7 } data-off="border:4px solid {$aData.PRIMARY_COLOR}; color:{$aData.PRIMARY_COLOR};" data-hover="border:4px solid {$aData.PRIMARY_COLOR}; color:{$aData.PRIMARY_COLOR};"{/if} data-toggle="{'VOIR_STOCK'|t}">
			  <a {if ($aData.PRIMARY_COLOR|count_characters)==7 } class="col span2" {/if} href="{urlParser url=$sUrlCarStore}" target="_blank" {gtm action="SeeMoreResults" data=$aData datasup=['eventCategory' =>'Showroom::Carstore','eventLabel'=>{'VOIR_STOCK'|t}]}>{'VOIR_STOCK'|t}</a>
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
{'NO_RESULTS_VEHICULES_NEUFS'|t}
{/if}
