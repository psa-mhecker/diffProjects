    
<div class="closer {if ($aData.PRIMARY_COLOR|count_characters)==7 }showroom{/if}"></div> 
<a name="{$formName}anchor"></a>
<input type="hidden" name="{$formName}deployed" value="1"/>
<input type="hidden" name="{$formName}idPage" value="{$aData.PAGE_ID}"/>
<input type="hidden" name="{$formName}areaId" value="{$aData.AREA_ID}"/>
<input type="hidden" name="{$formName}zoneOrder" value="{$aData.ZONE_ORDER}"/>
<input type="hidden" name="{$formName}zoneTid" value="{$aData.ZONE_TEMPLATE_ID}"/>
<input type="hidden" name="{$formName}styleForm" value="{$aData.ZONE_SKIN}"/>
{if $aData.ZONE_TITRE neq '' || $aData.ZONE_TEXTE neq ''}
    <section class="row of6{if ($aData.PRIMARY_COLOR|count_characters)==7 } showroom{/if}">
        {if $aData.ZONE_TITRE neq ''}<h2 id="{$aData.ID_HTML}" class="{if $aData.FORM_MODE_AFF eq 'C'}c-skin{elseif $aData.FORM_MODE_AFF eq 'DS'}ds{/if} subtitle {$aData.ZONE_SKIN}">{$aData.ZONE_TITRE}</h2>{/if}
        {if $aData.ZONE_TEXTE neq ''}
            <div class="{if $aData.FORM_MODE_AFF eq 'C'}c-skin{elseif $aData.FORM_MODE_AFF eq 'DS'}ds{/if}row of3 {$formName}Chapo {$aData.ZONE_SKIN}">
                <div class="col span2 zonetexte">
                    <span style="text-align: left">{$aData.ZONE_TEXTE|nl2br|replace:"#MEDIA_HTTP#":Pelican::$config.MEDIA_HTTP}</span>
                </div>
            </div>
        {/if}
    </section> 
{/if}
<section class="form {$formName} formulaireCitroen {if $aData.FORM_MODE_AFF eq 'C'}c-skin{elseif $aData.FORM_MODE_AFF eq 'DS'}ds{/if}{if ($aData.PRIMARY_COLOR|count_characters)==7 } showroom{/if}" id="{$formName}">
    <input type="hidden" name="formActivation" value="{$formActivation}"/>
    <input type="hidden" name="typeFormulaire" value="{if $aData.ZONE_ATTRIBUT2}{$aData.ZONE_ATTRIBUT2}{else}{$aData.ZONE_TITRE3}{/if}"/>
    <input type="hidden" name="FORM_CONTEXT_CODE" value="{$formulaire.FORM_CONTEXT_CODE}"/>
    <input type="hidden" name="formTypeLabel" value="{$formtype}"/>
    <input type="hidden" name="typeDevice" value="{$typeDevice}"/>
    <input type="hidden" name="InceCode" value="{$formulaire.FORM_INCE_CODE}"/>
    <input type="hidden" name="gdoMarketingCode" value="{$formulaire.FORM_GDO_MARKETING_CODE}"/>
    <input type="hidden" name="lcdv6Form" value="{$lcdv6Form}"/>
    <input type="hidden" name="deployed" value="1"/>
    <input type="hidden" name="email" value="{$email}"/>
    <input type="hidden" name="tranche_vehicule" value="{$formulaire.TRANCHE_VEHICULE}"/>

    <input type="hidden" name="TYPE_ID" value="{$formulaire.FORM_TYPE_ID}"/>
    <input type="hidden" name="EQUIPEMENT_CODE" value="{$formulaire.FORM_EQUIPEMENT_CODE}"/>
    <input type="hidden" name="USER_TYPE_CODE" value="{$formulaire.FORM_USER_TYPE_CODE}"/>
    {if $formActivation eq 'CHOIX'}
        <h2 class="parttitle"{if ($aData.PRIMARY_COLOR|count_characters)==7 } style="color:{$aData.PRIMARY_COLOR};{/if}">{'ETES_VOUS_PRO_PARTICULIER'|t}</h2>
        <div class="field">
            <input type="radio" name="isPro{$formName}" id="notPro{$formName}" value="IND" /><label for="notPro{$formName}">{'PARTICULIER'|t}</label>
            <input type="radio" name="isPro{$formName}" id="pro{$formName}" value="PRO" /><label for="pro{$formName}">{'PRO'|t}</label>
        </div>
        <ul class="actions"> 
            <li><a href="#" class="nextStepForm{$formName}" rel="{$formName}"><span>{'NEXT_STEP'|t}</span></a></li>
        </ul>
    {/if}
</section>
<span class="popClose"><span>{'FERMER'|t}</span></span>