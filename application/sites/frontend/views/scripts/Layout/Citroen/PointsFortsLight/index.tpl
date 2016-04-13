 {if $aData.ZONE_WEB == 1}
 <div class="sliceNew slicePointsFortsLightDesk">
 {if is_array($aSlideShow) && sizeof($aSlideShow)>0 }
 <section class="row of6 clspointsfortslight">
     
    {if $aData.ZONE_TITRE}<h2 class="subtitle" {if ($aData.PRIMARY_COLOR|count_characters)==7 } style="color:{$aData.PRIMARY_COLOR};" {/if}>{$aData.ZONE_TITRE|escape}</h2>{/if}
    {if $aData.ZONE_TEXTE2}<h3 class="parttitle" {if ($aData.SECOND_COLOR|count_characters)==7 } style="color:{$aData.SECOND_COLOR};" {/if}>{$aData.ZONE_TEXTE2|escape}</h3>{/if}
                    
                <div class="slide-bx">
                        <div class="slides">
						{foreach from=$aSlideShow item=slideShow name=listSlideShow}
						
                            <!-- GABARIT A / GRAND VISUEL -->
                                <!-- TEXTE DROITE (classe  text-right sur div de slide) -->
								
								{if $slideShow.PAGE_ZONE_MULTI_VALUE eq 'GRAND_VISUEL'}
								{if $slideShow.PAGE_ZONE_MULTI_LABEL3  neq ''}
									{if $slideShow.PAGE_ZONE_MULTI_LABEL3  == 'DROITE_HAUT'}
									 {assign var=sClass  value='text-top'}
									{elseif $slideShow.PAGE_ZONE_MULTI_LABEL3  == 'DROITE_BAS'}
									 {assign var=sClass  value='text-bottom'}
									 {/if}
                                <div class="slide gabarit-a text-right" data-label="{$slideShow.PAGE_ZONE_MULTI_TITRE}">
                                    <!-- GRAND VISUEL -->
                                    <figure class="main-visual">
                                        <img class="" src="{$slideShow.MEDIA_GRNAD_VISUEL}" width="960" height="255" />
                                    </figure>
                                    <!-- /GRAND VISUEL -->
                                    <!-- ZONE TEXTE -->
                                    <div class="text-block">
                                        <div class="v-align">
                                            <!-- DOM g?n?r? par le BO, balises d'exemple, non contractuelles -->
                                           
                                                {$slideShow.PAGE_ZONE_MULTI_TEXT3} 
                                           
											<ul class="cta">
											
												 {if $slideShow.CTA_2 neq ''}
													<li><a href="{$slideShow.CTA_2.BARRE_OUTILS_URL_WEB}" target="{if $slideShow.CTA_2.BARRE_OUTILS_MODE_OUVERTURE eq 2}_blank{/if}" {gtm action='Click' data=$aData datasup=['eventCategory' => $slideShow.GTM_BUTTON1.eventCategory,'eventAction'=>$slideShow.GTM_BUTTON1.eventAction, 'eventLabel' => $slideShow.GTM_BUTTON1.eventLabel]}><span>{$slideShow.CTA_2.LIB}</span></a></li>
												{elseif $slideShow.PAGE_ZONE_MULTI_LABEL neq '' && $slideShow.PAGE_ZONE_MULTI_URL neq ''}
													<li><a href="{$slideShow.PAGE_ZONE_MULTI_URL}" target="{$slideShow.TARGET_CLIC_CTA2}" {gtm action='Click' data=$aData datasup=['eventCategory' => $slideShow.GTM_BUTTON1.eventCategory,'eventAction'=>$slideShow.GTM_BUTTON1.eventAction, 'eventLabel' => $slideShow.GTM_BUTTON1.eventLabel]}><span>{$slideShow.PAGE_ZONE_MULTI_LABEL}</span></a></li>
												{/if}
												{if $slideShow.CTA_1 neq ''}
													<li><a href="{$slideShow.CTA_1.BARRE_OUTILS_URL_WEB}" target="{$slideShow.CTA_1.BARRE_OUTILS_MODE_OUVERTURE}" {gtm action='Click' data=$aData datasup=['eventCategory' => $slideShow.GTM_BUTTON2.eventCategory,'eventAction'=>$slideShow.GTM_BUTTON2.eventAction, 'eventLabel' => $slideShow.GTM_BUTTON2.eventLabel]}><span>{$slideShow.CTA_1.LIB}</span></a></li>
												{elseif $slideShow.PAGE_ZONE_MULTI_LABEL2 neq '' && $slideShow.PAGE_ZONE_MULTI_URL4 neq ''}
													<li><a href="{$slideShow.PAGE_ZONE_MULTI_URL4}" target="{$slideShow.TARGET_CLIC_CTA1}" {gtm action='Click' data=$aData datasup=['eventCategory' => $slideShow.GTM_BUTTON2.eventCategory,'eventAction'=>$slideShow.GTM_BUTTON2.eventAction, 'eventLabel' => $slideShow.GTM_BUTTON2.eventLabel]}><span>{$slideShow.PAGE_ZONE_MULTI_LABEL2}</span></a></li>
												{/if}
												
												
                                                <!--<li><a href="{$slideShow.PAGE_ZONE_MULTI_URL}" target="{$slideShow.TARGET_CLIC}"><span>{$slideShow.PAGE_ZONE_MULTI_LABEL}</span></a></li>-->	
												
						<!-- ajouter un CTA :   <li><a href="#"><span>Essayez-la</span></a></li>  si 2 CTA -->
                                            </ul>
                                            <!-- /DOM g?n?r? par le BO, balises d'exemple, non contractuelles -->
                                        </div>
                                    </div>
                                    <!-- /ZONE TEXTE -->
                                </div>
								
                                <!-- /TEXTE DROITE (classe  text-right sur div de slide -->
                                <!-- TEXTE GAUCHE (classe  text-left sur div de slide 	-->
								{elseif $slideShow.PAGE_ZONE_MULTI_LABEL3  == 'GAUCHE_HAUT'}
                                <div class="slide gabarit-a text-left" data-label="{$slideShow.PAGE_ZONE_MULTI_TITRE}">
                                    <!-- GRAND VISUEL -->
                                    <figure class="main-visual">
                                        <img class="" src="{$slideShow.MEDIA_GRNAD_VISUEL}" width="960" height="255" />
                                    </figure>
                                    <!-- /GRAND VISUEL -->
                                    <!-- ZONE TEXTE -->
                                    <div class="text-block">
                                        <div class="v-align">
                                            <!-- DOM g?n?r? par le BO, balises d'exemple, non contractuelles -->
                                            
                                              {$slideShow.PAGE_ZONE_MULTI_TEXT3} 
                                           
											<ul class="cta">
                                                 {if $slideShow.CTA_2 neq ''}
													<li><a href="{$slideShow.CTA_2.BARRE_OUTILS_URL_WEB}" target="{if $slideShow.CTA_2.BARRE_OUTILS_MODE_OUVERTURE eq 2}_blank{/if}" {gtm action='Click' data=$aData datasup=['eventCategory' => $slideShow.GTM_BUTTON1.eventCategory,'eventAction'=>$slideShow.GTM_BUTTON1.eventAction, 'eventLabel' => $slideShow.GTM_BUTTON1.eventLabel]}><span>{$slideShow.CTA_2.LIB}</span></a></li>
												{elseif $slideShow.PAGE_ZONE_MULTI_LABEL neq '' && $slideShow.PAGE_ZONE_MULTI_URL neq ''}
													<li><a href="{$slideShow.PAGE_ZONE_MULTI_URL}" target="{$slideShow.TARGET_CLIC_CTA2}" {gtm action='Click' data=$aData datasup=['eventCategory' => $slideShow.GTM_BUTTON1.eventCategory,'eventAction'=>$slideShow.GTM_BUTTON1.eventAction, 'eventLabel' => $slideShow.GTM_BUTTON1.eventLabel]}><span>{$slideShow.PAGE_ZONE_MULTI_LABEL}</span></a></li>
												{/if}
												{if $slideShow.CTA_1 neq ''}
													<li><a href="{$slideShow.CTA_1.BARRE_OUTILS_URL_WEB}" target="{$slideShow.CTA_1.BARRE_OUTILS_MODE_OUVERTURE}" {gtm action='Click' data=$aData datasup=['eventCategory' => $slideShow.GTM_BUTTON2.eventCategory,'eventAction'=>$slideShow.GTM_BUTTON2.eventAction, 'eventLabel' => $slideShow.GTM_BUTTON2.eventLabel]}><span>{$slideShow.CTA_1.LIB}</span></a></li>
												{elseif $slideShow.PAGE_ZONE_MULTI_LABEL2 neq '' && $slideShow.PAGE_ZONE_MULTI_URL4 neq ''}
													<li><a href="{$slideShow.PAGE_ZONE_MULTI_URL4}" target="{$slideShow.TARGET_CLIC_CTA1}" {gtm action='Click' data=$aData datasup=['eventCategory' => $slideShow.GTM_BUTTON2.eventCategory,'eventAction'=>$slideShow.GTM_BUTTON2.eventAction, 'eventLabel' => $slideShow.GTM_BUTTON2.eventLabel]}><span>{$slideShow.PAGE_ZONE_MULTI_LABEL2}</span></a></li>
												{/if}
                                            </ul>
                                            <!-- /DOM gnr par le BO, balises d'exemple, non contractuelles -->
                                        </div>
                                    </div>
                                    <!-- /ZONE TEXTE -->
                                </div>
									
							{elseif $slideShow.PAGE_ZONE_MULTI_LABEL3  eq ''}
                                <div class="slide gabarit-a text-left" data-label="{$slideShow.PAGE_ZONE_MULTI_TITRE}">
                                   
                                    <!-- ZONE TEXTE -->
                                    <div class="text-block">
                                        <div class="v-align">
                                            <!-- DOM g?n?r? par le BO, balises d'exemple, non contractuelles -->
                                            
                                              {$slideShow.PAGE_ZONE_MULTI_TEXT3} 
                                           
											<ul class="cta">
                                                 {if $slideShow.CTA_2 neq ''}
													<li><a href="{$slideShow.CTA_2.BARRE_OUTILS_URL_WEB}" target="{if $slideShow.CTA_2.BARRE_OUTILS_MODE_OUVERTURE eq 2}_blank{/if}" {gtm action='Click' data=$aData datasup=['eventCategory' => $slideShow.GTM_BUTTON1.eventCategory,'eventAction'=>$slideShow.GTM_BUTTON1.eventAction, 'eventLabel' => $slideShow.GTM_BUTTON1.eventLabel]}><span>{$slideShow.CTA_2.LIB}</span></a></li>
												{elseif $slideShow.PAGE_ZONE_MULTI_LABEL neq '' && $slideShow.PAGE_ZONE_MULTI_URL neq ''}
													<li><a href="{$slideShow.PAGE_ZONE_MULTI_URL}" target="{$slideShow.TARGET_CLIC_CTA2}" {gtm action='Click' data=$aData datasup=['eventCategory' => $slideShow.GTM_BUTTON1.eventCategory,'eventAction'=>$slideShow.GTM_BUTTON1.eventAction, 'eventLabel' => $slideShow.GTM_BUTTON1.eventLabel]}><span>{$slideShow.PAGE_ZONE_MULTI_LABEL}</span></a></li>
												{/if}
												{if $slideShow.CTA_1 neq ''}
													<li><a href="{$slideShow.CTA_1.BARRE_OUTILS_URL_WEB}" target="{$slideShow.CTA_1.BARRE_OUTILS_MODE_OUVERTURE}" {gtm action='Click' data=$aData datasup=['eventCategory' => $slideShow.GTM_BUTTON2.eventCategory,'eventAction'=>$slideShow.GTM_BUTTON2.eventAction, 'eventLabel' => $slideShow.GTM_BUTTON2.eventLabel]}><span>{$slideShow.CTA_1.LIB}</span></a></li>
												{elseif $slideShow.PAGE_ZONE_MULTI_LABEL2 neq '' && $slideShow.PAGE_ZONE_MULTI_URL4 neq ''}
													<li><a href="{$slideShow.PAGE_ZONE_MULTI_URL4}" target="{$slideShow.TARGET_CLIC_CTA1}" {gtm action='Click' data=$aData datasup=['eventCategory' => $slideShow.GTM_BUTTON2.eventCategory,'eventAction'=>$slideShow.GTM_BUTTON2.eventAction, 'eventLabel' => $slideShow.GTM_BUTTON2.eventLabel]}><span>{$slideShow.PAGE_ZONE_MULTI_LABEL2}</span></a></li>
												{/if}
                                            </ul>
                                            <!-- /DOM gnr par le BO, balises d'exemple, non contractuelles -->
                                        </div>
                                    </div>
									 <!-- GRAND VISUEL -->
                                    <figure class="main-visual">
                                        <img class="" src="{$slideShow.MEDIA_GRNAD_VISUEL}" width="960" height="255" />
                                    </figure>
                                    <!-- /GRAND VISUEL -->
                                    <!-- /ZONE TEXTE -->
                                </div>
									{/if}
                                <!-- /TEXTE GAUCHE (classe  text-left sur div de slide) -->
                            <!-- /GABARIT A / GRAND VISUEL -->
							{/if}
							{if $slideShow.PAGE_ZONE_MULTI_VALUE eq '3_COLONNE_MIXTE'}
                            <!-- GABARIT D / 3 COLONNES VISUELS -->
                                <div class="slide gabarit-d" data-label="{$slideShow.PAGE_ZONE_MULTI_TITRE}">
                                    <!-- PREMIER VISUEL -->
                                    <figure class="first-visual">
                                        <img class="" src="{$slideShow.3_COLONNE_MIXTE_GAUCHE}" width="327" height="225" />
                                    </figure>
                                    <!-- /PREMIER VISUEL -->
                                    <!-- ZONE TEXTE -->
                                    <div class="text-block">
                                        <div class="v-align">
                                            <!-- DOM g?n?r? par le BO, balises d'exemple, non contractuelles -->
                                            
                                              {$slideShow.PAGE_ZONE_MULTI_TEXT3} 

                                            
											<ul class="cta">
                                                 {if $slideShow.CTA_2 neq ''}
													<li><a href="{$slideShow.CTA_2.BARRE_OUTILS_URL_WEB}" target="{if $slideShow.CTA_2.BARRE_OUTILS_MODE_OUVERTURE eq 2}_blank{/if}" {gtm action='Click' data=$aData datasup=['eventCategory' => $slideShow.GTM_BUTTON1.eventCategory,'eventAction'=>$slideShow.GTM_BUTTON1.eventAction, 'eventLabel' => $slideShow.GTM_BUTTON1.eventLabel]}><span>{$slideShow.CTA_2.LIB}</span></a></li>
												{elseif $slideShow.PAGE_ZONE_MULTI_LABEL neq '' && $slideShow.PAGE_ZONE_MULTI_URL neq ''}
													<li><a href="{$slideShow.PAGE_ZONE_MULTI_URL}" target="{$slideShow.TARGET_CLIC_CTA2}" {gtm action='Click' data=$aData datasup=['eventCategory' => $slideShow.GTM_BUTTON1.eventCategory,'eventAction'=>$slideShow.GTM_BUTTON1.eventAction, 'eventLabel' => $slideShow.GTM_BUTTON1.eventLabel]}><span>{$slideShow.PAGE_ZONE_MULTI_LABEL}</span></a></li>
												{/if}
												{if $slideShow.CTA_1 neq ''}
													<li><a href="{$slideShow.CTA_1.BARRE_OUTILS_URL_WEB}" target="{$slideShow.CTA_1.BARRE_OUTILS_MODE_OUVERTURE}" {gtm action='Click' data=$aData datasup=['eventCategory' => $slideShow.GTM_BUTTON2.eventCategory,'eventAction'=>$slideShow.GTM_BUTTON2.eventAction, 'eventLabel' => $slideShow.GTM_BUTTON2.eventLabel]}><span>{$slideShow.CTA_1.LIB}</span></a></li>
												{elseif $slideShow.PAGE_ZONE_MULTI_LABEL2 neq '' && $slideShow.PAGE_ZONE_MULTI_URL4 neq ''}
													<li><a href="{$slideShow.PAGE_ZONE_MULTI_URL4}" target="{$slideShow.TARGET_CLIC_CTA1}" {gtm action='Click' data=$aData datasup=['eventCategory' => $slideShow.GTM_BUTTON2.eventCategory,'eventAction'=>$slideShow.GTM_BUTTON2.eventAction, 'eventLabel' => $slideShow.GTM_BUTTON2.eventLabel]}><span>{$slideShow.PAGE_ZONE_MULTI_LABEL2}</span></a></li>
												{/if}
                                            </ul>
                                            <!-- /DOM g?n?r? par le BO, balises d'exemple, non contractuelles -->
                                        </div>
                                    </div>
                                    <!-- /ZONE TEXTE -->
                                    <!-- SECOND VISUEL -->
                                    <figure class="second-visual">
                                        <img class="" src="{$slideShow.3_COLONNE_MIXTE_DROITE}" width="327" height="225" />
                                    </figure>
                                    <!-- /SECOND VISUEL -->
                                </div>
                            <!-- /GABARIT D / 3 COLONNES VISUELS -->
							{/if}
                            <!-- GABARIT B / 2 COLONNES MIXTES -->
							{if $slideShow.PAGE_ZONE_MULTI_VALUE eq '2_COLONNE_MIXTE'}
							{if $slideShow.PAGE_ZONE_MULTI_LABEL5 == 'GAUCHE'}
                                <!-- VISUEL GAUCHE (classe  visual-left sur div de slide -->
                                <div class="slide gabarit-b visual-left" data-label="{$slideShow.PAGE_ZONE_MULTI_TITRE}">
                                    <!-- VISUEL COLONNE -->
                                    <figure class="main-visual">
                                        <img class="" src="{$slideShow.MEDIA_GRNAD_VISUEL}" width="400" height="255" />
                                    </figure>
                                    <!-- /VISUEL COLONNE -->
                                    <!-- ZONE TEXTE -->
                                    <div class="text-block">
                                        <div class="v-align">
                                            <!-- DOM g?n?r? par le BO, balises d'exemple, non contractuelles -->
                                            
                                               {$slideShow.PAGE_ZONE_MULTI_TEXT3} 
                                            
                                            <!-- /DOM g?n?r? par le BO, balises d'exemple, non contractuelles -->
                                            <!-- CTA -->
                                            <ul class="cta">
                                                {if $slideShow.CTA_2 neq ''}
													<li><a href="{$slideShow.CTA_2.BARRE_OUTILS_URL_WEB}" target="{if $slideShow.CTA_2.BARRE_OUTILS_MODE_OUVERTURE eq 2}_blank{/if}" {gtm action='Click' data=$aData datasup=['eventCategory' => $slideShow.GTM_BUTTON1.eventCategory,'eventAction'=>$slideShow.GTM_BUTTON1.eventAction, 'eventLabel' => $slideShow.GTM_BUTTON1.eventLabel]}><span>{$slideShow.CTA_2.LIB}</span></a></li>
												{elseif $slideShow.PAGE_ZONE_MULTI_LABEL neq '' && $slideShow.PAGE_ZONE_MULTI_URL neq ''}
													<li><a href="{$slideShow.PAGE_ZONE_MULTI_URL}" target="{$slideShow.TARGET_CLIC_CTA2}" {gtm action='Click' data=$aData datasup=['eventCategory' => $slideShow.GTM_BUTTON1.eventCategory,'eventAction'=>$slideShow.GTM_BUTTON1.eventAction, 'eventLabel' => $slideShow.GTM_BUTTON1.eventLabel]}><span>{$slideShow.PAGE_ZONE_MULTI_LABEL}</span></a></li>
												{/if}
												{if $slideShow.CTA_1 neq ''}
													<li><a href="{$slideShow.CTA_1.BARRE_OUTILS_URL_WEB}" target="{$slideShow.CTA_1.BARRE_OUTILS_MODE_OUVERTURE}" {gtm action='Click' data=$aData datasup=['eventCategory' => $slideShow.GTM_BUTTON2.eventCategory,'eventAction'=>$slideShow.GTM_BUTTON2.eventAction, 'eventLabel' => $slideShow.GTM_BUTTON2.eventLabel]}><span>{$slideShow.CTA_1.LIB}</span></a></li>
												{elseif $slideShow.PAGE_ZONE_MULTI_LABEL2 neq '' && $slideShow.PAGE_ZONE_MULTI_URL4 neq ''}
													<li><a href="{$slideShow.PAGE_ZONE_MULTI_URL4}" target="{$slideShow.TARGET_CLIC_CTA1}" {gtm action='Click' data=$aData datasup=['eventCategory' => $slideShow.GTM_BUTTON2.eventCategory,'eventAction'=>$slideShow.GTM_BUTTON2.eventAction, 'eventLabel' => $slideShow.GTM_BUTTON2.eventLabel]}><span>{$slideShow.PAGE_ZONE_MULTI_LABEL2}</span></a></li>
												{/if}
                                            </ul>
                                            <!-- /CTA -->
                                        </div>
                                    </div>
                                    <!-- /ZONE TEXTE -->
                                </div>
                                <!-- / VISUEL GAUCHE -->
								
								{elseif $slideShow.PAGE_ZONE_MULTI_LABEL5 == 'DROITE'}
                                <!-- VISUEL DROITE (classe  visual-right sur div de slide -->
                                <div class="slide gabarit-b visual-right" data-label="{$slideShow.PAGE_ZONE_MULTI_TITRE}">
                                    <!-- VISUEL COLONNE -->
                                    <figure class="main-visual">
                                        <img class="" src="{$slideShow.MEDIA_GRNAD_VISUEL}" width="400" height="255" />
                                    </figure>
                                    <!-- /VISUEL COLONNE -->
                                    <!-- ZONE TEXTE -->
                                    <div class="text-block">
                                        <div class="v-align">
                                            <!-- DOM g?n?r? par le BO, balises d'exemple, non contractuelles -->
                                            
                                               {$slideShow.PAGE_ZONE_MULTI_TEXT3} 

                                            
											<ul class="cta">
											
                                                {if $slideShow.CTA_2 neq ''}
													<li><a href="{$slideShow.CTA_2.BARRE_OUTILS_URL_WEB}" target="{if $slideShow.CTA_2.BARRE_OUTILS_MODE_OUVERTURE eq 2}_blank{/if}" {gtm action='Click' data=$aData datasup=['eventCategory' => $slideShow.GTM_BUTTON1.eventCategory,'eventAction'=>$slideShow.GTM_BUTTON1.eventAction, 'eventLabel' => $slideShow.GTM_BUTTON1.eventLabel]}><span>{$slideShow.CTA_2.LIB}</span></a></li>
												{elseif $slideShow.PAGE_ZONE_MULTI_LABEL neq '' && $slideShow.PAGE_ZONE_MULTI_URL neq ''}
													<li><a href="{$slideShow.PAGE_ZONE_MULTI_URL}" target="{$slideShow.TARGET_CLIC_CTA2}" {gtm action='Click' data=$aData datasup=['eventCategory' => $slideShow.GTM_BUTTON1.eventCategory,'eventAction'=>$slideShow.GTM_BUTTON1.eventAction, 'eventLabel' => $slideShow.GTM_BUTTON1.eventLabel]}><span>{$slideShow.PAGE_ZONE_MULTI_LABEL}</span></a></li>
												{/if}
												{if $slideShow.CTA_1 neq ''}
													<li><a href="{$slideShow.CTA_1.BARRE_OUTILS_URL_WEB}" target="{$slideShow.CTA_1.BARRE_OUTILS_MODE_OUVERTURE}" {gtm action='Click' data=$aData datasup=['eventCategory' => $slideShow.GTM_BUTTON2.eventCategory,'eventAction'=>$slideShow.GTM_BUTTON2.eventAction, 'eventLabel' => $slideShow.GTM_BUTTON2.eventLabel]}><span>{$slideShow.CTA_1.LIB}</span></a></li>
												{elseif $slideShow.PAGE_ZONE_MULTI_LABEL2 neq '' && $slideShow.PAGE_ZONE_MULTI_URL4 neq ''}
													<li><a href="{$slideShow.PAGE_ZONE_MULTI_URL4}" target="{$slideShow.TARGET_CLIC_CTA1}" {gtm action='Click' data=$aData datasup=['eventCategory' => $slideShow.GTM_BUTTON2.eventCategory,'eventAction'=>$slideShow.GTM_BUTTON2.eventAction, 'eventLabel' => $slideShow.GTM_BUTTON2.eventLabel]}><span>{$slideShow.PAGE_ZONE_MULTI_LABEL2}</span></a></li>
												{/if}
                                            </ul>
                                            <!-- /DOM g?n?r? par le BO, balises d'exemple, non contractuelles -->
                                        </div>
                                    </div>
                                    <!-- /ZONE TEXTE -->
                                </div>
								{elseif $slideShow.PAGE_ZONE_MULTI_LABEL5 eq ''}
								 <!-- VISUEL DROITE (classe  visual-right sur div de slide -->
                                <div class="slide gabarit-b visual-right" data-label="{$slideShow.PAGE_ZONE_MULTI_TITRE}">
                                    <!-- VISUEL COLONNE -->
                                    <figure class="main-visual">
                                        <img class="" src="{$slideShow.MEDIA_GRNAD_VISUEL}" width="400" height="255" />
                                    </figure>
                                    <!-- /VISUEL COLONNE -->
                                    <!-- ZONE TEXTE -->
                                    <div class="text-block">
                                        <div class="v-align">
                                            <!-- DOM g?n?r? par le BO, balises d'exemple, non contractuelles -->
                                            
                                               {$slideShow.PAGE_ZONE_MULTI_TEXT3} 

                                            
											<ul class="cta">
											
                                                {if $slideShow.CTA_2 neq ''}
													<li><a href="{$slideShow.CTA_2.BARRE_OUTILS_URL_WEB}" target="{if $slideShow.CTA_2.BARRE_OUTILS_MODE_OUVERTURE eq 2}_blank{/if}" {gtm action='Click' data=$aData datasup=['eventCategory' => $slideShow.GTM_BUTTON1.eventCategory,'eventAction'=>$slideShow.GTM_BUTTON1.eventAction, 'eventLabel' => $slideShow.GTM_BUTTON1.eventLabel]}><span>{$slideShow.CTA_2.LIB}</span></a></li>
												{elseif $slideShow.PAGE_ZONE_MULTI_LABEL neq '' && $slideShow.PAGE_ZONE_MULTI_URL neq ''}
													<li><a href="{$slideShow.PAGE_ZONE_MULTI_URL}" target="{$slideShow.TARGET_CLIC_CTA2}" {gtm action='Click' data=$aData datasup=['eventCategory' => $slideShow.GTM_BUTTON1.eventCategory,'eventAction'=>$slideShow.GTM_BUTTON1.eventAction, 'eventLabel' => $slideShow.GTM_BUTTON1.eventLabel]}><span>{$slideShow.PAGE_ZONE_MULTI_LABEL}</span></a></li>
												{/if}
												{if $slideShow.CTA_1 neq ''}
													<li><a href="{$slideShow.CTA_1.BARRE_OUTILS_URL_WEB}" target="{$slideShow.CTA_1.BARRE_OUTILS_MODE_OUVERTURE}" {gtm action='Click' data=$aData datasup=['eventCategory' => $slideShow.GTM_BUTTON2.eventCategory,'eventAction'=>$slideShow.GTM_BUTTON2.eventAction, 'eventLabel' => $slideShow.GTM_BUTTON2.eventLabel]}><span>{$slideShow.CTA_1.LIB}</span></a></li>
												{elseif $slideShow.PAGE_ZONE_MULTI_LABEL2 neq '' && $slideShow.PAGE_ZONE_MULTI_URL4 neq ''}
													<li><a href="{$slideShow.PAGE_ZONE_MULTI_URL4}" target="{$slideShow.TARGET_CLIC_CTA1}" {gtm action='Click' data=$aData datasup=['eventCategory' => $slideShow.GTM_BUTTON2.eventCategory,'eventAction'=>$slideShow.GTM_BUTTON2.eventAction, 'eventLabel' => $slideShow.GTM_BUTTON2.eventLabel]}><span>{$slideShow.PAGE_ZONE_MULTI_LABEL2}</span></a></li>
												{/if}
                                            </ul>
                                            <!-- /DOM g?n?r? par le BO, balises d'exemple, non contractuelles -->
                                        </div>
                                    </div>
                                    <!-- /ZONE TEXTE -->
                                </div>
								{/if}
                                <!-- /VISUEL DROITE -->
                            <!-- /GABARIT B / 2 COLONNES MIXTES -->
							{/if}
							{if $slideShow.PAGE_ZONE_MULTI_VALUE eq 'SUPERPOSITION_VISUELS'}
                            <!-- GABARIT C / SUPERPOSITION DE VISUELS -->
                                <div class="slide gabarit-c" data-label="{$slideShow.PAGE_ZONE_MULTI_TITRE}">
                                    <!-- VISUEL PRINCIPAL -->
                                    <figure class="main-visual">
                                        <img class="" src="{$slideShow.MEDIA_GRNAD_VISUEL}" width="275" height="225" />
                                    </figure>
                                    <!-- /VISUEL PRINCIPAL -->
                                    <!-- VISUEL SUPERPOSITION -->
                                    <figure class="floating-visual">
                                        <img class="" src="{$slideShow.SUPERPOSITION_VISUELS}" width="164" height="164" />
                                    </figure>
                                    <!-- /VISUEL SUPERPOSITION -->
                                    <div class="text-block">
                                        <div class="v-align">
                                            <!-- DOM g?n?r? par le BO, balises d'exemple, non contractuelles -->
                                           
                                                 {$slideShow.PAGE_ZONE_MULTI_TEXT3} 
                                            
											<ul class="cta">
                                              {if $slideShow.CTA_2 neq ''}
													<li><a href="{$slideShow.CTA_2.BARRE_OUTILS_URL_WEB}" target="{if $slideShow.CTA_2.BARRE_OUTILS_MODE_OUVERTURE eq 2}_blank{/if}" {gtm action='Click' data=$aData datasup=['eventCategory' => $slideShow.GTM_BUTTON1.eventCategory,'eventAction'=>$slideShow.GTM_BUTTON1.eventAction, 'eventLabel' => $slideShow.GTM_BUTTON1.eventLabel]}><span>{$slideShow.CTA_2.LIB}</span></a></li>
												{elseif $slideShow.PAGE_ZONE_MULTI_LABEL neq '' && $slideShow.PAGE_ZONE_MULTI_URL neq ''}
													<li><a href="{$slideShow.PAGE_ZONE_MULTI_URL}" target="{$slideShow.TARGET_CLIC_CTA2}" {gtm action='Click' data=$aData datasup=['eventCategory' => $slideShow.GTM_BUTTON1.eventCategory,'eventAction'=>$slideShow.GTM_BUTTON1.eventAction, 'eventLabel' => $slideShow.GTM_BUTTON1.eventLabel]}><span>{$slideShow.PAGE_ZONE_MULTI_LABEL}</span></a></li>
												{/if}
												{if $slideShow.CTA_1 neq ''}
													<li><a href="{$slideShow.CTA_1.BARRE_OUTILS_URL_WEB}" target="{$slideShow.CTA_1.BARRE_OUTILS_MODE_OUVERTURE}" {gtm action='Click' data=$aData datasup=['eventCategory' => $slideShow.GTM_BUTTON2.eventCategory,'eventAction'=>$slideShow.GTM_BUTTON2.eventAction, 'eventLabel' => $slideShow.GTM_BUTTON2.eventLabel]}><span>{$slideShow.CTA_1.LIB}</span></a></li>
												{elseif $slideShow.PAGE_ZONE_MULTI_LABEL2 neq '' && $slideShow.PAGE_ZONE_MULTI_URL4 neq ''}
													<li><a href="{$slideShow.PAGE_ZONE_MULTI_URL4}" target="{$slideShow.TARGET_CLIC_CTA1}" {gtm action='Click' data=$aData datasup=['eventCategory' => $slideShow.GTM_BUTTON2.eventCategory,'eventAction'=>$slideShow.GTM_BUTTON2.eventAction, 'eventLabel' => $slideShow.GTM_BUTTON2.eventLabel]}><span>{$slideShow.PAGE_ZONE_MULTI_LABEL2}</span></a></li>
												{/if}
                                            </ul>
                                            <!-- /DOM g?n?r? par le BO, balises d'exemple, non contractuelles -->
                                        </div>
                                    </div>

                                </div>
                            <!-- /GABARIT C / SUPERPOSITION DE VISUELS -->
							{/if}
							{/foreach}
                        </div>
						<input type="hidden" name="autoplay" value="{if $aData.MEDIA_AUTOLOAD}{$aData.MEDIA_AUTOLOAD}{else}0{/if}"/>
                    </div>


                    <!-- DYNAMISATION DES COULEURS CONTROL SLIDERS : ICI il faut dynamiser la couleur dans le template avec -->
    		    <!-- #93004b  <== ? remplacer par la couleur Primaire -->
{literal}
                    <style type="text/css">

                        div.slicePointsFortsLightDesk .clspointsfortslight .slide-bx .pager li a.active .numb, 
                        div.slicePointsFortsLightDesk .clspointsfortslight .slide-bx .pager li a.active .label, 
                        div.slicePointsFortsLightDesk .clspointsfortslight .slide-bx .pager li a:hover .numb, 
                        div.slicePointsFortsLightDesk .clspointsfortslight .slide-bx .pager li a:hover .label
                        {
                            color:{/literal}{if $aData.PRIMARY_COLOR}{$aData.SECOND_COLOR}{else}#bed800{/if}{literal};
                        }
                        div.slicePointsFortsLightDesk .clspointsfortslight .slide-bx .bx-controls .bx-prev{
                            color:{/literal}{if $aData.PRIMARY_COLOR}{$aData.PRIMARY_COLOR}{else}#93004b{/if}{literal};
                            border:2px solid {/literal}{if $aData.PRIMARY_COLOR}{$aData.PRIMARY_COLOR}{else}#93004b{/if}{literal};
                        }
                        div.slicePointsFortsLightDesk .clspointsfortslight .slide-bx .bx-controls .bx-prev:hover{
                            color:#fff;
                            border:2px solid {/literal}{if $aData.PRIMARY_COLOR}{$aData.PRIMARY_COLOR}{else}#93004b{/if}{literal};
                            background-color:{/literal}{if $aData.PRIMARY_COLOR}{$aData.PRIMARY_COLOR}{else}#93004b{/if}{literal};
                        }
                        div.slicePointsFortsLightDesk .clspointsfortslight .slide-bx .bx-controls .bx-next{
                            color:{/literal}{if $aData.PRIMARY_COLOR}{$aData.PRIMARY_COLOR}{else}#93004b{/if}{literal};
                            border:2px solid {/literal}{if $aData.PRIMARY_COLOR}{$aData.PRIMARY_COLOR}{else}#93004b{/if}{literal};
                        }
                        div.slicePointsFortsLightDesk .clspointsfortslight .slide-bx .bx-controls .bx-next:hover{
                            color:#fff;
                            border:2px solid {/literal}{if $aData.PRIMARY_COLOR}{$aData.PRIMARY_COLOR}{else}#93004b{/if}{literal};
                            background-color:{/literal}{if $aData.PRIMARY_COLOR}{$aData.PRIMARY_COLOR}{else}#93004b{/if}{literal};
                        }
                
						div.slicePointsFortsLightDesk .clspointsfortslight .slide .text-block .cta a{
							border:4px solid {/literal}{if $aData.PRIMARY_COLOR}{$aData.PRIMARY_COLOR}{else}#93004b{/if}{literal};
							background-color:{/literal}{if $aData.PRIMARY_COLOR}{$aData.PRIMARY_COLOR}{else}#93004b{/if}{literal};
						}
						div.slicePointsFortsLightDesk .clspointsfortslight .slide .text-block .cta a:hover{
							border:4px solid {/literal}{if $aData.PRIMARY_COLOR}{$aData.PRIMARY_COLOR}{else}#93004b{/if}{literal};
							background-color:#ffffff;
						}
						div.slicePointsFortsLightDesk .clspointsfortslight .slide .text-block .cta a:hover span {
							color: {/literal}{if $aData.PRIMARY_COLOR}{$aData.PRIMARY_COLOR}{else}#93004b{/if}{literal};
						}
						
						div.slicePointsFortsLightDesk .clspointsfortslight .slide .text-block .cta a:hover:after{
							color: {/literal}{if $aData.PRIMARY_COLOR}{$aData.PRIMARY_COLOR}{else}#93004b{/if}{literal};
						}
                                                div.slicePointsFortsLightDesk .clspointsfortslight .slide .text-block .cta a:after{
                                                    height:auto;
                                                }
						
						
                    </style>
					{/literal}
                    <!-- /DYNAMISATION DES COULEURS CTA + CONTROL SLIDERS -->
                </section>
<!-- /TRANCHE POINTS FORTS LIGHT -->
{/if}
</div>
{/if}
