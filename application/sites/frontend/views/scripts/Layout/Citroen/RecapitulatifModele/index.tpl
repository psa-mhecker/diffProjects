</div>
{if $aDataParams.ZONE_WEB eq 1 and $display neq 0}
	<br/><br/><br/>
	<div class="sliceNew sliceSummaryModelDesk" style="margin: 0 auto;max-width: 1280px;min-width: 940px;padding: 0 15px;">
		<div id="{$aDataParams.ID_HTML}" class="mea {$aDataParams.ZONE_SKIN} showroom nobg clsrecapitulatifmodele">
			<div class="inner">
				{if $mentionType != "TEXT" }
					<!-- Concernant la popin recapitulatif du modele   -->
				{if $cashPriceLegalMention}
				{if $mentionType == "ROLL" }
					<div class="legal layertip" id="cashBuy">
						{$cashPriceLegalMention}
					</div>
					<!-- /.layertip -->
				{else}
					<script type="text/template" id="cashBuyPop">
						<div class="legal layerpop">
							{$cashPriceLegalMention}
						</div>
					</script>
				{/if}
				{/if}
					<!-- /.layertip -->
				{if $aDataSimulateurFinancement}
				{if $mentionType == "ROLL" }
					<div class="legal layertip" id="creditBuy">
						{if $useFinancialSimulator == true}
							{if $aDataSimulateurFinancement.TITLE|@trim}
								<p>{$aDataSimulateurFinancement.TITLE}</p> {/if}
							{foreach from=$aDataSimulateurFinancement.VARIABLES item=financement name=dataSimulateurFinancement key=key}
								{if $key == 'LegalText'}
									{if $financement.LABEL|@trim AND $financement.LABEL ne '-'}
										<div class="scroll">
											<p><small>{$financement.LABEL}{if $financement.VALUE|@trim} <span class="value">{$financement.VALUE} {$financement.UNIT}</span>{/if}</small></p>
										</div>
									{/if}
								{else}
									{if $financement.LABEL|@trim AND $financement.LABEL ne '-'}<p><small>{$financement.LABEL} {if $financement.VALUE|@trim}<span class="value">{$financement.VALUE} {$financement.UNIT}</span>{/if}</small></p>{/if}
								{/if}
							{/foreach}
						{else}
							{$creditPriceNextRentLM}
						{/if}
						<!-- /.scroll -->
					</div>
					<!-- /.layertip -->
				{else}
					<script type="text/template" id="creditBuyPop">
						<div class="legal layerpop">
							{if $useFinancialSimulator == true}
								{if $aDataSimulateurFinancement.TITLE|@trim}
									<p>{$aDataSimulateurFinancement.TITLE}</p>
								{/if}
								{foreach from=$aDataSimulateurFinancement.VARIABLES item=financement name=dataSimulateurFinancement key=key}
									{if $key == 'LegalText'}
										{if $financement.LABEL|@trim AND $financement.LABEL ne '-'}
											<div class="scroll">
												<p><small>{$financement.LABEL}{if $financement.VALUE|@trim} <span class="value">{$financement.VALUE} {$financement.UNIT}</span>{/if}</small></p>
											</div>
										{/if}
									{else}
										{if $financement.LABEL ne '' AND $financement.LABEL ne '-'}<p><small>{$financement.LABEL} {if $financement.VALUE|@trim}<span class="value">{$financement.VALUE} {$financement.UNIT}</span>{/if}</small></p>{/if}
									{/if}
								{/foreach}
							{else}
								{$creditPriceNextRentLM}
							{/if}
						</div>
					</script>
				{/if}
				{/if}
				{/if}
				<div class="title"><em>{$aDataParams.ZONE_TITRE|escape} {$aDataParams.ZONE_TITRE2|escape}</em></div>
				<div class="prices">
					<div>
						<div class="pricesInner">
							<div>
								{if $hasCashPrice ==  true}
									<span class="price">{'A_PARTIR_DE'|t} <em style="font-weight:bold;"><strong>{$aShowRoomInfo.CASH_PRICE} {$aShowRoomInfo.VEHICULE_CASH_PRICE_TYPE|t}</strong></em></span>
									{if $isPopinRecap && $cashPriceLegalMention}<a class="tooltip pop" href="#cashBuyPop" {gtmjs type='toggle'  action='Display::ToolTip|' eventGTM='over'   data=$aParams  datasup=['value'=>'cashBuyPop','eventLabel'=>$aShowRoomInfo.VEHICULE_LABEL]} >?</a>{/if}
									{if $isRollOverRecap && $cashPriceLegalMention}<a class="tooltip" href="#cashBuy" {gtmjs type='toggle'  action='Display::ToolTip|' eventGTM='over'   data=$aParams  datasup=['value'=>'cashBuy','eventLabel'=>$aShowRoomInfo.VEHICULE_LABEL]} >?</a>{/if}
									<br />
								{/if}
								{if $hasCreditPrice == true}
									{if $useFinancialSimulator == true}
										{if $wsCreditPriceNextRentValue}
											{'OU_A_PARTIR_DE'|t} <em style="font-weight:bold;">{$wsCreditPriceNextRentValue} {$wsCreditPriceNextRentUnit}{'PER_MONTH'|t}</em>
											{if $isPopinRecap && $aDataSimulateurFinancement}<a class="tooltip pop" href="#creditBuyPop" {gtmjs type='toggle'  action='Display::ToolTip|' eventGTM='over'   data=$aParams  datasup=['value'=>'cashBuyPop','eventLabel'=>$aShowRoomInfo.VEHICULE_LABEL]} >?</a>{/if}
											{if $isRollOverRecap && $aDataSimulateurFinancement}<a class="tooltip" href="#creditBuy" {gtmjs type='toggle'  action='Display::ToolTip|' eventGTM='over'   data=$aParams  datasup=['value'=>'creditBuy','eventLabel'=>$aShowRoomInfo.VEHICULE_LABEL]} >?</a>{/if}
											<br />
										{/if}
									{else}
										{if $creditPriceNextRent}
											{'OU_A_PARTIR_DE'|t} <em style="font-weight:bold;">{$creditPriceNextRent} {'PER_MONTH'|t}</em>
											{if $isPopinRecap && $creditPriceNextRentLM}<a class="tooltip pop" href="#creditBuyPop" {gtmjs type='toggle'  action='Display::ToolTip|' eventGTM='over'   data=$aParams  datasup=['value'=>'cashBuyPop','eventLabel'=>$aShowRoomInfo.VEHICULE_LABEL]} >?</a>{/if}
											{if $isRollOverRecap && $creditPriceNextRentLM}<a class="tooltip" href="#creditBuy" {gtmjs type='toggle'  action='Display::ToolTip|' eventGTM='over'   data=$aParams  datasup=['value'=>'creditBuy','eventLabel'=>$aShowRoomInfo.VEHICULE_LABEL]} >?</a>{/if}
											<br />
										{/if}
									{/if}
								{/if}
							</div>
						</div>
					</div>
				</div>
				<!-- /.prices -->
				<figure>
					<img width="717" height="438" alt="{$aKeyPointsMedia.MEDIA_ALT|escape}" data-original="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/235-100.png" src="{"{Pelican::$config.MEDIA_HTTP}{$aKeyPointsMedia.MEDIA_PATH}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_RECAP_MODELE}" class="" style="display: inline-block;">
					<noscript><img src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/235-100.png" src="{"{Pelican::$config.MEDIA_HTTP}{$aKeyPointsMedia.MEDIA_PATH}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_RECAP_MODELE}" width="717" height="438" alt="{$aKeyPointsMedia.MEDIA_ALT|escape}" /></noscript>
				</figure>

				<!-- /.prices -->
				{if ( $wsCreditPriceFirstRent && $useFinancialSimulator == true) || ( $creditPriceFirstRent && $useFinancialSimulator == false)}
					<div class="more">
						{if $useFinancialSimulator == true}
							{$wsCreditPriceFirstRent}
						{else}
							{$creditPriceFirstRent}<br />
						{/if}

						{if $useFinancialSimulator == true}
							<div style="margin-top: 5px; font-size: 12px; width: 440px" class="smallText">
								{$wsCreditPriceFirstRentLM}
							</div>
						{else}
							<div style="margin-top: 5px; font-size: 12px; width: 440px" class="smallText">
								{$creditPriceFirstRentLM}
							</div>
						{/if}
					</div>
				{/if}
				<!-- /.more -->

				<ul class="actions" {if ($aData.PRIMARY_COLOR|count_characters)==7 } data-off="border:4px solid {$aData.SECOND_COLOR};background:{$aData.SECOND_COLOR}; color:#ffffff;" data-hover="border:4px solid {$aData.SECOND_COLOR}; background:#ffffff; color:{$aData.SECOND_COLOR};"  data-firstoff="border:4px solid {$aData.PRIMARY_COLOR}!important;background:{$aData.PRIMARY_COLOR}!important; color:#ffffff!important;" data-firsthover="border:4px solid {$aData.PRIMARY_COLOR}!important; background:#ffffff!important; color:{$aData.PRIMARY_COLOR}!important;" {/if}>
					{if $urlConfigurateur}
						<li><a class="buttonTransversalInvert" href="{urlParser url=$urlConfigurateur}" {gtm action='Summary'  data=$aData datasup=['eventLabel'=>{'CONFIGURER'|t}]} target="_blank">{'CONFIGURER'|t}</a></li>
					{/if}
					{foreach from=$aTools item=tool}
						{$tool}
					{/foreach}
				</ul>
				<!-- /.actions -->
				{if $pelican_config.SITE.INFOS.SITE_ACTIVATION_MON_PROJET == 1}
					{if $bActiveAddToSelection}
						<a href="#" onclick="{literal}javascript:selectionVehicule.save('{/literal}{$aShowRoomInfo.LCDV6}{literal}'{/literal},{$iOrder},true);return false;" class="button add2selection"
						{gtm action='Summary'  data=$aData datasup=['eventLabel'=>{'ADD_TO_MY_SELECTION'|t}]}
					{else}
						<a href="#" onclick="javascript:return false;" class="button">{'VEHICULE_IN_SELECTION'|t}</a>
					{/if}
				{/if}
			</div>
		</div>
		{if $mentionType == "TEXT" }
			<section class="row of6  clsmlpagerecapmodele">

				<div class="col span4 of6 ">
					{if $useFinancialSimulator == true}

						{if $aDataSimulateurFinancement.TITLE|@trim}
							<p>{$aDataSimulateurFinancement.TITLE}</p>
						{/if}

						{foreach from=$aDataSimulateurFinancement.VARIABLES item=financement name=dataSimulateurFinancement key=key}
							{if $financement.LABEL|@trim AND $financement.LABEL ne '-'}
								<p><small>{$financement.LABEL}
										{if $financement.VALUE|@trim}
											<span class="value">{$financement.VALUE} {$financement.UNIT}</span>
										{/if}
									</small></p>
							{/if}

						{/foreach}
					{else}
						<p>{$creditPriceNextRentLM}</p>
					{/if}

				</div>
			</section>

		{else}
			<div class="legal layertip" id="cashBuyRecap">
				{$cashPriceLegalMention}
			</div>
		{/if}
		<!-- /.layertip -->
	</div>
{/if}