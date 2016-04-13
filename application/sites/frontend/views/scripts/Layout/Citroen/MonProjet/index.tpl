{if ($aData.ZONE_SKIN == "ds") }
	{  $aData.MEA = 0 }
{/if}

{if $aData.ZONE_WEB == 1}
{if !$aData["REFERER"]}
<input type="hidden" id="isPDV" value="RTO" >
{/if}

<!-- l'ajout de class "no-marginBottom": modifier le 03.10.2014 -->
<form id="{$aData.ID_HTML}" class="{$aData.ZONE_SKIN} clsformlocator locator locatorPDV row of12 no-marginBottom" data-config="/_/Layout_Citroen_PointsDeVente/getMapConfiguration" data-list="/_/Layout_Citroen_PointsDeVente/getStoreList" data-details="/_/Layout_Citroen_PointsDeVente/getDealer" data-page="{$aData.PAGE_ID}" data-version= "{$aData.PAGE_VERSION}" data-area="{$aData.AREA_ID}" data-order="{$aData.ZONE_ORDER}" data-ztid="{$aData.ZONE_TEMPLATE_ID}"  data-path="{Pelican::$config.MEDIA_HTTP}/" data-dom="map-canvas_{$iPosition}_{$aData.AREA_ID}_{$aData.ZONE_ORDER}" data-mea="{if ($aData.MEA == 1) }true{/if}" data-filter-bar="{$aData.filter_bar}" data-brand-activity="{$aData.brand_activity}" novalidate
data-matrice-affichage="{$matriceAffichage|json_encode|htmlspecialchars}"
>


	<fieldset class="col span9 geo">
		<legend>{'FIND_STORE'|t}</legend>
		<div class="lined">
			<div class="adapt">
				<div class="field include">
					<input type="text" name="address" id="address" placeholder="{'CP_CITY_DEP'|t}" />
				</div>
				<input class="grey" type="submit" name="register" value="OK" {gtm name="pdv_validation_recherche" data=$aData labelvars=['%intitule du lien%' => 'OK']} />
			</div>
			<span class="geoChoice">{'OR'|t}</span>
			<div class="geoButton"><a class="geoloc">{'GEOLOCALIZE_ME'|t}</a></div>
		</div>
	</fieldset>

	{if $pelican_config.SITE.INFOS.SITE_ACTIVATION_MON_PROJET == 1 && $hasBookmark}
	<div class="col span3 bookmarks">
		<a href="javascript://">{'ACCESS_FAVORITES'|t}</a>
	</div>
	{/if}
</form>
<!-- filters: modifier le 03.10.2014 => 07 / 11 / 2014 -->
{literal}
	<div class="{/literal}{$aData.ZONE_SKIN}{literal} filterLocator">
		<script type="text/template" class="filtersTpl">

			<form class="mapFilters clsformlocator row no-marginTop" novalidate>
				<span>{/literal}{'FILTRES'|t}{literal}</span>
				<div>
					<ul class="of3">
						<% _.each(services,function(service,i){ %>
							{/literal}{ if $aData.ZONE_SKIN=="ds"}{literal}
							<% if (service.code!= "DS5") { %> 
								<li class="col"><input type="checkbox" name="filter<%= service.index %>" value="<%= service.index %>" id="filter<%= service.index %>" checked="checked" /><label for="filter<%= service.index %>"><% if ((service.code!= "DS1")&&(service.code!= "DS2")&&(service.code!= "DS3")&&(service.code!= "DS4")) { %><img src="{/literal}{$imgFront}{literal}/design/frontend/images/picto/services/ds/white/<%= service.code %>.png"><% } else { %><img class="pdvfilterimgmarge" src="{/literal}{$imgFront}{literal}/design/frontend/images/spacer.gif" style="width:1px;height:26px;"><% } %><%= service.label %></label></li>
							<% } %>
							{/literal}{else}{literal}
							<% if (service.code!= "DS5") { %> 
								<li class="col"><input type="checkbox" name="filter<%= service.index %>" value="<%= service.index %>" id="filter<%= service.index %>" checked="checked" /><label for="filter<%= service.index %>"><% if ((service.code!= "DS1")&&(service.code!= "DS2")&&(service.code!= "DS3")&&(service.code!= "DS4")) { %><img src="{/literal}{$imgFront}{literal}/design/frontend/images/picto/services/white/<%= service.code %>.png"><% } else { %><img class="pdvfilterimgmarge" src="{/literal}{$imgFront}{literal}/design/frontend/images/spacer.gif" style="width:1px;height:26px;"><% } %><%= service.label %></label></li>							
							<% } %>
							{/literal}{/if}{literal}
							
						<% }); %>
					</ul>
				</div>
			</form>
		</script>
	</div>
<!-- /filters -->


	<div class="{/literal}{$aData.ZONE_SKIN}{literal} locations clslocator">
		<div class="maplegend">
			<span>{/literal}{'DVN_RAC'|t}{literal}<img src="{/literal}{$imgFront}{literal}/design/frontend/images/picto/legend2.png" alt="" /></span>
			<span>{/literal}{'RAC'|t}{literal}<img src="{/literal}{$imgFront}{literal}/design/frontend/images/picto/legend.png" alt="" /></span>
		</div>
		<div class="stores">
		<div class="parttitle stores-results {/literal}{if ($aData.MEA == 1) }{literal}mea{/literal}{/if}{literal}" data-search="{/literal}{ 'RESULTS_GMAP'|t|replace:'"':'' }{literal}" data-geo="{/literal}{'RESULTS_GEO_GMAP'|t|replace:'"':''}{literal}" data-noresult="{/literal}{'NO_RESULTS_FOUND_FOR_YOUR_SEARCH'|t|replace:'"':''}{literal}"  data-searchresult="{/literal}{'SEARCH_IN_PROGRESS'|t|replace:'"':''}{literal}" data-search-mea-pdv="{/literal}{'NOUS_AVONS_TROUVE'|t|replace:'"':''}{literal} <strong>###countPdv### {/literal}{'AGENT_S'|t|replace:'"':''}{literal}</strong> {/literal}{'PRES_DE'|t}{literal} ###address###" data-search-mea-dvn="{/literal}{'NOUS_AVONS_TROUVE'|t|replace:'"':''}{literal} <span>###countDvn### {/literal}{'CONCESSION_S'|t|replace:'"':''}{literal} </span>{/literal}{'PRES_DE'|t|replace:'"':''}{literal} ###address###" data-search-mea-both="{/literal}{'NOUS_AVONS_TROUVE'|t|replace:'"':''}{literal} <span> ###countDvn### {/literal}{'CONCESSION_S'|t|replace:'"':''}{literal}</span> {/literal}{'ET'|t|replace:'"':''}{literal} <strong>###countPdv### {/literal}{'AGENT_S'|t|replace:'"':''}{literal}</strong> {/literal}{'PRES_DE'|t|replace:'"':''}{literal} ###address###" data-geo-mea-pdv="{/literal}{'NOUS_AVONS_TROUVE'|t|replace:'"':''}{literal} <span>###countDvn### {/literal}{'CONCESSION_S'|t|replace:'"':''}{literal}</span> {/literal}{'PRES_DE_VOUS'|t|replace:'"':''}{literal}" data-geo-mea-dvn="{/literal}{'NOUS_AVONS_TROUVE'|t|replace:'"':''}{literal} <strong>###countPdv### {/literal}{'AGENT_S'|t|replace:'"':''}{literal}</strong> {/literal}{'PRES_DE_VOUS'|t|replace:'"':''}{literal}" data-geo-mea-both="{/literal}{'NOUS_AVONS_TROUVE'|t|replace:'"':''}{literal} <span>###countDvn### {/literal}{'CONCESSION_S'|t|replace:'"':''}{literal}</span> {/literal}{'ET'|t|replace:'"':''}{literal} <strong>###countPdv### {/literal}{'AGENT_S'|t|replace:'"':''}{literal} </strong>{/literal}{'PRES_DE_VOUS'|t|replace:'"':''}{literal}">
		    </div>
			<script type="text/template" id="itemTpl">
			<div class="item {/literal}{if ($aData.MEA == 1) }mea{/if}{literal} <% if (data.type) { %><%= data.type %><% }; %>" data-storeid="<%= data.id %>">
				<div class="name"><%= data.name %></div>
				
			{/literal}

			{if ($aData.MEA == 1) }
			 {literal}
					<div class="type">
						<% if (data.catName=='') { %>
							<% if(data.type  == 'dvn'){ %>{/literal}{'DVN_RAC'|t}{literal}<% }; %>
							<% if(data.type  == 'pdv'){ %>{/literal}{'RAC'|t}{literal}<% }; %>
						<% } else { %>
									<% if(data.isAgent==true) { %>
										{/literal}{'RAC'|t}{literal}
									<% } else { %>
										<%=data.catName%>
								<% } %>
						<% } %>			
					</div>
			  {/literal}
			{/if}
			
			{if ($aData.ZONE_SKIN == "ds") }
				{literal}
					<div class="type" style="color:white;">
						<% if (data.catName=='') { %>
							<% if(data.type  == 'dvn'){ %>{/literal}{'DVN_RAC'|t}{literal}<% }; %>
							<% if(data.type  == 'pdv'){ %>{/literal}{'RAC'|t}{literal}<% }; %>
						<% } else { %>
									<% if(data.isAgent==true) { %>
										{/literal}{'RAC'|t}{literal}
									<% } else { %>
										<%=data.catName%>
								<% } %>
						<% } %>
					</div>
				{/literal}
			{/if}

			{literal}<!-- Hack <div class="type"><%=data.catName%>-<%=data.type%>-Agent?:<%=data.isAgent%></div -->
			{/literal}
			
				{literal}
				
				<div class="details">
					<% if(data.distance){ %><strong><%= Math.round(data.distance*10)/10 %> {/literal}{'KM'|t}{literal}</strong><br /><% }; %>
					<%= data.address %><br />
					<% if(data.phone){ %>Tel : <%= data.phone %><br /><% }; %>
				</div>
				<% if(data.services){ %>
					<ul class="options">
                    <% _.each(data.services,function(option,i){ %>
                        <% if(services[option]) { %>
						
						{/literal}{ if $aData.ZONE_SKIN=="ds"}{literal}
							<% if ((services[option].code!= "DS1")&&(services[option].code!= "DS2")&&(services[option].code!= "DS3")&&(services[option].code!= "DS4")) { %>
                            <li><img src="{/literal}{$imgFront}{literal}/design/frontend/images/picto/services/ds/<%= services[option].code %>.png" alt="<%= services[option].label %>" title="<%= services[option].label %>" /></li>
							<% } %>
						{/literal}{else}{literal}
							<% if ((services[option].code!= "DS1")&&(services[option].code!= "DS2")&&(services[option].code!= "DS3")&&(services[option].code!= "DS4")) { %>
                            <li><img src="{/literal}{$imgFront}{literal}/design/frontend/images/picto/services/<%= services[option].code %>.png" alt="<%= services[option].label %>" title="<%= services[option].label %>" /></li>
							<% } %>						
						{/literal}{/if}{literal}
							
							
							
						<% } %>
                    <% }); %>
					</ul>
				<% }; %>
				</div>

			</script>
			<div class="scroll">
				<div class="items" data-more="<div class='addmore'><a href='#0'>{/literal}{'MORE_PDV'|t}{literal}</a></div>"></div>
			</div>
		</div>
		<div id="{/literal}map-canvas_{$iPosition}_{$aData.AREA_ID}_{$aData.ZONE_ORDER}{literal}" class="map-canvas"></div>
		
		<script type="text/template" class="advisorTpl">
			<div class="module_home">
		    <div class="titre_module_home">{/literal}{'AVIS_CLIENT'|t}{literal}</div>
		    <div class="avis_module_home">
		      <div class="content_notes_module">
		        <div class="titre_notes_module">
		          <h3>{/literal}{'VENTES'|t}{literal}</h3>
		        </div>
		        <div class="notes_module">
		          <div id="vnstar1" class="etoiles_1_gris_module">
		            <img src="{/literal}{Pelican::$config.MEDIA_HTTP}{literal}/design/frontend/images/etoile_module.png" alt="etoile" />
		          </div>
		          <div id="vnstar2" class="etoiles_1_gris_module">
		            <img src="{/literal}{Pelican::$config.MEDIA_HTTP}{literal}/design/frontend/images/etoile_module.png" alt="etoile" />
		          </div>
		          <div id="vnstar3" class="etoiles_1_gris_module">
		            <img src="{/literal}{Pelican::$config.MEDIA_HTTP}{literal}/design/frontend/images/etoile_module.png" alt="etoile" />
		          </div>
		          <div id="vnstar4" class="etoiles_1_gris_module">
		            <img src="{/literal}{Pelican::$config.MEDIA_HTTP}{literal}/design/frontend/images/etoile_module.png" alt="etoile" />
		          </div>
		          <div id="vnstar5" class="etoiles_1_gris_module">
		            <img src="{/literal}{Pelican::$config.MEDIA_HTTP}{literal}/design/frontend/images/etoile_module.png" alt="etoile" />
		          </div>
		          <div class="clear"></div>
		        </div>
		        <div class="text_notes_module"><a href="<%= data.urlVn %>" target="_blank"><%= data.vn.total %> {/literal}{'NOTES'|t}{literal}</a></div>
		        <div class="clear"></div>
		      </div>
		      <div class="content_notes_module">
		        <div class="titre_notes_module">
		          <h3>{/literal}{'ATELIER'|t}{literal}</h3>
		        </div>
		        <div class="notes_module">
		          <div id="apvstar1" class="etoiles_1_gris_module">
		            <img src="{/literal}{Pelican::$config.MEDIA_HTTP}{literal}/design/frontend/images/etoile_module.png" alt="etoile" />
		          </div>
		          <div id="apvstar2" class="etoiles_1_gris_module">
		            <img src="{/literal}{Pelican::$config.MEDIA_HTTP}{literal}/design/frontend/images/etoile_module.png" alt="etoile" />
		          </div>
		          <div id="apvstar3" class="etoiles_1_gris_module">
		            <img src="{/literal}{Pelican::$config.MEDIA_HTTP}{literal}/design/frontend/images/etoile_module.png" alt="etoile" />
		          </div>
		          <div id="apvstar4" class="etoiles_1_gris_module">
		            <img src="{/literal}{Pelican::$config.MEDIA_HTTP}{literal}/design/frontend/images/etoile_module.png" alt="etoile" />
		          </div>
		          <div id="apvstar5" class="etoiles_1_gris_module">
		            <img src="{/literal}{Pelican::$config.MEDIA_HTTP}{literal}/design/frontend/images/etoile_module.png" alt="etoile" />
		          </div>
		          <div class="clear"></div>
		        </div>
		        <div class="text_notes_module"><a href="<%= data.urlApv %>" target="_blank"><%= data.apv.total %> {/literal}{'NOTES'|t}{literal}</a></div>
		        <div class="clear"></div>
		      </div>
		    </div>
		    <div class="clear"></div>
		    <a id="advisorvoirtous"  href="<%= data.urlHome %>" target="_blank">{/literal}{'VOIR_ALL_AVIS'|t}{literal}</a>
			<a id="advisorlaisseravis"  href="<%= data.urlHome %>" target="_blank">{/literal}{'PREMIER_DONNER_AVIS'|t}{literal}</a>
		    <div class="clear"></div>
		  </div>
		</script>
		<script type="text/template" class="storeTpl">

			<div class="closer"></div>
			<div class="cumulative row of3">
				<div class="caption">
					<h3 class="subtitle"><%= data.name %></h3>

				{/literal}

 			  {if ($aData.MEA == 1) }
				 {literal}
						<div class="type">
							<% if(data.type  == 'pdv'){ %>{/literal}{'RAC'|t}{literal}<% }; %>
							<% if(data.type  == 'dvn'){ %>{/literal}{'DVN_RAC'|t}{literal}<% }; %>
						</div>
				  {/literal}
				{/if}
				
				
			
				{literal}
				</div>{/literal}
				{if $pelican_config.SITE.INFOS.SITE_ACTIVATION_MON_PROJET == 1}
					<div class="bookmarks">
						<a href="javascript://">{'ADD_FAVORITE'|t}</a>
					</div>
				{/if}

				{literal}
			</div>
			<div class="tabbed">
				<div class="tabs vndetailsmenu"></div>
				<div class="tab">
					<div class="cumulative row of2">
						<h4 class="caption subtitle tabtitle"><span>{/literal}{'INFOS_GENE'|t}{literal}</span></h4>
						<div class="col">
						 	<input type="hidden" id="isGeocodeActive" value="<%= data.id %>" >

							<%= data.address %><br />
							<% if(data.phone){ %><div class="phone">{/literal}{'TEL'|t}{literal} <%= data.phone %></div><% }; %>
							<% if(data.fax){ %><div class="fax">{/literal}{'FAX'|t}{literal} <%= data.fax %></div><% }; %>
							<% if(data.email){ %><div class="email"><a href="mailto:<%= data.email %>"><%= data.email %></a></div><% }; %>
							<br />
						</div>
						<div class="col">
							<ul class="actions">
								<% if(data.route){ %>
								<li class="blue cta"><a href="<%= data.route %>" target="_blank"><span>{/literal}{'ITINERAIRE'|t}{literal}</span></a></li>
								<% }; %>
								<% if(data.web){ %>
								<li class="blue cta"><a href="<%= data.web %>" target="_blank"><span>{/literal}{'VISIT_WEBSITE'|t}{literal}</span></a></li>
								<% }; %>
                                                                
							</ul>
							
							{/literal}{if $Advisor}{literal}
							<% if(data.bAdvisor){ %>
							<div class="advisor"></div>
							<% }; %>
							{/literal}{/if}{literal}
						</div>
						<p class="caption"><%= data.timetable %></p>
					</div>
					{/literal}
						{if $aOutil|@sizeof > 0}
							<div class="tools" data-style="{strip}
                                {if $codeCouleur.default.background}background:{$codeCouleur.default.background};{/if}
                                {if $codeCouleur.default.border}border-color:{$codeCouleur.default.border};{/if}
                                {if $codeCouleur.default.color}color:{$codeCouleur.default.color};{/if}
                            {/strip}" data-style-hover="{strip}
                                {if $codeCouleur.hover.background}background:{$codeCouleur.hover.background};{/if}
                                {if $codeCouleur.hover.border}border-color:{$codeCouleur.hover.border};{/if}
                                {if $codeCouleur.hover.color}color:{$codeCouleur.hover.color};{/if}
                            {/strip}">
								<ul>
									{foreach from=$aOutil item=outil name=listeOutil}
										{$outil}

									{/foreach}
								</ul>
							</div>
						{/if}
					{literal}
					<% if(data.services){ %>
						<div class="options">
							<h4 class="parttitle">{/literal}{'LES_SERVICES'|t}{literal}</h4>
							<ul class="row of2">


                    <% _.each(data.services,function(option,i){ %>
                        <% if(services[option]) { %>
						
						{/literal}{ if $aData.ZONE_SKIN=="ds"}{literal}
							<% if ((services[option].code!= "DS1")&&(services[option].code!= "DS2")&&(services[option].code!= "DS3")&&(services[option].code!= "DS4")) { %>
                            <li class="col"><img src="{/literal}{$imgFront}{literal}/design/frontend/images/picto/services/ds/<%= services[option].code %>_big.png" alt="<%= services[option].label %>" title="<%= services[option].label %>" /><%= services[option].label %></li>
							<% } %>
						{/literal}{else}{literal}
							<% if ((services[option].code!= "DS1")&&(services[option].code!= "DS2")&&(services[option].code!= "DS3")&&(services[option].code!= "DS4")) { %>
                            <li class="col"><img src="{/literal}{$imgFront}{literal}/design/frontend/images/picto/services/<%= services[option].code %>_big.png" alt="<%= services[option].label %>" title="<%= services[option].label %>" /><%= services[option].label %></li>
							<% } %>						
						{/literal}{/if}{literal}
							
						<% } %>
                    <% }); %>
					</ul>
							
						</div>
					<% }; %>
					<% if(data.benefits){ %>
						<div class="options">
							<h4 class="parttitle">{/literal}{'LES_PRESTATIONS'|t}{literal}</h4>
							<% if(!data.benefits.substr){ %>
							<% _.each(data.benefits,function(benefit,i){ %>
								<p><%= benefit %></p>
							<% }); %>
							<% } else { %>
							<p><%= data.benefits %></p>
							<% }; %>
						</div>
					<% }; %>
					<% if(data.welcome){ %>
					<div class="options">
						<h4 class="parttitle">{/literal}{'MOT_CONCESSION'|t}{literal}</h4>
						<p><%= data.welcome %></p>
					</div>
					<% }; %>
				</div>
				<% if(data.contacts){ %>
					<div class="tab">
						<h4 class="subtitle tabtitle"><span>{/literal}{'CONTACTS'|t}{literal}</span></h4>
						<div class="cumulative row of2 tabbed">
							<div class="col">
								<div class="tabs"></div>
								<% _.each(data.contacts,function(contacts,i){ %>
								<% if(contacts.timetable){ %>
								<p id="sync<%= i %>" class="bordered hidden"><%= contacts.timetable %></p>
								<% }; %>
								<% }); %>
							</div>
							<% if(data.contacts){ %>
							<% _.each(data.contacts,function(contacts,i){ %>

								<% if(contacts.group){ %>

							<div class="col tab" data-sync="sync<%= i %>">
								<h4 class="parttitle tabtitle"><span><%= contacts.group %></span></h4>
								<% _.each(contacts.list,function(contact){ %>
								<div class="item">
									<%= contact.name %><br />
									<% if(contact.office){ %><%= contact.office %><br /><% }; %>
									<% if(contact.phone){ %><div class="phone">{/literal}{'TEL'|t}{literal} <%= contact.phone %></div><% }; %>
									<% if(contact.fax){ %><div class="fax">{/literal}{'FAX'|t}{literal} <%= contact.fax %></div><% }; %>
									<% if(contact.email){ %><div class="email"><a href="mailto:<%= contact.email %>"><%= contact.email %></a></div><% }; %>
								</div>

								<% }); %>
							</div>
								<% }; %>
							<% }); %>
							<% }; %>
						</div>
					</div>
				<% }; %>
			</div>
{/literal}
		</script>
		<div class="store"></div>
		<script type="text/template" class="bookmark">
{literal}
			<div class="prompt" id="prompt<%= id %>">
				<input type="hidden" id="SAVChosen" name="SAVChosen" value="<%= id %>" />
				<p>{/literal}{'REPLACE_FAV_PDV'|t}{literal}</p>
				<p>
					{/literal}{'ASK_REPLACE_FAV_PDV'|t}{literal}<%=name %>
				</p>
				<ul class="actions clean">
					<li class="grey"><a href="javascript://">{/literal}{'CANCEL'|t}{literal}</a></li>
					<li class="green"><a href="javascript://">{/literal}{'CONFIRM'|t}{literal}</a></li>
				</ul>
			</div>
		</script>
{/literal}
	</div>
{/if}
