{literal}
<style>
	{/literal}
	{if ($aData.SECOND_COLOR|count_characters)==7}
	{literal}
	.slice2ColonnesVisuelTextDesk .actions .buttonTransversalInvert, .slice2ColonnesVisuelTextDesk .buttonTransversalInvert{
	{/literal}{if ($aData.SECOND_COLOR|count_characters)==7 }{literal}
		background-color:{/literal}{$aData.SECOND_COLOR}{literal}!important;
		border-color:{/literal}{$aData.SECOND_COLOR}{literal}!important;
		color:#ffffff!important;
	{/literal}{/if}{literal}
	}
	.slice2ColonnesVisuelTextDesk .actions .buttonTransversalInvert:hover, .slice2ColonnesVisuelTextDesk .actions .buttonTransversalInvert:active, .slice2ColonnesVisuelTextDesk .buttonTransversalInvert:hover, .slice2ColonnesVisuelTextDesk .buttonTransversalInvert:active:hover ,.slice2ColonnesVisuelTextDesk .buttonTransversalInvert:hover span {
	{/literal}{if ($aData.SECOND_COLOR|count_characters)==7 }{literal}
		background-color:#ffffff!important;
		border-color:{/literal}{$aData.SECOND_COLOR}{literal}!important;
		color:{/literal}{$aData.SECOND_COLOR} {literal}!important;
	{/literal}{/if}{literal}
	}
	{/literal}
	{/if}
	{literal}
</style>
{/literal}

<div class="sliceNew slice2ColonnesVisuelTextDesk">
	{if $aData.ZONE_WEB == 1 && $iNbMultiVisuel >= 2}

		<section class="{$aData.ZONE_SKIN} of6 cls2colonnevisueltext" id="{$aData.ID_HTML}">
			<div class="sep {$aData.ZONE_SKIN}"></div>

			{if $aData.ZONE_TITRE}<h2 class="subtitle"  {if ($aData.PRIMARY_COLOR|count_characters)==7 } style="color:{$aData.PRIMARY_COLOR};"  {/if}>{$aData.ZONE_TITRE}</h2>{/if}
			{if $aData.ZONE_TITRE2}<h3 class="parttitle"  {if ($aData.SECOND_COLOR|count_characters)==7 } style="color:{$aData.SECOND_COLOR};"  {/if}>{$aData.ZONE_TITRE2}</h3>{/if}

			{if $aData.ZONE_TEXTE}
				<div class="mgchapo">{$aData.ZONE_TEXTE}</div>
			{else}
				<br/><br/><br/>
			{/if}

			{assign var='new' value='true'}
			{foreach from=$aMultiVisuel item=multi}
				{if $new == 'true'}
					{assign var='class' value='new '}{assign var='new' value='false'}
				{else}
					{assign var='class' value=''}{assign var='new' value='true'}
				{/if}

				<div class="{$class}columns column_50 blocktext">
					<div class="row gutter">
					<figure class="columns column_50">
						<img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/1-1.png" data-original="{$multi.MEDIA_ID}" width="270" height="152" alt="{$multi.MEDIA_ALT}" />
						<noscript><img src="{$multi.MEDIA_ID}" width="270" height="152" alt="{$multi.MEDIA_ALT}" /></noscript>
					</figure>
					<!-- /.col -->
					<div class="columns column_50">
						{if $multi.PAGE_ZONE_MULTI_TITRE}<h2 class="parttitle"  {if ($aData.SECOND_COLOR|count_characters)==7 } style="color:{$aData.SECOND_COLOR};"  {/if}>{$multi.PAGE_ZONE_MULTI_TITRE}</h2>{/if}
						{if $multi.PAGE_ZONE_MULTI_TEXT}<div class="zonetexte">{$multi.PAGE_ZONE_MULTI_TEXT}</div>{/if}
					</div>
					<!-- /.col -->
				</div>
				</div>
			{/foreach}



			{if $aData.ZONE_TITRE6 && ($aData.ZONE_TITRE5 == "ROLL" || ($aData.ZONE_TITRE5 == "POP_IN" && $urlPopInMention != ""))}

					{if $aData.ZONE_TITRE5 == "ROLL"}
						<small class="legal"><a href="#LegalTip_{$aData.ZONE_ORDER}" class="texttip">{$aData.ZONE_TITRE6}</a></small>
						<div class="legal layertip" id="LegalTip_{$aData.ZONE_ORDER}">
							{if $MEDIA_PATH4 != ""}<img class="lazy noscale" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/235-100.png" data-original="{"{Pelican::$config.MEDIA_HTTP}{$MEDIA_PATH4}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_MENTION}" width="580" height="247" alt="{$MEDIA_ALT4}" />{/if}
							{if $aData.ZONE_TEXTE4} <div class="zonetexte">{$aData.ZONE_TEXTE4}</div> {/if}
						</div>
					{elseif $aData.ZONE_TITRE5 == "POP_IN" && $urlPopInMention != ""}
						<small class="legal">
							<a href="{urlParser url={$urlPopInMention|cat:'?popin=1'}}" class="popinfos fancybox.ajax" {gtmjs type='toggle' action='Display::ToolTip|' eventGTM='over'  data=$aData datasup=['eventLabel' =>  $aData.ZONE_TITRE6]}>
								{$aData.ZONE_TITRE6}
							</a>
						</small>
					{/if}

			{/if}
			{if $aData.ZONE_TITRE5 == "TEXT" && $aData.ZONE_TEXTE4 neq ""}
				<div class="legal">
					<figure>
						{if $MEDIA_PATH4 != ""}<img class="noscale" src="{"{Pelican::$config.MEDIA_HTTP}{$MEDIA_PATH4}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_MENTION}" width="580" height="247" alt="{$MEDIA_ALT4}" />{/if}
					</figure>
					{$aData.ZONE_TITRE6}<br>{if $aData.ZONE_TEXTE4} {$aData.ZONE_TEXTE4} {/if}
				</div>
			{/if}
			{if $aMedias|@sizeof > 0}
				<div class="thumbs">
					{foreach $aMedias as $aMedia  key=key}
					{if $aMedia.VIDEO}
					{if $aMedia.VIDEO.YOUTUBE_ID}
					<a class="popit" {if $aMedia.VIDEO.OTHER_MEDIA_PATH}data-video="{$aMedia.VIDEO.OTHER_MEDIA_PATH}"{/if} data-sneezy href="{if $aMedia.VIDEO.YOUTUBE_URL}{urlParser url=$aMedia.VIDEO.YOUTUBE_URL}{/if}" target="_blank" {gtm action='Display::Video' data=$aData datasup=['eventLabel'=>{$aMedia.VIDEO.MEDIA_TITLE}]}>
						{else}
						<a class="popit" {if $aMedia.VIDEO.OTHER_MEDIA_PATH}data-video="{$aMedia.VIDEO.OTHER_MEDIA_PATH}"{/if} data-sneezy href="{if $aMedia.VIDEO.MEDIA_PATH}{urlParser url={Pelican::$config.MEDIA_HTTP|cat:$aMedia.VIDEO.MEDIA_PATH}}{/if}" target="_blank" {gtm action='Display::Video' data=$aData datasup=['eventLabel'=>{$aMedia.VIDEO.MEDIA_TITLE}]}>
							{/if}
							<!--shadow video-->
							<figure class="shadow video">
								<img src="{if $aMedia.VIDEO.MEDIA_PATH}{"{Pelican::$config.MEDIA_HTTP}{$aMedia.VIDEO.MEDIA_PATH2}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_MENTION}{/if}" width="145" height="81" alt="{$aMedia.VIDEO.MEDIA_ALT2}">
							</figure>
							<span>{$aMedia.VIDEO.MEDIA_TITLE}</span>
						</a>
						{/if}
						{if $aMedia.IMAGE}
							{section name=push loop=$aMedia.IMAGE}
								{if $smarty.section.push.first}
									<a class="popit" data-sneezy="group{$key}" href="{urlParser url={Pelican::$config.MEDIA_HTTP|cat:$aMedia.IMAGE[push].MEDIA_PATH}}" target="_blank" {gtm action='Zoom' data=$aData datasup=['eventLabel'=>{$aMedia.MEDIA_TITLE}]}>
										<figure class="shadow">
											{IF $VIGN_GALLERY}
											{assign var="Vignette_Gal" value="$VIGN_GALLERY"}
											{ELSE}
											{assign var="Vignette_Gal" value=$aMedia.IMAGE[push].MEDIA_PATH}
											{/IF}
											<img src="{"{Pelican::$config.MEDIA_HTTP}{$Vignette_Gal}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_PUSH}" width="145" height="81" alt="{$aMedia.IMAGE[push].MEDIA_ALT}">
										</figure>
										<span>{$aMedia.MEDIA_TITLE}</span>
									</a>
								{else}
									<a class="popit grouped" data-sneezy="group{$key}" href="{urlParser url={Pelican::$config.MEDIA_HTTP|cat:$aMedia.IMAGE[push].MEDIA_PATH}}" target="_blank" {gtm action='Zoom' data=$aData datasup=['eventLabel'=>{$aMedia.IMAGE[push].MEDIA_ALT}]}>
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
				<ul class="actions" >
					{section name=cta loop=$aCta}
						{if $aCta[cta].OUTIL}
							{$aCta[cta].OUTIL}
						{else}
							<li class="cta"><a class="buttonTransversalInvert"  {gtm action="Push" data=$aData datasup=['eventLabel' => $aCta[cta].PAGE_ZONE_MULTI_LABEL,'eventCategory'=>'Content']} data-sync="cta{$aData.ORDER}" href="{urlParser url=$aCta[cta].PAGE_ZONE_MULTI_URL}" target="_{$aCta[cta].PAGE_ZONE_MULTI_VALUE}"><span>{$aCta[cta].PAGE_ZONE_MULTI_LABEL}</span></a></li>
						{/if}
					{/section}
				</ul>
				<!-- /.actions -->
			{/if}

		</section>
	{/if}
</div>