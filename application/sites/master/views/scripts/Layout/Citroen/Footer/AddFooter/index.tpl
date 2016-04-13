            <div id="AddFooter">
                <nav>
                <p>
	{section name=index loop=$navigation}
                    <a {if $navigation[index].menu.target != ""} target="{$navigation[index].menu.target}"{/if} href="{$navigation[index].menu.url}">{$navigation[index].menu.lib}</a>
	{/section}
	            <span>Copyright &copy; Citroën 2013. tous droits réservés</span></p>
                </nav>
                <div id="LogoFooter"><p>Créative technologie </p><a href="/"><span>Citroën</span></a></div>
            </div>
