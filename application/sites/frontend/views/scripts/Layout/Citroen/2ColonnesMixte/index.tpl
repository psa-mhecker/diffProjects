{if $aData.ZONE_WEB == 1 && $bTrancheVisible}
{literal}
	<style>
{/literal}
{if ($aData.SECOND_COLOR|count_characters)==7}
{literal}
div.slice2columnsMixedDesk .popit .icon-play, div.sneezies .vjs-default-skin .vjs-big-play-button {
        color: {/literal}{$aData.SECOND_COLOR}{literal}!important;
}
.slice2columnsMixedDesk .actions .buttonTransversalInvert, .slice2columnsMixedDesk .buttonTransversalInvert{
{/literal}{if ($aData.SECOND_COLOR|count_characters)==7 }{literal}
	background-color:{/literal}{$aData.SECOND_COLOR};{literal}
	border-color:{/literal}{$aData.SECOND_COLOR};{literal}
	color:#ffffff;
{/literal}{/if}{literal}
}
.slice2columnsMixedDesk .actions .buttonTransversalInvert:hover, .slice2columnsMixedDesk .actions .buttonTransversalInvert:active, .slice2columnsMixedDesk .buttonTransversalInvert:hover, .slice2columnsMixedDesk .buttonTransversalInvert:active:hover{
{/literal}{if ($aData.SECOND_COLOR|count_characters)==7 }{literal}
	background-color:#ffffff;
	border-color:{/literal}{$aData.SECOND_COLOR};{literal}
	color:{/literal}{$aData.SECOND_COLOR}; {literal}
{/literal}{/if}{literal}
}
.slice2columnsMixedDesk .popit.photo.activeRoll:hover:after{
{/literal}{if ($aData.SECOND_COLOR|count_characters)==7 }{literal}
	background-color:{/literal}{$aData.SECOND_COLOR};{literal}
{/literal}{/if}{literal}
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
	{if $aData.ZONE_TITRE18 == 1}
		{assign var="position" value="gauche"}
	{elseif $aData.ZONE_TITRE18 == 2}
		{assign var="position" value="droite"}
	{/if}
	<div class="sliceNew slice2columnsMixedDesk">
		<section class="" id="{$aData.ID_HTML}">
			{if $aData.ZONE_TITRE3}<h2 class="subtitle" {if ($aData.PRIMARY_COLOR|count_characters)==7 } style="color:{$aData.PRIMARY_COLOR};" {/if}>{$aData.ZONE_TITRE3|escape}</h2>{/if}
			{if $aData.ZONE_TITRE4}<h3 class="parttitle" {if ($aData.SECOND_COLOR|count_characters)==7 } style="color:{$aData.SECOND_COLOR};" {/if}>{$aData.ZONE_TITRE4|escape}</h3>{/if}

			{if $aData.ZONE_TEXTE}
				<div class="mgchapo">{$aData.ZONE_TEXTE|escape}</div>
			{else}
				<div class="col span4 no-mgchapo"></div>
			{/if}
			<div class="row gutter">
				{if $position == 'gauche' && $MEDIA_VIDEO && $MEDIA_PATH && Pelican::$config['TRANCHE_COL']["GAUCHE_DROITE"][$aData.ZONE_TITRE18] == Pelican::$config['TRANCHE_COL']["GAUCHE_DROITE"][1]}
					<div class="columns column_50">
						<figure class="new col span3{if $aData.ZONE_TEXTE} mgfigure{else} nomgfigure{/if}">
								<figure class="shadow video">
									<a class="popit" data-video="{$MEDIA_VIDEO}" href="{urlParser url=$MEDIA_VIDEO}" data-sneezy target="_blank"{gtm action='Display::Video' data=$aData datasup=['eventLabel'=>{$MEDIA_TITLE}]}>
                                                                            <i class="icon-play"></i>
                                                                            <img src="{"{Pelican::$config.MEDIA_HTTP}{$MEDIA_PATH}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_2_COLONNES_MIXTE}" width="580" height="323" alt='{$MEDIA_ALT}'>
									</a>
								</figure>
							</figure>
						</div>
				{elseif $position == 'gauche' && $VisuelMediaGallerie && Pelican::$config['TRANCHE_COL']["GAUCHE_DROITE"][$aData.ZONE_TITRE18] == Pelican::$config['TRANCHE_COL']["GAUCHE_DROITE"][1]}
					<div class="columns column_50">
						<figure class="new col span3 shadow shareable{if $aData.ZONE_TEXTE} mgfigure{else} nomgfigure{/if}">
							{section name=push loop=$VisuelMediaGallerie}
								{if $smarty.section.push.first}
									{if $VIGN_GALLERY_TOP}
										<span class="roll" style="border:0px;"></span>
										<img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/16-9.png" data-original="{$VIGN_GALLERY_TOP}" width="580" height="323" alt="{$VisuelMediaGallerie[push].MEDIA_ALT}" />
										<a class="photo" data-sneezy="group2colme{$aData.ORDER}"  href="{urlParser url=$VIGN_GALLERY_TOP}" target="_blank" {gtm action='Zoom' data=$aData datasup=['eventLabel'=>{$VisuelMediaGallerie[push].MEDIA_TITLE}]}></a>
									{else}
										<span class="roll" style="border:0px;"></span>
										<img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/16-9.png" data-original="{"{Pelican::$config.MEDIA_HTTP}{$VisuelMediaGallerie[push].MEDIA_PATH}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_2_COLONNES_MIXTE}" width="580" height="323" alt="{$VisuelMediaGallerie[push].MEDIA_ALT}" {gtm action='Zoom' data=$aData datasup=['eventLabel'=>{$VisuelMediaGallerie[push].MEDIA_TITLE}]}/>
										<a class="photo" data-sneezy="group2colme{$aData.ORDER}"  href="{urlParser url={"{Pelican::$config.MEDIA_HTTP}{$VisuelMediaGallerie[push].MEDIA_PATH}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_2_COLONNES_MIXTE}}" target="_blank"></a>
									{/if}
								{else}
									<a class="photo"  data-sneezy="group2colm{$aData.ORDER}" href="{urlParser url={"{Pelican::$config.MEDIA_HTTP}{$VisuelMediaGallerie[push].MEDIA_PATH}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_2_COLONNES_MIXTE}}"  target="_blank" {gtm action='Zoom' data=$aData datasup=['eventLabel'=>{$VisuelMediaGallerie[push].MEDIA_TITLE}]}></a>
								{/if}
							{/section}
							<a class="photo" data-sneezy="group2colm{$aData.ORDER}" href="{urlParser url={"{Pelican::$config.MEDIA_HTTP}{$VisuelMediaGallerie[0].MEDIA_PATH}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_2_COLONNES_MIXTE}}" target="_blank" {gtm action='Zoom' data=$aData datasup=['eventLabel'=>{$VisuelMediaGallerie[0].MEDIA_TITLE}]}></a>
						</figure>
					</div>
				{elseif $position == 'gauche' && $MEDIA_PATH && Pelican::$config['TRANCHE_COL']["GAUCHE_DROITE"][$aData.ZONE_TITRE18] == Pelican::$config['TRANCHE_COL']["GAUCHE_DROITE"][1]}
					<div class="columns column_50">
						<figure class="shareable visual">
							<span class="noroll"></span>
							{*<a {gtm action='Zoom' data=$aData datasup=['eventLabel'=>{$MEDIA_ALT}]} class="popit photo" data-sneezy="group2colm{$key}" href="{urlParser url={Pelican::$config.MEDIA_HTTP|cat:$MEDIA_PATH}}" target="_blank">*}
							<div class="backloupe"></div>
							{*</a>*}
							<img src="{urlParser url={Pelican::$config.MEDIA_HTTP|cat:$MEDIA_PATH}}" width="580" height="323" alt="">
							{*<a {gtm action='Zoom' data=$aData datasup=['eventLabel'=>{$MEDIA_ALT}]} class="popit photo" data-sneezy="group2colm{$aData.ORDER}" href="{urlParser url={Pelican::$config.MEDIA_HTTP|cat:$MEDIA_PATH}}" target="_blank"></a>*}
						</figure>
					</div>
				{/if}
				<div class="columns column_50">


					{if $aData.ZONE_TEXTE3}
						<div class="zonetexte">
							{$aData.ZONE_TEXTE3}
						</div>
					{/if}

					{if $aData.ZONE_TITRE6 && ($aData.ZONE_TITRE5 == "ROLL" || ($aData.ZONE_TITRE5 == "POP_IN" && $urlPopInMention != ""))}
						{if $aData.ZONE_TITRE5 == "ROLL"}
							<small class="legal"><a href="#LegalTip_{$aData.ZONE_ORDER}" class="texttip">{$aData.ZONE_TITRE6|escape}</a></small>
							<div class="legal layertip" id="LegalTip_{$aData.ZONE_ORDER}">
								{if $MEDIA_PATH4 != ""}<img class="lazy noscale" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/235-100.png" data-original="{"{Pelican::$config.MEDIA_HTTP}{$MEDIA_PATH4}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_MENTION}" width="580" height="247" alt="{$MEDIA_ALT4}" />{/if}
								{if $aData.ZONE_TEXTE4}<div class="zonetexte">{$aData.ZONE_TEXTE4}</div>{/if}
							</div>

						{elseif $aData.ZONE_TITRE5 == "POP_IN" && $urlPopInMention != ""}
							<div class="legal">
								<a href="{urlParser url={$urlPopInMention|cat:'?popin=1'}}" class="popinfos fancybox.ajax" {gtmjs type='toggle' data=$aData action='Display::ToolTip|' eventGTM='over'  datasup=['eventLabel' => $aData.ZONE_TITRE6, 'idBouton' => 'legal']}>
									{$aData.ZONE_TITRE6|escape}
								</a>
							</div>
						{/if}
					{/if}
					{if $aData.ZONE_TITRE5 == "TEXT" && $aData.ZONE_TEXTE4 neq ""}
						<div class="legal">
							<figure>
								{if $MEDIA_PATH4 != ""}<img class="lazy noscale" src="{"{Pelican::$config.MEDIA_HTTP}{$MEDIA_PATH4}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_MENTION}" width="580" height="247" alt="{$MEDIA_ALT4}" />{/if}
							</figure>
							{$aData.ZONE_TITRE6|escape}<br>{$aData.ZONE_TEXTE4}
						</div>
					{/if}

					{if $aMedias|@sizeof > 0}
						<div class="thumbs">
							{foreach $aMedias as $aMedia  key=key}
							{if $aMedia.VIDEO}
							{if $aMedia.VIDEO.YOUTUBE_ID}
							<a class="popit" {if $aMedia.VIDEO.OTHER_MEDIA_PATH}data-video="{$aMedia.VIDEO.OTHER_MEDIA_PATH}"{/if} data-sneezy href="{if $aMedia.VIDEO.YOUTUBE_URL}{urlParser url=$aMedia.VIDEO.YOUTUBE_URL}{/if}" target="_blank"  {gtm action='Display::Video' data=$aData datasup=['eventLabel'=>{$aMedia.VIDEO.MEDIA_TITLE}]} >
								{else}
								<a class="popit" {if $aMedia.VIDEO.OTHER_MEDIA_PATH}data-video="{$aMedia.VIDEO.OTHER_MEDIA_PATH}"{/if} data-sneezy href="{if $aMedia.VIDEO.MEDIA_PATH}{urlParser url={Pelican::$config.MEDIA_HTTP|cat:$aMedia.VIDEO.MEDIA_PATH}}{/if}" target="_blank" {gtm data=$aData action='Display::Video'  datasup=['eventLabel'=>{$aMedia.VIDEO.MEDIA_ALT}]}>
									{/if}
									<figure class="shadow video">
										<i class="icon-play"></i>
										<img src="{if $aMedia.VIDEO.MEDIA_PATH}{"{Pelican::$config.MEDIA_HTTP}{$aMedia.VIDEO.MEDIA_PATH2}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_MENTION}{/if}"   width="145" height="81" alt="{$aMedia.VIDEO.MEDIA_ALT2}">
									</figure>
									<span class="legend">{$aMedia.VIDEO.MEDIA_TITLE|escape}</span>
								</a>
								{/if}

								{if $aMedia.IMAGE}
									{section name=push loop=$aMedia.IMAGE}
										{if $smarty.section.push.first}
											<a class="popit" data-sneezy="group2colm1{$key}" href="{urlParser url={Pelican::$config.MEDIA_HTTP|cat:$aMedia.IMAGE[push].MEDIA_PATH}}" target="_blank"  data-gtm="eventGTM|Content|Zoom|Vignette accessoires DS 5||" >
												<figure class="shadow">
													<img src="{"{Pelican::$config.MEDIA_HTTP}{$aMedia.IMAGE[push].MEDIA_PATH}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_PUSH}" width="145" height="81" alt="{$aMedia.IMAGE[push].MEDIA_ALT}" />
												</figure>
											</a>
										{else}
											<a class="popit grouped" data-sneezy="group2colm1{$key}" href="{urlParser url={Pelican::$config.MEDIA_HTTP|cat:$aMedia.IMAGE[push].MEDIA_PATH}}" target="_blank" {gtm action='Zoom' data=$aData datasup=['eventLabel'=> $aMedia.IMAGE[push].MEDIA_TITLE]}>
												<figure class="shadow">
													<img data-original="{"{Pelican::$config.MEDIA_HTTP}{$aMedia.IMAGE[push].MEDIA_PATH}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_PUSH}" width="145" height="81" alt="{$aMedia.IMAGE[push].MEDIA_ALT}" />
												</figure>
											</a>
										{/if}
									{/section}
								{/if}
								{/foreach}
						</div>
					{/if}
				</div>

				<!-- /.col -->
				{if $position == 'droite' && $MEDIA_VIDEO && $MEDIA_PATH && Pelican::$config['TRANCHE_COL']["GAUCHE_DROITE"][$aData.ZONE_TITRE18] == Pelican::$config['TRANCHE_COL']["GAUCHE_DROITE"][2]}
					<div class="columns column_50 vjs-big-play-button">
						<figure class=" col span3{if $aData.ZONE_TEXTE} mgfigure{else} nomgfigure{/if}">
							<figure class="shadow video">
								<a class="popit" data-video="{$MEDIA_VIDEO}" href="{urlParser url=$MEDIA_VIDEO}" data-sneezy target="_blank" {gtm data=$aData action='Display::Video'  datasup=['eventLabel'=>$MEDIA_TITLE]}>
                                                                    <i class="icon-play"></i>
                                                                    <img src="{"{Pelican::$config.MEDIA_HTTP}{$MEDIA_PATH}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_2_COLONNES_MIXTE}" width="580" height="323" alt='{$MEDIA_ALT}'>
								</a>
							</figure>
						</figure>
					</div>
				{elseif $position == 'droite' && $VisuelMediaGallerie && Pelican::$config['TRANCHE_COL']["GAUCHE_DROITE"][$aData.ZONE_TITRE18] == Pelican::$config['TRANCHE_COL']["GAUCHE_DROITE"][2]}
					<div class="columns column_50">
						<figure class="col span3 shadow shareable{if $aData.ZONE_TEXTE} mgfigure{else} nomgfigure{/if}">
							{section name=push loop=$VisuelMediaGallerie}
								{if $smarty.section.push.first}
									{IF $VIGN_GALLERY_TOP}
									{assign var="Vignette_Gal_Top" value="$VIGN_GALLERY_TOP"}
									{ELSE}
									{assign var="Vignette_Gal_Top" value=$VisuelMediaGallerie[push].MEDIA_PATH}
									{/IF}
									<span class="roll" style="border:0px;"></span>
									<img src="{"{Pelican::$config.MEDIA_HTTP}{$Vignette_Gal_Top}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_2_COLONNES_MIXTE}" width="580" height="323" alt="{$VisuelMediaGallerie[push].MEDIA_ALT}">
								{else}
									<a  {gtm data=$aData action='Zoom' datasup=['eventLabel'=> $VisuelMediaGallerie[push].MEDIA_TITLE]} class="photo" data-sneezy="group2colm1{$aData.ORDER}"  href="{urlParser url={"{Pelican::$config.MEDIA_HTTP}{$VisuelMediaGallerie[push].MEDIA_PATH}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_2_COLONNES_MIXTE}}"  target="_blank"></a>
								{/if}
							{/section}
							<a class="popit photo" data-sneezy="group2colm1{$aData.ORDER}" href="{urlParser url={"{Pelican::$config.MEDIA_HTTP}{$VisuelMediaGallerie[0].MEDIA_PATH}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_2_COLONNES_MIXTE}}" target="_blank"></a>
						</figure>
					</div>
				{elseif $position == 'droite' && $MEDIA_PATH && Pelican::$config['TRANCHE_COL']["GAUCHE_DROITE"][$aData.ZONE_TITRE18] == Pelican::$config['TRANCHE_COL']["GAUCHE_DROITE"][2]}
				<div class="columns column_50">
					<figure class="shareable visual">
						<span class="noroll"></span>
						{*<a {gtm action='Zoom' data=$aData datasup=['eventLabel'=>{$MEDIA_ALT}]} class="popit photo" data-sneezy="group2colm{$key}" href="{urlParser url={Pelican::$config.MEDIA_HTTP|cat:$MEDIA_PATH}}" target="_blank">*}
						<div class="backloupe"></div>
						{*</a>*}
						<img src="{urlParser url={Pelican::$config.MEDIA_HTTP|cat:$MEDIA_PATH}}" width="580" height="323" alt="">
						{*<a {gtm action='Zoom' data=$aData datasup=['eventLabel'=>{$MEDIA_ALT}]} class="popit photo" data-sneezy="group2colm{$aData.ORDER}" href="{urlParser url={Pelican::$config.MEDIA_HTTP|cat:$MEDIA_PATH}}" target="_blank"></a>*}
					</figure>
					</div>
				{/if}
			<!-- /.col -->

			</div>
			{if $aCta|@sizeof > 0}
				<ul class="actions">
					{section name=cta loop=$aCta}
						{if $aCta[cta].OUTIL}
							{$aCta[cta].OUTIL}
						{/if}
					{/section}
				</ul>
				<ul class="actions">
					{section name=cta loop=$aCta}
						{if !$aCta[cta].OUTIL}
							<li class="cta">
								<a {gtm action="Push" data=$aData datasup=['eventCategory'=>'Content','eventLabel' =>$aCta[cta].PAGE_ZONE_MULTI_LABEL]} href="{urlParser url=$aCta[cta].PAGE_ZONE_MULTI_URL}" target="_{$aCta[cta].PAGE_ZONE_MULTI_VALUE}" {*if ($aData.SECOND_COLOR|count_characters)==7 } style="background-color:{$aData.SECOND_COLOR};border-color:{$aData.SECOND_COLOR};color:{$aData.PRIMARY_COLOR};"  {/if*} class="buttonTransversalInvert ">
									{$aCta[cta].PAGE_ZONE_MULTI_LABEL}
								</a>
							</li>
						{/if}
					{/section}
				</ul>
			{/if}
		</section>
	</div>
	<div class="parent" id="trancheParent" style="display: none;"></div>

	{if $aData.ZONE_LANGUETTE == 1}
		<section class="showroom row of3 clslanguetteshowroom">
			<div class="caption addmore folder" data-off="border:4px solid {if ($aData.SECOND_COLOR|count_characters)==7}{$aData.SECOND_COLOR}{else}#afadc3{/if};color:{if ($aData.SECOND_COLOR|count_characters)==7}{$aData.SECOND_COLOR}{else}#afadc3{/if};padding:8px;" data-hover="border:6px solid {if ($aData.SECOND_COLOR|count_characters)==7}{$aData.SECOND_COLOR}{else}#afadc3{/if};color:{if ($aData.SECOND_COLOR|count_characters)==7}{$aData.SECOND_COLOR}{else}#afadc3{/if};padding:6px;" data-toggle="{if $aData.ZONE_LANGUETTE_TEXTE_CLOSE}{$aData.ZONE_LANGUETTE_TEXTE_CLOSE}{else}{'VOIR_MOINS'|t}{/if}"><a {if ($aData.PRIMARY_COLOR|count_characters)==7 } class="col span2" {/if} href="#trancheParent" {gtm action="Push" data=$aData datasup=['eventLabel' => 'LANGUETTE_1_COLONNE']}><span style="color: inherit;">{if $aData.ZONE_LANGUETTE_TEXTE_OPEN}{$aData.ZONE_LANGUETTE_TEXTE_OPEN}{else}{t("Voir plus")}{/if}</span></a></div>
		</section>
	{/if}
{/if}