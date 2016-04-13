

{if $aDataOutils.ZONE_WEB == 1 and $display neq 0}
    {if $NbOutil > 0}

        <section id="{$aDataOutils.ID_HTML}" class="{if !$IsHome}enableAnima{/if}">
            <div class="sidebarToolsDesktopReview sliceNew  {if $aDataOutils.PAGE_GAMME_VEHICULE eq 'GAMME_LIGNE_DS'} ds {/if}"  {if $mode=='vertical'} data-animation="{if $aDataOutils.ZONE_TITRE5}{$aDataOutils.ZONE_TITRE5}{else}5{/if}"{/if} {if $mode!='vertical'}data-style="{strip}
                {if $codeCouleur.default.background}background:{$codeCouleur.default.background};{/if}
                {if $codeCouleur.default.border}border-color:{$codeCouleur.default.border};{/if}
                {if $codeCouleur.default.color}color:{$codeCouleur.default.color};{/if}
            {/strip}" {if $mode!='vertical'}data-style-hover="{strip}
                {if $codeCouleur.hover.background}background:{$codeCouleur.hover.background};{/if}
                {if $codeCouleur.hover.border}border-color:{$codeCouleur.hover.border};{/if}
                {if $codeCouleur.hover.color}color:{$codeCouleur.hover.color};{/if}
            {/strip}"{/if}{/if}>
            <div class="buttonListWrapper">
                
                {if $NbOutil >= 3}
                    {if $NbOutil <= 5}
                        {assign var='max' value=$NbOutil}
                    {else}
                        {assign var='max' value=5}
                    {/if}
                {/if}
                <ul class="buttonList">            
                    {section name=i loop=$aOutil max=$max}
                        {$aOutil[i]}
                    {/section}            
                </ul>
                 </div>
            </div>
        </section>
    {/if}



{/if}