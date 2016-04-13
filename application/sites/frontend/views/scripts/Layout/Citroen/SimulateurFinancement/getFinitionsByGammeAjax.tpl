{if $finitionsSelect}
    <li><a class="on" href="#0" data-value="0">{'CHOISIR_UNE_FINITION'|t}</a></li>
    {foreach $finitionsSelect as $finition  key=key}										
        <li><a  {gtm action='DropdownList'  data=$aParams datasup=['eventLabel'=>{$finition.FINITION_LABEL},'eventCategory'=>'Content']} data-value="{$finition.FINITION_CODE}|{$sLCDV6}" href="#0">{$finition.FINITION_LABEL}</a></li>
    {/foreach}
{/if}