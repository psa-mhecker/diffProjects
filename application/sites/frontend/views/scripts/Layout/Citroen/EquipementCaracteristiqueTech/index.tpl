{if $aParams.ZONE_WEB eq 1}
<section id="{$aParams.ID_HTML}" class="showroom row of3">
	{if $aParams.TEMPLATE_PAGE_ID neq Pelican::$config.TEMPLATE_PAGE.COMPARATEUR}
		<a class="button right" href="#addToCompare">{'COMPARER_AVEC_UN_AUTRE_MODELE_CITROEN'|t}</a>
	{/if}


	{if $aParams.ZONE_TITRE}<h3 class="parttitle" {if ($aData.SECOND_COLOR|count_characters)==7 } style="color:{$aData.SECOND_COLOR};" {/if} >{$aParams.ZONE_TITRE}</h3>{/if}

	<div class="caption datas">

		<div class="leftzone">
			<p><div class="zonetexte">{$aParams.ZONE_TEXTE}</div></p>
			<p>{'PARTAGE_COMPARAISON'|t}</p>
			<p>
                            {$sSharer}
                            <!--span data-displaytext="Facebook" class="st_facebook_large"></span>
				<span data-displaytext="Tweet" class="st_twitter_large"></span>
				<span data-displaytext="Google+" class="st_googleplus_large"></span>
				<span data-displaytext="ShareThis" class="st_sharethis_large"></span-->
			</p>
		</div>
		<!-- /.leftzone -->

		<div class="listickholder">

			<div class="listick"><div class="inner">

				<table>
					<thead>
						<!-- MODEL NAMES -->
						<tr>
							<td rowspan="4"></td>
							<th>
								<input type="text" {if $aVehiculeInSession.0.LCDV6 eq ''}data-ws="/_/Layout_Citroen_Comparateur/getFinitionsByModelAjax"{/if} value="0" data-next="#select0b" id="select0a" name="select0a" class="fakehidden">
								<div class="selectZone">
									<div class="closer reinitComparateur1" data-values=0></div>
									<ul class="select">
										<li><a {if $aVehiculeInSession.0.LCDV6 eq ''}class="on"{/if} href="#0" data-value="0">{'CHOISIR_UN_MODELE'|t}</a></li>

										{foreach $aCompSelect.LISTE1.MODELS as $aOneCompSelect  key=key}
											<li><a {if $aVehiculeInSession.0.LCDV6 eq $key}class="on"{/if}  data-value="{$key}" href="#0" {gtmjs type='toggle' action='DropdownList|' data=$aParams datasup=['eventLabel'=>$aOneCompSelect,'eventCategory'=>'Content']}>{$aOneCompSelect}</a></li>
										{/foreach}
									</ul>
									<!-- /.select -->
								</div>
								<!-- /.selectZone -->
							</th>
							<th>
								<input type="text" {if $aVehiculeInSession.1.LCDV6 eq ''}data-ws="/_/Layout_Citroen_Comparateur/getFinitionsByModelAjax"{/if} value="0" data-next="#select1b" id="select1a" name="select1a" class="fakehidden">
								<div class="selectZone">
									<div class="closer reinitComparateur2" data-values=1></div>
									<ul class="select">
										<li><a {if $aVehiculeInSession.1.LCDV6 eq ''}class="on"{/if} href="#0" data-value="0">{'CHOISIR_UN_MODELE'|t}</a></li>
										{foreach $aCompSelect.LISTE2.MODELS as $aOneCompSelect  key=key}
											<li><a {if $aVehiculeInSession.1.LCDV6 eq $key}class="on"{/if} data-value="{$key}" href="#0" {gtmjs type='toggle' action='DropdownList|' data=$aParams datasup=['eventLabel'=>$aOneCompSelect,'eventCategory'=>'Content']} >{$aOneCompSelect}</a></li>
										{/foreach}
									</ul>
									<!-- /.select -->
								</div>
								<!-- /.selectZone -->
							</th>
							<th>
								<input type="text" {if $aVehiculeInSession.2.LCDV6 eq ''}data-ws="/_/Layout_Citroen_Comparateur/getFinitionsByModelAjax"{/if} value="0" data-next="#select2b" id="select2a" name="select2a" class="fakehidden">
								<div class="selectZone">
									<div class="closer reinitComparateur3" data-values=2></div>
									<ul class="select">
										<li><a {if $aVehiculeInSession.2.LCDV6 eq ''}class="on"{/if} href="#0" data-value="0">{'CHOISIR_UN_MODELE'|t}</a></li>
										{foreach $aCompSelect.LISTE3.MODELS as $aOneCompSelect  key=key}
											<li><a {if $aVehiculeInSession.2.LCDV6 eq $key}class="on"{/if} data-value="{$key}" href="#0" {gtmjs type='toggle' action='DropdownList|' data=$aParams datasup=['eventLabel'=>$aOneCompSelect,'eventCategory'=>'Content']} >{$aOneCompSelect}</a></li>
										{/foreach}
									</ul>
									<!-- /.select -->
								</div>
								<!-- /.selectZone -->
							</th>
						</tr>
						<!-- MODEL FINITIONS -->
						<tr>
							<td>
								<input type="text" disabled="disabled" data-ws="/_/Layout_Citroen_Comparateur/getEngineByFinitionAjax" value="0" data-next="#select0c" id="select0b" name="select0b" class="fakehidden">
								<div class="selectZone">
									<ul class="select">
										<li><a {if $aVehiculeInSession.0.FINITION_CODE eq ''} class="on"{/if} href="#0" data-value="0">{'CHOISIR_UNE_FINITION'|t}</a></li>
										{foreach $finitionsSelect.0 as $finition key=key}
										<li><a  {gtmjs type='toggle' action='DropdownList::Finition|' data=$aParams datasup=['eventLabel' => {$finition.FINITION_LABEL} ]}  {if $aVehiculeInSession.0.FINITION_CODE eq $key }class="on"{/if} data-value="{$key}#{$finition.LCDV6}" href="#0">{$finition.FINITION_LABEL}</a></li>
										{/foreach}
									</ul>
									<!-- /.select -->
								</div>
								<!-- /.selectZone -->
							</td>
							<td>
								<input type="text" disabled="disabled" data-ws="/_/Layout_Citroen_Comparateur/getEngineByFinitionAjax" value="0" data-next="#select1c" id="select1b" name="select1b" class="fakehidden">
								<div class="selectZone">
									<ul class="select">
										<li><a {if $aVehiculeInSession.1.FINITION_CODE eq ''} class="on"{/if} href="#0" data-value="0">{'CHOISIR_UNE_FINITION'|t}</a></li>
										{foreach $finitionsSelect.1 as $finition  key=key}
										<li><a  {gtmjs type='toggle' action='DropdownList::Finition|' data=$aParams datasup=['eventLabel' => {$finition.FINITION_LABEL} ]}  {if $aVehiculeInSession.1.FINITION_CODE eq $key }class="on"{/if} data-value="{$key}#{$finition.LCDV6}" href="#0">{$finition.FINITION_LABEL}</a></li>
										{/foreach}
									</ul>
									<!-- /.select -->
								</div>
								<!-- /.selectZone -->
							</td>
							<td>
								<input type="text" disabled="disabled" data-ws="/_/Layout_Citroen_Comparateur/getEngineByFinitionAjax" value="0" data-next="#select2c" id="select2b" name="select2b" class="fakehidden">
								<div class="selectZone">
									<ul class="select">
										<li><a {if $aVehiculeInSession.2.FINITION_CODE eq ''} class="on"{/if} href="#0" data-value="0">{'CHOISIR_UNE_FINITION'|t}</a></li>
										{foreach $finitionsSelect.2 as $finition  key=key}
										<li><a {gtmjs type='toggle' action='DropdownList::Finition|' data=$aParams datasup=['eventLabel' => {$finition.FINITION_LABEL} ]}  {if $aVehiculeInSession.2.FINITION_CODE eq $key }class="on"{/if} data-value="{$key}#{$finition.LCDV6}" href="#0">{$finition.FINITION_LABEL}</a></li>
										{/foreach}
									</ul>
									<!-- /.select -->
								</div>
								<!-- /.selectZone -->
							</td>
						</tr>
						<!-- MODEL MOTOR -->
						<tr>
							<td>
								<input type="text" disabled="disabled" value="0" id="select0c" name="select0c" class="fakehidden" >
								<div class="selectZone">
									<ul class="select" id="selectVersion0">
										<li><a data-value="0" href="#0" class="on">{'CHOISIR_UNE_MOTORISATION'|t}</a></li>
									</ul>
									<!-- /.select -->
								</div>
								<!-- /.selectZone -->
							</td>
							<td>
								<input type="text" disabled="disabled" value="0" id="select1c" name="select1c" class="fakehidden" >
								<div class="selectZone">
									<ul class="select" id="selectVersion1">
										<li><a data-value="0" href="#0" class="on">{'CHOISIR_UNE_MOTORISATION'|t}</a></li>
									</ul>
									<!-- /.select -->
								</div>
								<!-- /.selectZone -->
							</td>
							<td>
								<input type="text" disabled="disabled" value="0" id="select2c" name="select2c" class="fakehidden" >
								<div class="selectZone">
									<ul class="select" id="selectVersion2">
										<li><a data-value="0" href="#0" class="on">{'CHOISIR_UNE_MOTORISATION'|t}</a></li>
									</ul>
									<!-- /.select -->
								</div>
								<!-- /.selectZone -->
							</td>
						</tr>
					</thead>
				</table>

			</div></div>

		</div>
		<!-- /.listickholder -->

		<table>
			<thead>
				<!-- MODEL ACTIONS -->
				<tr>
					<td></td>
					<td>
						<div class="car" id="car0">
							<figure>
								<img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/16-9.png" data-original="{$imageCarDefaut}" width="239" height="134" alt="{'VEHICULE'|t}" />
								<noscript><img src="{$imageCarDefaut}" width="239" height="134" alt="Lorem ipsum dolor" /></noscript>
							</figure>
							<!-- /.prices -->
						</div>
						<!-- /.car -->
						<div id="outils0"></div>
						<!-- /.actions -->
					</td>
					<td>
						<div class="car" id="car1">
							<figure>
								<img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/16-9.png" data-original="{$imageCarDefaut}" width="239" height="134" alt="{'VEHICULE'|t}" />
								<noscript><img src="{$imageCarDefaut}" width="239" height="134" alt="Lorem ipsum dolor" /></noscript>
							</figure>
						</div>
						<div id="outils1"></div>
						<!-- /.car -->
					</td>
					<td>
						<div class="car" id="car2">
							<figure>
								<img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/16-9.png" data-original="{$imageCarDefaut}" width="239" height="134" alt="{'VEHICULE'|t}"/>
								<noscript><img src="{$imageCarDefaut}" width="239" height="134" alt="Lorem ipsum dolor" /></noscript>
							</figure>
						</div>
						<div id="outils2"></div>
						<!-- /.car -->
					</td>
				</tr>
			</thead>
			<tbody id="caracteristiques-equipements" data-overall="true">

			</tbody>
		</table>

		<div class="disclaimer" style="display:none;">
			<span>{'VOUS_DEVEZ_SELECTIONNER_UNE_VERSION'|t}</span>
		</div>
		<!-- /.disclaimer -->

	</div>
	<!-- /.datas -->

	{if $aParams.ZONE_TITRE5 eq 'ROLL'}
		<small class="legal">
		  <a class="texttip" href="#cashBuyIn">{$aParams.ZONE_TITRE6}</a>
		</small>
		<div class="legal layertip" id="cashBuyIn">
			{if $sVisuelML neq ''}<img class="lazy" data-original="{$sVisuelML}" width="270" height="152" style="width:270px !important;height:152px !important;min-width:270px !important;max-width:270px !important;float:left"/>{/if}{if $aParams.ZONE_TEXTE4} <div class="zonetexte">{$aParams.ZONE_TEXTE4}</div> {/if}
		</div>
	{else if $aParams.ZONE_TITRE5 eq 'TEXT'}
		 <div {if $sVisuelML neq ''}style="min-height:119px"{/if}>
			 <small class="legal">
				{$aParams.ZONE_TITRE6}<br>
				{if $sVisuelML neq ''}<img class="lazy" data-original="{$sVisuelML}" width="270" height="152" style="width:270px !important;height:152px !important;min-width:270px !important;max-width:270px !important;float:left"/>{/if}{if $aParams.ZONE_TEXTE4} <div class="zonetexte">{$aParams.ZONE_TEXTE4}</div> {/if}
			 </small>
		 </div>
	{else if $aParams.ZONE_TITRE5 eq 'POP_IN' && $aMentionsLegales.PAGE_CLEAR_URL neq ''}
		{if $aParams.ZONE_TITRE6 neq ''}<small class="legal"><a class="simplepop" href="#creditBuyPopIn">{$aParams.ZONE_TITRE6}</a></small>{/if}
		<script type="text/template" id="creditBuyPopIn">
			<div style="min-width:450px" >
				<iframe src="{$aMentionsLegales.PAGE_CLEAR_URL}?popin=1" width="450px"></iframe>
			</div>
		</script>
	{/if}
	<!-- /.legal -->

</section>
{/if}