{literal}
<style>
    .slice3colonnesDesk .slider .bx-prev  , .slice3colonnesDesk .slider .bx-next  {
        color: {/literal}{$aData.SECOND_COLOR}{literal}!important;
        border: 2px solid {/literal}{$aData.SECOND_COLOR}{literal}!important;
    }
    .slice3colonnesDesk .slider .bx-prev:hover, .slice3colonnesDesk .slider .bx-next:hover{
        color: #fff!important;
        background-color: {/literal}{$aData.SECOND_COLOR}{literal}!important;
    }
    .slice3colonnesDesk .slider .bx-wrapper .bx-controls .bx-pager-item .bx-pager-link.active {
        background: {/literal}{$aData.SECOND_COLOR}{literal}!important;
    }
	.slice3colonnesDesk .icon-play{
	 color: {/literal}{$aData.SECOND_COLOR}{literal}!important;
	}
        .showroom.clslanguetteshowroom .addmore.folder a {
        filter: none;
        -webkit-filter: none;
        -moz-filter: none;
        -o-filter: none;
        -ms-filter: none;
    }
	</style>
{/literal}

{if ($aData.ZONE_WEB ==1) && $aColonnes|@count > 1}
<div class="sliceNew slice3colonnesDesk">
    <section class="cls3colonnes" id="{$aData.ID_HTML}">
		<div class="sep {$aData.ZONE_SKIN}"></div>

			{if ($aData.PRIMARY_COLOR|count_characters)==7 }					
				{if $aData.ZONE_TITRE3}<h2 class="subtitle" {if ($aData.PRIMARY_COLOR|count_characters)==7 } style="color:{$aData.PRIMARY_COLOR};" {/if}>{$aData.ZONE_TITRE3|escape}</h2>{/if}
				{if $aData.ZONE_TITRE4}<h3 class="parttitle" {if ($aData.SECOND_COLOR|count_characters)==7 } style="color:{$aData.SECOND_COLOR};" {/if}>{$aData.ZONE_TITRE4|escape}</h3>{/if}
				{if $aData.ZONE_TEXTE}
					<p ><strong>{$aData.ZONE_TEXTE|escape}</strong></p><br/>
				{else}
					<div ></div>
				{/if}
				
			{else}	
				{if $aData.ZONE_TITRE3}<h2 class="subtitle">{$aData.ZONE_TITRE3|escape}</h2>{/if}
				{if $aData.ZONE_TITRE4}<h3 class="parttitle">{$aData.ZONE_TITRE4|escape}</h3>{/if}
				{if $aData.ZONE_TEXTE}
					<p class="mgchapo"><strong>{$aData.ZONE_TEXTE|escape}</strong></p>
				{else}
					<div class="no-mgchapo"></div>
				{/if}
				
			{/if}	

            <div class="slider" {gtmjs type='slider' data=$aData  action='Click'} >
                <div class="row of3">
                    {section name=colonne loop=$aColonnes}
                        <div class="{if $smarty.section.colonne.first}new{/if} col" data-sync="col{$aData.ORDER}">

							<div class="mgcoltitle" data-sync="title{$aData.ORDER}">
								<h4>{$aColonnes[colonne].PAGE_ZONE_MULTI_LABEL|escape}</h4>
							</div>

                            <figure class="shadow">
                                <img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/235-100.png" data-original="{"{Pelican::$config.MEDIA_HTTP}{$aColonnes[colonne].MEDIA_PATH}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_3_COLONNES}" width="580" height="250" alt="{$aColonnes[colonne].MEDIA_ALT}" style="display: inline-block;">
                                <noscript><img src="{"{Pelican::$config.MEDIA_HTTP}{$aColonnes[colonne].MEDIA_PATH}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_3_COLONNES}" width="580" height="250" alt="{$aColonnes[colonne].MEDIA_ALT}" /></noscript>						<noscript><img src="{"{Pelican::$config.MEDIA_HTTP}{$aColonnes[colonne].MEDIA_PATH}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_3_COLONNES}" width="580" height="250" alt="{$aColonnes[colonne].MEDIA_ALT}" /></noscript>
                            </figure>
                            {if $aColonnes[colonne].PAGE_ZONE_MULTI_TEXT} <div class="zonetexte">{$aColonnes[colonne].PAGE_ZONE_MULTI_TEXT}</div> {/if}

                            {if $aData.ZONE_PARAMETERS == 1}
		                        {if $aColonnes[colonne].PAGE_ZONE_MULTI_URL2 != "" && $aColonnes[colonne].PAGE_ZONE_MULTI_LABEL2 != ""}
	                                <ul>
	                                    <li><a class="buttonLink" href="{urlParser url=$aColonnes[colonne].PAGE_ZONE_MULTI_URL2}" {if $aColonnes[colonne].PAGE_ZONE_MULTI_VALUE == 'BLANK'}target="_blank"{/if} {gtm action='Push' data=$aData datasup=['eventLabel'=>$aColonnes[colonne].PAGE_ZONE_MULTI_LABEL2]}>{$aColonnes[colonne].PAGE_ZONE_MULTI_LABEL2|escape}</a></li>
	                                </ul>
                           		{/if}
                           	{elseif $aData.ZONE_PARAMETERS == 2}
                           	    {if $aColonnes[colonne].PAGE_ZONE_MULTI_URL3 != "" || $aColonnes[colonne].PAGE_ZONE_MULTI_LABEL3 != "" 
                                    || $aColonnes[colonne].PAGE_ZONE_MULTI_URL5 != "" || $aColonnes[colonne].PAGE_ZONE_MULTI_LABEL5 != "" 
                                    || $aColonnes[colonne].PAGE_ZONE_MULTI_ATTRIBUT!="" || $aColonnes[colonne].PAGE_ZONE_MULTI_ATTRIBUT2!=""}
		                            {*CPW-3679   -- Fix Isobar - Suppression des sauts de lignes *}
		                        	<ul class="actions">
                                  
		                                    {if $aColonnes[colonne].PAGE_ZONE_MULTI_ATTRIBUT}
                                                <li><a  class="buttonLead" {gtm action="Push" data=$aData datasup=['eventCategory'=>'Content','eventLabel' =>$aColonnes[colonne].BARRE_OUTILS_TITRE]} href="{urlParser url=$aColonnes[colonne].BARRE_OUTILS_URL_WEB}" target="_{$aColonnes[colonne].BARRE_OUTILS_MODE_OUVERTURE}"><span>{$aColonnes[colonne].BARRE_OUTILS_TITRE}</span></a></li>     
			                                {else if $aColonnes[colonne].PAGE_ZONE_MULTI_URL3 != "" && $aColonnes[colonne].PAGE_ZONE_MULTI_LABEL3 != ""}
				                                <li><a class="buttonTransversalInvert" {gtm action="Push" data=$aData datasup=['eventCategory'=>'Content','eventLabel' =>$aColonnes[colonne].PAGE_ZONE_MULTI_LABEL3]} href="{urlParser url=$aColonnes[colonne].PAGE_ZONE_MULTI_URL3}" {if $aColonnes[colonne].PAGE_ZONE_MULTI_VALUE2 == "BLANK"}target="_blank"{/if}><span>{$aColonnes[colonne].PAGE_ZONE_MULTI_LABEL3}</span></a></li>
				                            {/if}
												{if $aColonnes[colonne].PAGE_ZONE_MULTI_ATTRIBUT2}
													<li><a class="buttonLead" {gtm action="Push" data=$aData datasup=['eventCategory'=>'Content','eventLabel' =>$aColonnes[colonne].BARRE_OUTILS_TITRE2]} href="{urlParser url=$aColonnes[colonne].BARRE_OUTILS_URL_WEB2}" target="_{$aColonnes[colonne].BARRE_OUTILS_MODE_OUVERTURE2}"><span>{$aColonnes[colonne].BARRE_OUTILS_TITRE2}</span></a></li>
			                                {else if $aColonnes[colonne].PAGE_ZONE_MULTI_URL5 != "" && $aColonnes[colonne].PAGE_ZONE_MULTI_LABEL4 != ""}
													<li><a  class="buttonTransversalInvert" {gtm action="Push" data=$aData datasup=['eventCategory'=>'Content','eventLabel' =>$aColonnes[colonne].PAGE_ZONE_MULTI_LABEL4]} href="{urlParser url=$aColonnes[colonne].PAGE_ZONE_MULTI_URL5}" {if $aColonnes[colonne].PAGE_ZONE_MULTI_VALUE3 == "BLANK"}target="_blank"{/if}><span>{$aColonnes[colonne].PAGE_ZONE_MULTI_LABEL4}</span></a></li>
                                            {/if}

									</ul>
		                        {/if}
		                    {/if}
                        </div>
                        <!-- /.col -->
                    {/section}
                </div>
            </div>





                {if ($aData.PRIMARY_COLOR|count_characters)==7 }
                <style type="text/css">
                    {literal}
                    .showroom.cls3colonnes .bx-pager-link{
                        border:3px solid {/literal}{$aData.SECOND_COLOR}{literal}!important; 
                        background:#ffffff!important;
                    }
                    .showroom.cls3colonnes .bx-pager-link.active{
                        border:3px solid {/literal}{$aData.SECOND_COLOR}{literal}!important; 
                        background:{/literal}{$aData.SECOND_COLOR}{literal}!important;
                    }
                    {/literal}
                </style>
                {/if}


		{if $aData.ZONE_TITRE6 && ($aData.ZONE_TITRE5 == "ROLL" || ($aData.ZONE_TITRE5 == "POP_IN" && $urlPopInMention != ""))}
			<div class="caption">
				{if $aData.ZONE_TITRE5 == "ROLL"}
					<small class="legal"><a href="#LegalTip_{$aData.ZONE_ORDER}" class="texttip">{$aData.ZONE_TITRE6}</a></small>
					<div class="legal layertip" id="LegalTip_{$aData.ZONE_ORDER}">
						{if $MEDIA_PATH4 != ""}<img class="lazy noscale" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/235-100.png" data-original="{"{Pelican::$config.MEDIA_HTTP}{$MEDIA_PATH4}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_MENTION}" width="580" height="247" alt="{$MEDIA_ALT4}" />{/if}
						{if $aData.ZONE_TEXTE4} <div class="zonetexte">{$aData.ZONE_TEXTE4}</div> {/if}
					</div>
				{elseif $aData.ZONE_TITRE5 == "POP_IN" && $urlPopInMention != ""}
					<small class="legal">
						<a href="{urlParser url={$urlPopInMention|cat:'?popin=1'}}" class="popinfos fancybox.ajax" {gtmjs type='toggle' data=$aData  action='DisplayTooltip' datasup=['eventLabel'=>{$aData.ZONT_TITRE6}]}>
							{$aData.ZONE_TITRE6}
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
                <small class="legal">{$aData.ZONE_TITRE6}<br>{if $aData.ZONE_TEXTE4} <div class="zonetexte">{$aData.ZONE_TEXTE4}</div> {/if}</small>
            </div>
        {/if}

		{if $aMedias|@sizeof > 0}
			<div class="thumbs">
			{foreach $aMedias as $aMedia  key=key}
					{if $aMedia.VIDEO}

					{if $aMedia.VIDEO.YOUTUBE_ID}
			
					<a class="popit" {if $aMedia.VIDEO.OTHER_MEDIA_PATH}data-video="{$aMedia.VIDEO.OTHER_MEDIA_PATH}"{/if} data-sneezy href="{if $aMedia.VIDEO.YOUTUBE_URL}{urlParser url=$aMedia.VIDEO.YOUTUBE_URL}{/if}" target="_blank" {gtm action='Display::Video' data=$aData datasup=['eventLabel'=>{$aMedia.VIDEO.MEDIA_TITLE}]}>
					{else}
					<a class="popit" {if $aMedia.VIDEO.OTHER_MEDIA_PATH}data-video="{$aMedia.VIDEO.OTHER_MEDIA_PATH}"{/if} data-sneezy href="{if $aMedia.VIDEO.MEDIA_PATH}{urlParser url={Pelican::$config.MEDIA_HTTP|cat:$aMedia.VIDEO.MEDIA_PATH}}{/if}" target="_blank" {gtm data=$aData action='Display::Video'  datasup=['eventLabel'=>{$aMedia.VIDEO.MEDIA_ALT}]}>
					{/if}
					
						<!--shadow video-->
						<figure class="shadow video">
							<i class="icon-play"></i>
							<img src="{if $aMedia.VIDEO.MEDIA_PATH}{Pelican::$config.MEDIA_HTTP}{$aMedia.VIDEO.MEDIA_PATH2}{/if}" style="width:145px;height:81px;"  alt="{$aMedia.VIDEO.MEDIA_ALT2}">
						</figure>
						{if $aMedia.VIDEO.MEDIA_TITLE}<span>{$aMedia.VIDEO.MEDIA_TITLE}</span>{/if}
					</a>
				{/if}
				{if $aMedia.IMAGE}
					{section name=push loop=$aMedia.IMAGE}
						{if $smarty.section.push.first}
							<a class="popit" data-sneezy="group3{$aData.ORDER}C{$key}" href="{urlParser url={Pelican::$config.MEDIA_HTTP|cat:$aMedia.IMAGE[push].MEDIA_PATH}}" target="_blank" {gtm data=$aData action='Zoom'  datasup=['eventLabel'=>{$aMedia.MEDIA_TITLE}]}>
							{IF $VIGN_GALLERY}
										{assign var="Vignette_Gal" value="$VIGN_GALLERY"}
										{ELSE}
										{assign var="Vignette_Gal" value=$aMedia.IMAGE[push].MEDIA_PATH}
									{/IF}
								<figure class="shadow">
									<img src="{"{Pelican::$config.MEDIA_HTTP}{$Vignette_Gal}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_PUSH}" width="145" height="81" alt="{$aMedia.IMAGE[push].MEDIA_ALT}">
								</figure>
								<span>{$aMedia.MEDIA_TITLE}</span>
							</a>
						{else}
							<a class="popit grouped" data-sneezy="group3{$aData.ORDER}C{$key}" href="{urlParser url={Pelican::$config.MEDIA_HTTP|cat:$aMedia.IMAGE[push].MEDIA_PATH}}" target="_blank" {gtm action='Zoom' data=$aData datasup=['eventLabel'=> $aMedia.IMAGE[push].MEDIA_ALT]}>
								<figure class="shadow">
									<img src="{"{Pelican::$config.MEDIA_HTTP}{$aMedia.IMAGE[push].MEDIA_PATH}"|format:Pelican::$config.MEDIA_FORMAT_ID.WEB_PUSH}" width="145" height="81" alt="{$aMedia.IMAGE[push].MEDIA_ALT}" />
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
						<li class="cta"><a class="buttonTransversalInvert" {gtm action='Push' data=$aData datasup=['eventLabel'=>{$aCta[cta].PAGE_ZONE_MULTI_LABEL}]} data-sync="cta{$aData.ORDER}" href="{urlParser url=$aCta[cta].PAGE_ZONE_MULTI_URL}" target="_{$aCta[cta].PAGE_ZONE_MULTI_VALUE}"><span>{$aCta[cta].PAGE_ZONE_MULTI_LABEL}</span></a></li>
					{/if}
				{/section}
			</ul>
		{/if}
	
	</section>
</div>

	<div class="parent" id="trancheParent" style="display: none;padding:0px;"></div>

	{if $aData.ZONE_LANGUETTE == 1}
		<section class="showroom row of3 clslanguetteshowroom">
			<div class="caption addmore folder" data-off="border:4px solid {if ($aData.SECOND_COLOR|count_characters)==7}{$aData.SECOND_COLOR}{else}#afadc3{/if};color:{if ($aData.SECOND_COLOR|count_characters)==7}{$aData.SECOND_COLOR}{else}#afadc3{/if};padding:8px;" data-hover="border:6px solid {if ($aData.SECOND_COLOR|count_characters)==7}{$aData.SECOND_COLOR}{else}#afadc3{/if};color:{if ($aData.SECOND_COLOR|count_characters)==7}{$aData.SECOND_COLOR}{else}#afadc3{/if};padding:6px;" data-toggle="{if $aData.ZONE_LANGUETTE_TEXTE_CLOSE}{$aData.ZONE_LANGUETTE_TEXTE_CLOSE}{else}{'VOIR_MOINS'|t}{/if}"><a {if ($aData.PRIMARY_COLOR|count_characters)==7 } class="col span2" {/if} href="#trancheParent" {gtm action="Push" data=$aData datasup=['eventLabel' => 'LANGUETTE_3_COLONNES']}>{if $aData.ZONE_LANGUETTE_TEXTE_OPEN}<span style="color: inherit;">{$aData.ZONE_LANGUETTE_TEXTE_OPEN}{else}{t("Voir plus")}{/if}</span></a></div>	
		</section>
	{/if}		




{/if}