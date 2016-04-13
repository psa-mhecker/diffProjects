{literal}
<style>
	{/literal}
	{if ($aData.SECOND_COLOR|count_characters)==7}
	{literal}
	.slice2columnsMixeEnrichiDesk .actions .buttonTransversalInvert, .slice2columnsMixeEnrichiDesk .buttonTransversalInvert{
	{/literal}{if ($aData.SECOND_COLOR|count_characters)==7 }{literal}
		background-color:{/literal}{$aData.SECOND_COLOR}{literal}!important;
		border-color:{/literal}{$aData.SECOND_COLOR}{literal}!important;
		color:#ffffff!important;
	{/literal}{/if}{literal}
	}
	.slice2columnsMixeEnrichiDesk .icon-play{
		color: {/literal}{$aData.SECOND_COLOR}{literal}!important;
	}
	{literal}
	{/literal}
	.slice2columnsMixeEnrichiDesk .actions .buttonTransversalInvert:hover, .slice2columnsMixeEnrichiDesk .actions .buttonTransversalInvert:active, .slice2columnsMixeEnrichiDesk .buttonTransversalInvert:hover, .slice2columnsMixeEnrichiDesk .buttonTransversalInvert:active:hover ,.slice2columnsMixeEnrichiDesk .buttonTransversalInvert:hover span {
	{/literal}{if ($aData.SECOND_COLOR|count_characters)==7 }{literal}
		background-color:#ffffff!important;
		border-color:{/literal}{$aData.SECOND_COLOR}{literal}!important;
		color:{/literal}{$aData.SECOND_COLOR} {literal}!important;
	{/literal}{/if}{literal}
	}
	.slice2columnsMixeEnrichiDesk .popit.photo.activeRoll:hover:after{
	{/literal}{if ($aData.SECOND_COLOR|count_characters)==7 }{literal}
		background-color:{/literal}{$aData.SECOND_COLOR};{literal}
	{/literal}{/if}{literal}
	}
	{/literal}
	{/if}
	{literal}
	.parttitle {
	}
</style>
{/literal}

{if $aData.ZONE_WEB == 1}
	<div class="sliceNew slice2columnsMixeEnrichiDesk">
		<section class="of2 cls2colonnemixteenrichi" id="{$aData.ID_HTML}">
			<div class="sep {$aData.ZONE_SKIN}"></div>

			{if $aData.ZONE_TITRE neq ''}<h2 class="subtitle" {if ($aData.PRIMARY_COLOR|count_characters)==7 } style="color:{$aData.PRIMARY_COLOR};"  {/if}>{$aData.ZONE_TITRE|escape}</h2>{/if}
			{if $aData.ZONE_TITRE2 neq ''}<h3 class="parttitle" {if ($aData.SECOND_COLOR|count_characters)==7 } style="color:{$aData.SECOND_COLOR};"  {/if}>{$aData.ZONE_TITRE2|escape}</h3>{/if}
			<div class="row gutter">

				{if $aData.ZONE_TEXTE}
					<span class="new columns column_50 zonetexte">{$aData.ZONE_TEXTE}</span>
				{else}
					<div class="new col no-mgchapo"></div>
				{/if}


				{if $MEDIA_VIDEO || $MEDIA_YOUTUBE || $MEDIA_PATH}
					<figure class="columns column_50  shadow video shareable nomgfigure">
						<span class="roll" style="border:0px;"></span>
						{if $MEDIA_VIDEO || $MEDIA_YOUTUBE}<a class="" data-video="{$MEDIA_VIDEO}" href="{urlParser url=$MEDIA_VIDEO}" data-sneezy target="_blank" {gtm action='Display::Video' data=$aData datasup=['eventLabel'=>{$MEDIA_TITLE}]}>{/if}
							<img src="{Pelican::$config.MEDIA_HTTP}{$MEDIA_PATH}" width="580" height="323" alt='{$MEDIA_ALT}'>
						{if $MEDIA_VIDEO || $MEDIA_YOUTUBE}</a>
                                                    <i class="icon-play"></i>
                                                {/if}
					</figure>
				{else}
					{if $aMultiVisuel|@sizeof > 0}
						<figure class="col span3 shadow shareable nomgfigure">
							{foreach from=$aMultiVisuel item=lib name=foo}
								{if $smarty.foreach.foo.first}
									{if $VIGN_GALLERY_TOP}
										{assign var="Vignette_Gal_Top" value=$VIGN_GALLERY_TOP}
									{else}
										{assign var="Vignette_Gal_Top" value=$lib.MEDIA_ID}
									{/if}
									<img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/16-9.png" data-original="{$Vignette_Gal_Top}" width="580" height="323" alt="{$lib.MEDIA_ALT}" />
								{else}
									<a class="photo" data-sneezy="group2colme{$aData.ORDER}"  href="{urlParser url=$lib.MEDIA_ID}" target="_blank" {gtm action='Zoom' data=$aData datasup=['eventLabel'=>{$lib.MEDIA_ALT}]}></a>
								{/if}
							{/foreach}
							<a class="popit photo" data-sneezy="group2colme{$aData.ORDER}"  href="{urlParser url=$aMultiVisuel[0].MEDIA_ID}" target="_blank" {gtm action='Zoom' data=$aData datasup=['eventLabel'=>{$aMultiVisuel[0].MEDIA_ALT}]}></a>
						</figure>
					{/if}
				{/if}

				<div class="new columns column_50">
					<div class="elastic"  {if !$aData.ZONE_TEXTE2}style="background:none;" {/if}>
						{if $aData.ZONE_TITRE3}<h3 class="caption parttitle" {if ($aData.SECOND_COLOR|count_characters)==7 } style="color:{$aData.SECOND_COLOR};"  {/if}>{$aData.ZONE_TITRE3|escape}</h3>{/if}
						{if $aData.ZONE_TEXTE2} <div class="zonetexte">{$aData.ZONE_TEXTE2}</div> {/if}
					</div>
					<!-- /.elastic -->
				</div>
				<!-- /.col -->
				{if $aData.ZONE_TEXTE3}
					<div class="columns column_50 verbatim">
						<blockquote>
							<p>{$aData.ZONE_TEXTE3|escape}</p>
							<footer>{$aData.ZONE_TEXTE6|escape}</footer>
						</blockquote>
					</div>
				{/if}
				<!-- /.verbatim -->

				{if $aData.ZONE_TITRE6}
					<div class="caption">
						{if $aData.ZONE_TITRE5 == "ROLL"}
							<small class="legal"><a href="#LegalTip_{$aData.ZONE_ORDER}" class="texttip">{$aData.ZONE_TITRE6}</a></small>
							<div class="legal layertip" id="LegalTip_{$aData.ZONE_ORDER}">
								{if $MEDIA_PATH4 != ""}<img class="lazy load noscale" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/235-100.png" data-original="{"{Pelican::$config.MEDIA_HTTP}{$MEDIA_PATH4}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_MENTION}" width="580" height="247" alt="{$MEDIA_ALT4}" />{/if}
								{if $aData.ZONE_TEXTE4} <div class="zonetexte">{$aData.ZONE_TEXTE4}</div> {/if}
							</div>
						{elseif $aData.ZONE_TITRE5 == "POP_IN"}
							<small class="legal"><a href="{urlParser url={$aData.PAGE_CLEAR_URL|cat:'?popin=1'}}" class="popinfos fancybox.ajax"  {gtmjs type='toggle' action='Display::ToolTip|' eventGTM='over'  data=$aData datasup=['eventLabel' => $aData.ZONE_TITRE6, 'value' => 'legal']}>{$aData.ZONE_TITRE6}</a></small>
						{elseif $aData.ZONE_TITRE5 == "TEXT"}
							<figure>
								{if $MEDIA_PATH4 != ""}<img class="lazy load  noscale" src="{"{Pelican::$config.MEDIA_HTTP}{$MEDIA_PATH4}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_MENTION}" width="580" height="247" alt="{$MEDIA_ALT4}" />{/if}
							</figure>
							<small class="legal">{$aData.ZONE_TITRE6}<br>{if $aData.ZONE_TEXTE4} <div class="zonetexte">{$aData.ZONE_TEXTE4}</div> {/if}</small>
						{/if}
					</div>
				{/if}
				<br/>
				{if $aMedias|@sizeof > 0}
					<div class="thumbs">
						{foreach $aMedias as $aMedia  key=key}
						{if $aMedia.VIDEO}

						{if $aMedia.VIDEO.YOUTUBE_ID}

						<a class="popit" {if $aMedia.VIDEO.OTHER_MEDIA_PATH}data-video="{$aMedia.VIDEO.OTHER_MEDIA_PATH}"{/if} data-sneezy href="{if $aMedia.VIDEO.YOUTUBE_URL}{urlParser url=$aMedia.VIDEO.YOUTUBE_URL}{/if}" target="_blank" {gtm data=$aData  action='Display::Video'  datasup=['eventLabel'=>{$aMedia.VIDEO.MEDIA_TITLE}]}>
							{else}
							<a class="popit" {if $aMedia.VIDEO.OTHER_MEDIA_PATH}data-video="{$aMedia.VIDEO.OTHER_MEDIA_PATH}"{/if} data-sneezy href="{if $aMedia.VIDEO.MEDIA_PATH}{urlParser url={Pelican::$config.MEDIA_HTTP|cat:$aMedia.VIDEO.MEDIA_PATH}}{/if}" target="_blank" {gtm data=$aData  action='Display::Video' datasup=['eventLabel'=>{$aMedia.VIDEO.MEDIA_TITLE}]}>
								{/if}

								<!--shadow video-->
								<figure class="shadow video">
									<img src="{if $aMedia.VIDEO.MEDIA_PATH}{"{Pelican::$config.MEDIA_HTTP}{$aMedia.VIDEO.MEDIA_PATH2}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_MENTION}{/if}" width="145" height="81" alt="{$aMedia.VIDEO.MEDIA_ALT2}">
								</figure>
								<i class="icon-play"></i>
								{if $aMedia.VIDEO.MEDIA_TITLE}<span>{$aMedia.VIDEO.MEDIA_TITLE}</span>{/if}
							</a>
							{/if}

							{if $aMedia.IMAGE}
								{section name=push loop=$aMedia.IMAGE}
									{if $smarty.section.push.first}
										<a class="popit" data-sneezy="group2CM{$key}" href="{urlParser url={Pelican::$config.MEDIA_HTTP|cat:$aMedia.IMAGE[push].MEDIA_PATH}}" target="_blank" {gtm data=$aData  action='Zoom' datasup=['eventLabel'=>{$aMedia.IMAGE[push].MEDIA_TITLE}]}>
											<figure class="shadow">
												{IF $VIGN_GALLERY}
												{assign var="Vignette_Gal" value="$VIGN_GALLERY"}
												{ELSE}
												{assign var="Vignette_Gal" value=$aMedia.IMAGE[push].MEDIA_PATH}
												{/IF}
												<img src="{"{Pelican::$config.MEDIA_HTTP}{$Vignette_Gal}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_PUSH}" width="145" height="81" alt="{$aMedia.IMAGE[push].MEDIA_ALT}">

											</figure>
											{if $aMedia.MEDIA_TITLE}<span>{$aMedia.MEDIA_TITLE}</span>{/if}
										</a>
									{else}
										<a class="popit grouped" data-sneezy="group2CM{$key}" href="{urlParser url={Pelican::$config.MEDIA_HTTP|cat:$aMedia.IMAGE[push].MEDIA_PATH}}" target="_blank" {gtm data=$aData  action='Zoom' datasup=['eventLabel'=>{$aMedia.IMAGE[push].MEDIA_TITLE}]}>
											<figure class="shadow">
												<img src="{"{Pelican::$config.MEDIA_HTTP}{$aMedia.IMAGE[push].MEDIA_PATH}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_PUSH}" width="145" height="81" alt="{$aMedia.IMAGE[push].MEDIA_ALT}" />
											</figure>
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
								<li class="cta"><a class="buttonTransversalInvert" {gtm action="Push" data=$aData datasup=['eventLabel' => {$aCta[cta].PAGE_ZONE_MULTI_LABEL}]} data-sync="cta{$aData.ORDER}" href="{urlParser url=$aCta[cta].PAGE_ZONE_MULTI_URL}" target="_{$aCta[cta].PAGE_ZONE_MULTI_VALUE}"><span>{$aCta[cta].PAGE_ZONE_MULTI_LABEL}</span></a></li>
							{/if}
						{/section}
					</ul>
					<!-- /.actions -->
				{/if}

				{if $aData.ZONE_TITRE4}
					<div style="text-align:left;margin-left:18px;" class="sharer addthis_toolbox addthis_default_style addthis_32x32_style">
						{if $aSharer.1 || $aSharer.0 != 1}
							<a class="addthis_button_email"></a>
							<a class="addthis_button_facebook_send"></a>
						{/if}
					</div>
					<!-- /.sharer -->
				{/if}
			</div>
		</section>
	</div>
{/if}