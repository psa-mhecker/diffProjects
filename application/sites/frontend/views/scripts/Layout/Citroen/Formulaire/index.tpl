{literal}

<script type="text/javascript">
    var cpw = cpw || [];

    cpw.{/literal}{$formTypeGTM}{literal} = cpw.{/literal}{$formTypeGTM}{literal} || [];
    cpw.{/literal}{$formTypeGTM}{literal}.form_gtm_data = {
        {/literal}
        {foreach from=$aDataGTM[$formTypeGTM] key=var_name item=foo2 name=gtm}
        '{$var_name}': "{$foo2}",
        {/foreach}
        {literal}

    };

</script>
    <script type="text/javascript" src="/version/vc/script/webforms_loader.js"></script>
<link href="{/literal}{$formcss}{literal}" type="text/css" rel="stylesheet">
{/literal}


{if $sCss}{$sCss}{/if}
<div class="sliceNew sliceDeployableFormDesk">
    {if $sError}
        <div class="clsformulaire showroom">
            <section class="row of6">
                <span class="subtitle c-skin" id="_150_1"
                      style="margin-bottom: 0px;{if ($aData.PRIMARY_COLOR|count_characters)==7 } color:{$aData.PRIMARY_COLOR};{/if}">{'FORMULAIRE'|t}</span>
            </section>
            <section class="{if ($aData.PRIMARY_COLOR|count_characters)!=7 }row of6{/if}">

                {if ($aData.PRIMARY_COLOR|count_characters)==7 }
                <div class="{$formName}Chapo {$aData.ZONE_SKIN}">
                    <div class="zonetexte error">
                        {else}
                        <div class="row of3 {$formName}Chapo {$aData.ZONE_SKIN}">
                            <div class="col span2 zonetexte error">
                                {/if}


                                <span style="text-align: left">{'FORMULAIRE_INDISPONIBLE_VEUILLEZ_RE_ESSAYER_PLUS_TARD'|t}
                                    <br/></span>
                                <br/>
                            </div>
                        </div>
            </section>
        </div>
    {else}
        {if $trancheEssayer}<a name="ESSAYER"></a>{else}<a name="{$formName}anchor"></a>{/if}
        {if $formulaire || $aData.ZONE_TITRE4 eq 'CHOIX'}
            {if ($aData.ZONE_ATTRIBUT neq 0 && ($aData.ZONE_ATTRIBUT eq 1 || $aData.ZONE_ATTRIBUT eq 3))}
                <div class="clsformulaire showroom">
                    <input type="hidden" name="{$formName}idPage" value="{$aData.PAGE_ID}"/>
                    <input type="hidden" name="{$formName}areaId" value="{$aData.AREA_ID}"/>
                    <input type="hidden" name="{$formName}zoneOrder" value="{$aData.ZONE_ORDER}"/>
                    <input type="hidden" name="{$formName}styleForm" value="{$aData.ZONE_SKIN}"/>
                    <input type="hidden" name="{$formName}zoneTid" value="{$aData.ZONE_TEMPLATE_ID}"/>
                    <input type="hidden" name="{$formName}form_page_pid" value="{$smarty.get.pid}"/>
                    <input type="hidden" name="{$formName}FORM_CONTEXT_CODE" value="{$formulaire.FORM_CONTEXT_CODE}"/>
                    <input type="hidden" name="{$formName}CODE_PAYS" value="{$sCodePays}"/>
                    <input type="hidden" name="{$formName}LANGUE_CODE" value="{$sLanguePays}"/>
                    <input type="hidden" name="{$formName}typeDevice" value="{$typeDevice}"/>
                    <input type="hidden" name="{$formName}formTypeGTM" value="{$formTypeGTM}"/>
                    {if $aData.ZONE_TITRE neq '' || $aData.ZONE_TEXTE neq ''}
                        <section class="{if ($aData.PRIMARY_COLOR|count_characters)!=7 }row of6{/if}">
                            {if $aData.ZONE_TITRE neq ''}<span id="{$aData.ID_HTML}"
                                                               class="subtitle {$aData.ZONE_SKIN}" {if ($aData.PRIMARY_COLOR|count_characters)==7 } style="color:{$aData.PRIMARY_COLOR};"{/if}>{$aData.ZONE_TITRE|escape}</span>{/if}
                            {if $aData.ZONE_TEXTE neq ''}

                            {if ($aData.PRIMARY_COLOR|count_characters)==7 }
                            <div class="{$formName}Chapo {$aData.ZONE_SKIN}">
                                <div class="zonetexte">
                                    {else}
                                    <div class="row of3 {$formName}Chapo {$aData.ZONE_SKIN}">
                                        <div class="col span2 zonetexte">
                                            {/if}


                                            <span style="text-align: left">{$aData.ZONE_TEXTE|nl2br|replace:"#MEDIA_HTTP#":Pelican::$config.MEDIA_HTTP}</span>
                                        </div>
                                    </div>
                                    {/if}
                        </section>
                    {/if}
                    <section
                            class="form showroom {$formName} formulaireCitroen {if $formulaire['FORM_MODE_AFF'] eq 'C'}c-skin{elseif $formulaire['FORM_MODE_AFF'] eq 'DS'}ds{/if}"
                            id="{$formName}">
                        <input type="hidden" name="formActivation" value="{$formActivation}"/>
                        <input type="hidden" name="FORM_CONTEXT_CODE" value="{$formulaire.FORM_CONTEXT_CODE}"/>
                        <input type="hidden" name="typeFormulaire"
                               value="{if $aData.ZONE_ATTRIBUT2}{$aData.ZONE_ATTRIBUT2}{else}{$aData.ZONE_TITRE3}{/if}"/>
                        <input type="hidden" name="formTypeLabel" value="{$formtype}"/>
                        <input type="hidden" name="typeDevice" value="{$typeDevice}"/>
                        <input type="hidden" name="InceCode" value="{$formulaire.FORM_INCE_CODE}"/>
                        <input type="hidden" name="lcdv6Form" value="{$lcdv6Form}"/>
                        <input type="hidden" name="deployed" value="0"/>
                        <input type="hidden" name="email" value="{$smarty.get.email}"/>
                        <input type="hidden" name="form_page_pid" value="{$smarty.get.pid}"/>
                        <input type="hidden" name="formActivationType" value="{$aData.FORM_DEPLOYE.ZONE_TITRE4}"/>
                        <input type="hidden" name="CODE_PAYS" value="{$sCodePays}"/>
                        <input type="hidden" name="LANGUE_CODE" value="{$sLanguePays}"/>

                        <input type="hidden" name="TYPE_ID" value="{$formulaire.FORM_TYPE_ID}"/>
                        <input type="hidden" name="EQUIPEMENT_CODE" value="{$formulaire.FORM_EQUIPEMENT_CODE}"/>
                        <input type="hidden" name="USER_TYPE_CODE" value="{$formulaire.FORM_USER_TYPE_CODE}"/>
                        <input type="hidden" name="formTypeGTM" value="{$formTypeGTM}"/>
                        {if $formActivation eq 'CHOIX'}
                            <script type="text/javascript">
                                delete cpw.{$formTypeGTM}.form_gtm_data.to_push;
                                dataLayer.push(cpw.{$formTypeGTM}.form_gtm_data);
                            </script>
                            <span class="parttitle" {if ($aData.PRIMARY_COLOR|count_characters)==7 } style="color:{$aData.PRIMARY_COLOR};"{else}style="color:#f0780a;"{/if}>{'ETES_VOUS_PRO_PARTICULIER'|t}</span>
                            <div class="field">
                                <input type="radio" name="isPro{$formName}" id="notPro{$formName}" value="IND"/><label
                                        for="notPro{$formName}"><span class="before"
                                                                      style="border:3px solid {if ($aData.PRIMARY_COLOR|count_characters)==7 }{$aData.PRIMARY_COLOR}{else}#f0780a{/if}"></span>{'PARTICULIER'|t}
                                    <span class="after"
                                          style="background:{if ($aData.PRIMARY_COLOR|count_characters)==7 }{$aData.PRIMARY_COLOR}{else}#f0780a{/if}"></span></label>
                                <input type="radio" name="isPro{$formName}" id="pro{$formName}" value="PRO"/><label
                                        for="pro{$formName}"><span class="before"
                                                                   style="border:3px solid {if ($aData.PRIMARY_COLOR|count_characters)==7 }{$aData.PRIMARY_COLOR}{else}#f0780a{/if}"></span>{'PRO'|t}
                                    <span class="after"
                                          style="background:{if ($aData.PRIMARY_COLOR|count_characters)==7 }{$aData.PRIMARY_COLOR}{else}#f0780a{/if}"></span></label>
                            </div>
                        {if $formulaire.ZONE_TITRE4 eq 'CHOIX' || $formActivation eq 'CHOIX'}
                            <ul class="actions">
                                <li><a href="#" class="nextStepForm{$formName}" id="nextstepformdeploy"
                                       rel="{$formName}" {if ($aData.PRIMARY_COLOR|count_characters)==7 } style="background:{$aData.PRIMARY_COLOR}" {/if}><span>{'NEXT_STEP'|t}</span></a>
                                </li>
                            </ul>
                        {/if}
                        {/if}
                    </section>
                </div>
            {elseif $aData.FORM_DEPLOYE}
                <input type="hidden" name="formTypeGTM" value="{$formTypeGTM}"/>
                <input type="hidden" name="{$formName}deployed" value="1"/>
                <input type="hidden" name="{$formName}FORM_CONTEXT_CODE" value="{$formulaire.FORM_CONTEXT_CODE}"/>
                <input type="hidden" name="{$formName}idPage" value="{$formulaire.PAGE_ID}"/>
                <input type="hidden" name="{$formName}areaId" value="{$formulaire.AREA_ID}"/>
                <input type="hidden" name="{$formName}zoneOrder" value="{$formulaire.ZONE_ORDER}"/>
                <input type="hidden" name="{$formName}zoneTid" value="{$formulaire.ZONE_TEMPLATE_ID}"/>
                <input type="hidden" name="{$formName}styleForm" value="{$aData.ZONE_SKIN}"/>
                <input type="hidden" name="{$formName}TypeGTM" value="{$formTypeGTM}"/>
                <input type="hidden" name="{$formName}form_page_pid" value="{$smarty.get.pid}"/>
                <input type="hidden" name="{$formName}CODE_PAYS" value="{$sCodePays}"/>
                <input type="hidden" name="{$formName}typeDevice" value="{$typeDevice}"/>
                <input type="hidden" name="{$formName}LANGUE_CODE" value="{$sLanguePays}"/>
                <div class="showroom">
                {if $formulaire.ZONE_TITRE neq '' || $formulaire.ZONE_TEXTE neq ''}
                    <section>
                        {$sSharer}
                        {if $formulaire.ZONE_TITRE neq ''}<span id="{$aData.ID_HTML}"
                                                                class="subtitle {$aData.ZONE_SKIN}" {if ($aData.PRIMARY_COLOR|count_characters)==7 } style="color:{$aData.PRIMARY_COLOR};"{/if}>{$formulaire.ZONE_TITRE|escape}</span>{/if}
                        {if $formulaire.ZONE_TEXTE neq ''}



                        {if ($aData.PRIMARY_COLOR|count_characters)==7 }
                        <div class="{$formName}Chapo {$aData.ZONE_SKIN}">
                            <div class="zonetexte">

                                {else}
                                <div class="row of3 {$formName}Chapo {$aData.ZONE_SKIN}">
                                    <div class="col span2 zonetexte">
                                        {/if}


                                        <span style="text-align: left">{$formulaire.ZONE_TEXTE|nl2br|replace:"#MEDIA_HTTP#":Pelican::$config.MEDIA_HTTP}</span>
                                    </div>
                                </div>
                                {/if}
                    </section>
                    </div>
                {/if}
                <section
                        class="form {$formName} formulaireCitroen {if $formulaire['FORM_MODE_AFF'] eq 'C'}c-skin{elseif $formulaire['FORM_MODE_AFF'] eq 'DS'}ds{/if}showroom "
                        id="{$formName}">
                    <input type="hidden" name="formActivation" value="{$formulaire.ZONE_TITRE4}"/>
                    <input type="hidden" name="typeFormulaire"
                           value="{if $formulaire.ZONE_ATTRIBUT2}{$formulaire.ZONE_ATTRIBUT2}{else}{$formulaire.ZONE_TITRE3}{/if}"/>
                    <input type="hidden" name="formTypeLabel" value="{$formtype}"/>
                    <input type="hidden" name="FORM_CONTEXT_CODE" value="{$formulaire.FORM_CONTEXT_CODE}"/>
                    <input type="hidden" name="typeDevice" value="{$typeDevice}"/>
                    <input type="hidden" name="InceCode" value="{$formulaire.FORM_INCE_CODE}"/>
                    <input type="hidden" name="lcdv6Form" value="{$lcdv6Form}"/>
                    <input type="hidden" name="deployed" value="1"/>
                    <input type="hidden" name="email" value="{$smarty.get.email}"/>
                    <input type="hidden" name="form_page_pid" value="{$smarty.get.pid}"/>
                    <input type="hidden" name="TYPE_ID" value="{$formulaire.FORM_TYPE_ID}"/>
                    <input type="hidden" name="EQUIPEMENT_CODE" value="{$formulaire.FORM_EQUIPEMENT_CODE}"/>
                    <input type="hidden" name="USER_TYPE_CODE" value="{$formulaire.FORM_USER_TYPE_CODE}"/>
                    <input type="hidden" name="formActivationType" value="{$aData.FORM_DEPLOYE.ZONE_TITRE4}"/>
                    <input type="hidden" name="CODE_PAYS" value="{$sCodePays}"/>
                    <input type="hidden" name="LANGUE_CODE" value="{$sLanguePays}"/>
                    <input type="hidden" name="{$formName}formTypeGTM" value="{$formTypeGTM}"/>


                    {if $formulaire.ZONE_TITRE4 eq 'CHOIX' || $formActivation eq 'CHOIX'}
                        <span class="parttitle" {if ($aData.PRIMARY_COLOR|count_characters)==7 } style="color:{$aData.PRIMARY_COLOR};"{else}style="color:#f0780a;"{/if}>{'ETES_VOUS_PRO_PARTICULIER'|t}</span>
                        <div class="field">
                            <input type="radio" name="isPro{$formName}" class="radiotypeform" id="notPro{$formName}"
                                   value="IND"/><label for="notPro{$formName}"><span class="before"
                                                                                     style="border:3px solid {if ($aData.PRIMARY_COLOR|count_characters)==7 }{$aData.PRIMARY_COLOR}{else}#f0780a{/if}"></span>{'PARTICULIER'|t}
                                <span class="after"
                                      style="background:{if ($aData.PRIMARY_COLOR|count_characters)==7 }{$aData.PRIMARY_COLOR}{else}#f0780a{/if}"></span></label>
                            <input type="radio" name="isPro{$formName}" class="radiotypeform" id="pro{$formName}"
                                   value="PRO"/><label for="pro{$formName}"><span class="before"
                                                                                  style="border:3px solid {if ($aData.PRIMARY_COLOR|count_characters)==7 }{$aData.PRIMARY_COLOR}{else}#f0780a{/if}"></span>{'PRO'|t}
                                <span class="after"
                                      style="background:{if ($aData.PRIMARY_COLOR|count_characters)==7 }{$aData.PRIMARY_COLOR}{else}#f0780a{/if}"></span></label>
                        </div>
                        <ul class="actions">
                            <li><a href="#" class="nextStepForm{$formName}" id="nextstepformdeploy"
                                   rel="{$formName}" {if ($aData.PRIMARY_COLOR|count_characters)==7 } style="background:{$aData.PRIMARY_COLOR}" {/if}><span>{'NEXT_STEP'|t}</span></a>
                            </li>
                        </ul>
                    {/if}
                </section>
                <input type="hidden" id="parcours" name="parcours" value=""/>
                <input type="hidden" name="form_page_pid" value="{$smarty.get.pid}"/>
            {/if}
        {/if}
    {/if}
    <input type="hidden" name="form_page_title" value="{$pageName}"/>
    <input type="hidden" name="vehicleModelBodystyleLabel" value="{$vehicleModelBodystyleLabel}"/>
    <input type="hidden" name="siteTypeLevel2" value="{$siteTypeLevel2}"/>
</div>

