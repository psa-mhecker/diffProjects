{if $enginesSelect}
    <li><a class="on" href="#0" data-value="0">{'CHOISIR_UNE_VERSION'|t}</a></li>
    {foreach $enginesSelect as $engine  key=key}										
        <li><a  {gtm action='DropdownList'  data=$aParams datasup=['eventLabel'=>{$engine.ENGINE_LABEL},'eventCategory'=>'Content']}
         data-value="{$sFinitionCode}|{$sLCDV6}|{$engine.ENGINE_CODE}" href="#0">{$engine.ENGINE_LABEL}</a></li>
    {/foreach}
{/if}