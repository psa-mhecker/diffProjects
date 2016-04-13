{if isset($finitionsSelect) && isset($smarty.get.COMPARER)}
<h2 id="{$aParams.ID_HTML}" class="title strike"><span class="line"><span>{$aParams.ZONE_TITRE}</span></span></h2>
<section class="row of3">
	<input type="hidden" class="comparateurMonProjet" value="1" />
	<input type="hidden" name="tpid" value="{$aParams.TEMPLATE_PAGE_ID}" />
	<div class="caption datas">
		<div class="leftzone">
			<p><div class="zonetexte">{$aParams.ZONE_TEXTE}</div></p>
			<p>{$aParams.ZONE_TITRE2}</p>
			{$sSharer}
		</div>
		<div class="listickholder">
			<div class="listick">
				<div class="inner">
					<table>
						<thead>
							{if isset($smarty.get.lastcomp)}
							<tr>
								<td rowspan="4"></td>
								<th>
									<input type="text" {if $aVehiculeInSession.0.LCDV6 eq ''}data-ws="/_/Layout_Citroen_MonProjet_Comparateur/getFinitionsByModelAjax"{/if} data-save="{$aVehiculeInSession.0.LCDV6}" value="0" data-next="#select0b" id="select0a" name="select0a" class="fakehidden">
									<div class="selectZone">
										<div class="closer reinitComparateur0" data-values=0 data-info="showroom"></div>
										<ul class="select">
											<li><a {if $aVehiculeInSession.0.LCDV6 eq ''}class="on"{/if} href="#0" data-value="0">{'CHOISIR_UN_MODELE'|t}</a></li>
											{foreach $aCompSelect.LISTE1.MODELS as $aOneCompSelect  key=key}
												<li><a {if $aVehiculeInSession.0.LCDV6 eq $key}class="on"{/if}  data-value="{$key}" href="#0">{$aOneCompSelect}</a></li>
											{/foreach}
										</ul>
									</div>
								</th>
								<th>
									<input type="text" {if $aVehiculeInSession.1.LCDV6 eq ''}data-ws="/_/Layout_Citroen_MonProjet_Comparateur/getFinitionsByModelAjax"{/if} data-save="{$aVehiculeInSession.1.LCDV6}" value="0" data-next="#select1b" id="select1a" name="select1a" class="fakehidden">
									<div class="selectZone">
										<div class="closer reinitComparateur1" data-values=1 data-info="comparateur"></div>
										<ul class="select">
											<li><a {if $aVehiculeInSession.1.LCDV6 eq ''}class="on"{/if} href="#0" data-value="0">{'CHOISIR_UN_MODELE'|t}</a></li>
											{foreach $aCompSelect.LISTE2.MODELS as $aOneCompSelect  key=key}
												<li><a {if $aVehiculeInSession.1.LCDV6 eq $key}class="on"{/if} data-value="{$key}" href="#0">{$aOneCompSelect}</a></li>
											{/foreach}
										</ul>
									</div>
								</th>
								<th>
									<input type="text" {if $aVehiculeInSession.2.LCDV6 eq ''}data-ws="/_/Layout_Citroen_MonProjet_Comparateur/getFinitionsByModelAjax"{/if} data-save="{$aVehiculeInSession.2.LCDV6}" value="0" data-next="#select2b" id="select2a" name="select2a" class="fakehidden">
									<div class="selectZone">
										<div class="closer reinitComparateur2" data-values=2 data-info="comparateur"></div>
										<ul class="select">
											<li><a {if $aVehiculeInSession.2.LCDV6 eq ''}class="on"{/if} href="#0" data-value="0">{'CHOISIR_UN_MODELE'|t}</a></li>
											{foreach $aCompSelect.LISTE3.MODELS as $aOneCompSelect  key=key}
												<li><a {if $aVehiculeInSession.2.LCDV6 eq $key}class="on"{/if} data-value="{$key}" href="#0">{$aOneCompSelect}</a></li>
											{/foreach}
										</ul>
									</div>
								</th>
							</tr>
							<tr>
								<td>
									<input type="text" disabled="disabled" data-ws="/_/Layout_Citroen_MonProjet_Comparateur/getEngineByFinitionAjax" data-save="{$aVehiculeInSession.0.FINITION_CODE}" value="0" data-next="#select0c" id="select0b" name="select0b" class="fakehidden">
									<div class="selectZone">
										<ul class="select">
											<li><a {if $aVehiculeInSession.0.FINITION_CODE eq ''} class="on"{/if} href="#0" data-value="0">{'CHOISIR_UNE_FINITION'|t}</a></li>
											{foreach $finitionsSelect.0 as $finition key=key}
											<li><a {if $aVehiculeInSession.0.FINITION_CODE eq $key }class="on"{/if} data-value="{$key}#{$finition.LCDV6}" href="#0">{$finition.FINITION_LABEL}</a></li>
											{/foreach}
										</ul>
									</div>
								</td>
								<td>
									<input type="text" disabled="disabled" data-ws="/_/Layout_Citroen_MonProjet_Comparateur/getEngineByFinitionAjax" data-save="{$aVehiculeInSession.1.FINITION_CODE}" value="0" data-next="#select1c" id="select1b" name="select1b" class="fakehidden">
									<div class="selectZone">
										<ul class="select">
											<li><a {if $aVehiculeInSession.1.FINITION_CODE eq ''} class="on"{/if} href="#0" data-value="0">{'CHOISIR_UNE_FINITION'|t}</a></li>
											{foreach $finitionsSelect.1 as $finition  key=key}
											<li><a {if $aVehiculeInSession.1.FINITION_CODE eq $key }class="on"{/if} data-value="{$key}#{$finition.LCDV6}" href="#0">{$finition.FINITION_LABEL}</a></li>
											{/foreach}
										</ul>
									</div>
								</td>
								<td>
									<input type="text" disabled="disabled" data-ws="/_/Layout_Citroen_MonProjet_Comparateur/getEngineByFinitionAjax" data-save="{$aVehiculeInSession.2.FINITION_CODE}" value="0" data-next="#select2c" id="select2b" name="select2b" class="fakehidden">
									<div class="selectZone">
										<ul class="select">
											<li><a {if $aVehiculeInSession.2.FINITION_CODE eq ''} class="on"{/if} href="#0" data-value="0">{'CHOISIR_UNE_FINITION'|t}</a></li>
											{foreach $finitionsSelect.2 as $finition  key=key}
											<li><a {if $aVehiculeInSession.2.FINITION_CODE eq $key }class="on"{/if} data-value="{$key}#{$finition.LCDV6}" href="#0">{$finition.FINITION_LABEL}</a></li>
											{/foreach}
										</ul>
									</div>
								</td>
							</tr>
							<tr>
								<td>
									<input type="text" disabled="disabled" data-save="{$aVehiculeInSession.0.ENGINE_CODE}" value="0" id="select0c" name="select0c" class="fakehidden" data-equipement="1" />
									<div class="selectZone">
										<ul class="select" id="selectVersion0">
											<li><a {if $aVehiculeInSession.0.ENGINE_CODE eq ''} class="on"{/if} href="#0" data-value="0">{'CHOISIR_UNE_MOTORISATION'|t}</a></li>
											{foreach $engineSelect.0 as $engine  key=key}
											<li><a {if $aVehiculeInSession.0.ENGINE_CODE eq $key }class="on"{/if} data-value="{$key}#{$aVehiculeInSession.0.LCDV6}#{$aVehiculeInSession.0.FINITION_CODE}" href="#0">{$engine.ENGINE_LABEL}</a></li>
											{/foreach}
										</ul>
									</div>
								</td>
								<td>
									<input type="text" disabled="disabled" data-save="{$aVehiculeInSession.1.ENGINE_CODE}" value="0" id="select1c" name="select1c" class="fakehidden" data-equipement="1" />
									<div class="selectZone">
										<ul class="select" id="selectVersion1">
											<li><a {if $aVehiculeInSession.1.ENGINE_CODE eq ''} class="on"{/if} href="#0" data-value="0">{'CHOISIR_UNE_MOTORISATION'|t}</a></li>
											{foreach $engineSelect.1 as $engine  key=key}
											<li><a {if $aVehiculeInSession.1.ENGINE_CODE eq $key }class="on"{/if} data-value="{$key}#{$aVehiculeInSession.1.LCDV6}#{$aVehiculeInSession.1.FINITION_CODE}" href="#0">{$engine.ENGINE_LABEL}</a></li>
											{/foreach}
										</ul>
									</div>
								</td>
								<td>
									<input type="text" disabled="disabled" data-save="{$aVehiculeInSession.2.ENGINE_CODE}" value="0" id="select2c" name="select2c" class="fakehidden" data-equipement="1" />
									<div class="selectZone">
										<ul class="select" id="selectVersion2">
											<li><a {if $aVehiculeInSession.2.ENGINE_CODE eq ''} class="on"{/if} href="#0" data-value="0">{'CHOISIR_UNE_MOTORISATION'|t}</a></li>
											{foreach $engineSelect.2 as $engine  key=key}
											<li><a {if $aVehiculeInSession.2.ENGINE_CODE eq $key }class="on"{/if} data-value="{$key}#{$aVehiculeInSession.2.LCDV6}#{$aVehiculeInSession.2.FINITION_CODE}" href="#0">{$engine.ENGINE_LABEL}</a></li>
											{/foreach}
										</ul>
									</div>
								</td>
							</tr>
							{else}
							<tr>
								<td rowspan="4"></td>
								{for $model=0 to 2}
								<th>
									<input type="text"{if $model!=0} data-ws="/_/Layout_Citroen_MonProjet_Comparateur/getFinitionsByModelAjax"{/if} value="0" data-next="#select{$model}b" id="select{$model}a" name="select{$model}a" class="fakehidden">
									<div class="selectZone">
										<div class="closer reinitComparateur{$model}" data-values={$model} data-info="showroom" ></div>
										<ul class="select">
											{if $model==0}
											<li><a class="on" href="#0" data-value="{$aLcdv6Gamme.LCDV6}">{$aVehicule.VEHICULE_LABEL}</a></li>
											{else}
											<li><a class="on" href="#0" data-value="0">{'CHOISIR_UN_MODELE'|t}</a></li>
											{if $model==1}
											{foreach $aCompSelect.LISTE2.MODELS as $aOneCompSelect  key=key}
											<li><a data-value="{$key}" href="#0">{$aOneCompSelect}</a></li>
											{/foreach}
											{elseif $model==2}
											{foreach $aCompSelect.LISTE3.MODELS as $aOneCompSelect  key=key}
											<li><a data-value="{$key}" href="#0">{$aOneCompSelect}</a></li>
											{/foreach}
											{/if}
											{/if}
										</ul>
									</div>
								</th>
								{/for}
							</tr>
							<tr>
								{for $modelfinition=0 to 2}
								<td>
									<input type="text" data-ws="/_/Layout_Citroen_MonProjet_Comparateur/getEngineByFinitionAjax" value="0" data-next="#select{$modelfinition}c" id="select{$modelfinition}b" name="select{$modelfinition}b" class="fakehidden">
									<div class="selectZone">
										<ul class="select">
											<li><a class="on" href="#0" data-value="0">{'CHOISIR_UNE_FINITION'|t}</a></li>
											{foreach $finitionsSelect as $finition key=key name=listFin}
											<li><a data-value="{$key}#{$finition.LCDV6}" href="#0">{$finition.FINITION_LABEL}</a></li>
											{/foreach}
										</ul>
									</div>
								</td>
								{/for}
							</tr>
							<tr>
								<td>
									<input type="text" disabled="disabled" value="0" id="select0c" name="select0c" class="fakehidden" data-equipement="1" />
									<div class="selectZone">
										<ul class="select" id="selectVersion0">
											<li><a href="#0" data-value="0" class="on">{'CHOISIR_UNE_MOTORISATION'|t}</a></li>
										</ul>
									</div>
								</td>
								<td>
									<input type="text" disabled="disabled" value="0" id="select1c" name="select1c" class="fakehidden" data-equipement="1" />
									<div class="selectZone">
										<ul class="select" id="selectVersion1">
											<li><a href="#0" data-value="0" class="on">{'CHOISIR_UNE_MOTORISATION'|t}</a></li>
										</ul>
									</div>
								</td>
								<td>
									<input type="text" disabled="disabled" value="0" id="select2c" name="select2c" class="fakehidden" data-equipement="1" />
									<div class="selectZone">
										<ul class="select" id="selectVersion2">
											<li><a href="#0" data-value="0" class="on">{'CHOISIR_UNE_MOTORISATION'|t}</a></li>
										</ul>
									</div>
								</td>
							</tr>
							{/if}
						</thead>
					</table>
				</div>
			</div>
		</div>
		<table>
			<thead>
				<tr>
					<td></td>
					<td>
						<div class="car" id="car0">
							<figure>
								<img class="" src="{Pelican::$config.IMAGE_FRONT_HTTP}/car/compare.png" data-original="{Pelican::$config.IMAGE_FRONT_HTTP}/car/compare.png" width="239" height="134" alt="Lorem ipsum dolor" style="display: inline-block;">
								<noscript><img src="{Pelican::$config.IMAGE_FRONT_HTTP}/car/compare.png" width="239" height="134" alt="" /></noscript>
							</figure>
						</div>
						<div id="outils0"></div>
					</td>
					<td>
						<div class="car" id="car1">
							<figure>
								<img class="" src="{Pelican::$config.IMAGE_FRONT_HTTP}/car/compare.png" data-original="{Pelican::$config.IMAGE_FRONT_HTTP}/car/compare.png" width="239" height="134" alt="Lorem ipsum dolor" style="display: inline-block;">
								<noscript><img src="{Pelican::$config.IMAGE_FRONT_HTTP}/car/compare.png" width="239" height="134" alt="" /></noscript>
							</figure>
						</div>
						<div id="outils1"></div>
					</td>
					<td>
						<div class="car" id="car2">
							<figure>
								<img class="" src="{Pelican::$config.IMAGE_FRONT_HTTP}/car/compare.png" data-original="{Pelican::$config.IMAGE_FRONT_HTTP}/car/compare.png" width="239" height="134" alt="Lorem ipsum dolor" style="display: inline-block;">
								<noscript><img src="{Pelican::$config.IMAGE_FRONT_HTTP}/car/compare.png" width="239" height="134" alt="" /></noscript>
							</figure>
						</div>
						<div id="outils2"></div>
					</td>
				</tr>
			</thead>
			<tbody data-overall="true" id="caracteristiques-equipements"></tbody>
		</table>
		<div class="disclaimer" style="display: none">
			<span>{'VOUS_DEVEZ_SELECTIONNER_UNE_VERSION'|t}</span>
		</div>
	</div>
	<div class="caption legal">
		<p><a href="#0">{$aParams.ZONE_TEXTE2}</a></p>
	</div>
</section>
<div id="layerconfirmadd">
	<div class="prompt">
		<p>{'MERCI_DE_CONFIRMER_L_AJOUT_A_LA_SELECTION'|t}</p>
		<ul class="actions clean">
			<li class="blue"><a href="javascript:confirmAjoutConfigurateur()">{'CONFIRMER'|t}</a></li>
		</ul>
	</div>
</div>
<div id="layerconfirmmaj" style="display: none">
	<div class="prompt">
		<p>{'MERCI_DE_CONFIRMER_LA_MISE_A_JOUR_DE_LA_SELECTION'|t}</p>
		<ul class="actions clean">
			<li class="blue"><a href="javascript:confirmConfigurateur()">{'CONFIRMER'|t}</a></li>
		</ul>
	</div>
</div>
{/if}