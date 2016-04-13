{literal}
<style>
{/literal}
{if ($aData.SECOND_COLOR|count_characters)==7 }
    {literal}
        .slice2ColonnesSansVisuelDesk a.buttonLink:hover {color: {/literal}{$aData.SECOND_COLOR}{literal}!important;}
    {/literal}
    {literal}
        .slice2ColonnesSansVisuelDesk .video:before,.slice2ColonnesSansVisuelDesk .actions a:after  {background-image: none;}
        .slice2ColonnesSansVisuelDesk .icon-play{
            color: {/literal}{$aData.SECOND_COLOR}{literal}!important;
        }
        .bx-controls .bx-next:hover, .bx-controls .bx-prev:hover{
            margin-top: -43px;
        }
    {/literal}
{/if}
{literal}
</style>
{/literal}

{if $aData.ZONE_WEB == 1}
<div class="sliceNew slice2ColonnesSansVisuelDesk">
    <section class="cls2colonnesansvisuel" id="{$aData.ID_HTML}">
	<div class="sep"></div>

    {if $aData.ZONE_TITRE}<h2 class="subtitle" {if ($aData.PAGE_PRIMARY_COLOR|count_characters)==7 } style="color:{$aData.PAGE_PRIMARY_COLOR};" {/if}>{$aData.ZONE_TITRE|escape}</h2>{/if}
    {if $aData.ZONE_TITRE2}<h3 class="parttitle" {if ($aData.PAGE_SECOND_COLOR|count_characters)==7 } style="color:{$aData.PAGE_SECOND_COLOR};" {/if}>{$aData.ZONE_TITRE2|escape}</h3>{/if}

	{if $aData.ZONE_TEXTE}
		<span class="mgchapo">{$aData.ZONE_TEXTE}</span>
	{else}
		<div class="no-mgchapo"></div>
	{/if}
	
	
	
{if $aData.ZONE_TITRE3 || $aData.ZONE_TEXTE2 || $aData.ZONE_TEXTE3}
<div class="row gutter">
    <div class="columns column_50 tmtl">

    {if $aData.ZONE_TITRE3}
	<div class="mgcoltitle" data-sync="title{$aData.ORDER}">
		<h4 class="lefttitle">{$aData.ZONE_TITRE3|escape}</h4>
	</div>
	{/if}
		
    {if $aData.ZONE_TEXTE2} <div class="zonetexte">{$aData.ZONE_TEXTE2}</div> {/if}

    {if $aData.ZONE_TEXTE3}
        <ul>
            <li><a  class="buttonLink" href="{urlParser url=$aData.ZONE_TEXTE3}" {if $aData.ZONE_TITRE16 == 2} target="_blank" {/if} {gtm action='Push' data=$aData datasup=['eventLabel'=>'Lien_2_Colonnes_Sans_Visuel_1']}>{if $aData.ZONE_TITRE8}{$aData.ZONE_TITRE8|escape}{else}{t('Link')}{/if}</a></li>
        </ul>
    {/if}

</div>
<!-- /.col -->
{/if}
{if $aData.ZONE_TITRE4 || $aData.ZONE_TEXTE5 || $aData.ZONE_TEXTE6}
    <div class="columns column_50 tmtl">

	{if $aData.ZONE_TITRE4}
	<div class="mgcoltitle" data-sync="title{$aData.ORDER}">
		<h4 class="lefttitle">{$aData.ZONE_TITRE4|escape}</h4>
	</div>
	{/if}
	
    {if $aData.ZONE_TEXTE5} <div class="zonetexte">{$aData.ZONE_TEXTE5}</div> {/if}

    {if $aData.ZONE_TEXTE6}
        <ul>
            <li><a class="buttonLink" href="{urlParser url=$aData.ZONE_TEXTE6}" {if $aData.ZONE_TITRE18 == 2} target="_blank" {/if} {gtm action='Push' data=$aData datasup=['eventLabel'=>'Lien_2_Colonnes_Sans_Visuel_2'] }>{if $aData.ZONE_TITRE9}{$aData.ZONE_TITRE9|escape}{else}{t('Link')}{/if}</a></li>
        </ul>
    {/if}

</div>
</div>
<!-- /.col -->
{/if}
        
            {if $aData.ZONE_TITRE6 && ($aData.ZONE_TITRE5 == "ROLL" || ($aData.ZONE_TITRE5 == "POP_IN" && $urlPopInMention != ""))}
				<div class="caption">
					{if $aData.ZONE_TITRE5 == "ROLL"}
					<small class="legal"><a href="#LegalTip_{$aData.ZONE_ORDER}" class="texttip">{$aData.ZONE_TITRE6|escape}</a></small>
					<div class="legal layertip" id="LegalTip_{$aData.ZONE_ORDER}">
						{if $MEDIA_PATH4 != ""}<img class="lazy noscale" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/235-100.png" data-original="{"{Pelican::$config.MEDIA_HTTP}{$MEDIA_PATH4}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_MENTION}" width="580" height="247" alt="{$MEDIA_ALT4}" />{/if}
						{if $aData.ZONE_TEXTE4} <div class="zonetexte">{$aData.ZONE_TEXTE4}</div> {/if}
					</div>

					{elseif $aData.ZONE_TITRE5 == "POP_IN" && $urlPopInMention != ""}
							<small class="legal">
								<a href="{urlParser url={$urlPopInMention|cat:'?popin=1'}}" class="popinfos fancybox.ajax" {gtmjs type='toggle' action='Display::ToolTip|' eventGTM='over'  data=$aData datasup=['eventLabel' =>  $aData.ZONE_TITRE6]}>
									{$aData.ZONE_TITRE6|escape}
								</a>
							</small>
					{/if}

				</div>
            {/if}
            {if $aData.ZONE_TITRE5 == "TEXT" && $aData.ZONE_TEXTE4 neq ""}
                <div class="caption">
                    <figure>
                        {if $MEDIA_PATH4 != ""}<img class="noscale" src="{"{Pelican::$config.MEDIA_HTTP}{$MEDIA_PATH4}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_MENTION}" width="580" height="247" alt="{$MEDIA_ALT4}" />{/if}
                    </figure>
                    <small class="legal">{$aData.ZONE_TITRE6|escape}<br>{if $aData.ZONE_TEXTE4} <div class="zonetexte">{$aData.ZONE_TEXTE4}</div> {/if}</small>
                </div>
            {/if}
            {if $aMedias|@sizeof > 0}
				<div class="thumbs">
					{foreach $aMedias as $aMedia  key=key}
						{if $aMedia.VIDEO}
							{if $aMedia.VIDEO.YOUTUBE_ID}
							<a class="popit" {if $aMedia.VIDEO.OTHER_MEDIA_PATH}data-video="{$aMedia.VIDEO.OTHER_MEDIA_PATH}"{/if} data-sneezy href="{if $aMedia.VIDEO.YOUTUBE_URL}{urlParser url=$aMedia.VIDEO.YOUTUBE_URL}{/if}" target="_blank" {gtm action='Display::Video' data=$aData datasup=['eventLabel'=>{$aMedia.VIDEO.MEDIA_ALT}]}>
							{else}
							<a class="popit" {if $aMedia.VIDEO.OTHER_MEDIA_PATH}data-video="{$aMedia.VIDEO.OTHER_MEDIA_PATH}"{/if} data-sneezy href="{if $aMedia.VIDEO.MEDIA_PATH}{urlParser url={Pelican::$config.MEDIA_HTTP|cat:$aMedia.VIDEO.MEDIA_PATH}}{/if}" target="_blank" {gtm action='Display::Video' data=$aData datasup=['eventLabel'=>{$aMedia.VIDEO.MEDIA_ALT}]}>
							{/if}
								<!--shadow video-->
								<figure class="shadow video">
                                                                        <i class="icon-play"></i>
									<img class="lazy" data-original="{if $aMedia.VIDEO.MEDIA_PATH}{"{Pelican::$config.MEDIA_HTTP}{$aMedia.VIDEO.MEDIA_PATH2}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_MENTION}{/if}" width="145" height="81" alt="{$aMedia.VIDEO.MEDIA_ALT2}">
								</figure>
								<span class="legend">{$aMedia.VIDEO.MEDIA_TITLE}</span>
							</a>
						{/if}
						{if $aMedia.IMAGE}
							{section name=push loop=$aMedia.IMAGE}
								{if $smarty.section.push.first}
									<a class="popit" data-sneezy="group{$key}" href="{urlParser url={Pelican::$config.MEDIA_HTTP|cat:$aMedia.IMAGE[push].MEDIA_PATH}}" target="_blank" {gtm action='Zoom' data=$aData datasup=['eventLabel'=>{$aMedia.MEDIA_TITLE}]}>
										<figure class="shadow">
										{IF $VIGN_GALLERY}
										{assign var="Vignette_Gal" value="$VIGN_GALLERY"}
										{ELSE}
										{assign var="Vignette_Gal" value=$aMedia.IMAGE[push].MEDIA_PATH}
										{/IF}
										
											<img class="lazy" data-original="{"{Pelican::$config.MEDIA_HTTP}{$Vignette_Gal}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_PUSH}" width="145" height="81" alt="{$aMedia.IMAGE[push].MEDIA_ALT}">
										
										
										</figure>
										<span>{$aMedia.MEDIA_TITLE}</span>
									</a>
								{else}
									<a class="popit grouped" data-sneezy="group{$key}" href="{urlParser url={Pelican::$config.MEDIA_HTTP|cat:$aMedia.IMAGE[push].MEDIA_PATH}}" target="_blank" {gtm action='Zoom' data=$aData datasup=['eventLabel'=>{$aMedia.IMAGE[push].MEDIA_ALT}]}>
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
            {if $aCta|@sizeof > 0}
                <ul class="actions">
                    {section name=cta loop=$aCta}
                    	{if $aCta[cta].OUTIL}
                        	{$aCta[cta].OUTIL}
                    	{else}
                        	<li class="cta">
                                    <a class="buttonTransversalInvert" {gtm action='Push' data=$aData datasup=['eventLabel'=> $aCta[cta].PAGE_ZONE_MULTI_LABEL]} data-sync="cta{$aData.ORDER}" href="{urlParser url=$aCta[cta].PAGE_ZONE_MULTI_URL}" target="_{$aCta[cta].PAGE_ZONE_MULTI_VALUE}">
                                        <span>{$aCta[cta].PAGE_ZONE_MULTI_LABEL}</span>
                                    </a>
                                </li>
                    	{/if}
                    {/section}
                </ul>

                <!-- /.actions -->
            {/if}


</section>

	<div class="parent" id="trancheParent" style="display: none;"></div>
	{if $aData.ZONE_LANGUETTE == 1}
		<section class="{$aData.ZONE_SKIN} {if ($aData.PRIMARY_COLOR|count_characters)==7 }showroom{/if} row of6 clslanguette{if ($aData.PRIMARY_COLOR|count_characters)==7 }showroom{/if}">
			<div class="caption addmore folder" {if ($aData.PRIMARY_COLOR|count_characters)==7 } data-off="border:4px solid {$aData.SECOND_COLOR};" data-hover="border:4px solid {$aData.SECOND_COLOR}; color:{$aData.SECOND_COLOR};"{/if} data-toggle="{if $aData.ZONE_LANGUETTE_TEXTE_CLOSE}{$aData.ZONE_LANGUETTE_TEXTE_CLOSE}{else}{'VOIR_MOINS'|t}{/if}"><a {if ($aData.PRIMARY_COLOR|count_characters)==7 } class="col span2" {/if} href="#trancheParent" {gtm action="Push" data=$aData datasup=['eventLabel' => 'LANGUETTE_2_COLONNES_SANS_VISUEL']}>{if $aData.ZONE_LANGUETTE_TEXTE_OPEN}{$aData.ZONE_LANGUETTE_TEXTE_OPEN}{else}{t("Voir plus")}{/if}</a></div>	
		</section>
	{/if}		
			

</div>
{/if}