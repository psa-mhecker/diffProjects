<div class="caption question"><p>{$aQuestion.ARBRE_DECISIONNEL_QUESTION}</p></div>
    {if $aQuestion.responses|@is_array}
        {foreach from=$aQuestion.responses item=aResponse}
            <div class="col reponse">
                <input data="{$aResponse.json_data}" type="radio" name="reponse" id="reponse-{$aResponse.id}" value="0"
				{if $niveauQuestion == "2"}
					{gtm name="aide_choix_financement_choix_etape_deux" data=$aParams datasup=['value' => $aResponse.id] labelvars=['%intitule du boutton%' => $aResponse.r, '%valeur etape 2%' => $aResponse.id]}
				{else}
					{gtm name="choix_etape_n" data=$aParams datasup=['value' => $aResponse.id] labelvars=['%intitule du boutton%' => $aResponse.r, '%valeur etape%' => $aResponse.id]}
				{/if}
				/><label for="reponse-{$aResponse.id}">{$aResponse.r}</label>
            </div>
        {/foreach}
    {/if}
{if $bDisplayReload}
    <div class="reset grey light">
        <a onclick="javascript:outilChoixFinancement.reload();return false;" href="#LOREM" class="button">{'RECOMMENCER'|t}</a>
    </div>
{/if}
