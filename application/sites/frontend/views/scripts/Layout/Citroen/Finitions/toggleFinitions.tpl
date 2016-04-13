<!-- aData.PRIMARY_COLOR  = {$aData.PRIMARY_COLOR} -->
{if $aEquipements|@sizeof > 0}

	<section class="{$aData.skin}" id="sticky">
		<h3 class="parttitle" {if ($aData.PRIMARY_COLOR|count_characters)==7 } style="color:{$aData.PRIMARY_COLOR};" {/if}>{'EQUIPEMENTS'|t}</h3>
		 <ul class="legend">
                   <li>
                    <p class="text">{'STANDARD'|t}</p>
                </li>
                <li>
                    <p class="text">{'OPTION'|t}</p>
                </li>
                <li>
                    <p class="text">{'INDISPONIBLE'|t}</p>
                </li>
            </ul>
		<div class="accordion accordion-lvl1 faq" data-init="0">
			{foreach from=$aEquipements item=category name=listCategory}
				<h4 class="title-tab color2" {$engine.ENGINE_LABEL} {gtmjs action='Accordion|' data=$aData datasup=['eventLabel' => {$category.LABEL} ]}  ><span>{$category.LABEL}</span></h4> 
				<div class="item">
					<table>
						<tbody>
							{foreach from=$category.EQUIPEMENTS item=equipement name=listEquipements}
								{if $equipement.DISPONIBILITY neq 'None'}
									<tr>
										<th scope="row">{$equipement.LABEL}</th>
										 <td valign="center" align="center" > <img src="{$equipement.DISPONIBILITY}"></td>
									</tr>
								{/if}
							{/foreach}
						</tbody>
					</table>
				</div>
			{/foreach}
		</div>
	</section>
{/if}
{if $aEngineList|@sizeof > 0}
	<section class="{$aData.skin}">
		<h3 class="parttitle" {if ($aData.PRIMARY_COLOR|count_characters)==7 } style="color:{$aData.PRIMARY_COLOR};" {/if}>{'CARACTERISTIQUES'|t}</h3> 
		<h4 class="title-lvl2" {if ($aData.SECOND_COLOR|count_characters)==7 } style="color:{$aData.SECOND_COLOR};" {/if}>{'SELECT_CARACTERISTIQUES'|t}</h4>
		<div class="box-lvl2">
			<form class="form-lvl2" action="" method="post"> 
				<select class="select" name="equipe">
					<option>{'CHOOSE_VERSIONS'|t}</option>
					{foreach from=$aEngineList item=engine name=listEngine}
						<option {gtm action='DropdownList' data=$aData datasup=['eventLabel' => {$engine.ENGINE_LABEL} ]}  value="{$engine.ENGINE_CODE}_{$aData.finition}_{$aData.lcvd6}_{$aData.gamme}" {if $engineCode eq $engine.ENGINE_CODE}selected{/if}>{$engine.ENGINE_LABEL}</option>
					{/foreach}
				</select>
			</form>
			<div class="accordion accordion-lvl1 faq" id="caracteristiques" data-init="0">
				{foreach from=$aCaracteristiques item=category name=listCategory}
					<h4 class="title-tab" {gtmjs action='Accordion|' data=$aData datasup=['eventLabel' => {$category.LABEL} ]} ><span>{$category.LABEL}</span></h4> 
					<div class="item">
						<table>
							<tbody>
								{foreach from=$category.CARACTERISTIQUES item=caracteristique name=listCaracteristique}
									{if $caracteristique.VALUE neq 'None' && $caracteristique.VALUE neq '-'}
										<tr>
											<th scope="row">{$caracteristique.NAME}</th>
											<td>{$caracteristique.VALUE}</td>
										</tr>
									{/if}
								{/foreach}
							</tbody>
						</table>
					</div>
				{/foreach}
			</div>
		</div>
	</section>
	
{/if}
</div>
