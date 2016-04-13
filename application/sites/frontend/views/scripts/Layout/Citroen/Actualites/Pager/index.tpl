{if $parentPage != "" || $aPager.ACTU_PREC || $aPager.ACTU_SUIV }
	<div class="sliceNew sliceActualitesPagerDesk">
		<div id="{$aData.ID_HTML}" class="clsactualitespager">
			{if $parentPage != ""}
				<ul class="actions">
					<li class="grey back">
						<a href="{urlParser url=$parentPage}" class="activeRoll">
							{"BACK_GALLERY"|t}
						</a>
					</li>
				</ul>
				<!-- /.actions -->
			{/if}

			<div class="col social-share social-share-bottom">
				<div class="social-container">
					<div class="share-label">{'Partager'|t}</div>
					<div class="addthis_toolbox addthis_default_style">
						<a class="addthis_button_facebook"></a>
						<a class="addthis_button_twitter"></a>
						<a class="addthis_button_google_plusone_share"></a>
						<a class="addthis_button_linkedin"></a>
						<a class="addthis_button_pinterest_share"></a>
						<a class="addthis_button_email"></a>
						<a class="addthis_button_compact"></a>
					</div>
					<hr />
				</div>
			</div>

			{if $aPager.ACTU_PREC || $aPager.ACTU_SUIV}
				<ul class="navigate">
					{if $aPager.ACTU_PREC}
						<li class="prev">
							<a href="{urlParser url=$aPager.ACTU_PREC.PAGE_CLEAR_URL}" class="activeRoll">
								{$aPager.ACTU_PREC.PAGE_TITLE}
							</a>
						</li>
					{/if}
					{if $aPager.ACTU_SUIV}
						<li class="next">
							<a href="{urlParser url=$aPager.ACTU_SUIV.PAGE_CLEAR_URL}" class="activeRoll">
								{$aPager.ACTU_SUIV.PAGE_TITLE}
							</a>
						</li>
					{/if}
				</ul>
			{/if}
		</div>
	</div>
{/if}	