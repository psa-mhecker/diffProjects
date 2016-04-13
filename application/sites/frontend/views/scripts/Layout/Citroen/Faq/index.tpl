{if $bDisplayBlock == true && $aParams.ZONE_WEB eq 1}
    <div class="sliceNew sliceFaqDesk">
        <section id="{$aParams.ID_HTML}" class="{$aParams.ZONE_SKIN} faqs clsfaq">
            {if $aParams.ZONE_TITRE}<h2 class="subtitle">{$aParams.ZONE_TITRE|escape}</h2>{/if}

            <div class="row of3 piclinks">
                {foreach $aFaq as $aRubriqueFaq}
                    {assign var="rubriqueClass" value=""}
                    {if $aRubriqueFaq@first}
                        {assign var="rubriqueClass" value="`$rubriqueClass` new"}
                    {/if}
                    {if $aRubriqueFaq.RUBRIQUE_SELECTED}
                        {assign var="rubriqueClass" value="`$rubriqueClass` active"}
                    {/if}
                    <div class="{$rubriqueClass} col">
                        <a href="{urlParser url=$aRubriqueFaq.RUBRIQUE_HREF}#faqactive">
                            <img src="{$aRubriqueFaq.RUBRIQUE_PICTO_PATH}" width="63" height="63" alt="{$aRubriqueFaq.RUBRIQUE_PICTO_ALT}">
                            <span>{$aRubriqueFaq.RUBRIQUE_LABEL}</span>
                        </a>
                    </div>
                {/foreach}
            </div>
            <!-- /.piclinks -->

            <a name="faqactive" id="faqactive"></a>

            <div class="toggle faq" data-overall="true">
                {if $aFaqSelected.RUBRIQUE_LABEL}<h2 class="subtitle">{$aFaqSelected.RUBRIQUE_LABEL}</h2>{/if}

                <div class="row of3">
                    <a class="overall closeall active" href="#LOREM" {gtm name='clic_sur_tout_fermer' data=$aParams labelvars=['%id lien%' => 'CLOSE_ALL'|t]}>{'CLOSE_ALL'|t}</a>
                    <a class="overall openall" href="#LOREM" {gtm name='clic_sur_tout_ouvrir' data=$aParams labelvars=['%id lien%' => 'OPEN_ALL'|t]}>{'OPEN_ALL'|t}</a>

                    <div class="col span2 clsfaqquestion">
                        {foreach $aFaqSelected.QUESTIONS as $aOneQuestion}

                            {if $aOneQuestion.CONTENT_WEB == 1}
                                <div class="toghead folder {if $aOneQuestion@first}open{/if}">
                                    <h3 class="parttitle" {gtmjs data=$aParams type='toggle' labels=['tranche' => $aParams.ZONE_TYPE_ID, 'id' => $aParams.ZONE_ID, 'nomQuestion' => $aOneQuestion.CONTENT_TITLE, 'idQuestion' => $aOneQuestion.CONTENT_ID]}>
                                        {$aOneQuestion.CONTENT_TITLE}
                                    </h3>
                                </div>
                                <!-- /.toghead -->
                                <div class="togbody">
                                    {if $aOneQuestion.CONTENT_SHORTTEXT }
                                        <p>{$aOneQuestion.CONTENT_SHORTTEXT}</p>
                                        <!-- chapô ?? -->
                                    {/if}

                                    <div class="zonetexte lc">{$aOneQuestion.CONTENT_TEXT}</div>

                                    {if $aOneQuestion.CONTENT_URL2 && $aOneQuestion.CONTENT_SUBTITLE}
                                        {assign var="target" value=$aOneQuestion.CONTENT_SUBTITLE}

                                        {if $aOneQuestion.CONTENT_TITLE}
                                            {assign var="gtm_id_lien" value=$aOneQuestion.CONTENT_TITLE}
                                        {else}
                                            {assign var="gtm_id_lien" value='LIEN'|t}
                                        {/if}

                                        <ul class="links">
                                            <li><a href="{urlParser url=$aOneQuestion.CONTENT_URL2}" target="{Pelican::$config.TRANCHE_COL.BLANK_SELF.$target}" {gtm name='clic_sur_lien' data=$aParams datasup=['value'=>1] labelvars=['%id lien%' => $gtm_id_lien]}>{if $aOneQuestion.CONTENT_TITLE2}{$aOneQuestion.CONTENT_TITLE2}{else}{t('LIEN')}{/if}</a></li>
                                        </ul>
                                    {/if}

                                    {if $aOneQuestion.PUSH_MEDIA > 0}
                                        <div class="thumbs">
                                            {foreach $aOneQuestion.PUSH_MEDIA as $aOnePushMedia}

                                                {assign var="group" value=$aOneQuestion.CONTENT_ID}
                                                {if $aOnePushMedia.type eq 'GALLERY' && $aOnePushMedia.gallery|@count > 0}
                                                    {section name=push loop=$aOnePushMedia.gallery}
                                                        {if $smarty.section.push.first}
                                                            <a class="popit" data-sneezy="group{$group}I" href="{urlParser url={Pelican::$config.MEDIA_HTTP|cat:$aOnePushMedia.gallery[push].MEDIA_PATH}}" target="_blank"
                                                               {gtm action='Zoom' data=$aData datasup=['eventLabel'=>{$aOnePushMedia.gallery[push].MEDIA_ALT}]}
                                                            >
                                                                <figure class="shadow">
                                                                    {if $aOneQuestion.MEDIA_ID9}
                                                                        {assign var="Vignette_Gal" value=$aOneQuestion.MEDIA_ID9}
                                                                    {else}
                                                                        {assign var="Vignette_Gal" value=$aOnePushMedia.gallery[push].MEDIA_PATH}
                                                                    {/if}
                                                                    <img src="{Pelican::$config.MEDIA_HTTP}{$Vignette_Gal}" width="145" height="81" alt="{$aOnePushMedia.gallery[push].MEDIA_TITLE}">
                                                                </figure>
                                                                <span>{$aOnePushMedia.lib}</span>
                                                            </a>
                                                        {else}
                                                            <a class="popit grouped" data-sneezy="group{$group}I" href="{urlParser url={Pelican::$config.MEDIA_HTTP|cat:$aOnePushMedia.gallery[push].MEDIA_PATH}}" target="_blank"
                                                               {gtm action='Zoom' data=$aData datasup=['eventLabel'=>{$aOnePushMedia.gallery[push].MEDIA_ALT}]}
                                                            >
                                                                <figure class="shadow">
                                                                    <img src="{"{Pelican::$config.MEDIA_HTTP}{$aOnePushMedia.gallery[push].MEDIA_PATH}"}" width="145" height="81" alt="{$aOnePushMedia.gallery[push].MEDIA_ALT}" />
                                                                </figure>
                                                            </a>
                                                        {/if}
                                                    {/section}
                                                {/if}

                                                {if $aOnePushMedia.type eq 'VIDEO' && $aOnePushMedia.video|@count > 0}
                                                    {if $aOnePushMedia.video.MEDIA_ID}
                                                        {if $aOnePushMedia.video.MEDIA_TYPE_ID == 'youtube'}
                                                            <a class="popit" data-sneezy="" data-video="//www.youtube.com/embed/{$aOnePushMedia.video.YOUTUBE_ID}" href="//www.youtube.com/embed/{$aOnePushMedia.video.YOUTUBE_ID}" target="_blank" {gtm  action='Display::Video' data=$aData datasup=['eventLabel'=>{$aOnePushMedia.video.MEDIA_TITLE}]}>
                                                                <figure class="shadow video">
                                                                    <img src="{if $aOnePushMedia.thumb.MEDIA_PATH}{Pelican::$config.MEDIA_HTTP}{$aOnePushMedia.thumb.MEDIA_PATH}{/if}" width="145" height="81" alt="image_alt" />
                                                                </figure>
                                                                <span>{$aOnePushMedia.lib}</span>
                                                            </a>
                                                        {elseif $aOnePushMedia.video.MEDIA_TYPE_ID == 'video'}
                                                            <a class="popit" data-sneezy="" data-video="{if $aOnePushMedia.video.MEDIA_PATH}{Pelican::$config.MEDIA_HTTP}{$aOnePushMedia.video.MEDIA_PATH}{/if}" href="{if $aOnePushMedia.video.MEDIA_PATH}{urlParser url={Pelican::$config.MEDIA_HTTP|cat:$aOnePushMedia.video.MEDIA_PATH}}{/if}" target="_blank" {gtm  action='Display::Video' data=$aData datasup=['eventLabel'=>{$aOnePushMedia.video.MEDIA_TITLE}]}>
                                                                <figure class="shadow video">
                                                                    <img src="{if $aOnePushMedia.thumb.MEDIA_PATH}{Pelican::$config.MEDIA_HTTP}{$aOnePushMedia.thumb.MEDIA_PATH}{/if}" width="145" height="81" alt="image_alt" />
                                                                </figure>
                                                                <span>{$aOnePushMedia.lib}</span>
                                                            </a>
                                                        {/if}
                                                    {/if}
                                                {/if}

                                            {/foreach}
                                        </div>
                                        <!-- /.thumbs -->
                                    {/if}
                                </div>
                                <!-- /.togbody -->
                            {/if}

                        {/foreach}
                    </div>
                    <!-- /.col.span2 -->
                </div>
                <!-- /.row.of3 -->
            </div>
            <!-- /.toggle -->

            {if !empty($aParams.ZONE_LABEL2) && !empty($aParams.ZONE_URL2) && !empty($aParams.ZONE_TITRE2)}
                {assign var="target" value=$aParams.ZONE_TITRE2}
                <ul class="actions">
                    <li><a href="{urlParser url=$aParams.ZONE_URL2}" target="{Pelican::$config.TRANCHE_COL.BLANK_SELF.$target}" class="buttonLead">{$aParams.ZONE_LABEL2}</a></li>
                </ul>
                <!-- / .cta -->
            {/if}
        </section>
    </div>
{/if}