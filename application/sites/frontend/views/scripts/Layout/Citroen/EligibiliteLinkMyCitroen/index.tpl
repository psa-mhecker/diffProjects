{if $aData.ZONE_WEB == 1}
	<div class="sliceNew sliceLinkMyCarDesk">
		<div id="link-my-car" class="link-my-car">
			<section class="row" id="{$aData.ID_HTML}" class="{$aData.ZONE_SKIN}">
				<div class="container-fluid" style="margin-bottom: 0px;">
					<div class="subtitle n-margin" {if ($aData.PRIMARY_COLOR|count_characters) == 7 }style="color:{$aData.PRIMARY_COLOR};"{/if}>
						{$aData.ZONE_TITRE|escape}
					</div>
					<strong class="parttitle" {if ($aData.SECOND_COLOR|count_characters) == 7 }style="color:{$aData.SECOND_COLOR};"{/if}>
						{$aData.ZONE_TEXTE}
					</strong>
					<div class="long-text">
						{$aData.ZONE_TEXTE2}
					</div>
					<form name="isEligible" method="post" id="eligibilite-form">
						<div class="of2 tb-padding-20">
							<div class="col">
								<label>{'ASK_TACTILE'|t}</label>
							</div>
							<div class="col">
								<div class="field">
									<input type="radio" name="tactileName" id="isTactile" value="1" onchange="hasATactileScreen(this.value);">
									<label for="isTactile">{'OUI'|t}</label>
									<input type="radio" name="tactileName" id="noTactile" value="0" onchange="hasATactileScreen(this.value);">
									<label for="noTactile">{'NON'|t}</label>
								</div>
							</div>
						</div>
						<div class="message_ineligibilite col hidden m-top-10" style="display: none;">
							{$aData.ZONE_TEXTE4}
						</div>
						<div class="tb-padding-20">
							<div id="eligibilite-form" class="of2 hidden saisie_VIN" style="display: block;">
								<div class="col">
									<p class="m-top-10">
										<label>{'SAISIE_VIN'|t}</label>
									</p>
								</div>
								<div class="col">
									<div class="field include">
										<input type="text" name="vin" id="vin" value="VF7" maxlength="17">

										<div class="clsselecteurteinte">
											<span class="edge-modal tooltip" onclick='promptPop("<img src={$MediaUrl}>");'>?</span>
										</div>
									</div>
									<input type="submit" id="submit" {gtm name="eligibility_link_my_citroen" data=$aData} name="submit" class="art-button" value="{'OK'|t}" onclick="checkEligibilityLinkMyCitroen($('input#vin').val(), {$aData.PAGE_ID}, {$aData.PAGE_VERSION}); return false;">
								</div>
							</div>
						</div>
						<div class="retour_ajax hidden col m-top-10" style="display: block;"></div>
						<div class="edge-notice hidden">
							{'ALERT_SIZE_VIN'|t}
						</div>
					</form>
				</div>
			</section>
		</div>
	</div>
{/if}