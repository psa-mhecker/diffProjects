<!-- ############################## HEAD SCRIPTS - START ############################## -->

		<!-- Google Tag Manager -->
		<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-KXVB" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
		<script>{literal}(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','GTM-KXVB');{/literal}</script>

		<!-- Facebook SDK -->
		<div id="fb-root"></div>
		<script>{literal}(function(d, s, id) {
			var js, fjs = d.getElementsByTagName(s)[0];
			if (d.getElementById(id)) return;
			js = d.createElement(s); js.id = id;
			js.src = "//connect.facebook.net/fr_FR/all.js#xfbml=1&appId=256514107720575";
			fjs.parentNode.insertBefore(js, fjs);
		}(document, 'script', 'facebook-jssdk'));{/literal}</script>

		<!-- ############################## HEAD SCRIPTS - END ############################## -->
<section class="row of3">

					<h2 class="col span2 subtitle">{$ZONE_TITRE}</h2>
					<h3 class="col span2 parttitle">{$ZONE_TITRE2}</h3>

					<p class="col span2">
						<strong>{$ZONE_TEXTE}</strong>
					</p>

					<figure class="caption shadow shareable"><span class="roll">
                    </span>
						<a class="popit photo" href="{Pelican::$config.MEDIA_HTTP}{$MEDIA_PATH}" target="_blank">
							<img class="" src="{Pelican::$config.MEDIA_HTTP}{$MEDIA_PATH}" data-original="{Pelican::$config.MEDIA_HTTP}{$MEDIA_PATH}" width="1200" height="517" alt="Lorem ipsum dolor" style="display: inline-block;">
							<noscript>&lt;img src="{Pelican::$config.MEDIA_HTTP}{$MEDIA_PATH}" width="1200" height="517" alt="Lorem ipsum dolor" /&gt;</noscript>
						</a>
					</figure>

					<div class="col span2">
						<p>
							{$ZONE_TEXTE2}<br>
						</p>
					</div>
					<!-- /.col -->

					<div class="caption">

						<small class="legal"><a href="#LOREM">Mentions globales au bloc</a></small>

						<div class="thumbs">

							<a class="popit" href="http://www.youtube.com/watch?v=opj24KnzrWo" target="_blank">
								<figure class="shadow video">
									<img src="{Pelican::$config.MEDIA_HTTP}/design/frontend/images/media/thumb.jpg" width="145" height="81" alt="Lorem ipsum dolor">
								</figure>
								<span>Voir la vidéo</span>
							</a>

							<a class="popit" href="{Pelican::$config.MEDIA_HTTP}/design/frontend/images/media/zoom1.jpg" target="_blank">
								<figure class="shadow">
									<img src="{Pelican::$config.MEDIA_HTTP}/design/frontend/images/media/thumb.jpg" width="145" height="81" alt="Lorem ipsum dolor">
								</figure>
								<span>Voir la photo</span>
							</a>

							<a class="popit" href="{Pelican::$config.MEDIA_HTTP}/design/frontend/images/media/zoom1.jpg" target="_blank">
								<figure class="shadow">
									<img src="{Pelican::$config.MEDIA_HTTP}/design/frontend/images/media/thumb.jpg" width="145" height="81" alt="Lorem ipsum dolor">
								</figure>
								<span>Voir la photo</span>
							</a>

						</div>
						<!-- /.thumbs -->

						<ul class="actions">
							<li class="blue"><a href="#LOREM">Call to action 1</a></li>
							<li class="blue"><a href="#LOREM">Call to action 2</a></li>
							<li class="blue"><a href="#LOREM">Call to action 3</a></li>
						</ul>
						<!-- /.actions -->

					</div>
					<!-- /.caption -->

					<div class="caption" id="more1col" style="display: none;">
						<p><strong>Contenu additionnel</strong></p>
					</div>

					<div class="addmore folder" data-toggle="Voir moins"><a href="#more1col">Voir plus</a></div>

				</section>

            <script type="text/template" id="shareTpl">
			{literal}<span class="sharebox">
				<span class="st_facebook_large" data-displaytext="Facebook" st_title="<%= title %>" st_url="<%= url %>"></span>
				<span class="st_twitter_large" data-displaytext="Tweet" st_title="<%= title %>" st_url="<%= url %>"></span>
				<span class="st_googleplus_large" data-displaytext="Google+" st_title="<%= title %>" st_url="<%= url %>"></span>
				<span class='st_tumblr_large' data-displaytext='Tumblr' st_title="<%= title %>" st_url="<%= url %>"></span>
				<span class='st_pinterest_large' data-displaytext='Pinterest' st_title="<%= title %>" st_url="<%= url %>"></span>
				<span class="st_sharethis_large" data-displaytext="ShareThis" st_title="<%= title %>" st_url="<%= url %>"></span>
				{/literal}
			</span>
		</script>
		<script type="text/template" id="closeTpl">
			{literal}<span class="popClose"><span>Fermer</span></span>{/literal}
		</script>
        <!-- ShareThis tools -->
		<script>{literal}var switchTo5x=false;{/literal}</script>
		<script src="http://w.sharethis.com/button/buttons.js"></script>
		<script>{literal}stLight.options({publisher: "ur-b3ff63db-68f9-aa46-4f2b-d10333ae80e3", shorten:false, doNotHash: false, doNotCopy: false, hashAddressBar: false, onhover:false });{/literal}</script>
