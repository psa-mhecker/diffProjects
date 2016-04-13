{if $engineSelect}
	<li><a class="on" href="#0" data-value="0">{'CHOISIR_UNE_MOTORISATION'|t}</a></li>
	{foreach $engineSelect as $engine  key=key}										
	<li><a {gtmjs type='toggle' action='DropdownList::Engine|' data=$aData datasup=['eventLabel' => {$engine.ENGINE_LABEL} ]} data-value="{$key}#{$lcdv6}#{$finition}" href="#0">{$engine.ENGINE_LABEL}</a></li>
	{/foreach}
{/if}