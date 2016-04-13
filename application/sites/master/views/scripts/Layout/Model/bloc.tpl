<div class="art-block">
	<div class="art-block-tl"></div>
	<div class="art-block-tr"></div>
	<div class="art-block-bl"></div>
	<div class="art-block-br"></div>
	<div class="art-block-tc"></div>
	<div class="art-block-bc"></div>
	<div class="art-block-cl"></div>
	<div class="art-block-cr"></div>
	<div class="art-block-cc"></div>
	<div class="art-block-body">{if $zone_title}    
			<div class="art-blockheader">
				<div class="l"></div>
				<div class="r"></div>
				<div class="t">{$zone_title}</div>
			</div>{/if}{if $template}
			<div class="art-blockcontent">
			                                                <div class="art-blockcontent-tl"></div>
                                                <div class="art-blockcontent-tr"></div>
                                                <div class="art-blockcontent-bl"></div>
                                                <div class="art-blockcontent-br"></div>
                                                <div class="art-blockcontent-tc"></div>
                                                <div class="art-blockcontent-bc"></div>
                                                <div class="art-blockcontent-cl"></div>
                                                <div class="art-blockcontent-cr"></div>
                                                <div class="art-blockcontent-cc"></div>
				<div class="art-blockcontent-body">
					<!-- block-content -->
					{include file="$template"}
					<!-- /Block-content -->
					<div class="cleared"></div>
				</div>
			</div>{/if}
		<div class="cleared"></div>
	</div>
</div>