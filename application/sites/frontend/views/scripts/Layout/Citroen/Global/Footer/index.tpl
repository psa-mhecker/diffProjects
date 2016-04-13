{if $bTplHome}</div>{/if}
<footer id="{$aParams.ID_HTML}" class="footerReviewDesk sliceNew" >
<div class="inner mainFooter">
    <div class="globalWrapper">
			<div class="row">
			 <div class="columns column_33">
				{if $assistances.ZONE_TITRE10=='1'}
				{if $assistances.ZONE_TITRE} <span class="parttitle">{$assistances.ZONE_TITRE}</span>{/if}
				{if $navigationAssistances}
				<ul class="list">
					{foreach from=$navigationAssistances item=navigation name=navigation}
					<li><a {gtm action="Redirection" data=$aParams datasup=[ 'eventLabel' => $navigation.PAGE_ZONE_MULTI_LABEL]} href="{urlParser url=$navigation.PAGE_ZONE_MULTI_URL}"{if $navigation.PAGE_ZONE_MULTI_OPTION=='2'} target="_blank"{/if}>{$navigation.PAGE_ZONE_MULTI_LABEL}</a></li>
					{/foreach}
				</ul>
				{/if}
				{/if}
				{if $assistances.ZONE_TITRE11=='1' && $assistances.ZONE_TITRE2 != '' && $assistances.ZONE_TITRE3 != 
''}<div class="caller">{$assistances.ZONE_TITRE2} <strong>{$assistances.ZONE_TITRE3}</strong> 
<small>{$assistances.ZONE_TITRE4}</small>
	{if $assistances.MEDIA_PATH!='' && $assistances.ZONE_URL != ''}
	        <a class="handi" href="{urlParser url=$assistances.ZONE_URL}" target="{if $assistances.ZONE_TITRE6 == '2'}_blank{else}_self{/if}">
	            <img src="{$assistances.MEDIA_PATH}" alt="{$assistances.MEDIA_ALT}" width="35" height="35" />
	            <span>{$assistances.ZONE_TITRE5}</span>
	        </a>
	    {/if}
</div>{/if}
</div>
			
			<div class="columns column_33">
				{if $autresSites.ZONE_TITRE10=='1'}
				{if $autresSites.ZONE_TITRE}<span class="parttitle">{$autresSites.ZONE_TITRE}</span>{/if}
				<ul class="list">
					{foreach from=$navigationAutresSites item=navigation name=navigation}
					<li><a {gtm action="Redirection" data=$aParams datasup=[ 'eventLabel' => $navigation.PAGE_ZONE_MULTI_LABEL]} href="{urlParser url=$navigation.PAGE_ZONE_MULTI_URL}"{if $navigation.PAGE_ZONE_MULTI_OPTION=='2'} target="_blank"{/if}>{$navigation.PAGE_ZONE_MULTI_LABEL}</a></li>
					{/foreach}
				</ul>
				{/if}
			</div>
			
			{if $reseauxSociauxSelected}
			
			<div class="columns column_33">
				{if $abonnements.ZONE_TITRE10=='1'}
				{if $abonnements.ZONE_TITRE}<span class="parttitle">{$abonnements.ZONE_TITRE}</span>{/if}
				<ul class="socials">
					{foreach from=$reseauxSociauxSelected item=rs name=rs}
					{if $reseauxSociaux[$rs].RESEAU_SOCIAL_AFFICHAGE_WEB && 
$reseauxSociaux[$rs].RESEAU_SOCIAL_URL_WEB}
					<li>
						<a href="{urlParser url=$reseauxSociaux[$rs].RESEAU_SOCIAL_URL_WEB}" target="{if $reseauxSociaux[$rs].RESEAU_SOCIAL_URL_MODE_OUVERTURE == '2'}_blank{else}_self{/if}" {gtm action="Social" data=$aParams datasup=[ 'eventLabel' => $reseauxSociaux[$rs].RESEAU_SOCIAL_LABEL]}>
							<img class="lazy" src="{Pelican::$config.MEDIA_HTTP}{$reseauxSociaux[$rs].MEDIA_PATH}" alt="{$reseauxSociaux[$rs].RESEAU_SOCIAL_LABEL}" />
						</a>
					</li>
					{/if}
					{/foreach}
				</ul>
				{/if}
				{if $abonnements.ZONE_TITRE11=='1'}
				<form class="newsletter" novalidate action="{$abonnements.ZONE_URL2}">
					<fieldset>
						<legend>{$abonnements.ZONE_TITRE2}</legend>
						<div class="field include">
							<input type="email" name="email" placeholder="{'VOTRE_EMAIL'|t}" />
						</div>
						<input {gtm action="Newsletter" data=$aParams datasup=[ 'eventLabel' => 'OK']} type="submit" class="grey" name="register" value="{$abonnements.ZONE_TITRE3}" />
					</fieldset>
				</form>
				{/if}
			</div>
			{/if}
		</div>
		</div>
	</div>
	
	
	
    <div class="inner map">
 
        <div class="footfold folder move"><a href="#footerMap" {gtmjs data=$aParams type='toggle' action="Display|" data=$aParams datasup=['eventLabel' => 'Sitemap']}>{$planDuSite.ZONE_TITRE2}</a></div>
		<div class="globalWrapper">
		<div class="row" id="footerMap">
        {*foreach from=$navigationPlanDuSite item=navigationLigne name=navigationLigne*}
            {foreach from=$navigationPlanDuSite.0 item=navigation1 name=navigation1}
               <div class="columns column_20">
                    <div class="maptitle">
                        {if $navigation1.n1.urlExterne != ''}
                        <a href="{urlParser url=$navigation1.n1.urlExterne}" {if $navigation.n1.urlExterneTarget == 2}target="_blank"{/if} {gtm action="Sitemap" data=$aParams datasup=['eventLabel' => $navigation1.n1.lib]}>{$navigation1.n1.lib}</a>
                        {else}
                        <a href="{urlParser url=$navigation1.n1.url}" {gtm action="Sitemap" data=$aParams datasup=['eventLabel' => $navigation1.n1.lib]}>{$navigation1.n1.lib}</a>
                        {/if}
                    </div>
                    <ul class="list thin">
                        {foreach from=$navigation1.n2 item=navigation2 name=navigation2}
                            {if $navigation2.urlExterne != ''}
                            <li><a {gtm action="Sitemap" data=$aParams datasup=[ 'eventLabel' => $navigation2.lib]} href="{urlParser url=$navigation2.urlExterne}" {if $navigation2.urlExterneTarget == 2}target="_blank"{/if}>{$navigation2.lib}</a></li>
                            {else}
                            <li><a {gtm action="Sitemap" data=$aParams datasup=[ 'eventLabel' => $navigation2.lib]} href="{urlParser url=$navigation2.url}">{$navigation2.lib}</a></li>
                            {/if}
                        {/foreach}
                    </ul>
                </div>
            {/foreach}

            <div class="caption fullmap">
                <a href="{urlParser url=$planDuSite.ZONE_TITRE3}" {gtm action="Redirection" data=$aParams datasup=['eventLabel' => $planDuSite.ZONE_TITRE]}>{$planDuSite.ZONE_TITRE}</a>
            </div>			

        </div>
		 </div>
        {*/foreach*}
	
    </div>
 	
    <div class="legals">
	<div class="globalWrapper">
        <ul class="links">
            {foreach from=$navigationElementsLegaux item=navigation name=navigation}
                {if $navigation.PAGE_ZONE_MULTI_URL}
                <li><a href="{urlParser url=$navigation.PAGE_ZONE_MULTI_URL}"{if $navigation.PAGE_ZONE_MULTI_OPTION=='2'} target="_blank"{/if} {gtm action="Redirection" data=$aParams datasup=[ 'eventLabel' => $navigation.PAGE_ZONE_MULTI_LABEL]}>{$navigation.PAGE_ZONE_MULTI_LABEL}</a></li>
                {/if}
            {/foreach}
            {if $elementsLegaux.ZONE_TITRE && $elementsLegaux.ZONE_URL}
                <li><a {gtm action="Redirection" data=$aParams datasup=[ 'eventLabel' => $elementsLegaux.ZONE_TITRE]} href="{urlParser url=$elementsLegaux.ZONE_URL}" rel="nofollow">{$elementsLegaux.ZONE_TITRE}</a></li>
            {/if}
            {if $elementsLegaux.ZONE_TITRE2 && $elementsLegaux.ZONE_URL2}
                {if $cookieType == "2"}
                    <li><a id="_bapw-link" href="#" target="_blank" style="text-decoration:none !important"><span style="vertical-align:middle !important">{$elementsLegaux.ZONE_TITRE2}</span></a></li>
                {else}
                    <li><a {gtm action="Redirection" data=$aParams datasup=[ 'eventLabel' => $elementsLegaux.ZONE_TITRE2]} href="{urlParser url=$elementsLegaux.ZONE_URL2}" rel="nofollow">{$elementsLegaux.ZONE_TITRE2}</a></li>
                {/if}
            {/if}
            <li><a {gtm action="Redirection" data=$aParams datasup=[ 'eventLabel' => 'VOIR_VERSION_MOBILE_SITE'|t]} href="#" class="site-version" data-version="mobile">{'VOIR_VERSION_MOBILE_SITE'|t}</a></li>
        </ul>
        {*
        {if $siteLangues|sizeof>1}
        <ul class="languages">
            {foreach from=$siteLangues item=site}
            {if $site.LANGUE_ID==$session.LANGUE_ID}
            <li><span>{$site.LANGUE_TRANSLATE}</span></li>
            {else}
            <li><a {gtm action="Language" data=$aParams datasup=[ 'eventLabel' => $site.LANGUE_TRANSLATE]} href="{$pageLangue.{$site.LANGUE_ID}.PAGE_CLEAR_URL}">{$site.LANGUE_TRANSLATE}</a></li>
            {/if}
            {/foreach}
        </ul>
        {/if}
        *}
		</div>
    </div>
</footer>

{literal}
<style>
	{/literal}
	{if ($aShowroom.SECOND_COLOR|count_characters)==7}
	{literal}
	#btn2top.sticker.top a#scrolltop{
	{/literal}{if ($aShowroom.PRIMARY_COLOR|count_characters)==7 }{literal}
		background-color:{/literal}{$aShowroom.PRIMARY_COLOR}!important;{literal};
	{/literal}{/if}{literal}
	}
	.sticker.top a:hover, .sticker.top a:active, #btn2top.sticker.top a#scrolltop:hover, .sticker.top a:active:hover{
	{/literal}{if ($aShowroom.SECOND_COLOR|count_characters)==7 }{literal}
		background-color:#ffffff!important;
		border-color:{/literal}{$aShowroom.PRIMARY_COLOR}!important;{literal}
		color:{/literal}{$aShowroom.PRIMARY_COLOR}; {literal}
	{/literal}{/if}{literal}
	}
	{/literal}
	{/if}
	{literal}
</style>
{/literal}
<div class="sticker top" id="btn2top" ><a {gtm action="BackToTop" data=$aParams datasup=[ 'eventLabel' => 'Arrow']} href="#" id="scrolltop">Top</a></div>

{literal}
	<script type="text/template" id="tplSneezy">
		<div class="sneezies" id="sneezy_<%= id %>"><div class="inner">
			<%
				_.each(items,function(item){

					var isImg 	= (/\.jpeg|jpg|gif|png$/).test(item.src);
					var isVideo = (/\.mp4|webm$/).test(item);
					var isFlash = (/\.swf$/).test(item);
					var isYoutube = (/youtube.com/).test(item), youtubeUrl = '';

					if (isYoutube) {
						if  ((/\/embed/).test(item)) {
							youtubeUrl = item;
						}
						else {
							var ytRegExp = new 
RegExp('(//www\\.youtube\\.com/watch\\?v=)|(//youtu\\.be/)(\\w*)')
							youtubeUrl = (ytRegExp.test(item))? 
item.replace(ytRegExp,'//www.youtube.com/embed/$3') : false;
						}

						youtubeUrl= youtubeUrl.split('&')[0];
					}

					if (isVideo) {
						var videos = item.split('|'),
							testExt = /^.+\.([^.]+)$/;
					}
			%>

			<div class="item <% if(isVideo || isYoutube){ %>videoTpl<% }; %>">
				<div class="closer"></div>
				<% if (isImg) { %>
					<div class="content shareable content-img">
						<a href="<%= item.src %>">
							<img class="lazy" src="<%= item.src %>" alt="<%= item.alt %>" />
						</a>
					</div>
				<% } else if (isVideo) { %>
					<div class="content content-video">
						<video preload="auto" controls="controls" class="video-js vjs-default-skin">
						<% _.each(videos,function(video){ %>
							<source src="<%= video %>" type="video/<%= testExt.exec(video)[1] %>" />
						<% }); %>
						</video>
					</div>
				<% } else if (isYoutube) { %>
					<div class="content content-iframe">
						<iframe src="<%= youtubeUrl 
%>?autoplay=0&autohide=1&fs=1&rel=0&hd=1&wmode=opaque&enablejsapi=1" frameborder="0"></iframe>
					</div>
				<% } %>
				<span class="popClose"><span>{/literal}{'FERMER'|t}{literal}</span></span>
			</div>

			<% }); %>
		</div></div>
	</script>
{/literal}
<script type="text/template" id="closeTpl">
	{literal}<span class="popClose"><span>{/literal}{'FERMER'|t}{literal}</span></span>{/literal}
</script>
<script type="text/javascript" >
var page_vehicule_label="{$vehicule_label}";
</script>


