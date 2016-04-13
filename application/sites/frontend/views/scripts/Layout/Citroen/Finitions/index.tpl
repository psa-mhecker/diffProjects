{if $aData.ZONE_WEB eq 1}

	{if $aFinitions|@sizeof > 0}
		<div class="finitionsReviewDesktop  sliceNew">
		<section id="{$aData.ID_HTML}" class="row showroom clsfinitions">
			<div class="sep"></div>

            {if $aData.ZONE_TITRE neq ''}<h3 class="parttitle" {if ($aData.SECOND_COLOR|count_characters)==7 } style="color:{$aData.SECOND_COLOR};" {/if} >{$aData.ZONE_TITRE}</h3>{/if}

			<div class="slider sepbot one row of6" {gtmjs type='slider'  data=$aData  action='Click'  } >

				 <div class="row of3" >
					{foreach from=$aFinitions item=finition name=listFinition}
						 <div class="col columns" >
							<figure>
								<img class="lazy" src="http://media.citroen.fr/design/frontend/images/lazy/16-9.png" data-original="{$finition.IMAGE}" width="373" height="209" alt="{$finition.FINITION_LABEL}" style="display: inline-block;"/>
								<noscript><img src="{$finition.IMAGE}" width="373" height="209" alt="{$finition.FINITION_LABEL}" /></noscript>
							</figure>
							<h3 class="subtitle" {if ($aData.PRIMARY_COLOR|count_characters)==7 } style="color:{$aData.PRIMARY_COLOR};" {/if}>{$finition.FINITION_LABEL}</h3>
							<div class="prices">
								{'A_PARTIR_DE'|t} <em {if ($aData.PRIMARY_COLOR|count_characters)==7 } style="color:{$aData.PRIMARY_COLOR};" {/if} ><strong>{$finition.PRIMARY_DISPLAY_PRICE} {'TTC'|t}</strong> </em>
								{if  $aVehicule.VEHICULE.VEHICULE_CASH_PRICE_LEGAL_MENTION neq ''}
									<a class="tooltip pop activeRoll3" href="#cashPrixComptant{$aData.ORDER}Pop{$smarty.foreach.listFinition.iteration}"
									{gtmjs type='toggle'  action='Display::ToolTip|' eventGTM='over'   data=$aParams  datasup=['value'=>'cashPrixComptant','eventLabel'=>$aVehicule.VEHICULE.VEHICULE_LABEL]} >?</a>
								{/if}
								<br />
								{if $hasCreditPrice == true && $finition.CREDIT}
									{'OU_A_PARTIR_DE'|t}
									<em {if ($aData.PRIMARY_COLOR|count_characters)==7 } style="color:{$aData.PRIMARY_COLOR};" {/if} >
										<strong >{$finition.CREDIT.PRIX}</strong>
										{if  $finition.MENTIONS_LEGALES.HTML neq ''}
											<a class="tooltip pop activeRoll" href="#cashPrixCredit{$aData.ORDER}Pop{$smarty.foreach.listFinition.iteration}" {gtmjs type='toggle'  action='Display::ToolTip|' eventGTM='over'   data=$aParams  datasup=['value'=>'cashPrixCredit','eventLabel'=>$aVehicule.VEHICULE.VEHICULE_LABEL]} >?</a>
										{/if}
									</em>
								{/if}
							</div>
							<p>{'CETTE_FINITION_COMPREND'|t}</p>
							<ul class="listed">
								{if $finition.FINITION_MERE}<li><em {if ($aData.PRIMARY_COLOR|count_characters)==7 } style="color:{$aData.PRIMARY_COLOR};" {/if} >{'EQUIPEMENTS_FINITION_MERE'|t} {$finition.FINITION_MERE}</em></li>{/if}
								{foreach from=$finition.EQUIPEMENTS item=equipement name=listEquipement}
									<li>{$equipement.EQUIPEMENT_LABEL}</li>
								{/foreach}
							</ul>
							<ul class="actions compareBtn" {if ($aData.PRIMARY_COLOR|count_characters)==7 } data-off="border:4px solid {$aData.SECOND_COLOR}; background:{$aData.SECOND_COLOR}; color:#ffffff;" data-hover="border:4px solid {$aData.SECOND_COLOR}; background:#ffffff; color:{$aData.SECOND_COLOR};" {/if}>
								<li class="grey plus"><a {gtm type='clickable' action="AddModel" data=$aParams datasup=['eventCategory' =>'Showroom::Finishing','eventLabel'=>{'ADD_FINITION_COMPARATEUR'|t}]} href="javascript://"  id="{$finition.LCDV6}" rel="{$finition.FINITION_CODE}" data-value="{$finition.FINITION_LABEL}" class="addtoCompare" >{'ADD_FINITION_COMPARATEUR'|t}</a></li>
							</ul>
						</div>
					{/foreach}
				</div>




                {if ($aData.PRIMARY_COLOR|count_characters)==7 }
                <style type="text/css">
                    {literal}
                    .showroom.clsfinitions .bx-pager-link{
                        border:3px solid {/literal}{$aData.SECOND_COLOR}{literal}!important;
                        background:#ffffff!important;
                    }
                    .showroom.clsfinitions .bx-pager-link.active{
                        border:3px solid {/literal}{$aData.SECOND_COLOR}{literal}!important;
                        background:{/literal}{$aData.SECOND_COLOR}{literal}!important;
                    }
                    {/literal}
                </style>
                {/if}


				{foreach from=$aFinitions item=finition name=listFinition}
					<script type="text/template" id="cashPrixComptant{$aData.ORDER}Pop{$smarty.foreach.listFinition.iteration}">
						<div class="legal layerpop">
							{$aVehicule.VEHICULE.VEHICULE_CASH_PRICE_LEGAL_MENTION}
						</div>
					</script>
					<!-- /.layertip -->
					<script type="text/template" id="cashPrixCredit{$aData.ORDER}Pop{$smarty.foreach.listFinition.iteration}">
						<div class="legal layerpop">
							{$finition.MENTIONS_LEGALES.HTML}
						</div>
					</script>
				{/foreach}

			</div>
                        {if $aData.ZONE_TITRE6 && ($aData.ZONE_TITRE5 == "ROLL" || ($aData.ZONE_TITRE5 == "POP_IN" && $urlPopInMention != ""))}
                            <div class="caption">
                                    {if $aData.ZONE_TITRE5 == "ROLL"}
                                    <small class="legal"><a href="#LegalTip_{$aData.ZONE_ORDER}" class="texttip">{$aData.ZONE_TITRE6|escape}</a></small>
                                    <div class="legal layertip" id="LegalTip_{$aData.ZONE_ORDER}">
                                            {if $MEDIA_PATH4 != ""}<img class="lazy noscale" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/235-100.png" data-original="{"{Pelican::$config.MEDIA_HTTP}{$MEDIA_PATH4}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_MENTION}" width="580" height="247" alt="{$MEDIA_TITLE4}" />{/if}
                                            {if $aData.ZONE_TEXTE4} <div class="zonetexte">{$aData.ZONE_TEXTE4}</div> {/if}
                                    </div>

                                    {elseif $aData.ZONE_TITRE5 == "POP_IN" && $urlPopInMention != ""}
                                                    <small class="legal">
                                                            <a href="{urlParser url={$urlPopInMention|cat:'?popin=1'}}" class="popinfos fancybox.ajax" {gtmjs type='toggle' data=$aData labels=['tranche' => $aData.ZONE_TYPE_ID, 'id' => $aData.ZONE_ID, 'profil' => $dataLayer.profiles, 'nomBouton' => $aData.ZONE_TITRE6, 'idBouton' => 'legal']}>
                                                                    {$aData.ZONE_TITRE6|escape}
                                                            </a>
                                                    </small>
                                    {/if}

                            </div>
                        {/if}
                        {if $aData.ZONE_TITRE5 == "TEXT" && $aData.ZONE_TEXTE4 neq ""}
                            <div class="caption">
                                <figure>
                                    {if $MEDIA_PATH4 != ""}<img class="noscale" src="{"{Pelican::$config.MEDIA_HTTP}{$MEDIA_PATH4}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_MENTION}" width="580" height="247" alt="{$MEDIA_TITLE4}" />{/if}
                                </figure>
                                <small class="legal">{$aData.ZONE_TITRE6|escape}<br>{if $aData.ZONE_TEXTE4} <div class="zonetexte">{$aData.ZONE_TEXTE4}</div> {/if}</small>
                            </div>
                        {/if}
                        {if $aMedias|@sizeof > 0}
                            <div class="thumbs">
                                    {foreach $aMedias as $aMedia  key=key}
                                            {if $aMedia.VIDEO}
                                                    {if $aMedia.VIDEO.YOUTUBE_ID}
                                                    <a class="popit" {if $aMedia.VIDEO.OTHER_MEDIA_PATH}data-video="{$aMedia.VIDEO.OTHER_MEDIA_PATH}"{/if} data-sneezy href="{if $aMedia.VIDEO.YOUTUBE_URL}{urlParser url=$aMedia.VIDEO.YOUTUBE_URL}{/if}" target="_blank" {gtmjs type='toggle' data=$aData labels=['tranche' => $aData.ZONE_TYPE_ID, 'id' => $aData.ZONE_ID, 'nomVideo' => $aMedia.VIDEO.MEDIA_ALT, 'idVideo' => $aMedia.VIDEO.MEDIA_ID]}>
                                                    {else}
                                                    <a class="popit" {if $aMedia.VIDEO.OTHER_MEDIA_PATH}data-video="{$aMedia.VIDEO.OTHER_MEDIA_PATH}"{/if} data-sneezy href="{if $aMedia.VIDEO.MEDIA_PATH}{urlParser url={Pelican::$config.MEDIA_HTTP|cat:$aMedia.VIDEO.MEDIA_PATH}}{/if}" target="_blank" {gtmjs type='toggle' data=$aData labels=['tranche' => $aData.ZONE_TYPE_ID, 'id' => $aData.ZONE_ID, 'nomVideo' => $aMedia.VIDEO.MEDIA_ALT, 'idVideo' => $aMedia.VIDEO.MEDIA_ID]}>
                                                    {/if}
                                                            <!--shadow video-->
                                                            <figure class="shadow video">
                                                                    <img class="lazy" data-original="{if $aMedia.VIDEO.MEDIA_PATH}{"{Pelican::$config.MEDIA_HTTP}{$aMedia.VIDEO.MEDIA_PATH2}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_MENTION}{/if}" width="145" height="81" alt="{$aMedia.VIDEO.MEDIA_ALT2}">
                                                            </figure>
                                                            <span>{$aMedia.VIDEO.MEDIA_TITLE}</span>
                                                    </a>
                                            {/if}
                                            {if $aMedia.IMAGE}
                                                    {section name=push loop=$aMedia.IMAGE}
                                                            {if $smarty.section.push.first}
                                                                    <a class="popit" data-sneezy="group{$key}" href="{urlParser url={Pelican::$config.MEDIA_HTTP|cat:$aMedia.IMAGE[push].MEDIA_PATH}}" target="_blank" {gtmjs type='toggle' data=$aData labels=['tranche' => $aData.ZONE_TYPE_ID, 'id' => $aData.ZONE_ID, 'idGalerie' => $push, 'idPremierMedia' => $aMedia.IMAGE[0].MEDIA_ID]}>
                                                                            <figure class="shadow">
                                                                            {if $VIGN_GALLERY}
                                                                            {assign var="Vignette_Gal" value="$VIGN_GALLERY"}
                                                                            {else}
                                                                            {assign var="Vignette_Gal" value=$aMedia.IMAGE[push].MEDIA_PATH}
                                                                            {/if}
                                                                                    <img class="lazy" data-original="{"{Pelican::$config.MEDIA_HTTP}{$Vignette_Gal}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_PUSH}" width="145" height="81" alt="{$aMedia.IMAGE[push].MEDIA_TITLE}">
                                                                            </figure>
                                                                            <span>{$aMedia.MEDIA_TITLE}</span>
                                                                    </a>
                                                            {else}
                                                                    <a class="popit grouped" data-sneezy="group{$key}" href="{urlParser url={Pelican::$config.MEDIA_HTTP|cat:$aMedia.IMAGE[push].MEDIA_PATH}}" target="_blank" {gtmjs type='toggle' data=$aData labels=['tranche' => $aData.ZONE_TYPE_ID, 'id' => $aData.ZONE_ID, 'idGalerie' => $push, 'idPremierMedia' => $aMedia.IMAGE[0].MEDIA_ID]}>
                                                                            <figure class="shadow">
                                                                                    <img class="lazy" data-original="{"{Pelican::$config.MEDIA_HTTP}{$aMedia.IMAGE[push].MEDIA_PATH}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_PUSH}" width="145" height="81" alt="{$aMedia.IMAGE[push].MEDIA_ALT}" />
                                                                            </figure>
                                                                    </a>
                                                            {/if}
                                                    {/section}
                                            {/if}
                                    {/foreach}
                            </div>
                        {/if}
		</section>

	{/if}
{/if}
<script type="text/javascript">
    var _addToComparator_OK = "{'ADD_COMPARATEUR_OK'|t}" ;
    var _addToComparator_KO = "{'ADD_COMPARATEUR_KO'|t}";
    var showRoomComparateur = {$json_showroom_comparateur} ;
</script>





