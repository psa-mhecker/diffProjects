<div class="sliceNew sliceActualitesContenuDesk">
    <section id="{$aParams.ID_HTML}" class=" clsactualitescontenu">
    <article class="row of7">
		{ if ($aContent.MEDIA_PATH=='' && $MEDIA_VIDEO=='') }
			<div class="row"></div>
		{/if}
	
        {if $aContent.DOC_ID || $aContent.MEDIA_PATH}
            <div class="row of7 sidebot rowable">
                <figure class="col span5 cellable celltablefigure">
                {if $MEDIA_VIDEO}
                    <figure class="shadow video">
                        <a class="popit" data-video="{$MEDIA_VIDEO}" href="{urlParser url=$MEDIA_VIDEO}" data-sneezy target="_blank" {gtm action='Display::Video' data=$aData datasup=['eventLabel'=>{$aContent.MEDIA_ALT}]}>
                            <img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/16-9.png" 
data-original="{$aContent.MEDIA_PATH}" width="373" height="210" alt="{$aContent.MEDIA_ALT}" />
                        </a>
                    </figure>
                {elseif $aContent.MEDIA_PATH != ''}
                    <img class="lazy" src="{Pelican::$config.IMAGE_FRONT_HTTP}/lazy/16-9.png" data-original="{$aContent.MEDIA_PATH}"
width="373" height="210" alt="{$aContent.MEDIA_ALT}" />
                    <noscript><img src="{$aContent.MEDIA_PATH}" width="373" height="210" alt="{$aContent.MEDIA_ALT}" /></noscript>
                {/if}   
            </figure>
        {/if}
                <div class="col span2 sidebar cellablefloat {if $aContent.DOC_ID || $aContent.MEDIA_PATH}cellable{/if} ">
                {if $bNewsLetter}
                <form class="newsletter" novalidate action="{$abonnements.ZONE_URL2}">
                    <fieldset>
                        <legend>{'ABO_NEWSLETTER'|t}</legend>
                        <p>{'TEXT_ABO_NEWSLETTER'|t}</p>
                        <div class="field include">
                            <input type="email" name="email" placeholder="{'VOTRE_EMAIL'|t}" />
                        </div>
                        <input type="submit" name="register" value="{'OK'|t}" {gtm name='abonnement_newsletter' data=$aParams }/>
				</fieldset>
			</form>
            {/if}
			<a href="{urlParser url=$sUrlRSS}" class="rss" target="_blank" {gtm name='clic_sur_lien_rss' data=$aParams}>{'ABO_RSS'|t}</a>
            {if !($aContent.DOC_ID || $aContent.MEDIA_PATH)}
                <div class="col span2">
                    {$sSharer}
                </div>
            {/if}
        {if $aContent.DOC_ID || $aContent.MEDIA_PATH}    
		</div>
        {/if}
	</div>
    {if $aContent.DOC_ID || $aContent.MEDIA_PATH}
    <div class="rowable">
    {/if}
            <div class="actuinfonone {if $aContent.DOC_ID || $aContent.MEDIA_PATH}actuinfo cellable{/if}">
        	   <time class=" caption parttitle" datetime="{$aContent.DATE_TIME_HTML}">{$aContent.DATE_FORMATEE}</time>
        	   <h1 class=" caption subtitle">{$aContent.CONTENT_TITLE}</h1>

                <div class="social-share">
                    <div class="social-container">
                        <div class="share-label">{'Partager'|t}</div>
                        <div class="addthis_toolbox addthis_default_style">
                            <a class="addthis_button_facebook"></a>
                            <a class="addthis_button_twitter"></a>
                            <a class="addthis_button_google_plusone_share"></a>
                            <a class="addthis_button_linkedin"></a>
                            <a class="addthis_button_pinterest_share"></a>
                            <a class="addthis_button_email"></a>                
                            <a class="addthis_button_compact"></a>
                        </div>
                        <hr />
                    </div>
                </div>

        	   <div class="zonetexte"><strong>{$aContent.CONTENT_TEXT2}</strong>{$aContent.CONTENT_TEXT}</div>

        	</div>
    {if $aContent.DOC_ID || $aContent.MEDIA_PATH}
    </div>
    {/if}
    </article>
</section>  
</div>
