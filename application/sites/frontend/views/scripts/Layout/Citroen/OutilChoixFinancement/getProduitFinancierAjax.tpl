<p>{'VOICI_LE_PRODUIT_QUI_REPOND_A_VOS_QUESTIONS'|t}</p>

<div class="elastic">
	<div class="row of2">
		<div class="col">
			{if $aData.ZONE_TITRE  neq ''}<h2 class="subtitle">{$aData.ZONE_TITRE}</h2>{/if}
			{if $aData.ZONE_TITRE2 neq ''}<h3 class="parttitle">{$aData.ZONE_TITRE2}</h3>{/if}
			{if $aData.ZONE_TEXTE}<div class="zonetexte">{$aData.ZONE_TEXTE}</div>{/if}
			
			{if $aData.ZONE_TEXTE2}
			<!-- Points forts -->
			<div class="elastic">
				{if $aData.ZONE_TITRE3}<h3 class="caption parttitle">{$aData.ZONE_TITRE3}</h3>{/if}
				{if $aData.ZONE_TEXTE2} <div class="zonetexte">{$aData.ZONE_TEXTE2}</div> {/if}
			</div>
			{/if}
			
			<!-- En savoir plus -->
			<ul class="actions clean center">
				<li class="blue"><a target="_self" href="{urlParser url=$aPage.PAGE_CLEAR_URL}" {gtm action='Push'  data=$aParams datasup=['eventLabel'=>{'EN_SAVOIR_PLUS'|t}]}>{'EN_SAVOIR_PLUS'|t}</a></li>
			</ul>
		</div>

		<!-- Visuel -->
		{if $MEDIA_VIDEO || $MEDIA_YOUTUBE && $MEDIA_PATH}
            <figure class="col span3 shadow video shareable nomgfigure">
                <a class="popit" data-video="{$MEDIA_VIDEO}" href="{urlParser url=$MEDIA_VIDEO}" data-sneezy target="_blank" {gtm   action='Display::Video' data=$aData datasup=['eventLabel'=>{$MEDIA_TITLE}]}>
                    <img src="{"{Pelican::$config.MEDIA_HTTP}{$MEDIA_PATH}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_2_COLONNES_MIXTE}" width="580" height="323">
                </a>
            </figure>
        {else}
            {if $aMultiVisuel|@sizeof > 0}
                <figure class="col span3 shadow shareable nomgfigure">
                    {foreach from=$aMultiVisuel item=lib name=foo}
                        {if $smarty.foreach.foo.first}
                            {if $VIGN_GALLERY_TOP}
                                {assign var="Vignette_Gal_Top" value=$VIGN_GALLERY_TOP}
                            {else}
                                {assign var="Vignette_Gal_Top" value=$lib.MEDIA_ID}
                            {/if}
                            <img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/16-9.png" data-original="{$Vignette_Gal_Top}" width="580" height="323" alt="" />
                        {else}
                            <a class="popit photo" data-sneezy="group2colme{$aData.ORDER}"  href="{urlParser url=$lib.MEDIA_ID}" target="_blank"  {gtm   action='Zoom' data=$aData datasup=['eventLabel'=>{$lib.MEDIA_TITLE}]}></a>
                        {/if}
                    {/foreach}      
                    <a class="popit photo" data-sneezy="group2colme{$aData.ORDER}"  href="{urlParser url=$aMultiVisuel[0].MEDIA_ID}" target="_blank" {gtm   action='Zoom' data=$aData datasup=['eventLabel'=>{$aMultiVisuel[0].MEDIA_TITLE}]}></a>
                </figure>
            {/if}
        {/if}
	</div>
</div>

<div class="reset grey light">
    <a onclick="javascript:outilChoixFinancement.reload(); return false;" href="#LOREM" class="button">{'RECOMMENCER'|t}</a>
</div>