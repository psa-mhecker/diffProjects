{literal}
    <style>
        div.slice404Desk figure+p, p+p {margin-top: 0px;}
    </style>
{/literal}
<div class="sliceNew slice404Desk">
    <div class="row of12 notfound" id="{$aData.ID_HTML}">
        {if $aData.ZONE_TITRE}<h1 class="subtitle" {if ($aData.PRIMARY_COLOR|count_characters)==7 } style="color:{$aData.PRIMARY_COLOR};" {/if}>{$aData.ZONE_TITRE}</h1>{/if}
        {if $aData.ZONE_TEXTE}<h2 class="caption parttitle" {if ($aData.SECOND_COLOR|count_characters)==7 } style="color:{$aData.SECOND_COLOR};" {/if}>{$aData.ZONE_TEXTE}</h2>{/if}
        <div class="col span5">
            <form action="{$recherche.PAGE_CLEAR_URL}" id="formSearch404" novalidate>
                <fieldset>
                    <div class="field include">
                        <span class="twitter-typeahead" style="position: relative; display: inline-block;">
                            <input class="tt-hint" type="text" autocomplete="off" spellcheck="off" disabled="" style="position: absolute; top: 0px; left: 0px; border-color: transparent; box-shadow: none; background: none 0% 0% / auto repeat scroll padding-box border-box rgb(255, 255, 255);">
                            <input placeholder="{$aData.ZONE_TITRE2}" type="text" name="search" class="tt-query placeholder" autocomplete="off" spellcheck="false" dir="auto" style="position: relative; vertical-align: top; background-color: transparent;">
                            <span style="position: absolute; left: -9999px; visibility: hidden; white-space: nowrap; font-family: citroenlight, Arial, sans-serif; font-size: 25px; font-style: normal; font-variant: normal; font-weight: 400; word-spacing: 0px; letter-spacing: 0px; text-indent: 0px; text-rendering: auto; text-transform: none;"></span>
                            <span class="tt-dropdown-menu" style="position: absolute; top: 100%; left: 0px; z-index: 100; display: none;"></span>
                        </span>
                    </div>
                    <button class="grey" type="submit" name="register">OK</button>
                </fieldset>
            </form>
        </div>
        {if $aData.ZONE_TITRE3 || $aData.ZONE_TITRE4}
            <ul class="redirect">
                <li><a href="/" class="buttonTransversalInvert">{$aData.ZONE_TITRE3}</a></li>
                <li><a href="{urlParser url=$sURLPagePlanDuSite}" class="buttonTransversalInvert">{$aData.ZONE_TITRE4}</a></li>
            </ul>
        {/if}
    </div>
</div>