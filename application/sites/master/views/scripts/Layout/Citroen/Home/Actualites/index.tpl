                <section id="Actualites">
                	<h2><span>Actualités</span></h2>
                    <nav>
                    	<ul>
                        	<li class="active"><a href="#">Tous les thèmes</a></li>
                            <li><a href="#">Innovation</a></li>
                            <li><a href="#">Design</a></li>
                            <li><a href="#">Environnement</a></li>
                            <li><a href="#">Racing</a></li>
                            <li><a href="#">Evenements / Salons</a></li>
                        </ul>
                    </nav>
                	<div id="NewsList">
                        <div class="regular">
                            <a href="#">
                            <article class="is-1">
                                <div class="inner">
                                <time datetime="2012-02-02">2 février 2012</time>
                                <h3>Racing</h3>
                                <div class="picture"><img src="img/_trash/news-01.jpg" alt="" /></div>
                                <h4>La médaille d'or pour sébastien Loeb aux X-Games!</h4>
                                </div>
                            </article>
                            </a>
                            {section name=index loop=$news}
                            <a href="{$news[index].url}">
                            <article class="is-1_2">
                                <div class="inner">
                                <time datetime="2012-02-02">2 février 2012</time>
                                <h3>Racing</h3>
                                <div class="picture"><img width="384" src="{$pelican_config.MEDIA_HTTP}{$news[index].media}" alt="" /></div>
                                <h4>{$news[index].title}</h4>
                                </div>
                            </article>
                            </a>
                            {/section}
                            <!-- <a href="#">
                            <article class="is-1_2 first">
                                <div class="inner">
                                <time datetime="2012-02-02">2 février 2012</time>
                                <h3>Racing</h3>
                                <div class="picture"><img src="img/_trash/news-02.jpg" alt="" /></div>
                                <h4>C_42 Les concept-cars à l'honneur</h4>
                                </div>
                            </article>
                            </a>
                            <a href="#">
                            <article class="is-1_2 last">
                                <div class="inner">
                                <time datetime="2012-02-02">2 février 2012</time>
                                <h3>Racing</h3>
                                <div class="picture"><img src="img/_trash/news-03.jpg" alt="" /></div>
                                <h4>Odyssée éléctrique : La C-ZERO au Japon</h4>
                                </div>
                            </article>
                            </a>
                            <a href="#">
                            <article class="is-1_2 first">
                                <div class="inner">
                                <time datetime="2012-02-02">2 février 2012</time>
                                <h3>Racing</h3>
                                <div class="picture"><img src="img/_trash/news-04.jpg" alt="" /></div>
                                <h4>Série spéciale DS3 Racing by Sébastien Loeb</h4>
                                </div>
                            </article>
                            </a>
                            <a href="#">
                            <article class="is-1_2 last">
                                <div class="inner">
                                <time datetime="2012-02-02">2 février 2012</time>
                                <h3>Racing</h3>
                                <div class="picture"><img src="img/_trash/news-05.jpg" alt="" /></div>
                                <h4>Citroën décroche le prix du moteur international 2012</h4>
                                </div>
                            </article>
                            </a>-->
                            <!-- <a href="#">
                            <article class="is-1_3 first">
                                <div class="inner">
                                <time datetime="2012-02-02">2 février 2012</time>
                                <h3>Racing</h3>
                                <div class="picture"><img src="img/_trash/news-06.jpg" alt="" /></div>
                                <h4>Citroën péblicité par les conducteurs</h4>
                                </div>
                            </article>
                            </a>
                            <a href="#">
                            <article class="is-2_3 last">
                                <div class="inner">
                                <time datetime="2012-02-02">2 février 2012</time>
                                <h3>Racing</h3>
                                <div class="picture"><img src="img/_trash/news-07.jpg" alt="" /></div>
                                <h4>Citroën au salon de Pékin 2012</h4>
                                </div>
                            </article>
                            </a> -->
                        </div>
                        <div class="mobile">
                            <a href="#">
                            <article class="is-2_3 first">
                                <div class="inner">
                                <time datetime="2012-02-02">2 février 2012</time>
                                <h3>Racing</h3>
                                <div class="picture"><img src="img/_trash/news-04.jpg" alt="" /></div>
                                <h4>Série spéciale DS3 Racing by Sébastien Loeb</h4>
                                </div>
                            </article>
                            </a>
                            <a href="#">
                            <article class="is-1_3 last">
                                <div class="inner">
                                <time datetime="2012-02-02">2 février 2012</time>
                                <h3>Racing</h3>
                                <div class="picture"><img src="img/_trash/news-05.jpg" alt="" /></div>
                                <h4>Citroën décroche le prix du moteur international 2012</h4>
                                </div>
                            </article>
                            </a>
                        
                        <div class="more"><a href="#">Voir toutes les actualités</a></div>
                        </div>
                    </div>                  
                </section>