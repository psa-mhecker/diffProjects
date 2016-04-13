<div class="col span4">
    <form id="car_selection_{$i}">
		<input type="hidden" class="vid" value="0" />
		<div class="content" data-sync="carCont{$aData.ORDER}">
			<span class="parttitle"><span class="sortLabel">{'SELECTION'|t} 0{$i+1}</span></span>
			<figure data-sync="cars{$aData.ORDER}" id="sv_car{$i}">
				<img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/16-9.png" data-original="{Pelican::$config.IMAGE_FRONT_HTTP}/picto/monprojet-selection0{$i+1}.png" width="201" height="110" alt="" />
				<noscript>
					<img src="{Pelican::$config.IMAGE_FRONT_HTTP}/picto/monprojet-selection0{$i+1}.png" width="201" height="110" alt="" />
				</noscript>
			</figure>
			<input type="text" class="fakehidden" name="sv_select_{$i}_a" id="sv_select_{$i}_a" data-next="#sv_select_{$i}_b" data-save="{if $aSelection.$i.lcdv6_code && $iEditionId == $i}{$aSelection.$i.lcdv6_code}{/if}" value="0" data-ws="/_/Layout_Citroen_MonProjet_SelectionVehicules/getFinitionsByGammeAjax" data-module="select_vehicule" />
			<div class="selectZone">
				<ul class="select">
					<li><a class="{if !($aSelectionDetails.$i.LCDV6 && $iEditionId == $i)} on{/if}" href="#0" data-value="0">{'CHOISIR_UN_MODELE'|t}</a></li>
					{foreach $aVehicules  key=k item=v}
					<li><a href="#0" class="selection_vehicules{if $k == $aSelectionDetails.$i.LCDV6 && $iEditionId == $i} on{/if}" data-value="{$k}" {gtm name='monprojet_mesvehicules_choix_modele_vehicule_'|cat:($i+1)  data=$aParams datasup=['value'=>$v|cat:' '|cat:$k] labelvars=['%nom du vehicule%'=>$v, '%code lcdv6%'=>$k, '%nom du boutton%'=>$v]}>{$v}</a></li>
					{/foreach}
				</ul>
			</div>
			<input type="text" class="fakehidden" name="sv_select_{$i}_b" id="sv_select_{$i}_b" data-next="#sv_select_{$i}_c" data-save="{if $aSelection.$i.finition_code && $iEditionId == $i}{$aSelection.$i.finition_code}{/if}" value="0" disabled="disabled" data-ws="/_/Layout_Citroen_MonProjet_SelectionVehicules/getEnginesByFinitionAjax" data-module="select_vehicule" />
			<div class="selectZone">
				<ul class="select">
					<li><a class="on" href="#0" data-value="0">{'CHOISIR_UNE_FINITION'|t}</a></li>
				</ul>
			</div>
			<input type="text" class="fakehidden" name="sv_select_{$i}_c" id="sv_select_{$i}_c" data-save="{if $aSelection.$i.version_code && $iEditionId == $i}{$aSelection.$i.version_code}{/if}" value="0" disabled="disabled" data-module="select_vehicule" />
			<div class="selectZone">
				<ul class="select">
					<li><a class="on" href="#0" data-value="0">{'CHOISIR_UNE_MOTORISATION'|t}</a></li>
				</ul>
			</div>
			<a id ="add_to_selection_{$i}" href="#LOREM" onclick="javascript:selectionVehicule.save(this); return false;" class="button" data-value="{$i}" {gtm name='clic_sur_selectionner' data=$aParams labelvars=['%intitule du boutton%'=>'SELECT'|t]}>{'SELECT'|t}</a>
		</div>
	</form>
</div>