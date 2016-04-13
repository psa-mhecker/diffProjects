{if $aParams.ZONE_WEB}
<section class="socialMediaReviewDesktop sliceNew">
    <div id="{$aParams.ID_HTML}" class="row of3 feeds clshomers">

        {if $aParams.ZONE_TITRE2}
        <div class="columns column_33 facebook">
            <div class="cont" style="overflow-y:hidden;padding-top: 0px;">
                <div class="fb-like-box-waiting socialink" data-href="{urlParser url=$reseauxSociaux[$aParams.ZONE_TITRE2].RESEAU_SOCIAL_URL_WEB}" data-width="293" data-height="520" data-show-faces="false" data-stream="true" data-show-border="false" data-header="false"></div>
                <div class="head">
                    <a class="socialink" href="{urlParser url=$reseauxSociaux[$aParams.ZONE_TITRE2].RESEAU_SOCIAL_URL_WEB}" target="{if $reseauxSociaux[$aParams.ZONE_TITRE2].RESEAU_SOCIAL_URL_MODE_OUVERTURE == '2'}_blank{else}_self{/if}"><img src="{Pelican::$config.MEDIA_HTTP}{$reseauxSociaux[$aParams.ZONE_TITRE2].MEDIA_PATH}" width="45" height="45" alt="Facebook" /></a>
                    <span>{'SUR_FACEBOOK'|t}</span>
                    <div class="fb-like" data-href="{urlParser url=$reseauxSociaux[$aParams.ZONE_TITRE2].RESEAU_SOCIAL_URL_WEB}" data-send="false" data-layout="button_count" data-width="100" data-show-faces="false"></div>
                </div>
            </div>
        </div>
        {/if}
        {if $aParams.ZONE_TITRE4}
         <div class="columns column_33 twitter">
            <div class="cont" style="overflow-y:scroll;padding-top:41px">
                <a class="twitter-timeline-waiting" 
                                   width="500" 
                                   height="485" 
                                   href="{urlParser url=$reseauxSociaux[$aParams.ZONE_TITRE4].RESEAU_SOCIAL_URL_WEB}" 
                                   data-widget-id="{$reseauxSociaux[$aParams.ZONE_TITRE4].RESEAU_SOCIAL_ID_WIDGET}" 
                                   data-link-color="#DC002E"
                                   data-tweet-limit="{$reseauxSociaux[$aParams.ZONE_TITRE4].RESEAU_SOCIAL_NB_FLUX}"
                                   >{'TWEETS_DE'|t} @{$reseauxSociaux[$aParams.ZONE_TITRE4].RESEAU_SOCIAL_ID_COMPTE}</a>
                <div class="head">
                    <a class="socialink" href="{urlParser url=$reseauxSociaux[$aParams.ZONE_TITRE4].RESEAU_SOCIAL_URL_WEB}" target="{if $reseauxSociaux[$aParams.ZONE_TITRE4].RESEAU_SOCIAL_URL_MODE_OUVERTURE == '2'}_blank{else}_self{/if}"><img src="{Pelican::$config.MEDIA_HTTP}{$reseauxSociaux[$aParams.ZONE_TITRE4].MEDIA_PATH}" width="45" height="45" alt="Twitter" /></a>
                    <span>{'SUR_TWITTER'|t}</span>
                    <a href="{urlParser url=$reseauxSociaux[$aParams.ZONE_TITRE4].RESEAU_SOCIAL_URL_WEB}" class="twitter-follow-button" data-show-count="true" data-lang="{$sLangue}" data-size="normal">{'SUIVRE'|t} @{$reseauxSociaux[$aParams.ZONE_TITRE4].RESEAU_SOCIAL_ID_COMPTE}</a>
                </div>
            </div>
        </div>
        {/if}
        {if $aParams.ZONE_TITRE3}
       <div class="columns column_33 youtube" data-feed="{$reseauxSociaux[$aParams.ZONE_TITRE3].RESEAU_SOCIAL_URL_WEB}">
            <div class="cont" style="width:295px;">
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
        </div>
        {/if}
        {if $aParams.ZONE_TITRE5 && $reseauxSociaux[$aParams.ZONE_TITRE5].FEED|@sizeof >0
                && (!$aParams.ZONE_TITRE2 || !$aParams.ZONE_TITRE3 || !$aParams.ZONE_TITRE4)}
            <input type="hidden" name="instaFeedId" value="{$aParams.ZONE_TITRE5}"/>
            <div class="col youtube" style="min-width:300px">
                <div class="cont" id="instagramFeed" style="width:295px;">
                    <div class="head">
                        <a class="socialink" href="{urlParser url=$reseauxSociaux[$aParams.ZONE_TITRE5].RESEAU_SOCIAL_URL_WEB}" target="_blank"><img src="{Pelican::$config.MEDIA_HTTP}{$reseauxSociaux[$aParams.ZONE_TITRE5].MEDIA_PATH}" width="45" height="45" alt="instagram" /></a>
                        <span>{'SUR_INSTAGRAM'|t}</span>
                        <a class="instabutton" href="{urlParser url=$reseauxSociaux[$aParams.ZONE_TITRE5].RESEAU_SOCIAL_URL_WEB}" target="_blank">{'S_INSCRIRE'|t}</a>
                    </div>
                </div>
            </div>
        {/if}
    </div>
</section>
{/if}
