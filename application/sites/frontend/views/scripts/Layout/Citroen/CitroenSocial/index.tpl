{if $aParams.ZONE_WEB}
    <div class="sliceNew sliceCitroenSocialDesk">
        <section id="{$aParams.ID_HTML}" class="networkWall wallNetwork clscitroensocial {$aParams.ZONE_SKIN}" style="padding-top: 0px;">
            <!-- /.feeds -->
            <aside>
                {if $aCountrySocialNetworks|@count}
                    <div class="bloc">
                        <h3>{$aParams.ZONE_TITRE2|escape}</h3>
                        <ul class="socials">
                            {foreach from=$aCountrySocialNetworks key=index item=aNetwork}
                                <li class="fixHeight">
                                    <a href="{urlParser url=$aNetwork.RESEAU_SOCIAL_URL_WEB}" target="{if $aNetwork.RESEAU_SOCIAL_URL_MODE_OUVERTURE == 1}_self{else}_blank{/if}" {gtm name='clic_sur_un_reseau' data=$aParams datasup=['value'=>$aNetwork.RESEAU_SOCIAL_ID] labelvars=['%id reseau social%'=>$aNetwork.RESEAU_SOCIAL_ID,'%id du post%'=>$index]}>
                                        {if $aNetwork.MEDIA_ID}
                                            <img src="{Pelican::$config.MEDIA_HTTP}{$aNetwork.MEDIA_PATH}" width="40" height="40" alt="{$aSocialNetworksTypes[$aNetwork.RESEAU_SOCIAL_TYPE]|lower|capitalize}">
                                            {$aNetwork.RESEAU_SOCIAL_LABEL}
                                        {/if}
                                    </a>
                                    {if $aSocialNetworksTypes[$aNetwork.RESEAU_SOCIAL_TYPE]|lower|in_array:$aUnusualNetworks && $aSocialNetworksTypes[$aNetwork.RESEAU_SOCIAL_TYPE]|lower != "facebook"}
                                        {include file="{$sIncludeTplPath}addbox_{$aSocialNetworksTypes[$aNetwork.RESEAU_SOCIAL_TYPE]|lower}.tpl"}
                                    {/if}
                                </li>
                            {/foreach}
                        </ul>
                    </div>
                {/if}
                {if $aCorporateSocialNetworks|@count}
                    <div class="bloc">
                        <h3>{$aParams.ZONE_TITRE3|escape}</h3>
                        <ul class="socials">
                            {foreach from=$aCorporateSocialNetworks key=index item=aNetwork}
                                <li class="fixHeight">
                                    <a href="{urlParser url=$aNetwork.RESEAU_SOCIAL_URL_WEB}" target="{if $aNetwork.RESEAU_SOCIAL_URL_MODE_OUVERTURE == 1}_self{else}_blank{/if}">
                                        {if $aNetwork.MEDIA_ID}
                                            <img src="{Pelican::$config.MEDIA_HTTP}{$aNetwork.MEDIA_PATH}" width="40" height="40" alt="{$aSocialNetworksTypes[$aNetwork.RESEAU_SOCIAL_TYPE]|lower|capitalize}">
                                            {$aNetwork.RESEAU_SOCIAL_LABEL}
                                        {/if}
                                    </a>
                                    {if $aSocialNetworksTypes[$aNetwork.RESEAU_SOCIAL_TYPE]|lower|in_array:$aUnusualNetworks && $aSocialNetworksTypes[$aNetwork.RESEAU_SOCIAL_TYPE]|lower != "facebook"}
                                        {include file="{$sIncludeTplPath}addbox_{$aSocialNetworksTypes[$aNetwork.RESEAU_SOCIAL_TYPE]|lower}.tpl"}
                                    {/if}
                                </li>
                            {/foreach}
                        </ul>
                    </div>
                {/if}
            </aside>
            <div class="masonry" data-ws="/_/Layout_Citroen_CitroenSocial/moreSocial" data-loadtext="{'CHARGEMENT_EN_COURS'|t}" style="min-height:70px;">
                {literal}
                <script type="text/template" id="masonryTpl">
                    <%
                    var months = ['{/literal}{'JAN'|t}{literal}','{/literal}{'FEV'|t}{literal}','{/literal}{'MAR'|t}{literal}','{/literal}{'AVR'|t}{literal}','{/literal}{'MAI'|t}{literal}','{/literal}{'JUN'|t}{literal}','{/literal}{'JUL'|t}{literal}','{/literal}{'AUG'|t}{literal}','{/literal}{'SEP'|t}{literal}','{/literal}{'OCT'|t}{literal}','{/literal}{'NOV'|t}{literal}','{/literal}{'DEC'|t}{literal}'];

                    _.each(obj.data,function(item){
                    %>
                        <div class="item added" {/literal}{gtm name="clic_sur_une_remont" data=$aParams data=$aParams datasup=['value' => '<% item.network.name %> <% item.post.url %>'] labelvars=['%id reseau social%' => '<% item.network.name %>', '%id du post%' => '<% item.post.url %>']}{literal}>
                            <div class="cont">
                                <div class="head">
                                    <span>
                                        <a href="<%= item.network.url %>" target="_blank"><strong><%= item.network.page %></strong></a><br>
                                        <%
                                        var str = item.post.date;
                                        str = str.replace(/( \+)/, ' UTC$1');
                                        var date = new Date(str),
                                        current = new Date(),
                                        sago = (current.getTime() - date.getTime())/(1000),
                                        mago = sago/60,
                                        hago = mago/60;

                                        var reglink = new RegExp('((http|https)://[\\w\\./]*)','gi'),
                                        linked = (item.post.text || '').replace(reglink,'<a href="$1" target="_blank">$1</a>');
                                        %>
                                        <%= date.getDate() +' '+ months[date.getMonth()]+' '+date.getFullYear() %>
                                        <% if(sago < 59){ %>
                                            - il y a <%= Math.round(sago) %> sec
                                        <% } else if(mago < 59){ %>
                                            - il y a <%= Math.round(mago) %> min
                                        <% } else if(hago < 23){ %>
                                            - il y a <%= Math.round(hago) %> h
                                        <% }; %>
                                    </span>
                                    <a class="socialink" href="<%= item.network.url %>" target="_blank">
                                        <img src="{/literal}{Pelican::$config.IMAGE_FRONT_HTTP}{literal}/picto/<%= item.network.name %>-l.png" width="18" height="18" alt="<%= item.network.name %>">
                                    </a>
                                </div>
                                <div>
                                    <%
                                    switch(item.network.name){
                                        case 'youtube':
                                        %>
                                            <div class="post zoner">
                                                <% if(item.post.media){ %>
                                                    <figure><img src="<%= item.post.media %>" alt=""></figure>
                                                <% }; %>
                                                <div class="txt">
                                                    <span class="title"><a href="<%= item.post.url %>" target="_blank"><%= item.post.title %></a></span>
                                                    <%= linked %>
                                                </div>
                                            </div>
                                        <%
                                        break;
                                        default:
                                        %>
                                            <div class="post zoner">
                                                <% if(item.post.media){ %>
                                                    <figure><img src="<%= item.post.media %>" alt="" /></figure>
                                                <% }; %>
                                                <div class="txt">
                                                    <span class="title"><a href="<%= item.post.url %>" target="_blank"><%= item.post.title %></a></span>
                                                    <%= linked %>
                                                </div>
                                            </div>
                                        <%
                                        break;
                                    };
                                    %>
                                </div>
                            </div>
                        </div>
                    <% }); %>
                </script>
                {/literal}
            </div>

            <div class="addmore">
                <a href="#LOREM" {gtm action='Push' data=$aParams datasup=['eventLabel'=>'VOIR_PLUS_ITEMS'|t]} style="display: table-cell">
                    {'VOIR_PLUS_ITEMS'|t}
                </a>
            </div>

        </section>
    </div>
{/if}