{if $aData.ZONE_WEB == 1 && $sFolderImg neq ''}
	{if $bIsGeneral}
	{literal}<link rel="stylesheet" type="text/css" href="{/literal}{Pelican::$config.DESIGN_HTTP}{literal}/animation/{/literal}{$sFolderImg}/{$sIsWebMobile}{literal}/css/style_mmd1404.css" media="screen" />{/literal}
	{elseif $bIsUk}
	{literal}<link rel="stylesheet" type="text/css" href="{/literal}{Pelican::$config.DESIGN_HTTP}{literal}/animation/{/literal}{$sFolderImg}/{$sIsWebMobile}{literal}/GB/css/mmd_style_uk.css" media="screen" />{/literal}
	{elseif $bIsPT}
	{literal}<link rel="stylesheet" type="text/css" href="{/literal}{Pelican::$config.DESIGN_HTTP}{literal}/animation/{/literal}{$sFolderImg}/{$sIsWebMobile}{literal}/PT/css/mmd_style_pt.css" media="screen" />{/literal}
	{/if}
	{if $sHtml5Display neq ''}
		<div class="sliceNew sliceAnimStrongPointsDesk">
			<div id="{$aData.ID_HTML}" class="{$aData.ZONE_SKIN} showroom">
				{if $aData.ZONE_TITRE neq '' || $aData.ZONE_TITRE2 neq '' }
					{if $aData.ZONE_TITRE neq ''}<h2 class="subtitle-1"{if ($aData.PRIMARY_COLOR|count_characters)==7 } style="color:{$aData.PRIMARY_COLOR};" {/if}>{$aData.ZONE_TITRE|escape}</h2>{/if}
					{if $aData.ZONE_TITRE2 neq ''}<h3 class="parttitle-1" {if ($aData.SECOND_COLOR|count_characters)==7 } style="color:{$aData.SECOND_COLOR};" {/if}>{$aData.ZONE_TITRE2|escape}</h3>{/if}
				{/if}
				{$sHtml5Display}
			</div>
		</div>
	{/if}
{/if}