{if $aParams.ZONE_WEB == 1 && $bTrancheVisible}
<section id="{$aParams.ID_HTML}" class="row of6 form funding">
    {if $aParams.ZONE_TITRE}<h2 class="col span4 subtitle">{$aParams.ZONE_TITRE|escape}</h2>{/if}
    {if $aParams.ZONE_TITRE2}<h3 class="col span4 parttitle">{$aParams.ZONE_TITRE2|escape}</h3>{/if}
	
    <div class="col span4 zonetexte">{$aParams.ZONE_TEXTE}</div>
    <form id="sim-fin" class="caption" method="post" action="{$step2action}">
        <fieldset>
        <h3 id="step1" class="parttitle" data-step="1">{$aParams.ZONE_TITRE3|escape}</h3>
        <div class="row of3 fields">
            <div class="new col field">
                <input type="text" class="fakehidden" name="sim_fin_select0" id="sim_fin_select0" value="0" data-module="sim_fin" data-next='#sim_fin_select1' data-ws="/_/Layout_Citroen_SimulateurFinancement/getFinitionsByGammeAjax" />
                <div class="selectZone">
                    <ul id="model" class="select" id="test">
                        <li>
                            <a class="on" href="#0" data-value="0">{'CHOISIR_UN_MODELE'|t}</a>
                        </li>

                        {foreach from=$aVehicules item=vehicule key=key}
                        <li>
                            <a href="#0" data-value="{$key}" {gtm action='DropdownList'  data=$aParams datasup=['eventLabel'=>$vehicule,'eventCategory'=>'Content']}>{$vehicule}</a>
                        </li>
                        {/foreach}

                    </ul>
                </div>
            </div>

            <div class="new col field">
                <input type="text" class="fakehidden" name="sim_fin_select1" id="sim_fin_select1" data-next='#sim_fin_select2' data-module="sim_fin" value="0" disabled="disabled"   data-ws="/_/Layout_Citroen_SimulateurFinancement/getEnginesByFinitionAjax" />
                <div class="selectZone">
                    <ul id="finition" class="select">
                        <li><a class="on" href="#0" data-value="0">{'CHOISIR_UNE_FINITION'|t}</a></li>
                        {foreach from=$aFinitions item=finition key=key}
                        <li>
                            <a href="#0" data-value="{$finition.FINITION_CODE}#" {gtm action='DropdownList'  data=$aParams datasup=['eventLabel'=>{$finition.FINITION_LABEL},'eventCategory'=>'Content']}>{$finition.FINITION_LABEL}</a>
                        </li>
                        {/foreach}
                    </ul>
                </div>
            </div>
            <div class="new col field">
                <input type="text" class="fakehidden" name="sim_fin_select2" id="sim_fin_select2" data-module="sim_fin" value="0" disabled="disabled"  />
                <div class="selectZone">
                    <ul id="engine" class="select">
                        <li><a class="on" href="#0" data-value="0">{'CHOISIR_UNE_MOTORISATION'|t}</a></li>
                    </ul>
                </div>
            </div>
        </div>
       <div id="result-wrapper" class="result-wrapper"></div>
        <ul class="actions">
            <li><a onClick="step1Off()" href="#step2" >{'ETAPE_SUIVANTE'|t}</a></li>
            <li><a href="#" class="modify">{'MODIFIER'|t}</a></li>
        </ul>
       </fieldset>

        <fieldset>
            <h3 id="step2" class="parttitle disabled" data-step="2">{$aParams.ZONE_TITRE4|escape}</h3>
            <iframe src=""  class="caption step2" {if $aParams.ZONE_TEXTE5 neq '' }height="{$aParams.ZONE_TEXTE5}{$sIframeUnit}"{/if} {if $aParams.ZONE_TEXTE6 neq '' }width="{$aParams.ZONE_TEXTE6}{$sIframeUnit}"{/if} frameborder="0" id="sim_fin_step2_iframe" ></iframe>
      </fieldset>
      {if $sLcdv6PreRempli}<input type="hidden" id="lcdv6_prerempli" name="lcdv6_prerempli" value="{$sLcdv6PreRempli}">{/if}
    </form>
</section>
{/if}