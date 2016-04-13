{if $aEquipements|@sizeof > 0}
	<tr>
                      <td class="spacer" colspan="4"></td>
                  </tr>
	<tr>
                      <td class="table-tools" colspan="4">
                          <input type="checkbox" name="dif" class="showdifcheck" id="dif">
                          <label for="dif">
                              <div class="squarelabel"></div>
                              {'Montrer_les_differences'|t}
                          </label>
                         <ul class="legend">
								<li><p>{'STANDARD'|t}</p></li>
                                <li><p>{'OPTION'|t}</p></li>
                                <li><p>{'INDISPONIBLE'|t}</p></li>
						</ul>
                          <a data-gtm-init="1" class="overall closeall activeRoll" href="javascript:void(0)" {gtm action="CloseAll::Equipment" data=$aData datasup=['eventLabel' =>{'CLOSE_ALL'|t}]}>
                              <div class="circle"></div>
                              <span>{'CLOSE_ALL'|t}</span>
                          </a>
                          <a data-gtm-init="1" class="overall openall activeRoll" href="javascript:void(0)" {gtm action="OpenAll::Equipment" data=$aData datasup=['eventLabel' =>{'OPEN_ALL'|t}]}>
                              <div class="circle"></div>
                              <span>{'OPEN_ALL'|t}</span>
                          </a>
                      </td>
                  </tr>
	{foreach from=$aEquipements item=category name=listCategory key=itemCate}
		<tr>
			<td colspan="4"><a href="#folder_equipement_{$smarty.foreach.listCategory.iteration}" class="folder ECFolder" {gtmjs  action='Open::Equipment|' data=$aData type='expandBar' datasup=['eventLabel' =>{$itemCate}]}><div class="square"></div>{$itemCate}</a></td>
		</tr>
		{foreach from=$category item=souscategory name=listSousCategory key=itemSousCate}
			<tr data-folder="folder_equipement_{$smarty.foreach.listCategory.iteration}" class='{cycle values="odd,even"}' style="display: table-row;">
				<th>{$itemSousCate}</th>
				{foreach from=$souscategory item=equipement name=listEquipement key=itemEquipement}
					{* Mémorisation de la valeur de la première colonne, pour ne mettre la classe dif que sur les cases différentes *}
					{if $smarty.foreach.listEquipement.first}
						{assign var="firstVal" value=$equipement}
					{/if}
					<td  valign="center" align="center" style="text-align:center;" class="{if !$smarty.foreach.listEquipement.first && $firstVal != $equipement}dif{/if}">

						<img src="{$equipement|replace:'-ds':''}">
					</td>
				{/foreach}
			</tr>
		{/foreach}
	{/foreach}
	{if $aCategory || $aCaracteristiques}
		<tr>
			<td colspan="4" class="head" {if ($aData.PRIMARY_COLOR|count_characters)==7 } style="color:{$aData.PRIMARY_COLOR};" {/if}>{'CARACTERISTIQUES_TECHNIQUES'|t}</td>
		</tr>
		{if $aCategory}
			{foreach from=$aCategory item=category name=listCategory key=itemCate}
				<tr>
					<td colspan="4"><a href="#folder_caracteristique_{$smarty.foreach.listCategory.iteration}" class="folder {$classCaracteristiques} ECFolder" {gtmjs action='Accordion|' data=$aData type='expandBar' datasup=['eventLabel' =>{$category.CATEGORY_NAME}]}><div class="square"></div>{$category.CATEGORY_NAME}</a></td>
				</tr>
			{/foreach}
		{else}
			{foreach from=$aCaracteristiques item=category name=listCategory key=itemCate}
				<tr>
					<td colspan="4"><a href="#folder_caracteristique_{$smarty.foreach.listCategory.iteration}" class="folder {$classCaracteristiques} ECFolder" {gtmjs action='Accordion|' data=$aData type='expandBar' datasup=['eventLabel' =>{$itemCate}]}><div class="square"></div>{$itemCate}</a></td>
				</tr>
				{foreach from=$category item=souscategory name=listSousCategory key=itemSousCate}
					<tr data-folder="folder_caracteristique_{$smarty.foreach.listCategory.iteration}" class='{cycle values="odd,even"}'>
						<th>{$itemSousCate}</th>
						{foreach from=$souscategory item=caracteristique name=listCaracteristique key=itemCaracteristique}
							{* Mémorisation de la valeur de la première colonne, pour ne mettre la classe dif que sur les cases différentes *}
							{if $smarty.foreach.listCaracteristique.first}
								{assign var="firstVal" value=$caracteristique}
							{/if}
							<td class="{if !$smarty.foreach.listCaracteristique.first && $firstVal != $caracteristique}dif{/if}">{$caracteristique}</td>
						{/foreach}
					</tr>
				{/foreach}
			{/foreach}
		{/if}
	{/if}
{/if}

