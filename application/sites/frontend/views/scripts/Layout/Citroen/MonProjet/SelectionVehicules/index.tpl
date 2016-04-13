	{if !$bAffichageFormulaire}
	<div id="{$aParams.ID_HTML}" class="vehiculesProjets clsmonprojselectvehicules"><a name="sv"></a>
		<h3 class="title">{'MES_VEHICULES'|t}</h3>
		<input type="text" class="fakehidden" disabled="disabled" name="listorder" value="[111111,222222,0]" />
		<div class="row of12 listeVehicules">
			{for $i=0 to 2}
				{if (is_null($iEditionId)||$iEditionId neq $i) && isset($aSelectionDetails.$i)  }
                                    {include file="{$sIncludeTplPath}/carSelectionDetails.tpl"}
                                {else}
                                    {include file="{$sIncludeTplPath}/carSelectionForm.tpl"}
                                {/if}
			{/for}
		</div>

	</div>
	{/if}
</section>