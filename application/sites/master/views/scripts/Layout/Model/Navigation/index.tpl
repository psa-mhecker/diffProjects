                            <div class="art-vmenublock">
{if $aMenu0}
                                <div class="art-vmenublock-body">
                                            <div class="art-vmenublockheader">
                                                <div class="l"></div>
                                                <div class="r"></div>
                                                 <div class="t">Navigation</div>
                                            </div>
                                            <div class="art-vmenublockcontent">
                                                <div class="art-vmenublockcontent-body">
                                            <!-- block-content -->
                                                            <ul class="art-vmenu">
	{section name=index0 loop=$aMenu0}
                                                            	<li{if $aMenu0[index0].selected} class="active"{/if}>
                                                            		<a href="{$aMenu0[index0].href}"{if $aMenu0[index0].selected} class="active"{/if}><span class="l"></span><span class="r"></span><span class="t">{$aMenu0[index0].lib}</span></a>
		{if $aMenu1 && $aMenu0[index0].selected}
                                                       				<!-- jqm-ul --><ul>
	{section name=index1 loop=$aMenu1}
                                                            			<li{if $aMenu1[index1].selected} class="active"{/if}>
                                                            			<a href="{$aMenu1[index1].href}"{if $aMenu1[index1].selected} class="active"{/if}>{$aMenu1[index1].lib}</a>
				{if $aMenu2 && $aMenu1[index1].selected}
		                                                       				<ul>
			{section name=index2 loop=$aMenu2}
		                                                            			<li{if $aMenu2[index2].selected} class="active"{/if}>
		                                                            			<a href="{$aMenu2[index2].href}"{if $aMenu2[index2].selected} class="active"{/if}>{$aMenu2[index2].lib}</a>
						{if $aMenu3 && $aMenu2[index2].selected}
				                                                       				<ul>
					{section name=index3 loop=$aMenu3}
				                                                            			<li{if $aMenu3[index3].selected} class="active"{/if}>
				                                                            			<a href="{$aMenu3[index3].href}"{if $aMenu3[index3].selected} class="active"{/if}>{$aMenu3[index3].lib}</a>
				                                                            			</li>
					{/section}
				                                                            		</ul>
						{/if}
						</li>
			{/section}
		                                                            		</ul>
				{/if}
                                                            			</li>
	{/section}
                                                            		</ul>
<!-- /jqm-ul -->
		{/if}
                                                            	</li>
	{/section}
                                                            </ul>
                                            <!-- /Block-content -->
                                            
                                            		<div class="cleared"></div>
                                                </div>
                                            </div>
                            		<div class="cleared"></div>
                                </div>
{/if}
                            </div>