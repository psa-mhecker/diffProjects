{foreach from=$aVehicules.CARS item=car name=listeCar}
	{if $zType eq 'PDV'}
		<div class="row of4 item {$aData.ZONE_SKIN}">
			<figure class="col" {if $car.POURCENTAGE}data-minus="-{$car.POURCENTAGE}%"{/if}>
				<img class="lazy" src="{$car.VehicleImage}" data-original="{$car.VehicleImage}" width="206" height="116" alt="{$car.VehicleModelLabel|utf8_encode}" />
				<noscript><img src="{$car.VehicleImage}" width="206" height="116" alt="{$car.VehicleModelLabel|utf8_encode}" /></noscript>
				<figcaption>{$car.DISPONIBLE_SOUS}</figcaption>
			</figure>
			<div class="col span2 detail">
				<h4 class="parttitle">{$car.VehicleModelLabel|utf8_encode}</h4>
				<p>
					{'COLOR'|t} : {$car.VehicleFeatureExtLabel|utf8_encode}<br />
					{'GARNISSAGE'|t} : {$car.VehicleFeatureIntLabel|utf8_encode}<br />
				</p>
				<div class="legal"><p>
						{'CONSO_MIXTE'|t} ({$sConso}): {$car.VehicleUrbanConsoMixt}<br />
						{'EMISSION_CO2_FRONT'|t} ({$sEmission}) : {$car.VehicleCO2Rate} <img src="{Pelican::$config.MEDIA_HTTP}/design/frontend/images/picto/{$car.BILAN_CARBONE}.gif" width="25" height="12" alt="{$car.BILAN_CARBONE}" /><br />
				</p></div>
			</div>
			<div class="col cost">
				<div class="price">{if $car.VehicleWebstorePrice}{$car.VehicleWebstorePrice}{else}{$car.VehiclePriceCatalogue}{/if} {$sDevise} <sup>(1)</sup></div>
				{if $car.VehicleWebstorePrice neq 0}
					<p>
						{'PRIX_CONSEILLE'|t} <strong class="strike">{$car.VehiclePriceCatalogue} {$sDevise}</strong><br />
						{if $car.SOIT_ECO}{$car.SOIT_ECO}<br />{/if}
					</p>
				{/if}
				{if $car.VehicleWebstoreLink}
				<ul class="actions">
					<li class="red"><a href="{urlParser url=$car.VehicleWebstoreLink}" target="_blank" {gtm action="Carstore" data=$aData datasup=['eventCategory' =>'Showroom::Carstore','eventLabel'=>{'PROFITER_OFFRE'|t}]} >{'PROFITER_OFFRE'|t}</a></li>
				</ul>
				{/if}
			</div>
		</div>
	{else}
		<div class="row of4 item {$aData.ZONE_SKIN}">
			<figure class="col" {if $car.POURCENTAGE}data-minus="-{$car.POURCENTAGE}%"{/if}>
				<img class="lazy" src="{$car.IMAGE}" data-original="{$car.IMAGE}" width="206" height="116" alt="{$car.ModelLabel|utf8_encode}" />
				<noscript><img src="{$car.IMAGE}" width="206" height="116" alt="{$car.ModelLabel|utf8_encode}" /></noscript>
				<figcaption>{$car.DISPONIBLE_SOUS|utf8_encode}</figcaption>
			</figure>
			<div class="col span2 detail">
				<h4 class="parttitle">{$car.ModelLabel|utf8_encode}</h4>
				<p>
					{'COLOR'|t} : {$car.ExtFeatureLabel|utf8_encode}<br />
					{'GARNISSAGE'|t} : {$car.IntFeatureLabel|utf8_encode}<br />
				</p>
				<div class="legal"><p>
					{'CONSO_MIXTE'|t} ({$sConso}): {$car.ConsoMixte}<br />
					{'EMISSION_CO2_FRONT'|t} ({$sEmission}) : {$car.CO2Rate} <img src="{Pelican::$config.MEDIA_HTTP}/design/frontend/images/picto/{$car.BILAN_CARBONE}.gif" width="25" height="12" alt="A" /><br />
				</p></div>
				{if $car.NearestDealerName && $zType eq 'CP_CITY'}
				<div class="adress legal"><p>
					{$car.NearestDealerName|utf8_encode}<br/>
					{$car.NearestDealerAddress1} {$car.NearestDealerAddress2|utf8_encode}<br/>
					{$car.NearestDealerZipCode} {$car.NearestDealerCity|utf8_encode}
				</p></div>
				{/if}
			</div>
			<div class="col cost">
				<div class="price">{if $car.InternetPrice}{$car.PRIX_WEBSTORE_STRING}{else}{$car.PRIX_CATALOGUE_STRING}{/if}</div>
				{if $car.InternetPrice neq 0}
					<p>
						{'PRIX_CONSEILLE'|t} <strong class="strike">{$car.PRIX_CATALOGUE_STRING}</strong><br />
						{if $car.SOIT_ECO}{$car.SOIT_ECO}<br />{/if}
					</p>
				{/if}
				{if $car.StoreDetailUrl}
				<ul class="actions clean center">
					<li class="red"><a href="{urlParser url=$car.StoreDetailUrl}" target="_blank" {gtm action="Carstore" data=$aData datasup=['eventCategory' =>'Showroom::Carstore','eventLabel'=>{'PROFITER_OFFRE'|t}]}>{'PROFITER_OFFRE'|t}</a></li>
				</ul>
				{/if}
			</div>
		</div>
	{/if}
{/foreach}