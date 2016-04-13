{if $engineSelect}
	<li><a class="on" href="#0" data-value="0">{'CHOISIR_UNE_MOTORISATION'|t}</a></li>
	{foreach $engineSelect as $engine  key=key}										
	<li><a data-value="{$key}#{$engine.PRICE_DISPLAY}" href="#0" {gtmjs type='toggle' action='DropdownList::Engine|' data=$aParams datasup=['eventLabel' => {$engine.ENGINE_LABEL} ]}>{$engine.ENGINE_LABEL}</a></li>
	{/foreach}
{/if}