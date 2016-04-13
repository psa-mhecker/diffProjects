{if ($aData.PRIMARY_COLOR|count_characters)==7 }
	<style>
		.showroom .clsformlocator.locator.locatorVN {literal}{{/literal}
			background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
		{literal}}{/literal}

		.stocks > .item::before {literal}{{/literal}
			background: none;
		{literal}}{/literal}

		.showroom .clsformlocator.locator legend {literal}{{/literal}
			display: none;
		{literal}}{/literal}

		.showroom .clsformlocator .field.include input[type="text"] {literal}{{/literal}
			background: #ffffff;
			border: 2px solid {$aData.PRIMARY_COLOR};
			border-radius: 0px;
		{literal}}{/literal}

		.showroom .clsformlocator .field.include + input[type="submit"]
		{literal}{{/literal}
			color : #ffffff;
			background-color : {$aData.PRIMARY_COLOR};
			border-radius: 0px;
		{literal}}{/literal}

		.showroom .geoloc {literal}{{/literal}
			background: #ffffff;
			border: 2px solid {$aData.PRIMARY_COLOR};
			border-radius: 0px;
		{literal}}{/literal}

		.clsstocks.showroom .geoloc:before {literal}{{/literal}
			color:{$aData.PRIMARY_COLOR}
		{literal}}{/literal}
                
                .sliceCarStoreDesk .locator .geo input.btn_geook[type=submit],
                .sliceCarStoreDesk form.clsformlocator a.geoloc {literal}{{/literal}
                    background-color: {$aData.PRIMARY_COLOR}
		{literal}}{/literal}
                .sliceCarStoreDesk .locator .geo input.btn_geook[type=submit]:hover,
                .sliceCarStoreDesk form.clsformlocator a.geoloc:hover{literal}{{/literal}
                    background: #ffffff;
                    border: 4px solid {$aData.PRIMARY_COLOR};
                    color:{$aData.PRIMARY_COLOR};
		{literal}}{/literal}
                .sliceCarStoreDesk form.clsformlocator a.geoloc:hover:before{literal}{{/literal}
                    color:{$aData.PRIMARY_COLOR};
		{literal}}{/literal}
	</style>


{/if}


{if $bTrancheVisible}
	<div class="sliceNew sliceCarStoreDesk">
		<section id="{$aData.ID_HTML}" class="stocks clsstocks {$aData.ZONE_SKIN}">
			<div class="sep {$aData.ZONE_SKIN}"></div>
			{if $aData.ZONE_TITRE neq '' || $aData.ZONE_TITRE2 neq '' || $aData.ZONE_TEXTE neq ''}
				<div class="row of3 wrapperHead">
					{if $aData.ZONE_TITRE neq ''}<h2 class="subtitle" {if ($aData.PRIMARY_COLOR|count_characters)==7 } style="color:{$aData.PRIMARY_COLOR};" {/if}>{$aData.ZONE_TITRE|escape}</h2>{/if}
					{if $aData.ZONE_TITRE2 neq ''}<h3 class="parttitle" {if ($aData.SECOND_COLOR|count_characters)==7 } style="color:{$aData.SECOND_COLOR};" {/if}>{$aData.ZONE_TITRE2|escape}</h3>{/if}
					{if $aData.ZONE_TEXTE neq ''}<div class="zonetexte">{$aData.ZONE_TEXTE|replace:"#MEDIA_HTTP#":Pelican::$config.MEDIA_HTTP}</div>{/if}
				</div>
			{/if}
			{if $bBE}
				<form class="clsformlocator locator locatorVN" name="newCarBelgium" novalidate="" style="position: relative;">
					<fieldset class="geo">
						{*<legend>{'FIND_STORE'|t}</legend>*}
						<div class="lined">
							<div class="adapt">
								<div class="field include">
									<input type="text" name="address" id="address" placeholder="{'CP_CITY_DEP'|t}" {if ($aData.PRIMARY_COLOR|count_characters)==7 } style="border:2px solid {$aData.PRIMARY_COLOR};" {/if} />
								</div>
								<input class="btn_geook" {gtm action='Search' data=$aData datasup=['eventLabel'=>{'OK'|t}] } type="submit" name="newCarBelgiumSub" value="{'OK'|t}" />
							</div>
							<span class="geoChoice">{'OR'|t}</span>
							<div class="geoButton"><a {gtm action='Search' data=$aData datasup=['eventLabel'=>{'GEOLOCALIZE_ME'|t}] } class="geoloc">{'GEOLOCALIZE_ME'|t}</a></div>
							<input type="hidden" name="zidVN" id="zidVN" value="{$aData.ZONE_ID}"/>
							<input type="hidden" name="zorderVN" id="zorderVN" value="{$aData.ZONE_ORDER}"/>
							<input type="hidden" name="zareaVN" id="zareaVN" value="{$aData.AREA_ID}"/>
							<input type="hidden" name="maxDistance" id="maxDistance" value="{$aData.ZONE_ATTRIBUT}"/>
							<input type="hidden" name="ZONE_SKIN" id="ZONE_SKIN" value="{$aData.ZONE_SKIN}"/>
							<input type="hidden" name="zType" id="zType" value="{$aData.ZONE_TITRE3}"/>
							<input type="hidden" name="countryCode" id="countryCode" value="{if $countryCode == 'CT'}FR{else}{$countryCode}{/if}"/>
							<input type="hidden" name="lng" id="lng" value="0" />
							<input type="hidden" name="lat" id="lat" value="0" />
							<input type="hidden" name="form_page_id" id="form_page_id" value="{$smarty.get.pid}" />
							<input type="hidden" name="groupvnlowkm" id="groupvnlowkm" value="{$aData.ZONE_ATTRIBUT2}" />
						</div>
					</fieldset>
				</form>
			{/if}
			{if $bFR}
				<form class="clsformlocator locator locatorVN" data-config="/_/Layout_Citroen_VehiculesNeufs/getMapConfiguration" data-list="/_/Layout_Citroen_VehiculesNeufs/getStoreList/"  data-attribut="{$aData.ZONE_ATTRIBUT}" data-page="{$aData.PAGE_ID}" data-version= "{$aData.PAGE_VERSION}" data-area="{$aData.AREA_ID}" data-order="{$aData.ZONE_ORDER}" data-ztid="{$aData.ZONE_TEMPLATE_ID}" data-details="/_/Layout_Citroen_VehiculesNeufs/getDealer"  data-dom="map-canvas_{$iPosition}" data-path="{Pelican::$config.MEDIA_HTTP}/" novalidate>
					<fieldset class="geo">
						<legend>{'FIND_STORE'|t}</legend>
						<div class="lined">
							<div class="adapt">
								<div class="field include">
									<input type="text" name="address" id="address" placeholder="{'CP_CITY_DEP'|t}" />
								</div>
								<input {gtm action='Search' data=$aData datasup=['eventLabel'=>{'OK'|t}] } type="submit" name="register" value="{'OK'|t}" />
							</div>
							<span class="geoChoice">{'OR'|t}</span>
							<div class="geoButton"><a  {gtm action='Search' data=$aData datasup=['eventLabel'=>{'GEOLOCALIZE_ME'|t}] } class="geoloc">{'GEOLOCALIZE_ME'|t}</a></div>
							<input type="hidden" name="zidVN" id="zidVN" value="{$aData.ZONE_ID}"/>
							<input type="hidden" name="zorderVN" id="zorderVN" value="{$aData.ZONE_ORDER}"/>
							<input type="hidden" name="zareaVN" id="zareaVN" value="{$aData.AREA_ID}"/>
							<input type="hidden" name="zType" id="zType" value="{$aData.ZONE_TITRE3}"/>
							<input type="hidden" name="storeId" id="storeId" value="" />
							<input type="hidden" name="storeRRDI" id="storeRRDI" value="" />
							<input type="hidden" name="ZONE_SKIN" id="ZONE_SKIN" value="{$aData.ZONE_SKIN}"/>
							<input type="hidden" name="lng" id="lng" value="0" />
							<input type="hidden" name="lat" id="lat" value="0" />
							<input type="hidden" name="form_page_id" id="form_page_id" value="{$smarty.get.pid}" />
							<input type="hidden" name="groupvnlowkm" id="groupvnlowkm" value="{$aData.ZONE_ATTRIBUT2}" />
						</div>
					</fieldset>
				</form>
			{literal}
				<div class="clslocator locations">
				<script type="text/template" class="filtersTpl">
					<form class="mapFilters" novalidate>
						<span>FILTRES</span>
						<ul>
							<% _.each(services,function(service,i){ %>
							<li><input type="checkbox" name="filter<%= service.index %>" value="<%= service.index %>" id="filter<%= service.index %>" checked="checked" /><label for="filter<%= service.index %>"><%= service.label %></label></li>
							<% }); %>
						</ul>
					</form>
				</script>
				<div class="stores">
				<div class="parttitle stores-results" data-search="{/literal}{'RESULTS_GMAP'|t}{literal}" data-geo="{/literal}{'RESULTS_GEO_GMAP'|t}{literal}" data-noresult="{/literal}{'NO_RESULTS_FOUND_FOR_YOUR_SEARCH'|t}{literal}"  data-searchresult="{/literal}{'SEARCH_IN_PROGRESS'|t}{literal}"></div>
				<script type="text/template" id="itemTpl">
			<div class="item" data-storeid="<%= data.id %>">
				<% if(data.media){ %><img class="media" src="<%= data.media %>" alt="<%= data.name %>" /><% }; %>
				<div class="name"><%= data.name %></div>
				<div class="details">
				<% if(data.distance){ %><strong><%= Math.round(data.distance*100)/100 %>{/literal}{'KM'|t}{literal}</strong><br /><% }; %>
				<%= data.address %><br />
				<% if(data.phone){ %>{/literal}{'TEL'|t}{literal}<%= data.phone %><br /><% }; %>
				</div>
				<% if(data.services){ %>
				<ul class="options">
				<% _.each(data.services,function(option,i){ %>
				<li><img src="{/literal}{Pelican::$config.MEDIA_HTTP}{literal}<%= services[option].img %>" alt="<%= services[option].label %>" /></li>
				<% }); %>
				</ul>
				<% }; %>
			</div>
				</script>
				<div class="scroll">
				<div class="items" data-more="<div class='addmore'><a href='#0'>{/literal}{'MORE_PDV'|t}{literal}</a></div>"></div>
				</div>
				</div>
				<div id="{/literal}map-canvas_{$iPosition}{literal}" class="map-canvas"></div>
				<div class="maplegend">
				<span>{/literal}{'DVN_RAC'|t}{literal} <img src="{/literal}{$imgFront}{literal}/design/frontend/images/picto/legend2.png" alt="" /></span>
				<span>{/literal}{'RAC'|t}{literal} <img src="{/literal}{$imgFront}{literal}/design/frontend/images/picto/legend.png" alt="" /></span>
				</div>
				</div>
			{/literal}
			{/if}
			<div id="resultVN" class="clsvehiculeneuf"></div>
		</section>
	</div>
{/if}