{if $aParams.ZONE_WEB eq 1}
<section id="{$aParams.ID_HTML}" class="row of3 showroom clscomparateur">

	<div class="sep"></div>
	{if $filterComparator && $aParams.TEMPLATE_PAGE_ID neq Pelican::$config.TEMPLATE_PAGE.COMPARATEUR}
		<a {gtm action='Comparator' data=$aParams datasup=['eventLabel' => {'COMPARER_AVEC_UN_AUTRE_MODELE_CITROEN'|t} ]} class="button right replaceToCompare" href="#addToCompare" data-value="{$aLcdv6Gamme.LCDV6}">{'COMPARER_AVEC_UN_AUTRE_MODELE_CITROEN'|t}</a>
	{/if}

	{if $aParams.ZONE_TITRE}<h3 class="parttitle" {if ($aData.SECOND_COLOR|count_characters)==7 } style="color:{$aData.SECOND_COLOR};" {/if} >{$aParams.ZONE_TITRE|escape}</h3>{/if}


	
	{if $showTypeFilter}	
		
		<form id="form_filter_comparateur" name="form_comparateur" action='#form_comparateur' method='POST'>
			
			<input type='hidden' name='TEMPLATE_PAGE_ID' id='TEMPLATE_PAGE_ID' value='{$aParams.TEMPLATE_PAGE_ID}'/>
			<input type='hidden' name='ZONE_ID' id='ZONE_ID'  value='{$aParams.ZONE_ID}'/>
			<input type='hidden' name='PAGE_ID' id='PAGE_ID'  value='{$aParams.PAGE_ID}'/>
			<input type="hidden" name="lcdv6Preset" id='lcdv6Preset' value="{$lcdv6Preset}"/>
			<input type="hidden" name="invoker" id='invoker' value="{$aParams.invoker}"/>
			
			<p>{'FILTER_COMPARATOR_LABEL'|t}</p>
			<ul class='filters' >
				<li><input name="filterComparator" id="filterComparatorVP" value="VP" {if $aParams.filterComparator == 'VP'} checked="checked"{/if} type="radio"><label for="filterComparatorVP">{'VEHICULE_LABEL_GAMMEVP'|t}</label></li>
				<li><input name="filterComparator" id="filterComparatorVU" value="VU"  {if $aParams.filterComparator == 'VU'} checked="checked"{/if} type="radio"><label for="filterComparatorVU">{'VEHICULE_LABEL_GAMMEVU'|t}</label></li>
			</ul>
			<button type='submit' class='button right submit' value='{'CONTINUER'|t}'>{'CONTINUER'|t}</button>
		</form>
	
	{/if}
	
	{if $showComparator eq 1}
  <form id="form_comparateur" name="form_comparateur" {if ($aData.PRIMARY_COLOR|count_characters)==7 }data-bg="background:{$aData.PRIMARY_COLOR}"  data-borderLeft="border-left:2px solid {$aData.PRIMARY_COLOR}"{/if}>
		<input type='hidden' name='trancheComparateur' value='1'/>
		<input type='hidden' name='tpid' value='{$aParams.TEMPLATE_PAGE_ID}'/>
		<input type='hidden' name='zid' value='{$aParams.ZONE_ID}'/>
		<input type="hidden" name="lcdv6Preset" id="lcdv6Preset" value="{$lcdv6Preset}"/>
		<input type="hidden" name="filterComparator"  value="{$aParams.filterComparator}"/>
		
		<div class="caption datas">		
		
			{if $aParams.ZONE_TEXTE || $aParams.ZONE_TITRE2}
			<div class="leftzone">
				{if $aParams.ZONE_TEXTE}<p><div class="zonetexte"><p>{$aParams.ZONE_TEXTE}</p></div></p>{/if}
				{if $aParams.ZONE_TITRE2}<p class="compareShare">{$aParams.ZONE_TITRE2|escape}</p>{/if}
				{$sSharer}
			</div>
			{/if}		
			
		
				<div class="listickholder">
					<div class="listick">
						<div class="inner">
							<table>
								<thead>
									{if $isComparateur}
										<!-- MODEL NAMES -->
										<tr>
											<td rowspan="4"></td>
											<th>
												<input data-module ="comparator" type="text" {if $aVehiculeInSession.0.LCDV6 eq ''}data-ws="/_/Layout_Citroen_Comparateur/getFinitionsByModelAjax"{/if} value="0" data-next="#select0b" id="select0a" name="select0a" class="fakehidden">
												<div class="selectZone">
													<div class="closer reinitComparateur0" data-values=0 data-info="comparateur"></div>
													<ul class="select">
														<li><a {if $aVehiculeInSession.0.LCDV6 eq ''}class="on"{/if} href="#0" data-value="0">{'CHOISIR_UN_MODELE'|t}</a></li>

														{foreach $aCompSelect.LISTE1.MODELS as $aOneCompSelect  key=key}
															<li><a {if $aVehiculeInSession.0.LCDV6 eq $key}class="on"{/if}  data-value="{$key}" 
																	{gtmjs type='toggle' action='DropdownList|'  data=$aParams datasup=['eventLabel'=>$aOneCompSelect,'eventCategory'=>'Content']}
																	href="#0">{$aOneCompSelect}</a></li>
														{/foreach}
													</ul>
													<!-- /.select -->
												</div>
												<!-- /.selectZone -->
											</th>
											<th>
												<input data-module ="comparator" type="text" {if $aVehiculeInSession.1.LCDV6 eq ''}data-ws="/_/Layout_Citroen_Comparateur/getFinitionsByModelAjax"{/if} value="0" data-next="#select1b" id="select1a" name="select1a" class="fakehidden">
												<div class="selectZone">
													<div class="closer reinitComparateur1" data-values=1 data-info="comparateur"></div>
													<ul class="select">
														<li><a {if $aVehiculeInSession.1.LCDV6 eq ''}class="on"{/if} href="#0" data-value="0">{'CHOISIR_UN_MODELE'|t}</a></li>
														{foreach $aCompSelect.LISTE2.MODELS as $aOneCompSelect  key=key}
															<li><a {if $aVehiculeInSession.1.LCDV6 eq $key}class="on"{/if} data-value="{$key}" href="#0" {gtmjs type='toggle' action='DropdownList|' data=$aParams datasup=['eventLabel'=>$aOneCompSelect,'eventCategory'=>'Content']}>{$aOneCompSelect}</a></li>
														{/foreach}
													</ul>
													<!-- /.select -->
												</div>
												<!-- /.selectZone -->
											</th>
											<th>
												<input data-module ="comparator" type="text" {if $aVehiculeInSession.2.LCDV6 eq ''}data-ws="/_/Layout_Citroen_Comparateur/getFinitionsByModelAjax"{/if} value="0" data-next="#select2b" id="select2a" name="select2a" class="fakehidden">
												<div class="selectZone">
													<div class="closer reinitComparateur2" data-values=2 data-info="comparateur"></div>
													<ul class="select">
														<li><a {if $aVehiculeInSession.2.LCDV6 eq ''}class="on"{/if} href="#0" data-value="0">{'CHOISIR_UN_MODELE'|t}</a></li>
														{foreach $aCompSelect.LISTE3.MODELS as $aOneCompSelect  key=key}
															<li><a {if $aVehiculeInSession.2.LCDV6 eq $key}class="on"{/if} data-value="{$key}" href="#0" {gtmjs type='toggle' action='DropdownList|' data=$aParams datasup=['eventLabel'=>$aOneCompSelect,'eventCategory'=>'Content']}>{$aOneCompSelect}</a></li>
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
												<input data-module ="comparator" type="text" disabled="disabled" data-ws="/_/Layout_Citroen_Comparateur/getEngineByFinitionAjax" value="0" data-next="#select0c" id="select0b" name="select0b" class="fakehidden">
												<div class="selectZone">
													<ul class="select">
														<li><a {if $aVehiculeInSession.0.FINITION_CODE eq ''} class="on"{/if} href="#0" data-value="0">{'CHOISIR_UNE_FINITION'|t}</a></li>
														{foreach $finitionsSelect.0 as $finition key=key}
															<li><a {gtmjs type='toggle' action='DropdownList::Finition|' data=$aParams datasup=['eventLabel' => {$finition.FINITION_LABEL} ]} {if $aVehiculeInSession.0.FINITION_CODE eq $key}class="on"{/if} data-value="{$key}#{$finition.LCDV6}" href="#0">{$finition.FINITION_LABEL}</a></li>
														{/foreach}
													</ul>
													<!-- /.select -->
												</div>
												<!-- /.selectZone -->
											</td>
											<td>
												<input data-module ="comparator" type="text" disabled="disabled" data-ws="/_/Layout_Citroen_Comparateur/getEngineByFinitionAjax" value="0" data-next="#select1c" id="select1b" name="select1b" class="fakehidden">
												<div class="selectZone">
													<ul class="select">
														<li><a {if $aVehiculeInSession.1.FINITION_CODE eq ''} class="on"{/if} href="#0" data-value="0">{'CHOISIR_UNE_FINITION'|t}</a></li>
														{foreach $finitionsSelect.1 as $finition  key=key}
														<li><a {gtmjs type='toggle' action='DropdownList::Finition|' data=$aParams datasup=['eventLabel' => {$finition.FINITION_LABEL} ]} {if $aVehiculeInSession.1.FINITION_CODE eq $key}class="on"{/if} data-value="{$key}#{$finition.LCDV6}" href="#0">{$finition.FINITION_LABEL}</a></li>
														{/foreach}
													</ul>
													<!-- /.select -->
												</div>
												<!-- /.selectZone -->
											</td>
											<td>
												<input data-module ="comparator" type="text" disabled="disabled" data-ws="/_/Layout_Citroen_Comparateur/getEngineByFinitionAjax" value="0" data-next="#select2c" id="select2b" name="select2b" class="fakehidden">
												<div class="selectZone">
													<ul class="select">
														<li><a {if $aVehiculeInSession.2.FINITION_CODE eq ''} class="on"{/if} href="#0" data-value="0">{'CHOISIR_UNE_FINITION'|t}</a></li>
														{foreach $finitionsSelect.2 as $finition  key=key}
														<li><a {gtmjs type='toggle' action='DropdownList::Finition|' data=$aParams datasup=['eventLabel' => {$finition.FINITION_LABEL} ]} {if $aVehiculeInSession.2.FINITION_CODE eq $key}class="on"{/if} data-value="{$key}#{$finition.LCDV6}" href="#0">{$finition.FINITION_LABEL}</a></li>
														{/foreach}
													</ul>
													<!-- /.select -->
												</div>
												<!-- /.selectZone -->
											</td>
										</tr>
									{else}
										<!-- MODEL NAMES -->
										<tr>
											<td rowspan="4"></td>
																			
											{for $model=0 to 2}
												<th>
													<input data-module="comparator" type="text" value="{$aLcdv6Gamme.LCDV6}" data-next="#select{$model}b" id="select{$model}a" name="select{$model}a" class="fakehidden">
												<div class="selectZone">
													<div class="closer reinitComparateur{$model}" data-values={$model} data-info="showroom" ></div>
													<ul class="select off">
														<li><span class="off" data-value="{$aLcdv6Gamme.LCDV6}">{$aVehicule.VEHICULE_LABEL}</span></li>
													</ul>
												<!-- /.select -->
													</div>
													<!-- /.selectZone -->
												
												</th>
											{/for}
										</tr>
										<!-- MODEL FINITIONS -->
										<tr>
											{for $modelfinition=0 to 2}
											<td>
												<input data-module="comparator" type="text" data-ws="/_/Layout_Citroen_Comparateur/getEngineByFinitionAjax" value="0" data-next="#select{$modelfinition}c" id="select{$modelfinition}b" name="select{$modelfinition}b" class="fakehidden">
												<div class="selectZone" >
													<ul class="select">
														<li><a {if $modelfinition neq 0 && $modelfinition neq 1}class="on"{/if} href="#0" data-value="0">{'CHOISIR_UNE_FINITION'|t}</a></li>
														{foreach $finitionsSelect as $finition key=key name=listFin}
														<li><a 
																{gtmjs type='toggle' action='DropdownList::Finishing|' data=$aParams datasup=['eventLabel' => {$finition.FINITION_LABEL} ]}
															{if ($modelfinition eq 0 && $smarty.foreach.listFin.iteration eq 1) || ($modelfinition eq 1 &&  $smarty.foreach.listFin.iteration eq 2)}class="on"{/if} data-value="{$key}#{$finition.LCDV6}" href="#0">{$finition.FINITION_LABEL}</a></li>
														{/foreach}
													</ul>
													<!-- /.select -->
												</div>
												<!-- /.selectZone -->
											</td>
											{/for}
										</tr>
									{/if}
									<!-- MODEL MOTOR -->
									<tr>
										<td>
											<input data-module ="comparator" type="text" disabled="disabled" value="0" id="select0c" name="select0c" class="fakehidden" data-equipement="1" >
											<div class="selectZone">
												<ul class="select" id="selectVersion0">
													<li><a data-value="0" href="#0" class="on">{'CHOISIR_UNE_MOTORISATION'|t}</a></li>
												</ul>
												<!-- /.select -->
											</div>
											<!-- /.selectZone -->
										</td>
										<td>
											<input data-module ="comparator" type="text" disabled="disabled" value="0" id="select1c" name="select1c" class="fakehidden" data-equipement="1" >
											<div class="selectZone">
												<ul class="select" id="selectVersion1">
													<li><a data-value="0" href="#0" class="on">{'CHOISIR_UNE_MOTORISATION'|t}</a></li>
												</ul>
												<!-- /.select -->
											</div>
											<!-- /.selectZone -->
										</td>
										<td>
											<input data-module ="comparator" type="text" disabled="disabled" value="0" id="select2c" name="select2c" class="fakehidden" data-equipement="1" >
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
						</div>
					</div>
				</div>
			<!-- /.listickholder -->

			<table class="table-list showdif">
				<thead >
					<!-- MODEL ACTIONS -->
					<tr>
						<td></td>
						<td>
							<div class="car" id="car0" style="margin-bottom:145px;">
								<figure>
									<img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/16-9.png" data-original="{$imageCarDefaut}" width="239" height="134" alt="{'VEHICULE'|t}" />
									<noscript><img src="{$imageCarDefaut}" width="239" height="134" alt="Lorem ipsum dolor" /></noscript>
								</figure>
								<!-- /.prices -->
							</div>
							<!-- /.car -->
							<div id="outils0" class="outils"></div>
							<!-- /.actions -->
						</td>
						<td>
							<div class="car" id="car1" style="margin-bottom:145px;">
								<figure>
									<img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/16-9.png" data-original="{$imageCarDefaut}" width="239" height="134" alt="{'VEHICULE'|t}" />
									<noscript><img src="{$imageCarDefaut}" width="239" height="134" alt="Lorem ipsum dolor" /></noscript>
								</figure>
							</div>
							<div id="outils1" class="outils"></div>
							<!-- /.car -->
						</td>
						<td>
							<div class="car" id="car2" style="margin-bottom:145px;">
								<figure>
									<img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/16-9.png" data-original="{$imageCarDefaut}" width="239" height="134" alt="{'VEHICULE'|t}"/>
									<noscript><img src="{$imageCarDefaut}" width="239" height="134" alt="Lorem ipsum dolor" /></noscript>
								</figure>
							</div>
							<div id="outils2" class="outils"></div>
							<!-- /.car -->
						</td>
					</tr>
				</thead>
				<tbody id="caracteristiques-equipements" data-overall="true" data-localise-standard="{'STANDARD'|t}" data-localise-option="{'OPTION'|t}" data-localise-nondispo="{'NON_DISPONIBLE'|t}">

				</tbody>
			</table>

			<div class="disclaimer" style="display:none;">
				<span {if ($aData.PRIMARY_COLOR|count_characters)==7 } style="color:{$aData.PRIMARY_COLOR};" {/if}>{'VOUS_DEVEZ_SELECTIONNER_UNE_VERSION'|t}</span>
			</div>
			<!-- /.disclaimer -->

		</div>
		<!-- /.datas -->
		{if $aParams.ZONE_TEXTE2 neq '' && $aParams.TEMPLATE_PAGE_ID neq Pelican::$config.TEMPLATE_PAGE.COMPARATEUR}
			<div class="caption legal zonetexte">
				{$aParams.ZONE_TEXTE2}
			</div>
		{else}
			{if $aParams.ZONE_TITRE5 eq 'ROLL'}
				<small class="legal">
				  <a class="texttip" href="#cashBuyIn">{$aParams.ZONE_TITRE6}</a>
				</small>
				<div class="legal layertip" id="cashBuyIn">
					{if $sVisuelML neq ''}<img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/16-9.png" data-original="{$sVisuelML}" width="270" height="152" style="width:270px !important;height:152px !important;min-width:270px !important;max-width:270px !important;float:left"/>{/if}{if $aParams.ZONE_TEXTE4} <div class="zonetexte">{$aParams.ZONE_TEXTE4}</div> {/if}
				</div>
			{else if $aParams.ZONE_TITRE5 eq 'TEXT'}
				 <div {if $sVisuelML neq ''}style="min-height:119px"{/if}>
					 <small class="legal">
						{$aParams.ZONE_TITRE6}<br>
						{if $sVisuelML neq ''}<img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/16-9.png" data-original="{$sVisuelML}" width="270" height="152" style="width:270px !important;height:152px !important;min-width:270px !important;max-width:270px !important;float:left"/>{/if}{if $aParams.ZONE_TEXTE4} <div class="zonetexte">{$aParams.ZONE_TEXTE4}</div> {/if}
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
		{/if}
		<!-- /.legal -->
	    </form>
	{/if}
</section>
{/if}
</div>