{if $aParams.ZONE_WEB eq 1 and $display neq 0}
	<div class="sliceNew sliceTintSelectorDesk" style="padding-bottom: 2px;">
		<div id="{$aParams.ID_HTML}" class="banner full {if $aDataParams.PAGE_GAMME_VEHICULE == Pelican::$config['VEHICULE_GAMME'].GAMME_LIGNE_DS}ds{/if} colors showroom clsselecteurteinte" {if ($aDataParams.PRIMARY_COLOR|count_characters)==7}data-textBg="background:{$aDataParams.SECOND_COLOR};" data-arrows="color:{$aDataParams.PRIMARY_COLOR};" data-pagerBg="background:{$aDataParams.PRIMARY_COLOR};" data-colorHover="border:4px solid {$aDataParams.PRIMARY_COLOR};" {/if} {gtmjs type='jColors' action='ChangeCarView' datasup=['eventCategory'=>'Showroom']}>

			<div class="texts" style='width: 233px'>
				{if $aParams.ZONE_TITRE5 != "TEXT"}
					<!-- Concernant la popin selecteur de teinte   -->
				{if $cashPriceLegalMentionSelecteurTeinte}
				{if $aParams.ZONE_TITRE5 == "ROLL"}
					<div class="legal layertip" id="cashBuySelecteurTeinte">
						{$cashPriceLegalMentionSelecteurTeinte}
					</div>
					<!-- /.layertip -->
				{else}
					<script type="text/template" id="cashBuyPopSelecteurTeinte">
						<div class="legal layerpop">
							{$cashPriceLegalMentionSelecteurTeinte}
						</div>
					</script>
				{/if}
				{/if}
					<!-- /.layertip -->
				{if $aDataSimulateurFinancementSelecteurTeinte}
				{if $aParams.ZONE_TITRE5 == "ROLL"}
					<div class="legal layertip" id="creditBuySelecteurTeinte">
						{if $useFinancialSimulator == true}
							{if $aDataSimulateurFinancementSelecteurTeinte.TITLE|@trim} <p>{$aDataSimulateurFinancementSelecteurTeinte.TITLE}</p>
							{/if}
							{foreach from=$aDataSimulateurFinancementSelecteurTeinte.VARIABLES item=financement name=dataSimulateurFinancement key=key}
								{if $financement.VALUE eq '0'}
								{assign var="finanvalue" value=$financement.VALUE|number_format:2:",":"."}
								{else}
								{assign var="finanvalue" value=$financement.VALUE}
								{/if}
				
								{if $key == 'LegalText'}
									{if $financement.LABEL|@trim AND $financement.LABEL ne '-'}
										<div class="scroll">
											<p><small>{$financement.LABEL} {if finanvalue|@trim}<span class="value">{$finanvalue} {$financement.UNIT}</span>{/if}</small></p>
										</div>
									{/if}
								{else}
								
									{if $financement.LABEL|@trim AND $financement.LABEL ne '-'}<p><small>{$financement.LABEL} {if $finanvalue|@trim}<span class="value">{$finanvalue} {$financement.UNIT}</span>{/if}</small></p>{/if}
								{/if}
							{/foreach}
						{else}
							{$creditPriceNextRentLMSelecteurTeinte}
						{/if}
						<!-- /.scroll -->
					</div>
					<!-- /.layertip -->
				{else}
					<script type="text/template" id="creditBuyPopSelecteurTeinte">
						<div class="legal layerpop">
							{if $useFinancialSimulator == true}
								{if $aDataSimulateurFinancementSelecteurTeinte.TITLE|@trim}
									<p>{$aDataSimulateurFinancementSelecteurTeinte.TITLE}</p> {/if}
								{foreach from=$aDataSimulateurFinancementSelecteurTeinte.VARIABLES item=financement name=dataSimulateurFinancement key=key}
									{if $financement.VALUE eq '0'}
									{assign var="finanvalue" value=$financement.VALUE|number_format:2:",":"."}
									{else}
									{assign var="finanvalue" value=$financement.VALUE}
									{/if}
									{if $key == 'LegalText'}
										{if $financement.LABEL|@trim AND $financement.LABEL ne '-'}
											<div class="scroll">
												<p><small>{$financement.LABEL} {if $finanvalue|@trim}<span class="value">{$finanvalue} {$financement.UNIT}</span>{/if}</small></p>
											</div>
										{/if}
									{else}
										{if $financement.LABEL|@trim AND $financement.LABEL ne '-'}<p><small>{$financement.LABEL} {if $finanvalue|@trim}<span class="value">{$finanvalue} {$financement.UNIT}</span>{/if}</small></p>{/if}
									{/if}
								{/foreach}
							{else}
								{$creditPriceNextRentLMSelecteurTeinte}
							{/if}
						</div>
					</script>
				{/if}
				{/if}
					<!-- Fin de la popin mention légale selecteur de teinte   -->
				{/if}
				<div itemscope itemtype="http://schema.org/Product"><span itemprop="name"><h1 class="title">{$aParams.ZONE_TITRE|escape} <em>{$aParams.ZONE_TITRE2|escape}</em></h1></span></div>
				{if !$hidePrice}
				<div class="prices">
					<div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
						<div class="pricesInner">


							{if $hasCashPrice ==  true}
								{if $aShowRoomInfo.CASH_PRICE}
									{counter name=tooltipCounter assign="tooltipPosition"}
									<div>
										<span class="price">{$stAPartirDe} <em><strong><span itemprop="price">{$aShowRoomInfo.CASH_PRICE}</span></strong> {$cashPriceType}</em></span>
										{if $isPopin && $cashPriceLegalMentionSelecteurTeinte}<a class="tooltip pop" {gtmjs type='toggle'  action='Display::ToolTip|' eventGTM='over'  data=$aParams  datasup=['value'=>'cashBuyPopSelecteurTeinte','eventLabel'=>$aShowRoomInfo.VEHICULE_LABEL]}  href="#cashBuyPopSelecteurTeinte">?</a>{/if}
										{if $isRollOver && $cashPriceLegalMentionSelecteurTeinte}<a class="tooltip" {gtmjs type='toggle'  action='Display::ToolTip|' eventGTM='over'  data=$aParams  datasup=['value'=>$tooltipPosition,'eventLabel'=>$aShowRoomInfo.VEHICULE_LABEL]} href="#cashBuySelecteurTeinte">?</a>{/if}
									</div>
								{/if}
							{/if}
							
							{if !$bIsEsNl}
							{if $hasCreditPrice == true}
								{if $useFinancialSimulator == true}
									{if $wsCreditPriceNextRentValue}
										{counter name=tooltipCounter assign="tooltipPosition"}
										<div>
											<span class="price">{'OU_A_PARTIR_DE'|t} <em><strong><span itemprop="price">{$wsCreditPriceNextRentValue}{$wsCreditPriceNextRentUnit}</span></strong> {'PER_MONTH'|t}</em></span>
											{if $isPopin && $aDataSimulateurFinancementSelecteurTeinte.TITLE}<a class="tooltip pop" {gtmjs type='toggle'  action='Display::ToolTip|' eventGTM='over'  data=$aParams  datasup=['value'=>'creditBuyPopSelecteurTeinte','eventLabel'=>$aShowRoomInfo.VEHICULE_LABEL]} href="#creditBuyPopSelecteurTeinte">?</a>{/if}
											{if $isRollOver && $aDataSimulateurFinancementSelecteurTeinte.TITLE}<a class="tooltip" {gtmjs type='toggle'  action='Display::ToolTip|' eventGTM='over'  data=$aParams  datasup=['value'=>$tooltipPosition,'eventLabel'=>$aShowRoomInfo.VEHICULE_LABEL]} href="#creditBuySelecteurTeinte">?</a>{/if}
										</div>
									{/if}
								{else}
									{if $creditPriceNextRent}
										{counter name=tooltipCounter assign="tooltipPosition"}
										<div>
											<span class="price">{'OU_A_PARTIR_DE'|t} <em><strong><span itemprop="price">{$creditPriceNextRent}</span></strong> {'PER_MONTH'|t}</em></span>
											{if $isPopin && $creditPriceNextRentLMSelecteurTeinte}<a class="tooltip pop" {gtmjs type='toggle'  action='Display::ToolTip|' eventGTM='over'  data=$aParams  datasup=['value'=>'creditBuyPopSelecteurTeinte','eventLabel'=>$aShowRoomInfo.VEHICULE_LABEL]} href="#creditBuyPopSelecteurTeinte">?</a>{/if}
											{if $isRollOver && $creditPriceNextRentLMSelecteurTeinte}<a class="tooltip" {gtmjs type='toggle'  action='Display::ToolTip|' eventGTM='over'  data=$aParams  datasup=['value'=>$tooltipPosition,'eventLabel'=>$aShowRoomInfo.VEHICULE_LABEL]} href="#creditBuySelecteurTeinte">?</a>{/if}
										</div>
									{/if}
								{/if}
							{/if}
							{/if}
                                                        
                                                      

						</div>
					</div>


					{* Lien popup calculatrice finacement *}
					{if $calculatriceFinancement && $useFinancialSimulator}
						<a class="calculatrice-financement" href="{urlParser url=$calculatriceFinancement.PersoURL}" target="_blank" onClick="sendGTM('Showroom','Redirection','{'CALCULATRICE_FINANCEMENT'|t}');">{'CALCULATRICE_FINANCEMENT'|t}</a> 
					{/if}

				</div>
				{/if}
				<!-- /.more -->
			</div>
			
			  <!-- /.prices -->
            {if $codepays neq 'FR'}                                          
		  {if ( $wsCreditPriceFirstRent && $useFinancialSimulator == true) || ( $creditPriceFirstRent && $useFinancialSimulator == false)}
			<div class="smallText">
				{if $useFinancialSimulator == true}
					{$wsCreditPriceFirstRent}
				{else}
					{$creditPriceFirstRent}
				{/if}
				</div>
		   
		{/if} 
		{else}
			
			{if $useFinancialSimulator == true}
				<div class="smallText">
					{$wsCreditPriceFirstRentLM}
				</div>
			{else}
				<div class="smallText">
					{$creditPriceFirstRentLM}
				</div>
			{/if}
			{/if} 
			
			<!-- /.texts -->
			{if $aDataParams.PAGE_GAMME_VEHICULE == Pelican::$config['VEHICULE_GAMME'].GAMME_VEHICULE_UTILITAIRE}
				<img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/colors.png" data-original="{Pelican::$config.MEDIA_HTTP}{$aShowRoomInfo.BCKGRD1_PATH}" width="1480" height="438" alt="{$aShowRoomInfo.BCKGRD1_ALT}" />
			{/if}
			<div class="larger" >
				<script type="text/template" class="viewTpl">
			   {literal}<div class="views">
					<% if(imgs.length > 0) { %>
						<% _.each(imgs,function(img,i){ %>
						<div class="view"<% if(bgs[i]){ %> style="background-image:url(<%= bgs[i] %>){/literal}{if $aDataParams.PAGE_GAMME_VEHICULE == Pelican::$config['VEHICULE_GAMME'].GAMME_LIGNE_DS}{literal}!important{/literal}{/if}{literal};" data-img="<%= bgs[i] %>"<%}; %>>
						{/literal}
						{if $aShowRoomColors}
						{literal}
						<img itemprop="image" class="car" src="<%= img %>" width="717" height="438" alt="" />
						{/literal}
						{/if}
						{literal}
						</div>
						<%}); %>
					<%} else { %>
						<% _.each(bgs,function(bg,i){ %>
						<div class="view" style="background-image:url(<%= bg %>);" data-img="<%= bg %>"></div>
						<%}); %>
					<%} %>
				</div>{/literal}
			</script>
			</div>

			<ul {if $aShowRoomColors} data-text=""{/if} class="showroom clsselecteurteinte">
				{if $aShowRoomColors}
					{foreach from=$aShowRoomColors item=color name=showroomColors key=id}
						{if $smarty.foreach.showroomColors.iteration == 1}<li class="on">{else}<li>{/if}
						<a href="{urlParser url={Pelican::$config.MEDIA_HTTP|cat:$color.CARWEB1_PATH}}"
						   target="_blank"
						   data-views="{Pelican::$config.MEDIA_HTTP}{$color.CARWEB1_PATH}{if $color.CARWEB2_PATH}|{Pelican::$config.MEDIA_HTTP}{$color.CARWEB2_PATH}{/if}{if $color.CARWEB3_PATH}|{Pelican::$config.MEDIA_HTTP}{$color.CARWEB3_PATH}{/if}"
						   data-bgs="{Pelican::$config.MEDIA_HTTP}{$aShowRoomInfo.BCKGRD1_PATH}{if $color.CARWEB2_PATH}|{Pelican::$config.MEDIA_HTTP}{$aShowRoomInfo.BCKGRD2_PATH}{/if}{if $color.CARWEB3_PATH}|{Pelican::$config.MEDIA_HTTP}{$aShowRoomInfo.BCKGRD3_PATH}{/if}"
						   data-refs="COLOR2|COLOR2B"
								{gtm action='ColorSelector' data=$aParams datasup=['value'=>{$id+1} , 'eventLabel'=>{$color.VEHICULE_COULEUR_LABEL}]}

						>
							<img src="{Pelican::$config.MEDIA_HTTP}{$color.PICTO_PATH}" width="32" height="32" alt="{$color.VEHICULE_COULEUR_LABEL}" />
						</a>
						</li>
					{/foreach}
				{else}
					<li>
						<a href=""
						   target="_blank"
						   data-views="{Pelican::$config.MEDIA_HTTP}{$aShowRoomInfo.THUMBNAIL_PATH}{if $aShowRoomInfo.BCKGRD2_PATH}|{Pelican::$config.MEDIA_HTTP}{$aShowRoomInfo.THUMBNAIL_PATH}{/if}{if $aShowRoomInfo.BCKGRD3_PATH}|{Pelican::$config.MEDIA_HTTP}{$aShowRoomInfo.THUMBNAIL_PATH}{/if}"
						   data-bgs="{Pelican::$config.MEDIA_HTTP}{$aShowRoomInfo.BCKGRD1_PATH}{if $aShowRoomInfo.BCKGRD2_PATH}|{Pelican::$config.MEDIA_HTTP}{$aShowRoomInfo.BCKGRD2_PATH}{/if}{if $aShowRoomInfo.BCKGRD3_PATH}|{Pelican::$config.MEDIA_HTTP}{$aShowRoomInfo.BCKGRD3_PATH}{/if}"
						   data-refs="COLOR2|COLOR2B"
								{gtm action='ColorSelector' data=$aParams datasup=['value'=>$id , 'eventLabel'=>'N/A']}


						>
							<img src="{Pelican::$config.MEDIA_HTTP}{$color.PICTO_PATH}" width="32" height="32" alt="{$color.VEHICULE_COULEUR_LABEL}" />
						</a>
					</li>
				{/if}
			</ul>

			{if $pelican_config.SITE.INFOS.SITE_ACTIVATION_MON_PROJET == 1}
				{if $bActiveAddToSelection}
					<a href="#" onclick="{literal}javascript:selectionVehicule.save('{/literal}{$aShowRoomInfo.LCDV6}{literal}'{/literal},{$iOrder},true);return false;" class="button add2selection" {gtm action='Push' data=$aParams datasup=['eventLabel'=>{'ADD_TO_MY_SELECTION'|t}|cat:':'|cat:$aShowRoomInfo.VEHICULE_LABEL]}>{'ADD_TO_MY_SELECTION'|t}</a>
				{else}
					<a href="#" onclick="javascript:return false;" class="button">{'VEHICULE_IN_SELECTION'|t}</a>
				{/if}
			{/if}

			<!-- EXTRA CTAS CPW-4101 -->
			{if $CTAShowroom}

				<div class="extraCtas">
					<ul class="actions">
						{foreach $CTAShowroom as $k=>$cta}
							{if $cta.BARRE_OUTILS_ID}
								<li class="blue"><a href="{urlParser url=$cta.BARRE_OUTILS_URL_WEB}" target="_{if $cta.BARRE_OUTILS_MODE_OUVERTURE == 1}self{else}blank{/if}" data-sync="forcesync_extraCtaH" data-gtm='eventGTM|Showroom::Color Selector|Redirection|{$cta.BARRE_OUTILS_LABEL}||' data-gtm-init='0'><span>{$cta.BARRE_OUTILS_TITRE}</span></a></li>
							{else}
								<li class="blue"><a href="{urlParser url=$cta.VEHICULE_CTA_SHOWROOM_URL}" target="_{$cta.VEHICULE_CTA_SHOWROOM_VALUE|strtolower}" data-sync="forcesync_extraCtaH" data-gtm='eventGTM|Showroom::Color Selector|Redirection|{$cta.VEHICULE_CTA_SHOWROOM_LABEL}||' data-gtm-init='0'><span>{$cta.VEHICULE_CTA_SHOWROOM_LABEL}</span></a></li>
							{/if}
						{/foreach}
					</ul>
					<div class="close"></div>
				</div>
				<style type="text/css">
					{literal}
					.showroom .extraCtas a{
						border:4px solid {/literal}{$aDataParams.SECOND_COLOR}{literal}!important;
						background:{/literal}{$aDataParams.SECOND_COLOR}{literal}!important;
						color:#ffffff!important;
					}
					.showroom .extraCtas a:hover{
						border:4px solid {/literal}{$aDataParams.SECOND_COLOR}{literal}!important;
						background:#ffffff!important;
						color:{/literal}{$aDataParams.SECOND_COLOR}{literal}!important;
					}
					{/literal}
				</style>
			{/if}
			<!-- 360 CPW-3622 -->

			{if $affichageVisuelsInterieur360.AFFICHAGE_VISUEL_360_WEB eq 1 && $show360 == "false"}
				<div class="view-360 init">
					<canvas id="canvas" width="100%" height="438px" style="width: 100%; height: 438px;"></canvas>
					<script type="text/javascript">
						{literal}
						var tabImages = [];
						tabImages.push('{/literal}{$urlVisuelsInterieur360.MEDIA_ID5}{literal}'); // GAUCHE
						tabImages.push('{/literal}{$urlVisuelsInterieur360.MEDIA_ID2}{literal}'); // DROITE
						tabImages.push('{/literal}{$urlVisuelsInterieur360.MEDIA_ID}{literal}'); // HAUT
						tabImages.push('{/literal}{$urlVisuelsInterieur360.MEDIA_ID4}{literal}'); // BAS
						tabImages.push('{/literal}{$urlVisuelsInterieur360.MEDIA_ID3}{literal}'); // ARRIERE
						tabImages.push('{/literal}{$urlVisuelsInterieur360.MEDIA_ID6}{literal}'); // AVANT
						/*
						 tabImages.push('{/literal}{Pelican::$config.DESIGN_HTTP}{literal}/images/360-5.png');
						 tabImages.push('{/literal}{Pelican::$config.DESIGN_HTTP}{literal}/images/360-2.png');
						 tabImages.push('{/literal}{Pelican::$config.DESIGN_HTTP}{literal}/images/360-1.png');
						 tabImages.push('{/literal}{Pelican::$config.DESIGN_HTTP}{literal}/images/360-4.png');
						 tabImages.push('{/literal}{Pelican::$config.DESIGN_HTTP}{literal}/images/360-3.png');
						 tabImages.push('{/literal}{Pelican::$config.DESIGN_HTTP}{literal}/images/360-6.png');
						 */

						var tabScripts = [];
						tabScripts.push('{/literal}{Pelican::$config.DESIGN_HTTP}{literal}/js/typedarray.js');
						tabScripts.push('{/literal}{Pelican::$config.DESIGN_HTTP}{literal}/js/three.min.js');
						tabScripts.push('{/literal}{Pelican::$config.DESIGN_HTTP}{literal}/js/Projector.js');
						tabScripts.push('{/literal}{Pelican::$config.DESIGN_HTTP}{literal}/js/CanvasRenderer.js');
						tabScripts.push('{/literal}{Pelican::$config.DESIGN_HTTP}{literal}/js/inside/namespace.js');
						tabScripts.push('{/literal}{Pelican::$config.DESIGN_HTTP}{literal}/js/inside/object3d/pointofinterest.js');
						tabScripts.push('{/literal}{Pelican::$config.DESIGN_HTTP}{literal}/js/inside/object3d/cube.js');
						tabScripts.push('{/literal}{Pelican::$config.DESIGN_HTTP}{literal}/js/inside/inside.js');

						{/literal}
					</script>
				</div>
				<div class="view-selector">
					<div class="outside"><a class="active" href="javascript:void(0);"  {gtm action='Display::Exterior' data=$aData datasup=['eventCategory'=>'Showroom::Color selector' , 'eventLabel' => 'VOIR-EXT-DESKTOP'|t]}><span>{'VOIR-EXT-DESKTOP'|t}</span></a></div>
					<div class="inside"><a href="javascript:void(0);"  {gtm action='Display::Interior' data=$aData datasup=['eventCategory'=>'Showroom::Color selector' , 'eventLabel' => 'VOIR-INT-DESKTOP'|t]}><span>{'VOIR-INT-DESKTOP'|t}</span></a></div>
					<input type="hidden" id="discover-gtm" {gtm action='Discover' data=$aData datasup=['eventCategory'=>'Showroom::Color selector' , 'eventLabel' => 'ViewMore360']}>
				</div>


				<style type="text/css">
					{literal}
					.showroom .view-selector a:hover:before,
					.showroom .view-selector a.active:before{
						color:{/literal}{$aDataParams.PRIMARY_COLOR}{literal}!important;
					}
					.showroom .view-selector a:hover span,
					.showroom .view-selector a.active span{
						color:{/literal}{$aDataParams.PRIMARY_COLOR}{literal}!important;
					}

					.showroom .view-360:before{
						color:{/literal}{$aDataParams.PRIMARY_COLOR}{literal}!important;
					}
					{/literal}
				</style>

			{/if}

		</div>
		{if $aParams.ZONE_TITRE5 == "TEXT"}
			<div class="row of6 clsmlpageselecteurteinte">

				<div class="col span4">
					{if $useFinancialSimulator == true}

						{if $aDataSimulateurFinancementSelecteurTeinte.TITLE|@trim}
							<p>{$aDataSimulateurFinancementSelecteurTeinte.TITLE}</p>
						{/if}

						{foreach from=$aDataSimulateurFinancementSelecteurTeinte.VARIABLES item=financement name=dataSimulateurFinancement key=key}
							{if $financement.VALUE eq '0'}
									{assign var="finanvalue" value=$financement.VALUE|number_format:2:",":"."}
									{else}
									{assign var="finanvalue" value=$financement.VALUE}
									{/if}
							{if $financement.LABEL|@trim AND $financement.LABEL ne '-'}
								<p><small>{$financement.LABEL}
										{if $finanvalue|@trim}
											<span class="value">{$finanvalue} {$financement.UNIT}</span>
										{/if}
									</small></p>
							{/if}
						{/foreach}
					{else}
						<p>{$creditPriceNextRentLMSelecteurTeinte}</p>
					{/if}
				</div>
			</div>

		{/if}
	</div>
{/if}