{literal}
<style>
    .sliceOffrePlusDesk .slider .bx-prev  , .sliceOffrePlusDesk .slider .bx-next  {
        color: {/literal}{$aData.SECOND_COLOR}{literal}!important;
        border: 2px solid {/literal}{$aData.SECOND_COLOR}{literal}!important;
    }
    .sliceOffrePlusDesk .slider .bx-prev:hover, .sliceOffrePlusDesk .slider .bx-next:hover{
        color: #fff!important;
        background-color: {/literal}{$aData.SECOND_COLOR}{literal}!important;
    }
    .sliceOffrePlusDesk .slider .bx-wrapper .bx-controls .bx-pager-item .bx-pager-link.active {
        background: {/literal}{$aData.SECOND_COLOR}{literal}!important;
    }
	 .sliceOffrePlusDesk .slider .bx-wrapper .col span:hover {
        color: {/literal}{$aData.SECOND_COLOR}{literal}!important;
    }
	
	.sliceOffrePlusDesk .slider.offers a:hover span, .sliceOffrePlusDesk .slider.offers a:active span,
	.sliceOffrePlusMobile .slider.offers a:hover span,
	.sliceOffrePlusMobile .slider.offers a:active span {
	color: {/literal}{$aData.SECOND_COLOR}{literal}!important; }
        div.sliceOffrePlusDesk .actions .buttonTransversalInvert, div.sliceOffrePlusDesk .buttonTransversalInvert{
        {/literal}{if ($aData.SECOND_COLOR|count_characters)==7 }{literal}
                background-color:{/literal}{$aData.SECOND_COLOR}!important;{literal}
                border-color:{/literal}{$aData.SECOND_COLOR}!important;{literal}
                color:#ffffff!important;
        {/literal}{/if}{literal}
        }
        div.sliceOffrePlusDesk .actions .buttonTransversalInvert:hover, div.sliceOffrePlusDesk .buttonTransversalInvert:hover{
        {/literal}{if ($aData.SECOND_COLOR|count_characters)==7 }{literal}
                background-color:#ffffff!important;
                border-color:{/literal}{$aData.SECOND_COLOR}!important;{literal}
                color:{/literal}{$aData.SECOND_COLOR}!important; {literal}
        {/literal}{/if}{literal}
        }
	</style>
{/literal}

{if $aData.ZONE_WEB == 1 && count($aMulti) > 2}
<div class="sliceNew sliceOffrePlusDesk">
	<section id="{$aData.ID_HTML}" class="clsOffrePlus">
		<div class="sep {$aParams.ZONE_SKIN}"></div>

		{if $aData.ZONE_TITRE3}<h2 class="subtitle" {if ($aData.PRIMARY_COLOR|count_characters)==7 } style="color:{$aData.PRIMARY_COLOR};" {/if}>{$aData.ZONE_TITRE3}</h2>{/if}
		{if $aData.ZONE_TITRE4}<h3 class="parttitle" {if ($aData.SECOND_COLOR|count_characters)==7 } style="color:{$aData.SECOND_COLOR};" {/if}>{$aData.ZONE_TITRE4}</h3>{/if}
		{if $aData.ZONE_TEXTE}
			<div class="mgchapo">{$aData.ZONE_TEXTE}</div>
		{else}
			<div class="no-mgchapo"></div>
		{/if}	
		
		{if $aMulti|@sizeof > 0}
			
				<div class="slider offers built" data-speed="1750" {gtmjs type='slider' data=$aData action="click" datasup=['eventCategory' => 'Content::Slideshow']}>
						<div class="row of3">
								{foreach from=$aMulti item=Multi}
									<a href="{urlParser url=$Multi.PAGE_ZONE_MULTI_URL}" {if $Multi.PAGE_ZONE_MULTI_ATTRIBUT == 2} target="blank"{/if} class="col" style="float: left; list-style: outside none none; position: relative; width: 383.333px; margin-right: 40px;">
										<figure>
												<img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/1-1.png" data-original="{$Multi.MEDIA_ID}" width="408" height="313" alt="" />
												<noscript><img src="{$Multi.MEDIA_ID}" width="373" height="373" alt="" /></noscript>
										</figure>
										<span>{$Multi.PAGE_ZONE_MULTI_TITRE}</span>
									</a>
								{/foreach}

						</div>
				</div>
			
		{/if}
		
		{if $aData.ZONE_TITRE6 && ($aData.ZONE_TITRE5 == "ROLL" || ($aData.ZONE_TITRE5 == "POP_IN" && $urlPopInMention != ""))}
			<div class="caption">
				{if $aData.ZONE_TITRE5 == "ROLL"}
					<small class="legal"><a href="#LegalTip" class="texttip">{$aData.ZONE_TITRE6}</a></small>
					<div class="legal layertip" id="LegalTip">
						{if $MEDIA_PATH4 != ""}<img class="lazy noscale" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/1-1.png" data-original="{Pelican::$config.MEDIA_HTTP}{$MEDIA_PATH4}" width="580" height="247" alt="{$MEDIA_TITLE4}" />{/if}
						{if $aData.ZONE_TEXTE4} <div class="zonetexte">{$aData.ZONE_TEXTE4}</div> {/if}
					</div>
				{elseif $aData.ZONE_TITRE5 == "POP_IN"}
					<small class="legal"><a href="{urlParser url={$urlPopInMention|cat:"?popin=1"}}" class="popinfos fancybox.ajax">{$aData.ZONE_TITRE6}</a></small>
				{/if}
			</div>
		{/if}
        {if $aData.ZONE_TITRE5 == "TEXT" && $aData.ZONE_TEXTE4 neq ""}
            <div class="caption">
                <figure>
                    {if $MEDIA_PATH4 != ""}<img class="lazy load  noscale" src="{"{Pelican::$config.MEDIA_HTTP}{$MEDIA_PATH4}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_MENTION}" width="580" height="247" alt="{$MEDIA_TITLE4}" />{/if}
                </figure>
                <small class="legal">{$aData.ZONE_TITRE6}<br>{if $aData.ZONE_TEXTE4} <div class="zonetexte">{$aData.ZONE_TEXTE4}</div> {/if}</small>
            </div>
        {/if}
		{if $aCTA|@sizeof > 0}
			<ul class="actions" style="margin-bottom: 0px;">
				{foreach from=$aCTA item=Multi}
				 	{if $Multi.OUTIL}
                        {$Multi.OUTIL}
                    {else}
                        <li class="cta"><a class="buttonTransversalInvert" data-sync="cta{$aData.ORDER}" href="{urlParser url=$Multi.PAGE_ZONE_MULTI_URL}" target="_{$Multi.PAGE_ZONE_MULTI_VALUE}"><span style="color:inherit!important">{$Multi.PAGE_ZONE_MULTI_LABEL}</span></a></li>
					{/if}
				{/foreach}
			</ul>
		{/if}
		
	</section>
	</div>
{/if}