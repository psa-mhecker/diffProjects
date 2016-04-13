<a name="FINANCER"></a>
{if $aParams.ZONE_WEB && $bTrancheVisible}
    <section id="{$aParams.ID_HTML}" class="{$aParams.ZONE_SKIN} row of3 questions clsoutilchoixfinancement">
    <div class="sep {$aData.ZONE_SKIN}"></div>
        
		{if $aParams.ZONE_TITRE}<h2 class="col span2 subtitle">{$aParams.ZONE_TITRE}</h2>{/if}
		{if $aParams.ZONE_TITRE2}<h3 class=" span2 parttitle">{$aParams.ZONE_TITRE2}</h3>{/if}
		{if $aParams.ZONE_TEXTE}
			<div class="col span2 mgchapo zonetexte"><strong>{$aParams.ZONE_TEXTE}</strong></div>
		{else}
			<div class="no-mgchapo"></div>
		{/if}
		
		
		

	{if $aQuestion|@is_array}
		<div id="choix_financement" class="row {$ofclass} field">
			<div class="caption question"><p>{$aQuestion.ARBRE_DECISIONNEL_QUESTION}</p></div>
			{if $aQuestion.responses|@is_array}
				{foreach from=$aQuestion.responses item=aResponse}
					<div class="col reponse">
						<input data="{$aResponse.json_data}" type="radio" name="reponse" id="reponse-{$aResponse.id}" value="0" {gtm name='aide_choix_financement_choix_duree' data=$aParams datasup=['value' => $aResponse.id] labelvars=['%intitule du boutton%' => $aResponse.r, '%valeurduree%' => $aResponse.id]} /><label for="reponse-{$aResponse.id}">{$aResponse.r}</label>
					</div>
				{/foreach}
			{/if}

		</div>
	{/if}

	{if $aParams.ZONE_TITRE6 && ($aParams.ZONE_TITRE5 == "ROLL" || ($aParams.ZONE_TITRE5 == "POP_IN" && $urlPopInMention != ""))}
		{if $aParams.ZONE_TITRE5 == "ROLL"}
            <div class="legal layertip" id="LegalTip">
                {if $MEDIA_PATH4 != ""}<img class="lazy noscale" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/235-100.png" data-original="{Pelican::$config.MEDIA_HTTP}{$MEDIA_PATH4}" width="580" height="247" alt="{$MEDIA_TITLE4}" />{/if}
                {if $aParams.ZONE_TEXTE4} <div class="zonetexte">{$aParams.ZONE_TEXTE4}</div> {/if}
            </div>
            <small class="legal"><a href="#LegalTip" class="texttip">{$aParams.ZONE_TITRE6}</a></small>
        {elseif $aParams.ZONE_TITRE5 == "POP_IN"}
            <small class="legal"><a href="{urlParser url={$urlPopInMention|cat:"?popin=1"}}" class="popinfos fancybox.ajax">{$aParams.ZONE_TITRE6}</a></small>
		{/if}
	{/if}
    {if $aParams.ZONE_TITRE5 == "TEXT" && $aParams.ZONE_TEXTE4 neq ""}
        
            <figure>
                {if $MEDIA_PATH4 != ""}<img class="lazy load  noscale" src="{"{Pelican::$config.MEDIA_HTTP}{$MEDIA_PATH4}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_MENTION}" width="580" height="247" alt="{$MEDIA_TITLE4}" />{/if}
            </figure>
            <small class="legal">{$aParams.ZONE_TITRE6}<br>{if $aParams.ZONE_TEXTE4} <div class="zonetexte">{$aParams.ZONE_TEXTE4}</div> {/if}</small>

    {/if}
	</section>
{/if}