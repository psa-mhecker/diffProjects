{if $trancheConcession==1 && $user && $user->isLogged()}<section class="form">{/if}
{if $bHasDealer eq false}
<div class="clsmonprojcvnavloc">
<h2 id="{$aData.ID_HTML}" class="parttitle">{$aData.ZONE_TITRE}</h2>
<form id="locator-form-{$aData.ZONE_TEMPLATE_ID}" class="locator locatorMesCS alt row of12" data-config="/_/Layout_Citroen_MonProjet_ConcessionVNAV/getMapConfiguration" data-list="/_/Layout_Citroen_MonProjet_ConcessionVNAV/getStoreList" data-page="{$aData.PAGE_ID}" data-version= "{$aData.PAGE_VERSION}" data-area="{$aData.AREA_ID}" data-order="{$aData.ZONE_ORDER}" data-ztid="{$aData.ZONE_TEMPLATE_ID}" data-details="/_/Layout_Citroen_PointsDeVente/getDealer" data-path="{Pelican::$config.MEDIA_HTTP}/" data-dom="map-canvas-{$aData.ZONE_TEMPLATE_ID}"  novalidate>
    <fieldset class="col span9 geo">
        <div class="lined">
            <div class="adapt">
                <div class="field include">
                    <input type="text" name="address" id="address{$aData.ZONE_TEMPLATE_ID}" placeholder="{'CP_CITY_DEP'|t}" />
                </div>
                <input type="submit" name="register" value="OK" />
            </div>
            <span class="geoChoice">ou</span>
            <div class="geoButton"><a class="geoloc" href="#LOREM">{'GEOLOCALIZE_ME'|t}</a></div>
        </div>
    </fieldset>
</form>
<div id="locations-{$aData.ZONE_TEMPLATE_ID}" class="locations storechoice">
    <div class="parttitle" data-result data-search="{'RESULTS_GMAP'|t}" data-geo="{'RESULTS_GEO_GMAP'|t}"></div>

    {literal}
    <div class="stores">

        <script type="text/template" id="itemTplDealer">
            <input type="radio" name="dealer" value="<%= data.id %>" id="dealer<%= data.id %>" />

            <label for="dealer<%= data.id %>" class="item">
            <span class="name"><%= data.name %></span>
            <span class="details">
            <% if(data.distance){ %><strong><%= Math.round(data.distance*100)/100 %> Km</strong><br /><% }; %>
            <%= data.address %><br />
            <% if(data.phone){ %>{/literal}{'TEL'|t}{literal} <%= data.phone %><br /><% }; %>
            </span>
            <% if(data.services){ %>
            <ul class="options">
            <% _.each(data.services,function(option,i){ %>
            <li><img src="{/literal}{Pelican::$config.MEDIA_HTTP}{literal}<%= services[option].img %>" alt="<%= services[option].label %>" /></li>
            <% }); %>
            </ul>
            <% }; %>
            </label>
        </script>
        <div class="scroll">
            <div class="items" data-more="<div class='addmore'><a href='#0'{/literal}>{'MORE_PDV'|t}{literal}</a></div>"></div>
        </div>
    </div>
    {/literal}
    <div id="map-canvas-{$aData.ZONE_TEMPLATE_ID}" class="map-canvas"></div>
    {literal}
    <script type="text/template" class="prompt">
        <div class="prompt" id="prompt<%= id %>">
        <input type="hidden" id="SAVChosen" name="SAVChosen" value="<%= id %>" />
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco lmaboris nisi ut aliquip ex ea commodo consequat.</p>
        <ul class="actions clean">
        <li class="green"><a id="add_to_favs_<%= id %>" href="#<%= id %>#{/literal}{$aData.ZONE_PARAMETERS}{literal}" onclick="javascript:concession.addToFavs(this);return false;">{/literal}{'ADD_FAVORITE'|t}{literal}</a></li>
        <li class="green"><a id="show_details_<%= id %>" href="#<%= id %>">{/literal}{'SHOW_DETAIL_CONESSION'|t}{literal}</a></li>
        </ul>
        </div>
    </script>
    {/literal}
    {literal}
    <script type="text/template" class="storeTpl">
            <div class="closer"></div>
            <div class="cumulative row of3">
                <div class="caption">
                    <h3 class="subtitle"><%= data.name %></h3>
                </div>{/literal}
                {if $pelican_config.SITE.INFOS.SITE_ACTIVATION_MON_PROJET == 1}
                    <div class="bookmarks">
                        <a href="javascript://">{'ADD_FAVORITE'|t}</a>
                    </div>
                {/if}{literal}
            </div>
            <div class="tabbed">
                <div class="tabs"></div>
                <div class="tab">
                    <div class="cumulative row of2">
                        <h4 class="caption subtitle tabtitle"><span>{/literal}{'INFOS_GENE'|t}{literal}</span></h4>
                        <div class="col">
                            <%= data.address %><br />
                            <% if(data.phone){ %><div class="phone">{/literal}{'TEL'|t}{literal} <%= data.phone %></div><% }; %>
                            <% if(data.fax){ %><div class="fax">{/literal}{'FAX'|t}{literal} <%= data.fax %></div><% }; %>
                            <% if(data.email){ %><div class="email"><a href="mailto:<%= data.email %>"><%= data.email %></a></div><% }; %>
                            <br />
                        </div>
                        <div class="col">
                            <ul class="actions">
                                <% if(data.route){ %>
                                <li class="blue"><a href="<%= data.route %>" target="_blank">{/literal}{'ITINERAIRE'|t}{literal}</a></li>
                                <% }; %>
                                <% if(data.web){ %>
                                <li class="blue"><a href="<%= data.web %>" target="_blank">{/literal}{'VISIT_WEBSITE'|t}{literal}</a></li>
                                <% }; %>
                            </ul>
                        </div>
                        <p class="caption"><%= data.timetable %></p>
                    </div>
                    {/literal}
                        {if $aOutil|@sizeof > 0}
                            <div class="tools">
                                <ul>
                                    {foreach from=$aOutil item=outil name=listeOutil}

                                        {if $outil.BARRE_OUTILS_MODE_OUVERTURE == 1}
                                            {assign var='target' value='_self'}
                                        {elseif $outil.BARRE_OUTILS_MODE_OUVERTURE == 2}
                                            {assign var='target' value='_blank'}
                                        {elseif $outil.BARRE_OUTILS_MODE_OUVERTURE == 3}
                                            {assign var='target' value=''}
                                        {/if}

                                {if $outil.BARRE_OUTILS_MODE_OUVERTURE == 3}
                                    {assign var="ouverture" value='outil_clic_sur_un_outil_en_mode_de_layer'}
                                    {assign var="aVal" value=['value'=>$i|cat:' '|cat:$aVehicule.VEHICULE_LCDV6_CONFIG|cat:' '|cat:$aData.SITE_ID]}
                                {else}
                                    {assign var="ouverture" value='outil_clic_sur_un_outil_en_mode_ouverture_de_page'}
                                    {assign var="aVal" value=['value'=>$i|cat:' '|cat:$aVehicule.VEHICULE_LCDV6_CONFIG|cat:' '|cat:$aData.SITE_ID]}
                                {/if}
                                        <li {if $outil.BARRE_OUTILS_MODE_OUVERTURE == 3}class="folder"{/if}><a href="{urlParser url=$outil.BARRE_OUTILS_URL_WEB}" {if $outil.BARRE_OUTILS_MODE_OUVERTURE == 2}target="_blank"{/if} {if $target}target="{$target}"{/if} {gtm name=$ouverture data=$aData datasup=$aVal labelvars=['%type outils%'=> $outil[i].TYPE_OUTIL,'%id de outils%'=>$outil[i].BARRE_OUTILS_FORMULAIRE,'%id sitegeo%'=>$aData.SITE_ID, '%LCDV%'=>$aVehicule.VEHICULE_LCDV6_CONFIG]}><span>{$outil.BARRE_OUTILS_TITRE}</span></a></li>

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
                                <li class="col"><img src="{/literal}{Pelican::$config.MEDIA_HTTP}{literal}<%= services[option].big %>" alt="<%= services[option].label %>" title="<%= services[option].label %>" /><%= services[option].label %></li>
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
                                <p id="sync<%= i %>" class="bordered hidden"><%= contacts.timetable %></p>
                                <% }); %>
                            </div>
                            <% if(data.contacts){ %>
                            <% _.each(data.contacts,function(contacts,i){ %>
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
                            <% }); %>
                            <% }; %>
                        </div>
                    </div>
                <% }; %>
            </div>
        </script>
        {/literal}
        <div class="store"></div>
</div>
</div>
{else}

<div class="static clsmonprojcvnav">
    <h2 class="parttitle">{'VOTRE_CONCESSION_VEHICULE_NEUF'|t}</h2>
    <a href="#{$aDealers.{$aData.ZONE_PARAMETERS}.id}#{$aData.ZONE_PARAMETERS}" onclick="javascript:concession.deleteFromFavs(this);return;" class="edit">{'EDIT_CONCESSION_FAVS'|t}</a>
    <div class="store">
        <div class="row of4">
            <div class="caption">
                <img class="media noscale" src="img/media/store.png" alt="" />
                <h3 class="subtitle">{$aDealers.{$aData.ZONE_PARAMETERS}.name}</h3>
            </div>

            <div class="row of3">
                <div class="col span2">

                    <div class="tabbed bounded">

                        <div class="tabs"></div>

                        <div class="tab">

                            <div class="cumulative row of2">

                                <h4 class="caption subtitle tabtitle"><span>{'INFOS_GENE'|t}</span></h4>

                                <div class="col">
                                    {$aDealers.{$aData.ZONE_PARAMETERS}.adress}
                                    <div class="phone">{'TEL'|t} : {$aDealers.{$aData.ZONE_PARAMETERS}.phone}</div>
                                    <div class="fax">{'FAX'|t} : {$aDealers.{$aData.ZONE_PARAMETERS}.fax}</div>
                                    <div class="email"><a href="{$aDealer.email}">{$aDealers.{$aData.ZONE_PARAMETERS}.email}</a></div>
                                    <br>
                                </div>

                                <div class="col">
                                    <ul class="actions">
                                        <li class="blue"><a href="{urlParser url=$aDealers.{$aData.ZONE_PARAMETERS}.route}" target="_blank">{'ITINERAIRE'|t}</a></li>
                                        <li class="blue"><a href="{urlParser url=$aDealers.{$aData.ZONE_PARAMETERS}.web}" target="_blank">{'VISIT_WEBSITE'|t}</a></li>
                                    </ul>
                                </div>

                                <div class="caption"><p>{$aDealers.{$aData.ZONE_PARAMETERS}.timetable}</p></div>

                            </div>
                            <!-- /.row -->
                            {if $aOutil|@sizeof > 0}
                                <div class="tools">
                                    <ul>
                                        {foreach from=$aOutil item=outil name=listeOutil}
                                            <li {if $outil.BARRE_OUTILS_MODE_OUVERTURE}class="folder"{/if}><a href="{urlParser url=$outil.BARRE_OUTILS_URL_WEB}" {if $outil.BARRE_OUTILS_MODE_OUVERTURE == 2}target="_blank"{/if}><span>{$outil.BARRE_OUTILS_TITRE}</span></a></li>
					{/foreach}
                                    </ul>
				</div>
                            {/if}

                            <!-- /.tools -->

                            <div class="options">
                                <h4 class="parttitle">{'LES_SERVICES'|t}</h4>
                                <ul class="row of2">
                                    {foreach from=$aDealers.{$aData.ZONE_PARAMETERS}.servicesMob item=service}
                                    <li class="col"><img class="noscale" src="{Pelican::$config.IMAGE_FRONT_HTTP}/picto/service0{$service.code}.png" alt="{$service.label}" />{$service.label}</li>
                                    {/foreach}
                                </ul>
                            </div>
                            <!-- /.options -->

                            <div class="options">
                                <h4 class="parttitle">{'LES_PRESTATIONS'|t}</h4>
                                {foreach from=$aDealers.{$aData.ZONE_PARAMETERS}.benefits item=benefit}
                                <p>{$benefit}</p>
                                {/foreach}
                            </div>
                            <!-- /.options -->

                            <div class="options">
                                <h4 class="parttitle">{'MOT_CONCESSION'|t}</h4>
                                <p>{$aDealers.{$aData.ZONE_PARAMETERS}.welcome}</p>
                            </div>
                            <!-- /.options -->

                        </div>
                        <!-- /.tab -->

                        <div class="tab">

                            <h4 class="subtitle tabtitle"><span>{'CONTACTS'|t}</span></h4>

                            <div class="cumulative row of2 tabbed">

                                <div class="col">

                                    <div class="tabs"></div>
                                    {foreach from=$aDealers.{$aData.ZONE_PARAMETERS}.contacts key=contactKey item=contact}
                                    <p id="sync{$contactKey}{$aData.ORDER}" class="bordered">{$contact.timetable}</p>
                                    {/foreach}

                                </div>
                                <!-- /.col -->
                                {foreach from=$aDealers.{$aData.ZONE_PARAMETERS}.contacts key=contactKey item=contacts}
                                <div class="col tab opened" data-sync="sync{$contactKey}{$aData.ORDER}">
                                    <h4 class="parttitle tabtitle"><span>{$contacts.group}</span></h4>
                                    {foreach from=$contacts.list key=contactId item=contact}

                                    <div class="item">
                                        {$contact.name|trim}<br>
                                        {$contact.office}<br>
                                        <div class="phone">{'TEL'|t} :{$contact.phone}</div>
                                        <div class="fax">{'FAX'|t} : {$contact.fax}</div>
                                        <div class="email"><a href="mailto:{$contact.email}">{$contact.email}</a></div>
                                    </div>
                                    {/foreach}
                                    <!-- /.item -->
                                </div>
                                {/foreach}
                                <!-- /.tab -->
                            </div>
                            <!-- /.tabbed -->
                        </div>
                        <!-- /.tab -->
                    </div>
                    <!-- /.tabbed -->
                </div>
                <!-- /.col -->
                <div class="col">
                    <figure>
                        <img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/1-1.png" data-original="http://maps.googleapis.com{$urlMaps}" width="288" height="288" alt="{$aDealers.{$aData.ZONE_PARAMETERS}.name}" />
                    </figure>
                </div>
                <!-- /.col -->
            </div>



        </div>
    </div>

</div>
{/if}
{if $trancheConcession==2 && $user && $user->isLogged()}</section>{/if}
