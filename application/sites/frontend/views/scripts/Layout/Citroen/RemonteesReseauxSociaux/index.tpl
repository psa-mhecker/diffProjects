{if $aData.ZONE_WEB == 1}
    <section id="{$aData.ID_HTML}" class="{$aData.ZONE_SKIN} row of6 clsremonteesreseauxsociaux">
    <div class="sep {$aParams.ZONE_SKIN}"></div>

        {if $aData.ZONE_TITRE3}<h2 class="col span4 subtitle">{$aData.ZONE_TITRE3|escape}</h2>{/if}
        {if $aData.ZONE_TITRE4}<h3 class="col span4 parttitle">{$aData.ZONE_TITRE4|escape}</h3>{/if}

		{if $aData.ZONE_TEXTE}
			<div class="col span4 mgchapo zonetexte"><strong>{$aData.ZONE_TEXTE}</strong></div>
		{else}
			<div class="col span4 no-mgchapo"></div>
		{/if}
        {if $aReseauxSociaux|@sizeof > 0}
        <div class="row of3 feeds">
                {if $aData.ZONE_TITRE5}
                <div class="col facebook" style="min-width:300px"><div class="cont" style='width:295px;padding-top:0px;'>
                        {$aReseauxSociaux[$aData.ZONE_TITRE5].RESEAU_SOCIAL_URL_WEB}
                        <div class="fb-like-box" data-href="{$aReseauxSociaux[$aData.ZONE_TITRE5].RESEAU_SOCIAL_URL_WEB}" data-width="293" data-height="520" data-show-faces="false" data-stream="true" data-show-border="false" data-header="false"></div>
                        <div class="head">
                                <a class="socialink" href="{$aReseauxSociaux[$aData.ZONE_TITRE5].RESEAU_SOCIAL_URL_WEB}" target="{if $aReseauxSociaux[$aData.ZONE_TITRE5].RESEAU_SOCIAL_URL_MODE_OUVERTURE == '2'}_blank{else}_self{/if}"><img src="{Pelican::$config.MEDIA_HTTP}{$aReseauxSociaux[$aData.ZONE_TITRE5].MEDIA_PATH}" width="45" height="45" alt="Facebook" /></a>
                                <span>{'SUR_FACEBOOK'|t}</span>

                                <div class="fb-like" data-href="{$aReseauxSociaux[$aData.ZONE_TITRE5].RESEAU_SOCIAL_URL_WEB}" data-send="false" data-layout="button_count" data-width="100" data-show-faces="false"></div>
                        </div>
                        <!-- /.head -->
                </div></div>
                {/if}
                <!-- /.col -->
                {if $aData.ZONE_TITRE7} 
                <div class="col twitter" style="min-width:300px"><div class="cont" style='overflow-y:scroll;width:295px;padding-top:41px'>
                        <a class="twitter-timeline" 
                           width="500" 
                           height="492" 
                           href="{$aReseauxSociaux[$aData.ZONE_TITRE7].RESEAU_SOCIAL_URL_WEB}" 
                           data-widget-id="{$aReseauxSociaux[$aData.ZONE_TITRE7].RESEAU_SOCIAL_ID_WIDGET}" 
                           data-link-color="#DC002E"
                           >{'TWEETS_DE'|t} @{$aReseauxSociaux[$aData.ZONE_TITRE7].RESEAU_SOCIAL_ID_COMPTE}</a>
                        <div class="head">
                                <a class="socialink" href="{$aReseauxSociaux[$aData.ZONE_TITRE7].RESEAU_SOCIAL_URL_WEB}" target="{if $aReseauxSociaux[$aData.ZONE_TITRE7].RESEAU_SOCIAL_URL_MODE_OUVERTURE == '2'}_blank{else}_self{/if}"><img src="{Pelican::$config.MEDIA_HTTP}{$aReseauxSociaux[$aData.ZONE_TITRE7].MEDIA_PATH}" width="45" height="45" alt="Twitter" /></a>
                                <span>{'SUR_TWITTER'|t}</span>

                                <a href="{$aReseauxSociaux[$aData.ZONE_TITRE7].RESEAU_SOCIAL_URL_WEB}" class="twitter-follow-button" data-show-count="true" data-lang="{$sLangue}" data-size="normal">{'SUIVRE'|t} @{$aReseauxSociaux[$aData.ZONE_TITRE7].RESEAU_SOCIAL_ID_COMPTE}</a>
                        </div>
                        <!-- /.head -->
                </div></div>
                {/if}<!-- /.col -->   
                {if $aData.ZONE_TITRE6}
                <div class="col youtube" data-feed="{$aReseauxSociaux[$aData.ZONE_TITRE6].RESEAU_SOCIAL_URL_WEB}" style="min-width:300px">

                    <script type="text/template" class="tpl">
                        <div class="cont">
                            <ul>
                            </ul>
                            <script type="text/template" class="loopTpl">
                                <li>
                                <a class="popit" data-sneezy data-video="https://www.youtube.com/watch?v=<%= contentDetails.upload.videoId %>" href="https://www.youtube.com/watch?v=<%= contentDetails.upload.videoId %>" target="_blank" {gtm  action='Display::Video' data=$aData datasup=['eventLabel'=>'<%= snippet.title %>']}>
                                    <img  src="<%= snippet.thumbnails.default.url %>" />
                                    <%= snippet.title %>
                                </a>
                                </li>
                            </script>       
                            <script type="text/template" class="headerTpl">
                                <div class="head">
                                    <a class="socialink" href="http://www.youtube.com/channel/<%= snippet.channelId %>" target="_blank"><img src="{Pelican::$config.MEDIA_HTTP}{$reseauxSociaux[$aParams.ZONE_TITRE3].MEDIA_PATH}" width="45" height="45" alt="YouTube" /></a>
                                        <span>{'SUR_YOUTUBE'|t}</span>
                                    <a class="youbutton" href="http://www.youtube.com/channel/<%= snippet.channelId %>?sub_confirmation=1" target="_blank">{'SUIVRE_LA_CHAINE'|t}</a>
                                </div>
                            </script>           
                        </div>
        			</script>		
			
                </div>
                {/if}
                <!-- /.col -->
                {if $aData.ZONE_TITRE8 && $aReseauxSociaux[$aData.ZONE_TITRE8].FEED|@sizeof >0}
                    <input type="hidden" name="instaFeedId" value="{$aReseauxSociaux[$aData.ZONE_TITRE8].RESEAU_SOCIAL_ID}"/>
                    <div class="col youtube" style="min-width:300px">
                        <div class="cont" id="instagramFeed" style="width:295px;">
                            <div class="head">
                                <a class="socialink" data-sneezy href="{$aReseauxSociaux[$aData.ZONE_TITRE8].RESEAU_SOCIAL_URL_WEB}" target="_blank"><img src="{Pelican::$config.MEDIA_HTTP}{$aReseauxSociaux[$aData.ZONE_TITRE8].MEDIA_PATH}" width="45" height="45" alt="Instagram" /></a>
                                    <span>{'SUR_INSTAGRAM'|t}</span>
                                    <a class="instabutton" href="{$aReseauxSociaux[$aData.ZONE_TITRE8].RESEAU_SOCIAL_URL_WEB}" target="_blank">{'S_INSCRIRE'|t}</a>
                            </div>
                        </div>
                    </div>
                {/if}
        </div>
        <!-- /.feeds -->
        {/if}

</section>
{/if}