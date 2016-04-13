<div class="sliceNew">
<h1 class="subtitle " {if ($aData.PRIMARY_COLOR|count_characters)==7 } style="color:{$aData.PRIMARY_COLOR};" {/if}>{$aData.PAGE_TITLE}</h1>
</div>
{if $aData.ZONE_WEB == 1}
{if $aVehicules && $aVehicules|@count gt 0}
<section id="{$aData.ID_HTML}" class="clsvehicleselector row of2">
	<div class="new col">
		<p >{$titre|escape}</p>
		<div class="caption row of2">
			<div class="col selectZone">
				<ul class="select">
                    <li><a class="on" href="#0">{"CHOISISSEZ_SELECTION_DETAIL"|t}</a></li>
                    {foreach from=$aVehicules key=k item=vehicule}
                        {if $vehicule}<li ><a href="{urlParser url=$vehicule.url}#sticky" {if $vehicule.mode_ouverture==2}target='_blank'{/if} {gtm action='DropdownList' data=$aData datasup=['eventCategory'=>'Content','eventLabel'=>{$vehicule.label}]}>{$vehicule.label} </a></li>{/if}
                    {/foreach}
				</ul>
			</div>
		</div>
	</div>
</section>
{/if}
{/if}