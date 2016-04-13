{literal}
<style>
	{/literal}
	{if ($aData.SECOND_COLOR|count_characters)==7}
	{literal}
	.slice1ColonneTextePictoDesk .actions .buttonTransversalInvert, .slice1ColonneTextePictoDesk .buttonTransversalInvert{
	{/literal}{if ($aData.SECOND_COLOR|count_characters)==7 }{literal}
		background-color:{/literal}{$aData.SECOND_COLOR}{literal}!important;
		border-color:{/literal}{$aData.SECOND_COLOR}{literal}!important;
		color:#ffffff!important;
	{/literal}{/if}{literal}
	}
	.slice1ColonneTextePictoDesk .actions .buttonTransversalInvert:hover, .slice1ColonneTextePictoDesk .actions .buttonTransversalInvert:active, .slice1ColonneTextePictoDesk .buttonTransversalInvert:hover, .slice1ColonneTextePictoDesk .buttonTransversalInvert:active:hover ,.slice1ColonneTextePictoDesk .buttonTransversalInvert:hover span {
	{/literal}{if ($aData.SECOND_COLOR|count_characters)==7 }{literal}
		background-color:#ffffff!important;
		border-color:{/literal}{$aData.SECOND_COLOR}{literal}!important;
		color:{/literal}{$aData.SECOND_COLOR} {literal}!important;
	{/literal}{/if}{literal}
	}
	.slice1ColonneTextePictoDesk .actions a:after{
		background-position:0px!important;
		right:26px!important;
		width: 0px!important;
	}
	{/literal}
	{/if}
	{literal}
        .showroom.clslanguetteshowroom .addmore.folder a {
            filter: none;
            -webkit-filter: none;
            -moz-filter: none;
            -o-filter: none;
            -ms-filter: none;
        }
</style>
{/literal}

{if $aData.ZONE_WEB}
	<div class="sliceNew slice1ColonneTextePictoDesk">
		<section class="row of3 cls1colonnetexte" id="{$aData.ID_HTML}">
			<div class="sep {$aData.ZONE_SKIN}"></div>

			{if $aData.ZONE_TITRE3}<h2 class="subtitle"{if ($aData.PRIMARY_COLOR|count_characters)==7 } style="color:{$aData.PRIMARY_COLOR};" {/if}>{$aData.ZONE_TITRE3|escape}</h2>{/if}
			{if $aData.ZONE_TITRE4}<h3 class="parttitle" {if ($aData.SECOND_COLOR|count_characters)==7 } style="color:{$aData.SECOND_COLOR};" {/if}>{$aData.ZONE_TITRE4|escape}</h3>{/if}

			{if $aData.ZONE_TEXTE}
                            <p class="mgchapo" style="padding-left:20px">{$aData.ZONE_TEXTE|escape}</p>
			{else}
				<div class="no-mgchapo"></div>
			{/if}

			{if $aLignes|@sizeof > 0}
				<div class="row of3">
					{section name=ligne loop=$aLignes}
                                            <div class="col span2 pic" style="width:100%">
							<figure>
								<noscript><img src="{"{Pelican::$config.MEDIA_HTTP}{$aLignes[ligne].MEDIA_PATH}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_1_COLONNE_TEXTE_PICTO}" width="63" height="63" alt="{$aLignes[ligne].MEDIA_ALT}" /></noscript>
								<img width="63" height="63" src="{"{Pelican::$config.MEDIA_HTTP}{$aLignes[ligne].MEDIA_PATH}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_1_COLONNE_TEXTE_PICTO}" alt="{$aLignes[ligne].MEDIA_ALT}" >
							</figure>

							<div class="zonetexte">
								{$aLignes[ligne].PAGE_ZONE_MULTI_TEXT}
							</div>
							<!-- /.text -->
						</div>
						<!-- /.pic -->
					{/section}
				</div>
			{/if}


			{if $aData.ZONE_TITRE6 && ($aData.ZONE_TITRE5 == "ROLL" || ($aData.ZONE_TITRE5 == "POP_IN" && $urlPopInMention != ""))}

					{if $aData.ZONE_TITRE5 == "ROLL"}
						<small class="legal"><a href="#LegalTip_{$aData.ZONE_ORDER}" class="texttip">{$aData.ZONE_TITRE6|escape}</a></small>
						<div class="legal layertip" id="LegalTip_{$aData.ZONE_ORDER}">
							{if $MEDIA_PATH4 != ""}<img class="lazy noscale" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/235-100.png" data-original="{"{Pelican::$config.MEDIA_HTTP}{$MEDIA_PATH4}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_MENTION}" width="580" height="247" alt="{$MEDIA_ALT4}" />{/if}
							{if $aData.ZONE_TEXTE4} <div class="zonetexte">{$aData.ZONE_TEXTE4}</div> {/if}
						</div>
					{elseif $aData.ZONE_TITRE5 == "POP_IN" && $urlPopInMention != ""}
						<small class="legal">
							<a href="{urlParser url={$urlPopInMention|cat:'?popin=1'}}" class="popinfos fancybox.ajax"  {gtmjs type='toggle' action='Display::ToolTip|' eventGTM='over'  data=$aData datasup=['eventLabel' => $aData.ZONE_TITRE6, 'value' => 'legal']}>
								{$aData.ZONE_TITRE6|escape}
							</a>
						</small>

					{/if}

			{/if}
			{if $aData.ZONE_TITRE5 == "TEXT" && $aData.ZONE_TEXTE4 neq ""}
				<div class="caption">
					<figure>
						{if $MEDIA_PATH4 != ""}<img class="lazy noscale" src="{"{Pelican::$config.MEDIA_HTTP}{$MEDIA_PATH4}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_MENTION}" width="580" height="247" alt="{$MEDIA_ALT4}" />{/if}
					</figure>
					<small class="legal">{$aData.ZONE_TITRE6|escape}<br><div class="zonetexte">{$aData.ZONE_TEXTE4}</div></small>
				</div>
			{/if}
			{if $aMedias|@sizeof > 0}
				<div class="thumbs">
					{foreach $aMedias as $aMedia  key=key}
					{if $aMedia.VIDEO}

					{if $aMedia.VIDEO.YOUTUBE_ID}

					<a class="popit" {if $aMedia.VIDEO.OTHER_MEDIA_PATH}data-video="{$aMedia.VIDEO.OTHER_MEDIA_PATH}"{/if} data-sneezy href="{if $aMedia.VIDEO.YOUTUBE_URL}{urlParser url=$aMedia.VIDEO.YOUTUBE_URL}{/if}" target="_blank" {gtm action='Display::Video' data=$aData datasup=['eventLabel'=>{$aMedia.VIDEO.MEDIA_ALT}]}>
						{else}
						<a class="popit" {if $aMedia.VIDEO.OTHER_MEDIA_PATH}data-video="{$aMedia.VIDEO.OTHER_MEDIA_PATH}"{/if} data-sneezy href="{if $aMedia.VIDEO.MEDIA_PATH}{urlParser url={Pelican::$config.MEDIA_HTTP|cat:$aMedia.VIDEO.MEDIA_PATH}}{/if}" target="_blank" {gtm action='Display::Video' data=$aData datasup=['eventLabel'=>{$aMedia.VIDEO.MEDIA_ALT}]}>
							{/if}

							<!--shadow video-->
							<figure class="shadow video">
								<img src="{if $aMedia.VIDEO.MEDIA_PATH}{"{Pelican::$config.MEDIA_HTTP}{$aMedia.VIDEO.MEDIA_PATH2}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_MENTION}{/if}" width="145" height="81" alt="{$aMedia.VIDEO.MEDIA_ALT2}">
							</figure>
							{if $aMedia.VIDEO.MEDIA_TITLE}<span>{$aMedia.VIDEO.MEDIA_TITLE}</span>{/if}
						</a>
						{/if}

						{if $aMedia.IMAGE}
							{section name=push loop=$aMedia.IMAGE}
								{if $smarty.section.push.first}
									<a class="popit" data-sneezy="group2CM{$key}" href="{urlParser url={Pelican::$config.MEDIA_HTTP|cat:$aMedia.IMAGE[push].MEDIA_PATH}}" target="_blank" {gtm action='Zoom' data=$aData datasup=['eventLabel'=>{$aMedia.MEDIA_TITLE}]}>
										<figure class="shadow">
											{IF $VIGN_GALLERY}
											{assign var="Vignette_Gal" value="$VIGN_GALLERY"}
											{ELSE}
											{assign var="Vignette_Gal" value=$aMedia.IMAGE[push].MEDIA_PATH}
											{/IF}
											<img 
                                                                                        src="{"{Pelican::$config.MEDIA_HTTP}{$Vignette_Gal}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_PUSH}"
                                                                                        data-original="{"{Pelican::$config.MEDIA_HTTP}{$Vignette_Gal}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_PUSH}" width="145" height="81" alt="{$aMedia.IMAGE[push].MEDIA_ALT}">
										</figure>
										{if $aMedia.MEDIA_TITLE}<span>{$aMedia.MEDIA_TITLE}</span>{/if}
									</a>
								{else}
									<a class="popit grouped" data-sneezy="group2CM{$key}" href="{urlParser url={Pelican::$config.MEDIA_HTTP|cat:$aMedia.IMAGE[push].MEDIA_PATH}}" target="_blank" {gtm action='Zoom' data=$aData datasup=['eventLabel'=>{$aMedia.IMAGE[push].MEDIA_ALT}]}>
										<figure class="shadow">
											<img 
                                                                                        src="{"{Pelican::$config.MEDIA_HTTP}{$aMedia.IMAGE[push].MEDIA_PATH}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_PUSH}"
                                                                                        data-original="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/16-9.png" data-original="{"{Pelican::$config.MEDIA_HTTP}{$aMedia.IMAGE[push].MEDIA_PATH}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_PUSH}" width="145" height="81" alt="{$aMedia.IMAGE[push].MEDIA_ALT}" />
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

		<div class="parent" id="trancheParent" style="display: none;"></div>

		{if $aData.ZONE_LANGUETTE == 1}
			<section class="showroom row of3 clslanguetteshowroom">
				<div class="caption addmore folder" data-off="border:4px solid {if ($aData.SECOND_COLOR|count_characters)==7}{$aData.SECOND_COLOR}{else}#afadc3{/if};color:{if ($aData.SECOND_COLOR|count_characters)==7}{$aData.SECOND_COLOR}{else}#afadc3{/if};padding:8px;" data-hover="border:6px solid {if ($aData.SECOND_COLOR|count_characters)==7}{$aData.SECOND_COLOR}{else}#afadc3{/if};color:{if ($aData.SECOND_COLOR|count_characters)==7}{$aData.SECOND_COLOR}{else}#afadc3{/if};padding:6px;" data-toggle="{if $aData.ZONE_LANGUETTE_TEXTE_CLOSE}{$aData.ZONE_LANGUETTE_TEXTE_CLOSE}{else}{'VOIR_MOINS'|t}{/if}"><a {if ($aData.PRIMARY_COLOR|count_characters)==7 } class="col span2" {/if} href="#trancheParent" {gtm action="Push" data=$aData datasup=['eventLabel' => 'LANGUETTE_1_COLONNE_TEXTE']}><span style="color: inherit;">{if $aData.ZONE_LANGUETTE_TEXTE_OPEN}{$aData.ZONE_LANGUETTE_TEXTE_OPEN}{else}{t("Voir plus")}{/if}</a></div>
			</section>
		{/if}
	</div>
{/if}