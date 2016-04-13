{if $aData.ZONE_WEB}
	<div class="sliceNew sliceTechnologieDetailDesk">

		<div class="banner-wrapper" style="position:relative;">
			{if $aData.MEDIA_ID != '' && $aData.ZONE_TEXTE3 == 0 && $aData.ZONE_TEXTE3 != ''}
				<div id="{$aData.ID_HTML}" class="banner tall full invert">
					<figure>
						<img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/235-100.png" data-original="{$aData.MEDIA_ID}" width="1440" height="545" alt="{$aData.MEDIA_ALT}" />
						<noscript><img src="{$aData.MEDIA_ID}" width="1440" height="545" alt="Citroen" /></noscript>
					</figure>
				</div>
			{else}
				{assign var="StyleForTexte" value="position: relative;"}
			{/if}
			<div class="header" style="{$StyleForTexte}">
				<div class="valign">
					<div>
						{if $aData.ZONE_TITRE}
							<h1 class="subtitle" {if ($aData.PRIMARY_COLOR|count_characters) == 7 }style="color:{$aData.PRIMARY_COLOR};"{/if}>
								{$aData.ZONE_TITRE|escape}
							</h1>
						{/if}
						<div class="mgchapo">
							{$aData.ZONE_TEXTE|escape}
						</div>
						{if $aData.ZONE_TEXTE2}
							<div class="zonetexte">
								{$aData.ZONE_TEXTE2}
							</div>
						{/if}
					</div>
				</div>

			</div>
		</div>

		<div class="clstechnologiedetail">
			{if $sSharer neq ''}
				{$sSharer}
			{/if}

			{if $aData.ZONE_TITRE5 eq 'ROLL'}
				<small class="legal">
					<a class="texttip" href="#cashBuyIn">{$aData.ZONE_TITRE6|escape}</a>
				</small>
				<div class="legal layertip" id="cashBuyIn">
					{if $sVisuelML neq ''}
						<img class="lazy" data-original="{$sVisuelML}" width="270" height="152" style="width:270px !important;height:152px !important;min-width:270px !important;max-width:270px !important;float:left"/>
					{/if}
					{if $aData.ZONE_TEXTE4}
						<div class="zonetexte">
							{$aData.ZONE_TEXTE4}
						</div>
					{/if}
				</div>
			{elseif $aData.ZONE_TITRE5 eq 'TEXT'}
				<div {if $sVisuelML neq ''}style="min-height:119px"{/if}>
					<small class="caption legal">
						{if $aData.ZONE_TITRE6 neq ''}
							{$aData.ZONE_TITRE6|escape}<br>
						{/if}
						{if $sVisuelML neq ''}
							<img class="lazy" data-original="{$sVisuelML}" width="270" height="152" style="width:270px !important;height:152px !important;min-width:270px !important;max-width:270px !important;float:left"/>
						{/if}
						{if $aData.ZONE_TEXTE4}
							<div class="zonetexte">
								{$aData.ZONE_TEXTE4}
							</div>
						{/if}
					</small>
				</div>
			{elseif $aData.ZONE_TITRE5 eq 'POP_IN' && $aMentionsLegales.PAGE_CLEAR_URL neq ''}
				{if $aData.ZONE_TITRE6 neq ''}
					<small class="caption legal">
						<a class="simplepop" href="#creditBuyPopIn">{$aData.ZONE_TITRE6|escape}</a>
					</small>
				{/if}
				<script type="text/template" id="creditBuyPopIn">
					<div style="min-width:450px" >
						<iframe src="{$aMentionsLegales.PAGE_CLEAR_URL}?popin=1" width="450px"></iframe>
					</div>
				</script>
			{/if}

			{if $aMedias|@sizeof > 0}
				<div class="thumbs">
					{foreach $aMedias as $aMedia key=key}
						{if $aMedia.VIDEO}
							{if $aMedia.VIDEO.YOUTUBE_ID}
								<a class="popit" {if $aMedia.VIDEO.OTHER_MEDIA_PATH}data-video="{$aMedia.VIDEO.OTHER_MEDIA_PATH}"{/if} data-sneezy href="{if $aMedia.VIDEO.YOUTUBE_URL}{urlParser url=$aMedia.VIDEO.YOUTUBE_URL}{/if}" target="_blank"
								   {gtm action='Display::Video' data=$aData datasup=['eventLabel'=>{$aMedia.VIDEO.MEDIA_TITLE}]} >
							{else}
								<a class="popit" {if $aMedia.VIDEO.OTHER_MEDIA_PATH}data-video="{$aMedia.VIDEO.OTHER_MEDIA_PATH}"{/if} data-sneezy href="{if $aMedia.VIDEO.MEDIA_PATH}{urlParser url={Pelican::$config.MEDIA_HTTP|cat:$aMedia.VIDEO.MEDIA_PATH}}{/if}" target="_blank"
								   {gtm action='Display::Video' data=$aData datasup=['eventLabel'=>{$aMedia.VIDEO.MEDIA_TITLE}]} >
							{/if}
									<figure class="video">
										<i class="icon-play"></i>
										<img src="{if $aMedia.VIDEO.MEDIA_PATH}{"{Pelican::$config.MEDIA_HTTP}{$aMedia.VIDEO.MEDIA_PATH2}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_MENTION}{/if}" width="145" height="81" alt="{$aMedia.VIDEO.MEDIA_ALT2}">
									</figure>

									{if $aMedia.VIDEO.MEDIA_TITLE}
										<span class="legend">{$aMedia.VIDEO.MEDIA_TITLE}</span>
									{/if}
								</a>
						{/if}
						{if $aMedia.IMAGE}
							{section name=push loop=$aMedia.IMAGE}
								{if $smarty.section.push.first}
									<a class="popit" data-sneezy="group{$key}" href="{urlParser url={Pelican::$config.MEDIA_HTTP|cat:$aMedia.IMAGE[push].MEDIA_PATH}}" target="_blank"
									   {gtm action='Zoom' data=$aData datasup=['eventLabel'=>{$aMedia.IMAGE[push].MEDIA_TITLE}]} >

										<figure class="shadow">
											{if $VIGN_GALLERY}
												{assign var="Vignette_Gal" value="$VIGN_GALLERY"}
											{else}
												{assign var="Vignette_Gal" value=$aMedia.IMAGE[push].MEDIA_PATH}
											{/if}
											<img src='{"{Pelican::$config.MEDIA_HTTP}{$Vignette_Gal}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_PUSH}}' width="145" height="81" alt="{$aMedia.IMAGE[push].MEDIA_TITLE}">
										</figure>

										<span class="legend">{$aMedia.MEDIA_TITLE}</span>
									</a>
								{else}
									<a class="popit grouped" data-sneezy="group{$key}" href="{urlParser url={Pelican::$config.MEDIA_HTTP|cat:$aMedia.IMAGE[push].MEDIA_PATH}}" target="_blank"
									   {gtm action='Zoom' data=$aData datasup=['eventLabel'=>{$aMedia.IMAGE[push].MEDIA_ALT}]} >

										<figure class="shadow">
											<img src="{"{Pelican::$config.MEDIA_HTTP}{$aMedia.IMAGE[push].MEDIA_PATH}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_PUSH}" width="145" height="81" alt="{$aMedia.IMAGE[push].MEDIA_ALT}" />
										</figure>

										<span class="legend">{$aMedia.MEDIA_TITLE}</span>
									</a>
								{/if}
							{/section}
						{/if}
					{/foreach}
				</div>
			{/if}

			{if $aCta|@sizeof > 0}
				<ul class="actions">
					{section name=cta loop=$aCta}
						{if $aCta[cta].OUTIL}
							{$aCta[cta].OUTIL}
						{else}
							<li class="cta">
								<a class="buttonTransversalInvert" data-sync="cta{$aData.ORDER}" href="{urlParser url=$aCta[cta].PAGE_ZONE_MULTI_URL}" target="_{$aCta[cta].PAGE_ZONE_MULTI_VALUE}">
									<span>{$aCta[cta].PAGE_ZONE_MULTI_LABEL}</span>
								</a>
							</li>
						{/if}
					{/section}
				</ul>
			{/if}
		</div>
	</div>
{/if}