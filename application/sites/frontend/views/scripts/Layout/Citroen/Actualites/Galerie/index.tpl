{if $aData.ZONE_WEB eq 1}
	<div class="sliceNew sliceActualitesGalerieDesk">

		<section id="{$aData.ID_HTML}" class="row of7 clsactualitesgalerie {$aData.ZONE_SKIN}">
			<div class="sep {$aData.ZONE_SKIN}"></div>

			<form class="caption filters" id="filterNews" action="" method="GET">
				{if $sUrlRSS}
					<a href="{urlParser url=$sUrlRSS}" class="rsslink">RSS</a>
				{/if}

				<ul data-text="{'FILTRER_PAR'|t}">
					<li>
						<input type="radio" name="themeId" id="themeId0" value="0" {if $iThemeId eq ''}checked="checked"{/if} />
						<label for="themeId0">{'TOUT'|t}</label>
					</li>
					{foreach from=$aFiltres item=filtre name=listeFiltre}
						<li>
							<input type="radio" name="themeId" id="themeId{$filtre}" value="{$filtre}" {if $iThemeId eq $filtre}checked="checked"{/if} />
							<label for="themeId{$filtre}">{$aThemes[$filtre]}</label>
						</li>
					{/foreach}
				</ul>
			</form>

			<div class="col span5 news">
				<input type="hidden" name="iCount" id="iCount" value="{$iCount}"/>
				<input type="hidden" name="pid" id="pid" value="{$aData.pid}"/>

				<div id="allActu" style="min-height:70px;">
					{include file="{$sIncludeTplPath}/moreNews.tpl"}
				</div>

				{if $nbActus > 10}
					<div id="seeMoreNews" class="caption addmore">
						<a href="#moreNews">{'VOIR_PLUS_ACTU'|t}</a>
					</div>
				{/if}
			</div>

			<div class="col span2 sidebar">

				{if $aData.ZONE_TITRE11 == '1'}
					<form class="newsletter" novalidate action="{$abonnements.ZONE_URL2}">
						<fieldset>
							<legend>{'ABO_NEWSLETTER'|t}</legend>
							<p>{'TEXT_ABO_NEWSLETTER'|t}</p>

							<div class="field include">
								<input type="email" name="email" placeholder="{'VOTRE_EMAIL'|t}" />
							</div>

							<input type="submit" name="register" value="{'OK'|t}" />
						</fieldset>
					</form>
				{/if}

				<div class="follow">
					<div class="title">{'FOLLOW_US'|t}</div>

					<div class="buttons">
						{$sSharerButton}
					</div>

					<ul class="socials">
						{foreach from=$reseauxSociauxSelected item=rs name=rs}
							{if $rs.RESEAU_SOCIAL_AFFICHAGE_WEB && $rs.RESEAU_SOCIAL_URL_WEB}
								<li>
									<a href="{urlParser url=$rs.RESEAU_SOCIAL_URL_WEB}" target="{if $rs.RESEAU_SOCIAL_URL_MODE_OUVERTURE == '2'}_blank{else}_self{/if}">
										<img src="{Pelican::$config.MEDIA_HTTP}{$rs.MEDIA_PATH}" alt="{$rs.RESEAU_SOCIAL_LABEL|escape}" />
									</a>
								</li>
							{/if}
						{/foreach}
					</ul>
				</div>

				{if $sUrlRSS}
					<a href="{urlParser url=$sUrlRSS}" class="rss" target="_blank">
						{'ABO_RSS'|t}
					</a>
					<!-- /.rss -->
				{/if}

				{foreach from=$aDisplayBox item=display name=listeDisplay}
					{if $display eq 1 && $iFacebook neq ""}
						<div class="fb-like-box" data-href="{urlParser url=$aReseauxSociaux[$iFacebook].RESEAU_SOCIAL_URL_WEB}" data-width="292" data-height="560" data-show-faces="true" data-stream="true" data-header="false"></div>
					{/if}
					{if $display eq 2 && $iTwitter neq ""}
						<a class="twitter-timeline"
						   width="500"
						   height="335"
						   href="{urlParser url=$aReseauxSociaux[$iTwitter].RESEAU_SOCIAL_URL_WEB}"
						   data-widget-id="{$aReseauxSociaux[$iTwitter].RESEAU_SOCIAL_ID_WIDGET}"
						   data-link-color="#DC002E"
						   data-tweet-limit="{$aReseauxSociaux[$iTwitter].RESEAU_SOCIAL_NB_FLUX}"
						>
							{'TWEETS_DE'|t} @Citroën
						</a>
					{/if}
					{if $display eq 3 && $iYoutube neq ""}
						<div class="feeds">
							<div class="youtube" data-feed="{$aReseauxSociaux[$iYoutube].RESEAU_SOCIAL_URL_WEB}">
								<script type="text/template" class="tpl">
									<div class="cont">
										<div class="head">
											<a class="socialink" href="http://www.youtube.com/user/<%= feed.entry[0].author[0].name.$t %>" target="_blank">
												<img src="{Pelican::$config.MEDIA_HTTP}{$aReseauxSociaux[$iYoutube].MEDIA_PATH}" width="45" height="45" alt="YouTube" />
											</a>
											<span>{'SUR_YOUTUBE'|t}</span>
											<a class="youbutton" href="http://www.youtube.com/user/<%= feed.entry[0].author[0].name.$t %>?sub_confirmation=1" target="_blank">
												{'SUIVRE_LA_CHAINE'|t}
											</a>
										</div>
										<ul>
											<% _.each(feed.entry,function(entry) {ldelim} %>
											<li>
												<a class="popit" data-sneezy  data-video="<%= entry.link[0].href %>" href="<%= entry.link[0].href %>" target="_blank" {gtm  action='Display::Video' data=$aData datasup=['eventLabel'=>'<%= entry.title.$t %>']}>
													<img src="<%= entry.media$group.media$thumbnail[0].url %>" />
													<%= entry.title.$t %>
												</a>
											</li>
											<%  {rdelim}); %>
										</ul>
									</div>
								</script>
							</div>
						</div>
					{/if}
				{/foreach}

				{if $aData.ZONE_TITRE11 == '1'}
					<form class="newsletter" novalidate action="{$abonnements.ZONE_URL2}">
						<fieldset>
							<legend>{'ABO_NEWSLETTER'|t}</legend>
							<p>{'TEXT_ABO_NEWSLETTER'|t}</p>

							<div class="field include">
								<input type="email" name="email" placeholder="{'VOTRE_EMAIL'|t}" />
							</div>

							<input type="submit" name="register" value="{'OK'|t}" />
						</fieldset>
					</form>
				{/if}
			</div>
		</section>
	</div>
{/if}