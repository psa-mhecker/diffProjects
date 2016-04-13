{if $bTrancheVisible && $aAccessoires|@sizeof > 0}
	<div class="sliceNew sliceAccessoiresDesk">
		<section id="{$aData.ID_HTML}" class="row of3 foldbyrow accessoriesTranche showroom clsaccessoires {$aData.ZONE_SKIN}">
			{if $aData.ZONE_TEXTE}
				<div class="row of3">
					<div class="col span2 zonetexte">{$aData.ZONE_TEXTE}</div>
					<!-- /.col -->
				</div>
			{/if}

			{if ($aData.SECOND_COLOR|count_characters)==7 }
				<style type="text/css">
					.sliceAccessoiresDesk .showroom.clsaccessoires .mosaic a figure span:before {ldelim}
						border-color: {$aData.SECOND_COLOR} !important;
					{rdelim}
					.sliceAccessoiresDesk .showroom.clsaccessoires .mosaic a figure:hover figcaption,
					.sliceAccessoiresDesk .showroom.clsaccessoires .mosaic.open a figure figcaption {ldelim}
						color: {$aData.SECOND_COLOR} !important;
					{rdelim}
					.sliceAccessoiresDesk .showroom.clsaccessoires .tabbed .tabs li h3 {ldelim}
						border-color: {$aData.SECOND_COLOR};
					{rdelim}
					.sliceAccessoiresDesk .showroom.clsaccessoires .tabbed .tabs li h3:hover {ldelim}
						border-color: {$aData.SECOND_COLOR};
						background: {$aData.SECOND_COLOR};
					{rdelim}
					.sliceAccessoiresDesk .showroom.clsaccessoires .tabbed .tabs li.on h3 {ldelim}
						border-color: {$aData.SECOND_COLOR};
						background: {$aData.SECOND_COLOR};
					{rdelim}
				</style>
			{/if}

			{foreach from=$aAccessoires item=univers name=listUnivers}
				{if $univers.UNIVERS_IMG}
					<div class="col folder mosaic" data-group="add">
						<a href="#{$univers.CODE}" {gtmjs type='toggle'  action='Choose::Category|' data=$aData datasup=['eventLabel'=>{$univers.UNIVERS}]}>
							<figure>
								<span>
									<img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/accessory-cat.png" data-original="{Pelican::$config.MEDIA_HTTP}{$univers.UNIVERS_IMG}" width="373" height="162" alt="{$univers.UNIVERS}">
									<noscript><img src="{Pelican::$config.MEDIA_HTTP}{$univers.UNIVERS_IMG}" width="373" height="162" alt="{$univers.UNIVERS}"></noscript>
								</span>
								<figcaption>{$univers.UNIVERS|mb_strtoupper}</figcaption>
							</figure>
						</a>
					</div>
				{/if}
			{/foreach}

			{foreach from=$aAccessoires item=univers name=listUnivers}
				{if $univers.UNIVERS_IMG}
					<div class="tabbed bounded caption" id="{$univers.CODE}">
						<div class="tabs"></div>
						{foreach from=$univers.SOUS_UNIVERS item=sousunivers name=listSousUnivers}
							{if $sousunivers.ACCESSOIRES.CONTENTS}
								<div class="tab">
									<h3 class="subtitle tabtitle" {gtm  action='DisplayTab' data=$aData datasup=['eventLabel'=>{$sousunivers.LABEL}]}>
										<span>{$sousunivers.LABEL}</span>
									</h3>
									<div id="allAccessories_{$iPosition}_{$univers.CODE}_{$sousunivers.CODE}">
										<div class="cumulative row of4">
											{foreach from=$sousunivers.ACCESSOIRES.CONTENTS item=accessoire name=listAccessoires}
												<div class="col">
													<figure>
														<img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/accessory.png" data-original="{Pelican::$config.MEDIA_HTTP}{$accessoire.IMAGE}" width="250" height="166" alt="{$accessoire.LABEL}">
														<noscript><img src="{Pelican::$config.MEDIA_HTTP}{$accessoire.IMAGE}" width="250" height="166" alt="{$accessoire.LABEL}"></noscript>
													</figure>
													<div class="cart">
														<strong>
															{if $aConfiguration.ZONE_TITRE15 == 1}
																{$aConfiguration.ZONE_TITRE2}
															{/if}
															{$accessoire.PRIX}
															{if $aConfiguration.ZONE_TITRE15 == 2}
																{$aConfiguration.ZONE_TITRE2}
															{/if}
															*
														</strong><br>
														{$accessoire.LABEL}<br>
														<small class="legal">Ref. {$accessoire.REF}</small><br>
														{if $accessoire.URL_BUY}
															<a class="button" target="_blank" {if ($aData.SECOND_COLOR|count_characters) == 7}style="border-color: {$aData.SECOND_COLOR};color: {$aData.SECOND_COLOR};"{/if}
															   {gtm action='Accessories' data=$aData datasup=['eventLabel'=>$accessoire.LABEL]} href="{urlParser url=$accessoire.URL_BUY}"
															>
																{'BUY'|t}
															</a>
														{/if}
													</div>
												</div>
												{if $smarty.foreach.listAccessoires.iteration % 4 == 0}
													</div><div class="cumulative row of4">
												{/if}
											{/foreach}
										</div>
									</div>
									<input type="hidden" name="iCount" id="iCount_{$iPosition}_{$univers.CODE}_{$sousunivers.CODE}" value="{$iCount}">
									{if $sousunivers.ACCESSOIRES.COUNT > 12}
										<div class="addmore folder seeMoreAccessories" id="moreAcc_{$iPosition}_{$univers.CODE}_{$sousunivers.CODE}"><a href="#addMore" rel="{$iPosition}_{$univers.CODE}_{$sousunivers.CODE}_{$sLCDV6}">{'SEE_MORE_ACCESSORIES'|t}</a></div>
									{/if}
								</div>
							{/if}
						{/foreach}
					</div>
				{/if}
			{/foreach}
		</section>
	</div>
{/if}