{if $finitionsSelect}
	<li><a {if $finitionSelected eq ''}class="on"{/if} href="#0" data-value="0">{'CHOISIR_UNE_FINITION'|t}</a></li>
	{foreach $finitionsSelect as $finition  key=key}										
	<li><a {if $finitionSelected eq $key}class="on"{/if} data-value="{$key}#{$finition.LCDV6}" href="#0" {gtmjs type='toggle' action='DropdownList::Finishing|' data=$aParams datasup=['eventLabel' => {$finition.FINITION_LABEL} ]}>{$finition.FINITION_LABEL}</a></li>
	{/foreach}
{/if}