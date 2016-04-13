{if $aData.ZONE_WEB}
<section id="{$aData.ID_HTML}" class="">
        {if $aData.ZONE_TEXTE} <div class="zonetexte">{$aData.ZONE_TEXTE}</div> {/if}
        {IF $TYPE_COOKIE == "ACCEPT_COOKIES" && $displayBtn neq 1}
        <ul class="actions margeBottom">
            <li class="blue"><a href="#"  class="btnCookiePage" data-close=".cookies" onClick="acceptCookies('{$url}');return false;">{t('ACCEPTER_COOKIE')}</a></li>
        </ul>
        {/IF}
</section>
{/if}